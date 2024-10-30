<?php

namespace Dev4Press\Plugin\coreSocial\Table;

use Dev4Press\v50\Core\Plugins\DBLite;
use Dev4Press\v50\Core\Quick\Sanitize;
use Dev4Press\v50\Core\Quick\WPR;
use Dev4Press\v50\Core\UI\Elements;
use Dev4Press\v50\WordPress\Admin\Table;

class Log extends Table {
	public array $_sanitize_orderby_fields = array( 'l.id', 'l.network', 'i.item', 'l.item_id' );
	public string $_table_class_name = 'coresocial-grid-log';
	public string $_checkbox_field = 'id';
	public string $_self_nonce_key = 'coresocial-table-log';

	private $_item_types;
	private $_networks;

	public function __construct( $args = array() ) {
		parent::__construct( array(
			'singular' => 'log',
			'plural'   => 'logs',
			'ajax'     => false,
		) );

		$this->_item_types = array(
			''       => __( 'All Items', 'coresocial' ),
			'post'   => __( 'Posts/Pages', 'coresocial' ),
			'term'   => __( 'Terms', 'coresocial' ),
			'custom' => __( 'Custom', 'coresocial' ),
		);

		$this->_networks = array_merge( array( '' => __( 'All Networks', 'coresocial' ) ), coresocial_loader()->get_networks_list() );

		$this->process_request_args();
	}

	public function rows_per_page() : int {
		$per_page = get_user_option( 'coresocial_log_rows_per_page' );

		if ( empty( $per_page ) || $per_page < 1 ) {
			$per_page = 20;
		}

		return $per_page;
	}

	public function get_columns() : array {
		return array(
			'cb'      => '<input type="checkbox" />',
			'id'      => __( 'ID', 'coresocial' ),
			'network' => __( 'Network', 'coresocial' ),
			'module'  => __( 'Module', 'coresocial' ),
			'item'    => __( 'Item', 'coresocial' ),
			'real_id' => __( 'Real ID', 'coresocial' ),
			'user_id' => __( 'User', 'coresocial' ),
			'url'     => __( 'URL', 'coresocial' ),
			'logged'  => __( 'Logged', 'coresocial' ),
		);
	}

	public function prepare_items() {
		$this->prepare_column_headers();

		$per_page = $this->rows_per_page();
		$sel_item = $this->get_request_arg( 'filter-item' );
		$sel_net  = $this->get_request_arg( 'filter-network' );
		$sel_id   = $this->get_request_arg( 'filter-item_id' );

		$sql = array(
			'select' => array(
				'l.*',
				'i.`item`',
				'i.`item_id` AS `real_id`',
				'i.`url`',
			),
			'from'   => array(
				coresocial_db()->log . ' l ',
				'INNER JOIN ' . coresocial_db()->items . ' i ON i.`id` = l.`item_id`',
			),
			'where'  => array(),
		);

		if ( ! empty( $sel_item ) ) {
			$sql['where'][] = coresocial_db()->prepare( 'i.`item` = %s', $sel_item );
		} else if ( $sel_id > 0 ) {
			$sql['where'][] = coresocial_db()->prepare( 'l.`item_id` = %d', $sel_id );
		}

		if ( ! empty( $sel_net ) ) {
			$sql['where'][] = coresocial_db()->prepare( 'l.`network` = %s', $sel_net );
		}

		$this->query_items( $sql, $per_page );
	}

	protected function db() : ?DBLite {
		return coresocial_db();
	}

	protected function process_request_args() {
		$this->_request_args = array(
			'filter-item'    => Sanitize::_get_slug( 'filter-item' ),
			'filter-item_id' => Sanitize::_get_absint( 'filter-item_id' ),
			'filter-network' => Sanitize::_get_slug( 'filter-network' ),
			'search'         => $this->_get_field( 's' ),
			'orderby'        => $this->_get_field( 'orderby', 'l.id' ),
			'order'          => $this->_get_field( 'order', 'DESC' ),
			'paged'          => $this->_get_field( 'paged' ),
		);
	}

	protected function filter_block_top() {
		echo '<div class="alignleft actions">';
		Elements::instance()->select( $this->_networks, array(
			'selected' => $this->get_request_arg( 'filter-network' ),
			'name'     => 'filter-network',
		) );
		Elements::instance()->select( $this->_item_types, array(
			'selected' => $this->get_request_arg( 'filter-item' ),
			'name'     => 'filter-item',
		) );

		if ( $this->get_request_arg( 'filter-item_id' ) > 0 ) {
			Elements::instance()->input( $this->get_request_arg( 'filter-item_id' ), array(
				'name'        => 'filter-item_id',
				'placeholder' => __( 'Item ID', 'coresocial' ),
			) );
		}

		submit_button( __( 'Filter', 'coresocial' ), 'button', false, false, array( 'id' => 'coresocial-items-submit' ) );
		echo '</div>';
	}

	protected function get_bulk_actions() : array {
		return array(
			'delete' => __( 'Delete', 'coresocial' ),
		);
	}

	protected function get_sortable_columns() : array {
		return array(
			'id'      => array( 'l.id', false ),
			'network' => array( 'l.network', false ),
			'module'  => array( 'l.module', false ),
			'item'    => array( 'i.item', false ),
			'item_id' => array( 'l.item_id', false ),
			'user_id' => array( 'l.user_id', false ),
		);
	}

	protected function column_id( $item ) : string {
		return $item->id;
	}

	protected function column_module( $item ) : string {
		return ucfirst( $item->module );
	}

	protected function column_user_id( $item ) : string {
		if ( $item->user_id == 0 ) {
			return '<div><i class="d4p-icon d4p-ui-user-square"></i><span>' . __( 'Visitor', 'coresocial' ) . '</span></div>';
		} else {
			return '<div>' . get_avatar( $item->user_id, 28 ) . '<span>' . WPR::get_user_display_name( $item->user_id ) . '</span></div>';
		}
	}

	protected function column_item( $item ) : string {
		return $this->_item_types[ $item->item ] ?? ucfirst( $item->item );
	}

	protected function column_network( $item ) : string {
		$label = $this->_networks[ $item->network ] ?? ucfirst( $item->network );

		return '<div class="coresocial-log-network" style="background: rgb(var(--coresocial-color-' . $item->network . '-primary));"><i class="coresocial-icon coresocial-icon-' . $item->network . ' coresocial-mod-fw"></i><span>' . $label . '</span></div>';
	}

	public function column_logged( $item ) : string {
		$timestamp = coresocial()->datetime()->timestamp_gmt_to_local( strtotime( $item->logged ) );

		return gmdate( 'Y.m.d', $timestamp ) . '<br/>@ ' . gmdate( 'H:m:s', $timestamp );
	}
}
