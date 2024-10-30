<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\Plugin\coreSocial\Sharing\Loader;
use Dev4Press\v50\Core\Admin\GetBack as BaseGetBack;
use Dev4Press\v50\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GetBack extends BaseGetBack {
	protected function process() {
		parent::process();

		if ( ! empty( $this->a()->panel ) ) {
			if ( $this->is_bulk_action() ) {
				if ( $this->a()->panel == 'items' ) {
					$this->bulk_panel_items();
				} else if ( $this->a()->panel == 'log' ) {
					$this->bulk_panel_log();
				}
			} else {
				if ( $this->a()->panel == 'items' ) {
					$this->action_panel_items();
				}
			}
		}
	}

	private function bulk_panel_items() {
		check_admin_referer( 'bulk-items' );

		$action  = $this->get_bulk_action();
		$message = 'nothing';

		if ( $action != '' ) {
			$ids = Sanitize::_get_ids( 'item' );

			if ( ! empty( $ids ) ) {
				switch ( $action ) {
					case 'delete':
						$message = 'items-deleted';

						foreach ( $ids as $id ) {
							DB::instance()->delete_item( $id );
						}
						break;
					case 'clear':
						$message = 'items-cleared';

						foreach ( $ids as $id ) {
							DB::instance()->clear_item( $id );
						}
						break;
					case 'queue':
						$message = 'items-queued';

						foreach ( $ids as $id ) {
							Loader::instance()->maybe_add_to_queue_by_id( $id );
						}

						coresocial()->s()->save( 'storage' );
						coresocial()->spawn_queue_job();
						break;
				}
			}
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}

	private function bulk_panel_log() {
		check_admin_referer( 'bulk-logs' );

		$action  = $this->get_bulk_action();
		$message = 'nothing';

		if ( $action != '' ) {
			$ids = Sanitize::_get_ids( 'log' );

			if ( ! empty( $ids ) ) {
				if ( $action == 'delete' ) {
					$message = 'logs-deleted';

					DB::instance()->delete_log_entries( $ids );
				}
			}
		}

		wp_redirect( $this->a()->current_url() . '&message=' . $message );
		exit;
	}

	private function action_panel_items() {
		$action = $this->get_single_action();

		if ( ! empty( $action ) ) {
			$item    = Sanitize::_get_absint( 'item' );
			$message = 'nothing';

			if ( in_array( $action, array( 'delete-item', 'clear-item', 'queue-item' ) ) ) {
				check_admin_referer( 'coresocial-' . $action . '-' . $item );

				switch ( $action ) {
					case 'delete-item':
						$message = 'item-deleted';

						DB::instance()->delete_item( $item );
						break;
					case 'clear-item':
						$message = 'item-cleared';

						DB::instance()->clear_item( $item );
						break;
					case 'queue-item':
						$message = 'item-queued';

						Loader::instance()->maybe_add_to_queue_by_id( $item );

						coresocial()->s()->save( 'storage' );
						coresocial()->spawn_queue_job();
						break;
				}
			}

			wp_redirect( $this->a()->current_url() . '&message=' . $message );
			exit;
		}
	}
}
