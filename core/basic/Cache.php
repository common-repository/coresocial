<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Cache\Core;
use Dev4Press\v50\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cache extends Core {
	public $store = 'coresocial';

	public static function instance() : Cache {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Cache();
		}

		return $instance;
	}

	public function get_item_id( string $item, int $item_id, string $item_url, string $item_hash = '' ) {
		$item_url = WPR::remove_site_url( $item_url );
		$item_key = md5( $item . '-' . $item_id . '-' . $item_url );

		if ( empty( $item_hash ) && $item == 'custom' ) {
			$item_hash = md5( $item_url );
		}

		if ( ! $this->in( 'item-id', $item_key ) ) {
			$id = DB::instance()->get_item_db_id( $item, $item_id, $item_url, $item_hash );

			$this->set( 'item-id', $item_key, $id );
		}

		return $this->get( 'item-id', $item_key );
	}

	public function init_item_data( string $item, int $item_id, string $item_url, bool $force = false ) {
		$item_url = WPR::remove_site_url( $item_url );

		if ( $item == 'custom' ) {
			$hash = md5( $item_url );

			if ( ! $this->in( $item, $hash ) || $force ) {
				$this->set( $item, $hash, true );

				$data = coresocial_db()->get_item_counts( $item, $item_id, $item_url, $hash );

				foreach ( $data as $network => $obj ) {
					$this->set( $network, $item . '-' . $hash, $obj );
				}
			}
		} else {
			if ( ! $this->in( $item, $item_id ) || $force ) {
				$this->set( $item, $item_id, true );

				$data = coresocial_db()->get_item_counts( $item, $item_id, $item_url );

				foreach ( $data as $network => $obj ) {
					$this->set( $network, $item . '-' . $item_id, $obj );
				}
			}
		}
	}

	public function get_item_network_count( string $item, int $item_id, string $item_url, string $network, int $pad = 0 ) : int {
		$obj = $this->get_item_network_object( $item, $item_id, $item_url, $network );

		return $this->process_count_to_return( $obj, $pad );
	}

	public function get_item_network_object( string $item, int $item_id, string $item_url, string $network ) : array {
		$item_url = WPR::remove_site_url( $item_url );

		if ( $item == 'custom' ) {
			$hash = md5( $item_url );

			if ( $this->in( $network, $item . '-' . $hash ) ) {
				return $this->get( $network, $item . '-' . $hash );
			}
		} else {
			if ( $this->in( $network, $item . '-' . $item_id ) ) {
				return $this->get( $network, $item . '-' . $item_id );
			}
		}

		return array(
			'id'       => 0,
			'item'     => array(
				'id'   => $item_id,
				'url'  => $item_url,
				'hash' => $hash ?? '',
			),
			'network'  => $network,
			'internal' => 0,
			'online'   => 0,
			'updated'  => 0,
		);
	}

	private function process_count_to_return( $data, int $pad = 0 ) : int {
		$method = coresocial_settings()->get( 'show_counts' );

		switch ( $method ) {
			default:
			case 'combined':
				$result = $data['online'] + $data['internal'];
				break;
			case 'average':
				$result = $data['online'] > 0 && $data['internal'] > 0 ? ceil( ( $data['online'] + $data['internal'] ) / 2 ) : $data['online'] + $data['internal'];
				break;
			case 'online':
				$result = $data['online'] + $pad;
				break;
			case 'internal':
				$result = $data['internal'];
				break;
			case 'online_fallback':
				$result = $data['online'] > 0 ? $data['online'] + $pad : $data['internal'];
				break;
		}

		return $result;
	}
}
