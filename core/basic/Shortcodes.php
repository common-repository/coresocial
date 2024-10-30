<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\Plugin\coreSocial\Display\Layouts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcodes {
	public $codes = array();

	public function __construct() {
		$this->setup_globals();
		$this->add_shortcodes();
	}

	public static function instance() : Shortcodes {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Shortcodes();
		}

		return $instance;
	}

	private function setup_globals() {
		$this->codes = apply_filters( 'coresocial_shortcodes', array(
			'coresocial-profiles-list' => array( $this, 'display_profiles_list' ),
			'coresocial-share-inline'  => array( $this, 'display_share_inline' ),
		) );
	}

	private function add_shortcodes() {
		foreach ( $this->codes as $code => $function ) {
			add_shortcode( $code, $function );
		}
	}

	public function display_profiles_list( $atts = array() ) : string {
		$layouts = Layouts::instance()->prepare( $atts );

		$render = '<div class="coresocial-shortcode-profiles-list">';
		$render .= $layouts->icons();
		$render .= '</div>';

		return $render;
	}

	public function display_share_inline( $atts = array() ) : string {
		$atts = empty( $atts ) ? array() : ( is_array( $atts ) ? $atts : wp_parse_args( $atts ) );

		return coresocial_display_inline( $atts, false );
	}
}
