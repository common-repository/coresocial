<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pinterest extends Network {
	protected string $network = 'pinterest';
	protected bool $online_counts = true;

	public function __construct() {
		parent::__construct();

		if ( ! $this->settings['online'] ) {
			$this->online_counts = false;
		}
	}

	public static function instance() : Pinterest {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Pinterest();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return sprintf( 'https://www.pinterest.com/pin/create/button/?url=%1$s&description=%2$s&media=%3$s', esc_attr( $url ), esc_attr( $title ), esc_url( $image_url ) );
	}

	public function get_online_count( string $url ) : array {
		$result = parent::get_online_count( $url );

		$endpoint = 'https://widgets.pinterest.com/v1/urls/count.json?url=' . $url;

		$response = wp_remote_get( $endpoint, array(
			'timeout' => 15,
		) );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			if ( substr( $response['body'], 0, 13 ) == 'receiveCount(' ) {
				$response['body'] = substr( $response['body'], 13, strlen( $response['body'] ) - 14 );
			}

			$body = json_decode( $response['body'], true );

			if ( is_array( $body ) ) {
				if ( isset( $body['count'] ) ) {
					$result['count'] = absint( $body['count'] );
				}
			}
		} else {
			$result['status'] = 'error';

			if ( is_wp_error( $response ) ) {
				$result['error'] = $response->get_error_message();
			}
		}

		return $result;
	}
}
