<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MailTo extends Network {
	protected string $network = 'mailto';
	protected string $action = 'link';

	public static function instance() : MailTo {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new MailTo();
		}

		return $instance;
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		return sprintf( 'mailto:?body=%1$s&subject=%2$s', esc_attr( $url ), esc_attr( $title ) );
	}
}
