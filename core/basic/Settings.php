<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Plugins\Settings as BaseSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public $base = 'coresocial';
	public $plugin = 'coresocial';
	public $has_db = true;

	public $settings = array(
		'core'     => array(
			'installed'  => '',
			'updated'    => '',
			'db_version' => 0,
		),
		'storage'  => array(
			'queue' => array(),
		),
		'profiles' => array(
			'list' => array(
				'id'    => 1,
				'items' => array(),
			),
		),
		'networks' => array(
			'twitter_active'          => true,
			'twitter_x'               => false,
			'twitter_url'             => 'x-share',
			'twitter_color_primary'   => '#1da1f2',
			'twitter_color_text'      => '#ffffff',
			'twitter_color_icon'      => '#ffffff',
			'twitter_name'            => 'Twitter',
			'twitter_label'           => 'Tweet',
			'twitter_account'         => '',
			'twitter_hashtags'        => array(),
			'facebook_active'         => true,
			'facebook_color_primary'  => '#3b5998',
			'facebook_color_text'     => '#ffffff',
			'facebook_color_icon'     => '#ffffff',
			'facebook_name'           => 'Facebook',
			'facebook_label'          => 'Share',
			'facebook_hashtag'        => '',
			'facebook_method'         => 'legacy',
			'facebook_app_id'         => '',
			'facebook_redirect'       => '',
			'facebook_online'         => false,
			'facebook_app_token'      => '',
			'reddit_active'           => true,
			'reddit_color_primary'    => '#ff4500',
			'reddit_color_text'       => '#ffffff',
			'reddit_color_icon'       => '#ffffff',
			'reddit_name'             => 'Reddit',
			'reddit_label'            => 'Submit',
			'tumblr_active'           => true,
			'tumblr_color_primary'    => '#35465c',
			'tumblr_color_text'       => '#ffffff',
			'tumblr_color_icon'       => '#ffffff',
			'tumblr_name'             => 'Tumblr',
			'tumblr_label'            => 'Share',
			'tumblr_online'           => false,
			'mix_active'              => false,
			'mix_color_primary'       => '#fd8235',
			'mix_color_text'          => '#ffffff',
			'mix_color_icon'          => '#ffffff',
			'mix_name'                => 'Mix',
			'mix_label'               => 'Mix',
			'linkedin_active'         => true,
			'linkedin_color_primary'  => '#0077b5',
			'linkedin_color_text'     => '#ffffff',
			'linkedin_color_icon'     => '#ffffff',
			'linkedin_name'           => 'LinkedIn',
			'linkedin_label'          => 'Share',
			'pinterest_active'        => false,
			'pinterest_color_primary' => '#e60023',
			'pinterest_color_text'    => '#ffffff',
			'pinterest_color_icon'    => '#ffffff',
			'pinterest_name'          => 'Pinterest',
			'pinterest_label'         => 'Pin',
			'pinterest_online'        => false,
			'yummly_active'           => false,
			'yummly_color_primary'    => '#e16120',
			'yummly_color_text'       => '#ffffff',
			'yummly_color_icon'       => '#ffffff',
			'yummly_name'             => 'Yummly',
			'yummly_label'            => 'Yum',
			'yummly_online'           => false,
			'mailto_active'           => false,
			'mailto_color_primary'    => '#ff1493',
			'mailto_color_text'       => '#ffffff',
			'mailto_color_icon'       => '#ffffff',
			'mailto_name'             => 'Email',
			'mailto_label'            => 'Email',
			'printer_active'          => false,
			'printer_color_primary'   => '#007a7a',
			'printer_color_text'      => '#ffffff',
			'printer_color_icon'      => '#ffffff',
			'printer_name'            => 'Print',
			'printer_label'           => 'Print',
		),
		'inline'   => array(
			'active'                   => true,
			'location'                 => 'bottom',
			'post_types'               => array( 'post' ),
			'networks'                 => array( 'twitter', 'facebook', 'linkedin', 'pinterest', 'mailto' ),
			'align'                    => 'center',
			'layout'                   => 'left',
			'color'                    => 'fill',
			'font_size'                => '16px',
			'button_size'              => '40px',
			'button_gap'               => '8px',
			'styling'                  => 'normal',
			'rounded'                  => '4px',
			'share_count_active'       => true,
			'share_count_hide_if_zero' => true,
			'class'                    => '',
		),
		'settings' => array(
			'show_setup_wizard'        => true,
			'load_files_on_demand'     => true,
			'log_logging_active'       => true,
			'log_visitors_hash'        => false,
			'css_font_embedded'        => true,
			'css_font_packed'          => true,
			'title_source'             => 'default',
			'title_with_site_name'     => false,
			'show_counts'              => 'online_fallback',
			'short_counts'             => true,
			'online_counts_active'     => true,
			'online_counts_posts_only' => true,
			'online_counts_period'     => 'week',
			'popup_width'              => '600',
			'popup_height'             => '400',
			'skip_url_args'            => array(
				'nowprocket',
				'no_optimize',
				'utm_medium',
				'utm_source',
				'utm_campaign',
				'utm_term',
				'utm_content',
			),
			'skip_archive_paged_pages' => true,
			'skip_search_paged_pages'  => true,
		),
	);

	protected function constructor() {
		$this->info = new Information();

		add_action( 'coresocial_load_settings', array( $this, 'init' ), 2 );
	}

	protected function _install_db() {
		return InstallDB::instance();
	}

	public function add_to_queue( $network, $url, $item, $item_id, $id ) : bool {
		if ( count( $this->current['storage']['queue'] ) > 2048 ) {
			return false;
		}

		$key = md5( $url );
		$idx = $network . '-' . $key;

		if ( ! isset( $this->current['storage']['queue'][ $idx ] ) ) {
			$this->current['storage']['queue'][ $idx ] = array(
				'network' => $network,
				'url'     => $url,
				'item'    => $item,
				'item_id' => $item_id,
				'id'      => $id,
			);

			return true;
		}

		return false;
	}

	public function remove_from_queue( $idx, $save = true ) {
		if ( isset( $this->current['storage']['queue'][ $idx ] ) ) {
			unset( $this->current['storage']['queue'][ $idx ] );

			if ( $save ) {
				$this->save( 'storage' );
			}
		}
	}
}
