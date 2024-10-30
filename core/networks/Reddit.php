<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Reddit extends Network {
	protected string $network = 'reddit';

	public static function instance() : Reddit {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Reddit();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return sprintf( 'https://www.reddit.com/submit?url=%1$s&title=%2$s', esc_attr( $url ), esc_attr( $title ) );
	}
}
