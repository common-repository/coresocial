<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Helpers\IP;
use Dev4Press\v50\Core\Quick\BBP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helper {
	public static function get_post_types() : array {
		$list = array();
		$skip = array( 'attachment', 'wp_block', 'wp_navigation' );
		$skip = array_merge( $skip, BBP::get_post_types() );

		$post_types = get_post_types( array(
			'show_ui' => true,
			'public'  => true,
		), 'objects' );

		foreach ( $post_types as $post_type => $object ) {
			if ( ! in_array( $post_type, $skip ) ) {
				$list[ $post_type ] = $object->label;
			}
		}

		/**
		 * Filters the list of supported post types.
		 *
		 * @param array $post_types_list All public post types, with some exceptions.
		 */
		return apply_filters( 'coresocial_auto_embed_supported_post_types', $list );
	}

	public static function get_counts_methods() : array {
		return array(
			'online_fallback' => __( 'Online if available, internal as fallback', 'coresocial' ),
			'combined'        => __( 'Combined, internal with online', 'coresocial' ),
			'average'         => __( 'Average, internal and online', 'coresocial' ),
			'online'          => __( 'Online only', 'coresocial' ),
			'internal'        => __( 'Internal only', 'coresocial' ),
		);
	}

	public static function get_default_post_settings() : array {
		return array(
			'twitter_account'       => '',
			'twitter_hashtags'      => 'merge',
			'twitter_hashtags_list' => '',
			'inline_location'       => 'inherit',
		);
	}

	public static function get_visitor_hash() : string {
		if ( ! is_user_logged_in() && coresocial_settings()->get( 'log_visitors_hash' ) ) {
			return self::random_section( hash( 'sha512', IP::visitor() ), 32, 0 );
		}

		return '';
	}

	public static function validate_check( string $check, string $action, string $network, string $item, int $item_id, string $item_url ) : bool {
		$full = $action . '-' . $network . '-' . $item . '-' . $item_id . '-' . $item_url;
		$test = hash( 'sha512', $full );

		return strlen( $check ) === 16 && strpos( $test, $check ) !== false;
	}

	public static function generate_check( string $action, string $network, string $item, int $item_id, string $item_url ) : string {
		$full  = $action . '-' . $network . '-' . $item . '-' . $item_id . '-' . $item_url;
		$check = hash( 'sha512', $full );

		return self::random_section( $check, 16 );
	}

	public static function random_section( string $input, int $length, int $from = - 1 ) : string {
		$max = strlen( $input );

		if ( $from < 0 ) {
			$from = wp_rand( 0, $max / 2 );

			if ( $from + $length > $max ) {
				$from = $max - $length;
			}
		}

		return substr( $input, $from, $length );
	}

	public static function render_vars( array $vars = array(), string $id = 'coresocial-root-overrides', string $element = ':root' ) : string {
		$render = array();

		foreach ( $vars as $var => $value ) {
			$render[] = $var . ': ' . $value . ';';
		}

		if ( ! empty( $render ) ) {
			return '<style id="' . esc_attr( $id ) . '">' . PHP_EOL . $element . ' {' . PHP_EOL . DEV4PRESS_TAB . join( PHP_EOL . DEV4PRESS_TAB, $render ) . PHP_EOL . '}' . PHP_EOL . '</style>';
		}

		return '';
	}
}
