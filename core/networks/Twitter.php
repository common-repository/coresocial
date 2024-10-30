<?php

namespace Dev4Press\Plugin\coreSocial\Networks;

use Dev4Press\Plugin\coreSocial\Base\Network;
use Dev4Press\Plugin\coreSocial\Sharing\Post;
use Dev4Press\Plugin\coreSocial\Sharing\Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string   $account
 * @property string   $url
 * @property bool     $x
 * @property string[] $hashtags
 */
class Twitter extends Network {
	protected string $network = 'twitter';
	protected array $urls = array(
		'x-share'        => 'https://x.com/share',
		'x-intent'       => 'https://x.com/intent/tweet',
		'twitter-share'  => 'https://twitter.com/share',
		'twitter-intent' => 'https://twitter.com/intent/tweet',
	);

	public static function instance() : Twitter {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Twitter();
		}

		return $instance;
	}

	public function icon() : string {
		return '<i class="coresocial-icon coresocial-icon-' . ( $this->x ? 'x' : $this->network ) . '"></i>';
	}

	public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string {
		$title = preg_replace( '/(?<!&)#/', ' ', $title );

		/**
		 * Filters the Twitter account to include with the share block URL.
		 *
		 * @param string $account Account name, default is from plugin settings.
		 * @param int    $item_id ID of the item that will be shared.
		 */
		$account = apply_filters( 'coresocial_network_twitter_link_account', $this->get_account( $item, $item_id ), $item_id );

		/**
		 * Filters the Twitter hashtags to include with the share block URL.
		 *
		 * @param array $hashtags List of hashtags, default are from plugin settings.
		 * @param int   $item_id  ID of the item that will be shared.
		 */
		$hashtags = apply_filters( 'coresocial_network_twitter_link_hashtags', $this->get_hashtags( $item, $item_id ), $item_id );

		$url_type = $this->settings['url'];

		if ( ! isset( $this->urls[ $url_type ] ) ) {
			$url_type = 'x-share';
		}

		$final = $this->urls[ $url_type ] . '?text=' . esc_attr( $title );
		$final .= '&url=' . esc_attr( $url );

		if ( ! empty( $account ) ) {
			$final .= '&via=' . esc_attr( $account );
		}

		if ( ! empty( $hashtags ) ) {
			$final .= '&hashtags=' . esc_attr( join( ',', $hashtags ) );
		}

		return $final;
	}

	private function get_account( string $item = 'post', int $item_id = 0 ) : string {
		$global = $this->account;
		$local  = '';

		if ( $item == 'post' && $item_id > 0 ) {
			$local = Post::instance( $item_id )->twitter_account;
		} else if ( $item == 'term' && $item_id > 0 ) {
			$local = Term::instance( $item_id )->twitter_account;
		}

		if ( ! empty( $local ) ) {
			return $local;
		}

		return $global;
	}

	private function get_hashtags( string $item = 'post', int $item_id = 0 ) : array {
		$global = $this->hashtags;
		$local  = '';
		$extra  = '';

		if ( $item == 'post' && $item_id > 0 ) {
			$local = Post::instance( $item_id )->twitter_hashtags;
			$extra = Post::instance( $item_id )->twitter_hashtags_list;
		} else if ( $item == 'term' && $item_id > 0 ) {
			$local = Term::instance( $item_id )->twitter_hashtags;
			$extra = Term::instance( $item_id )->twitter_hashtags_list;
		}

		if ( $local === 'local' ) {
			$global = array();
		}

		if ( ! empty( $extra ) ) {
			$extra = explode( ',', $extra );
			$extra = array_map( 'trim', $extra );

			$global = array_merge( $global, $extra );
		}

		$global = array_unique( $global );

		return array_filter( $global );
	}
}
