<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tumblr extends Network {
	protected string $network = 'tumblr';
	protected bool $online_counts = true;

	public function __construct() {
		parent::__construct();

		if ( ! $this->settings['online'] ) {
			$this->online_counts = false;
		}
	}

	public static function instance() : Tumblr {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Tumblr();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return sprintf( 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=%1$s&title=%2$s', esc_attr( $url ), esc_attr( $title ) );
	}

	public function get_online_count( string $url ) : array {
		$result = parent::get_online_count( $url );

		$endpoint = 'https://api.tumblr.com/v2/share/stats?url=' . urlencode( $url );

		$response = wp_remote_get( $endpoint, array(
			'timeout' => 15,
		) );

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$body = json_decode( $response['body'], true );

			if ( is_array( $body ) ) {
				if ( isset( $body['response']['note_count'] ) ) {
					$result['count'] = absint( $body['response']['note_count'] );
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
