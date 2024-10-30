<?php

namespace Dev4Press\Plugin\coreSocial\Admin;

use Dev4Press\Plugin\coreSocial\Basic\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MetaBoxes {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_meta' ) );

		add_action( 'coresocial_admin_metabox_social_meta_content_twitter', array(
			$this,
			'metabox_content_twitter',
		), 10, 2 );
		add_action( 'coresocial_admin_metabox_social_meta_content_stats', array(
			$this,
			'metabox_content_stats',
		), 10, 2 );
		add_action( 'coresocial_admin_metabox_social_meta_content_online', array(
			$this,
			'metabox_content_online',
		), 10, 2 );
		add_action( 'coresocial_admin_metabox_social_meta_content_inline', array(
			$this,
			'metabox_content_inline',
		), 10, 2 );

		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	public static function instance() : MetaBoxes {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new MetaBoxes();
		}

		return $instance;
	}

	public function admin_meta() {
		$post_types = array_keys( Helper::get_post_types() );

		add_meta_box( 'coresocial-metabox', __( 'coreSocial: Sharing', 'coresocial' ), array(
			$this,
			'metabox_social',
		), $post_types, 'advanced', 'high' );
	}

	public function metabox_social() {
		include CORESOCIAL_PATH . 'forms/metabox-social.php';
	}

	public function metabox_content_twitter( $post_ID, $meta_data ) {
		include CORESOCIAL_PATH . 'forms/metabox-social-twitter.php';
	}

	public function metabox_content_stats( $post_ID, $meta_data ) {
		include CORESOCIAL_PATH . 'forms/metabox-social-stats.php';
	}

	public function metabox_content_inline( $post_ID, $meta_data ) {
		include CORESOCIAL_PATH . 'forms/metabox-social-inline.php';
	}

	public function metabox_content_online( $post_ID, $meta_data ) {
		include CORESOCIAL_PATH . 'forms/metabox-social-online.php';
	}

	public function save_post( $post_id ) {
		$_nonce_value = ! empty( $_POST['coresocial_social_nonce'] ) ? sanitize_key( $_POST['coresocial_social_nonce'] ) : '';

		if ( wp_verify_nonce( $_nonce_value, 'coresocial-post-' . $post_id ) ) {
			if ( isset( $_POST['coresocial_social_settings'] ) && sanitize_key( $_POST['coresocial_social_settings'] ) === 'edit' ) {
				$data = (array) $_POST['coresocial_settings'] ?? array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$meta = Helper::get_default_post_settings();

				$_keys = array(
					'twitter_account',
					'twitter_hashtags',
					'inline_location',
				);

				foreach ( $_keys as $key ) {
					if ( isset( $data[ $key ] ) ) {
						$meta[ $key ] = sanitize_key( $data[ $key ] );
					}
				}

				if ( isset( $data['twitter_hashtags_list'] ) ) {
					$list = explode( ',', $data['twitter_hashtags_list'] );
					$list = array_map( 'trim', $list );
					$list = array_map( 'Dev4Press\v50\Core\Quick\Sanitize::text', $list );
					$list = array_unique( $list );
					$list = array_filter( $list );

					$meta['twitter_hashtags_list'] = join( ', ', $list );
				}

				update_post_meta( $post_id, '_coresocial_settings', $meta );
			}
		}
	}
}
