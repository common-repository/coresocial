<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\Plugin\coreSocial\Basic\InstallDB;
use Dev4Press\v50\Core\Admin\PostBack as BasePostBack;
use Dev4Press\v50\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PostBack extends BasePostBack {
	protected function process() {
		parent::process();

		if ( $this->p() == $this->get_page_name( 'wizard' ) ) {
			coresocial_wizard()->panel_postback();
		}

		do_action( 'coresocial_admin_postback_handler', $this->p() );
	}

	protected function tools() {
		if ( $this->a()->subpanel == 'cleanup' ) {
			$this->cleanup();
		} else {
			parent::tools();
		}
	}

	protected function cleanup() {
		$message = 'nothing';
		$remove  = Sanitize::_get_switch_array( 'coresocial-tools', 'cleanup' );

		if ( in_array( 'empty', $remove ) ) {
			$message = 'cleanup-completed';

			DB::instance()->remove_empty_items();
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}

	protected function remove() {
		$message = 'nothing-removed';
		$remove  = Sanitize::_get_switch_array( 'coresocial-tools', 'remove' );

		if ( ! empty( $remove ) ) {
			if ( in_array( 'settings', $remove ) ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'settings' );
			}

			if ( in_array( 'networks', $remove ) ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'networks' );
			}

			if ( in_array( 'inline', $remove ) ) {
				$this->a()->settings()->remove_plugin_settings_by_group( 'inline' );
			}

			if ( in_array( 'drop', $remove ) ) {
				InstallDB::instance()->drop();

				if ( ! isset( $remove['disable'] ) ) {
					$this->a()->settings()->mark_for_update();
				}
			} else if ( in_array( 'truncate', $remove ) ) {
				InstallDB::instance()->truncate();
			}

			if ( in_array( 'disable', $remove ) ) {
				coresocial()->deactivate();

				wp_redirect( admin_url( 'plugins.php' ) );
				exit;
			}

			$message = 'removed';
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}
}
