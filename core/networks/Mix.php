<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Mix extends Network {
	protected string $network = 'mix';

	public static function instance() : Mix {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Mix();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return sprintf( 'https://mix.com/add?url=%1$s', esc_attr( $url ) );
	}
}
