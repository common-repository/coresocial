<?php

namespace Dev4Press\Plugin\coreSocial\Sharing;

use Dev4Press\Plugin\coreSocial\Basic\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $twitter_account
 * @property string $twitter_hashtags
 * @property string $twitter_hashtags_list
 * @property string $inline_location
 */
class Term {
	private $meta;

	public function __construct( $term_id ) {
		$meta = get_term_meta( $term_id, '_coresocial_settings', true );

		$this->meta = Helper::get_default_post_settings();

		if ( is_array( $meta ) && ! empty( $meta ) ) {
			$this->meta = wp_parse_args( $meta, $this->meta );
		}
	}

	public function __get( $name ) {
		if ( isset( $this->meta[ $name ] ) ) {
			return $this->meta[ $name ];
		}

		return false;
	}

	public function get_meta() : array {
		return $this->meta;
	}

	public static function instance( $term_id ) : Term {
		static $instance = array();

		if ( ! isset( $instance[ $term_id ] ) ) {
			$instance[ $term_id ] = new Term( $term_id );
		}

		return $instance[ $term_id ];
	}
}
