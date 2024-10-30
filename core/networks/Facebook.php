<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $method
 * @property string $app_id
 * @property string $redirect
 * @property string $hashtag
 */
class Facebook extends Network {
	protected string $network = 'facebook';
	protected bool $online_counts = true;

	public function __construct() {
		parent::__construct();

		if ( empty( $this->settings['app_id'] ) || empty( $this->settings['redirect'] ) ) {
			$this->settings['method'] = 'legacy';
		}

		if ( empty( $this->settings['app_token'] ) || ! $this->settings['online'] ) {
			$this->online_counts = false;
		}
	}

	public static function instance() : Facebook {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Facebook();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		if ( $this->method == 'legacy' ) {
			return sprintf( 'https://www.facebook.com/sharer.php?u=%1$s&t=%2$s', esc_attr( $url ), esc_attr( $title ) );
		} else {
			return sprintf( 'https://www.facebook.com/dialog/share?app_id=%2$s&href=%1$s&display=popup&redirect_uri=%3$s&hashtag=%4$s', esc_attr( $url ), esc_attr( $this->app_id ), esc_attr( $this->redirect ), esc_attr( $this->hashtag ) );
		}
	}

	public function get_online_count( string $url ) : array {
		$result = parent::get_online_count( $url );

		if ( ! empty( $this->settings['app_token'] ) ) {
			$endpoint = 'https://graph.facebook.com/v17.0/?id=' . urlencode( $url ) . '&fields=engagement&access_token=' . $this->settings['app_token'];

			$response = wp_remote_get( $endpoint, array(
				'timeout' => 15,
			) );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$body = json_decode( $response['body'], true );

				if ( is_array( $body ) ) {
					if ( isset( $body['engagement']['share_count'] ) ) {
						$result['count'] = absint( $body['engagement']['share_count'] );
					}
				}
			} else {
				$result['status'] = 'error';

				if ( is_wp_error( $response ) ) {
					$result['error'] = $response->get_error_message();
				}
			}
		}

		return $result;
	}
}
