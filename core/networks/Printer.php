<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Printer extends Network {
	protected string $network = 'printer';
	protected string $action = 'show';

	public static function instance() : Printer {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Printer();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return '';
	}
}
