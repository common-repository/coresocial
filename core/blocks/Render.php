<?php

namespace Dev4Press\Plugin\coreSocial\Blocks;

use Dev4Press\Plugin\coreSocial\Display\Layouts;
use Dev4Press\Plugin\coreSocial\Methods\Inline;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Render {
	public function __construct() {
	}

	public static function instance() : Render {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Render();
		}

		return $instance;
	}

	public function profiles( $attributes ) : string {
		$layouts = Layouts::instance()->prepare( $attributes );

		return $layouts->icons();
	}

	public function share( $post_id, $attributes ) : string {
		return Inline::instance()->block( $post_id, $attributes );
	}
}
