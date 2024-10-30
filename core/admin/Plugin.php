<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

use Dev4Press\Plugin\coreSocial\Basic\Plugin as CorePlugin;
use Dev4Press\Plugin\coreSocial\Basic\Settings as CoreSettings;
use Dev4Press\Plugin\coreSocial\Basic\Wizard;
use Dev4Press\v50\Core\Admin\Menu\Plugin as BasePlugin;
use Dev4Press\v50\Core\Quick\Sanitize;
use Dev4Press\v50\Core\UI\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends BasePlugin {
	public $plugin = 'coresocial';
	public $plugin_prefix = 'coresocial';
	public $plugin_menu = 'coreSocial';
	public $plugin_title = 'coreSocial';

	public $auto_mod_interface_colors = true;
	public $has_widgets = true;
	public $has_metabox = true;

	public $enqueue_wp = array(
		'dialog'       => true,
		'color_picker' => true,
	);
	public $per_page_options = array(
		'coresocial_log_rows_per_page',
		'coresocial_items_rows_per_page',
	);

	public static function instance() : Plugin {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Plugin();
		}

		return $instance;
	}

	public function constructor() {
		$this->url  = CORESOCIAL_URL;
		$this->path = CORESOCIAL_PATH;

		MetaBoxes::instance();

		add_filter( $this->h( 'render_option_call_back_for_social_profiles' ), function() {
			return array( coresocial_admin(), 'render_social_profile' );
		} );
		add_filter( $this->h( 'process_option_call_back_for_social_profiles' ), array(
			$this,
			'process_social_profile',
		), 10, 3 );
	}

	public function register_scripts_and_styles() {
		$this->enqueue->register( 'css', 'coresocial-admin',
			array(
				'path' => 'css/',
				'file' => 'admin',
				'ext'  => 'css',
				'min'  => true,
				'ver'  => coresocial_settings()->file_version(),
				'src'  => 'plugin',
			) )->register( 'css', 'coresocial-meta',
			array(
				'path' => 'css/',
				'file' => 'meta',
				'ext'  => 'css',
				'min'  => true,
				'ver'  => coresocial_settings()->file_version(),
				'src'  => 'plugin',
			) )->register( 'js', 'coresocial-admin',
			array(
				'path' => 'js/',
				'file' => 'admin',
				'ext'  => 'js',
				'min'  => true,
				'ver'  => coresocial_settings()->file_version(),
				'src'  => 'plugin',
			) );
	}

	protected function extra_enqueue_scripts_plugin() {
		$this->enqueue->css( 'coresocial-admin' );
		$this->enqueue->js( 'coresocial-admin' );
	}

	protected function extra_enqueue_scripts_metabox( $hook ) {
		$this->enqueue->css( 'coresocial-meta' );
	}

	public function render_social_profile( $element, $value, $name_base, $id_base ) {
		$this->_profile_element( $name_base . '[items][0]', $id_base . '_items_0', 0, array(
			'id'      => '',
			'network' => '',
			'name'    => '',
			'url'     => '',
			'count'   => '',
		), $element, true );

		foreach ( $value['items'] as $item_id => $item ) {
			$this->_profile_element( $name_base . '[items][' . $item_id . ']', $id_base . '_items_' . $item_id, $item_id, $item, $element );
		}

		echo '<a role="button" class="button-primary" href="#">' . esc_html__( 'Add New Profile', 'coresocial' ) . '</a>';
		echo '<input name="' . esc_attr( $name_base ) . '[id]" type="hidden" value="' . esc_attr( $value['id'] ) . '" class="d4p-next-id" />';
	}

	public function process_social_profile( $value, $post, $setting ) : array {
		$_networks = coresocial_profiles()->list_networks_names();

		$value = array(
			'id'    => absint( $post['id'] ),
			'items' => array(),
		);

		foreach ( $post['items'] as $item ) {
			$id      = absint( $item['id'] );
			$network = sanitize_key( $item['network'] );

			if ( $id > 0 && isset( $_networks[ $network ] ) ) {
				$name = Sanitize::text( $item['name'] );

				$valid = array(
					'id'      => $id,
					'network' => $network,
					'name'    => empty( $name ) ? $_networks[ $network ] : $name,
					'url'     => Sanitize::url( $item['url'] ),
					'count'   => absint( $item['count'] ),
					'label'   => Sanitize::slug( $item['label'] ),
				);

				$value['items'][ $id ] = $valid;
			}
		}

		return $value;
	}

	public function svg_icon() : string {
		return coresocial()->svg_icon;
	}

	public function admin_menu_items() {
		$this->setup_items = array(
			'install' => array(
				'title' => __( 'Install', 'coresocial' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin installation was successful.', 'coresocial' ),
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Install',
			),
			'update'  => array(
				'title' => __( 'Update', 'coresocial' ),
				'icon'  => 'ui-traffic',
				'type'  => 'setup',
				'info'  => __( 'Before you continue, make sure plugin was successfully updated.', 'coresocial' ),
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Update',
			),
		);

		$this->menu_items = array(
			'dashboard' => array(
				'title' => __( 'Overview', 'coresocial' ),
				'icon'  => 'ui-home',
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Dashboard',
			),
			'about'     => array(
				'title' => __( 'About', 'coresocial' ),
				'icon'  => 'ui-info',
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\About',
			),
			'items'     => array(
				'title' => __( 'Shared Items', 'coresocial' ),
				'icon'  => 'ui-folder',
				'info'  => __( 'All the pages registered with the coreSocial for the sharing tracking.', 'coresocial' ),
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Items',
			),
			'log'       => array(
				'title' => __( 'Share Logs', 'coresocial' ),
				'icon'  => 'ui-memo-pad',
				'info'  => __( 'Tracking of all the share, like and QR Code display actions.', 'coresocial' ),
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Log',
			),
			'settings'  => array(
				'title' => __( 'Settings', 'coresocial' ),
				'icon'  => 'ui-cog',
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Settings',
			),
			'tools'     => array(
				'title' => __( 'Tools', 'coresocial' ),
				'icon'  => 'ui-wrench',
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Tools',
			),
		);

		if ( $this->settings()->get( 'show_setup_wizard' ) ) {
			$this->menu_items['wizard'] = array(
				'title' => __( 'Setup Wizard', 'coresocial' ),
				'icon'  => 'ui-magic',
				'class' => '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Panel\\Wizard',
			);
		}
	}

	public function run_getback() {
		new GetBack( $this );
	}

	public function run_postback() {
		new PostBack( $this );
	}

	public function message_process( $code, $msg ) {
		switch ( $code ) {
			case 'logs-deleted':
				$msg['message'] = __( 'Selected log entries have been deleted.', 'coresocial' );
				break;
			case 'items-deleted':
				$msg['message'] = __( 'Selected items have been deleted.', 'coresocial' );
				break;
			case 'items-cleared':
				$msg['message'] = __( 'Logged entries for selected items have been removed.', 'coresocial' );
				break;
			case 'item-deleted':
				$msg['message'] = __( 'Selected item has been deleted.', 'coresocial' );
				break;
			case 'item-cleared':
				$msg['message'] = __( 'Logged entries for selected item have been removed.', 'coresocial' );
				break;
			case 'cleanup-completed':
				$msg['message'] = __( 'Cleanup operation has been completed.', 'coresocial' );
				break;
		}

		return $msg;
	}

	public function settings() : CoreSettings {
		return coresocial_settings();
	}

	public function plugin() : CorePlugin {
		return coresocial();
	}

	public function settings_definitions() : Settings {
		return Settings::instance();
	}

	public function help_tab_getting_help() {
		if ( ! empty( $this->panel ) ) {
			new Help( $this );
		}

		parent::help_tab_getting_help();
	}

	public function wizard() {
		return Wizard::instance();
	}

	protected function _profile_element( $name, $id, $i, $value, $element, $hide = false ) {
		$value['count'] = $value['count'] ?? 0;
		$value['label'] = $value['label'] ?? 'follower';

		echo '<div class="d4p-profile-element-wrapper profile-element-' . esc_attr( $i ) . '" style="display: ' . ( $hide ? 'none' : 'block' ) . '">';
		echo '<input type="hidden" id="' . esc_attr( $id ) . '_id" name="' . esc_attr( $name ) . '[id]" value="' . esc_attr( $value['id'] ) . '" />';

		echo '<label for="' . esc_attr( $id ) . '_network">' . esc_html__( 'Network', 'coresocial' ) . ':</label>';
		Elements::instance()->select( coresocial_profiles()->list_networks_names(), array(
			'selected' => $value['network'],
			'name'     => $name . '[network]',
			'id'       => $id . '_network',
			'class'    => 'widefat',
		) );

		echo '<label for="' . esc_attr( $id ) . '_name">' . esc_html__( 'Profile Name', 'coresocial' ) . ':</label>';
		echo '<input type="text" name="' . esc_attr( $name ) . '[name]" id="' . esc_attr( $id ) . '_name" value="' . esc_attr( $value['name'] ) . '" class="widefat" />';

		echo '<label for="' . esc_attr( $id ) . '_url">' . esc_html__( 'Full Profile URL', 'coresocial' ) . ':</label>';
		echo '<input type="url" placeholder="https://" name="' . esc_attr( $name ) . '[url]" id="' . esc_attr( $id ) . '_url" value="' . esc_attr( $value['url'] ) . '" class="widefat" />';

		echo '<label for="' . esc_attr( $id ) . '_count">' . esc_html__( 'Counter', 'coresocial' ) . ':</label>';
		echo '<input type="number" name="' . esc_attr( $name ) . '[count]" id="' . esc_attr( $id ) . '_count" value="' . esc_attr( $value['count'] ) . '" class="widefat" />';

		echo '<label for="' . esc_attr( $id ) . '_label">' . esc_html__( 'Counter Label', 'coresocial' ) . ':</label>';
		Elements::instance()->select( array(
			'follower'   => __( 'Follower', 'coresocial' ),
			'fan'        => __( 'Fan', 'coresocial' ),
			'subscriber' => __( 'Subscriber', 'coresocial' ),
			'friend'     => __( 'Friend', 'coresocial' ),
			'patron'     => __( 'Patron', 'coresocial' ),
			'star'       => __( 'Star', 'coresocial' ),
			'member'     => __( 'Member', 'coresocial' ),
			'user'       => __( 'User', 'coresocial' ),
		), array(
			'selected' => $value['label'],
			'name'     => $name . '[label]',
			'id'       => $id . '_network',
			'class'    => 'widefat',
		) );

		echo '<div>';
		echo '<a role="button" class="button-secondary" href="#">' . esc_html__( 'Remove this Profile', 'coresocial' ) . '</a>';
		echo '<span style="float: right">ID: <strong>' . esc_html( $i ) . '</strong></span>';
		echo '</div>';
		echo '</div>';
	}
}
