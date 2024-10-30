<?php

namespace Dev4Press\Plugin\coreSocial\Table;

use Dev4Press\Plugin\coreSocial\Basic\Statistics;
use Dev4Press\v50\Core\Plugins\DBLite;
use Dev4Press\v50\Core\Quick\Sanitize;
use Dev4Press\v50\Core\UI\Elements;
use Dev4Press\v50\WordPress\Admin\Table;

class Items extends Table {
	public array $_sanitize_orderby_fields = array( 'id', 'item', 'item_id' );
	public string $_table_class_name = 'coresocial-grid-items';
	public string $_checkbox_field = 'id';
	public string $_self_nonce_key = 'coresocial-table-items';

	private $_item_types;
	private $_item_counts;

	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => 'item',
			'plural'   => 'items',
			'ajax'     => false,
		) );

		$this->_item_types = array(
			''       => __( 'All Items', 'coresocial' ),
			'post'   => __( 'Posts/Pages', 'coresocial' ),
			'term'   => __( 'Terms', 'coresocial' ),
			'custom' => __( 'Custom', 'coresocial' ),
		);

		$this->_item_counts = array(
			''        => __( 'All Counts', 'coresocial' ),
			'zero'    => __( 'Without Counts', 'coresocial' ),
			'counted' => __( 'With Counts', 'coresocial' ),
		);

		$this->process_request_args();
	}

	public function rows_per_page() : int {
		$per_page = get_user_option( 'coresocial_items_rows_per_page' );

		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = 20;
		}

		return $per_page;
	}

	public function get_columns() : array {
		return array(
			'cb'      => '<input type="checkbox" />',
			'id'      => __( 'ID', 'coresocial' ),
			'item'    => __( 'Item', 'coresocial' ),
			'item_id' => __( 'Item ID', 'coresocial' ),
			'url'     => __( 'URL', 'coresocial' ),
			'stats'   => __( 'Share Counts', 'coresocial' ),
			'online'  => __( 'Online Counts', 'coresocial' ),
		);
	}

	public function prepare_items() {
		$this->prepare_column_headers();

		$per_page   = $this->rows_per_page();
		$sel_item   = $this->get_request_arg( 'filter-item' );
		$sel_counts = $this->get_request_arg( 'filter-counts' );

		$sql = array(
			'select' => array(
				'i.*',
				'SUM(c.`internal`) as internal',
				'SUM(c.`online`) as online',
				'SUM(c.`internal`) + SUM(c.`online`) as total',
			),
			'from'   => array(
				coresocial_db()->items . ' i',
				'LEFT JOIN ' . coresocial_db()->counts . ' c ON c.`item_id` = i.`id`',
			),
			'where'  => array(),
			'group'  => 'i.`id`',
		);

		if ( $sel_counts == 'zero' ) {
			$sql['group'] .= ' HAVING total = 0 OR total IS NULL';
		} else if ( $sel_counts == 'counted' ) {
			$sql['group'] .= ' HAVING total > 0';
		}

		if ( ! empty( $sel_item ) ) {
			$sql['where'][] = coresocial_db()->prepare( 'i.`item` = %s', $sel_item );
		}

		$this->query_items( $sql, $per_page );
	}

	protected function db() : ?DBLite {
		return coresocial_db();
	}

	protected function process_request_args() {
		$this->_request_args = array(
			'filter-item'   => Sanitize::_get_slug( 'filter-item' ),
			'filter-counts' => Sanitize::_get_slug( 'filter-counts' ),
			'search'        => $this->_get_field( 's' ),
			'orderby'       => $this->_get_field( 'orderby', 'id' ),
			'order'         => $this->_get_field( 'order', 'DESC' ),
			'paged'         => $this->_get_field( 'paged' ),
		);
	}

	protected function filter_block_top() {
		echo '<div class="alignleft actions">';
		Elements::instance()->select( $this->_item_types, array(
			'selected' => $this->get_request_arg( 'filter-item' ),
			'name'     => 'filter-item',
		) );
		Elements::instance()->select( $this->_item_counts, array(
			'selected' => $this->get_request_arg( 'filter-counts' ),
			'name'     => 'filter-counts',
		) );
		submit_button( __( 'Filter', 'coresocial' ), 'button', false, false, array( 'id' => 'coresocial-items-submit' ) );
		echo '</div>';
	}

	protected function get_bulk_actions() : array {
		return array(
			'delete' => __( 'Delete', 'coresocial' ),
			'clear'  => __( 'Clear', 'coresocial' ),
			'queue'  => __( 'Queue Online Counts', 'coresocial' ),
		);
	}

	protected function get_sortable_columns() : array {
		return array(
			'id'      => array( 'id', false ),
			'item'    => array( 'item', false ),
			'item_id' => array( 'item_id', false ),
		);
	}

	protected function column_id( $item ) : string {
		return $item->id;
	}

	protected function column_item( $item ) : string {
		return $this->_item_types[ $item->item ] ?? ucfirst( $item->item );
	}

	protected function column_item_id( $item ) : string {
		$render  = $item->item_id == 0 ? $item->item_hash : $item->id;
		$actions = array(
			'delete' => sprintf( '<a href="%s">%s</a>', $this->_self( 'item=' . $item->id . '&single-action=delete-item', true, wp_create_nonce( 'coresocial-delete-item-' . $item->id ) ), __( 'Delete', 'coresocial' ) ),
			'clear'  => sprintf( '<a href="%s">%s</a>', $this->_self( 'item=' . $item->id . '&single-action=clear-item', true, wp_create_nonce( 'coresocial-clear-item-' . $item->id ) ), __( 'Clear', 'coresocial' ) ),
			'queue'  => sprintf( '<a href="%s">%s</a>', $this->_self( 'item=' . $item->id . '&single-action=queue-item', true, wp_create_nonce( 'coresocial-queue-item-' . $item->id ) ), __( 'Queue Online Counts', 'coresocial' ) ),
		);

		return $render . $this->row_actions( $actions );
	}

	protected function column_url( $item ) : string {
		$render  = $item->url;
		$actions = array(
			'log'  => sprintf( '<a target="_blank" href="%s">%s</a>', coresocial_admin()->panel_url( 'log', '', 'filter-item_id=' . $item->id ), __( 'Log', 'coresocial' ) ),
			'view' => sprintf( '<a target="_blank" href="%s">%s</a>', site_url( $item->url ), __( 'View', 'coresocial' ) ),
		);

		if ( $item->item == 'post' ) {
			$actions['edit'] = sprintf( '<a target="_blank" href="%s">%s</a>', site_url( get_edit_post_link( $item->item_id ) ), __( 'Edit', 'coresocial' ) );
		}

		return $render . $this->row_actions( $actions );
	}

	protected function column_stats( $item ) : string {
		$data   = Statistics::instance()->networks( $item->id, false );
		$render = '<div class="coresocial-item-stats">';

		foreach ( $data['networks'] as $network => $data ) {
			if ( $data['internal'] > 0 ) {
				$render .= '<div class="__network" style="background: rgb(var(--coresocial-color-' . $network . '-primary));" title="' . $data['label'] . '"><i class="coresocial-icon coresocial-icon-' . $network . ' coresocial-mod-fw"></i><span>' . $data['internal'] . '</span></div>';
			}
		}

		$render .= '</div>';

		return $render;
	}

	protected function column_online( $item ) : string {
		$data   = Statistics::instance()->networks( $item->id, false );
		$render = '<div class="coresocial-item-stats">';

		foreach ( $data['networks'] as $network => $data ) {
			if ( $data['online'] > 0 ) {
				$render .= '<div class="__network" style="background: rgb(var(--coresocial-color-' . $network . '-primary));" title="' . $data['label'] . '"><i class="coresocial-icon coresocial-icon-' . $network . ' coresocial-mod-fw"></i><span>' . $data['online'] . '</span></div>';
			}
		}

		$render .= '</div>';

		return $render;
	}
}
