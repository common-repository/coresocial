<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Profiles {
	private $_profiles = array();
	private $_networks = array(
		'amazon'        => array(
			'label' => 'Amazon',
			'color' => 'ff9900',
		),
		'blogger'       => array(
			'label' => 'Blogger',
			'color' => 'f57d00',
		),
		'buffer'        => array(
			'label' => 'Buffer',
			'color' => '76b852',
		),
		'buymeacoffee'  => array(
			'label' => 'Buy Me a Coffee',
			'color' => 'fed200',
		),
		'dev'           => array(
			'label' => 'Dev',
			'color' => '05cc47',
		),
		'dev4press'     => array(
			'label' => 'Dev4Press',
			'color' => '2288cc',
		),
		'deviantart'    => array(
			'label' => 'DeviantART',
			'color' => '05cc47',
		),
		'discord'       => array(
			'label' => 'Discord',
			'color' => '7289da',
		),
		'email'         => array(
			'label' => 'Email',
			'color' => '6688aa',
		),
		'evernote'      => array(
			'label' => 'Evernote',
			'color' => '2facb2',
		),
		'facebook'      => array(
			'label' => 'Facebook',
			'color' => '3b5998',
		),
		'getpocket'     => array(
			'label' => 'GetPocket',
			'color' => 'ef4056',
		),
		'github'        => array(
			'label' => 'GitHub',
			'color' => '6e5494',
		),
		'gitlab'        => array(
			'label' => 'GitLab',
			'color' => 'fc6d26',
		),
		'google'        => array(
			'label' => 'Google',
			'color' => '4285f4',
		),
		'instagram'     => array(
			'label' => 'Instagram',
			'color' => 'e1306c',
		),
		'linkedin'      => array(
			'label' => 'LinkedIn',
			'color' => '0077b5',
		),
		'medium'        => array(
			'label' => 'Medium',
			'color' => '00ab6c',
		),
		'mix'           => array(
			'label' => 'Mix',
			'color' => 'fd8235',
		),
		'patreon'       => array(
			'label' => 'Patreon',
			'color' => 'f96854',
		),
		'paypal'        => array(
			'label' => 'PayPal',
			'color' => '012169',
		),
		'pinterest'     => array(
			'label' => 'Pinterest',
			'color' => 'bd081c',
		),
		'phone'         => array(
			'label' => 'Phone',
			'color' => '333333',
		),
		'reddit'        => array(
			'label' => 'Reddit',
			'color' => 'ff4500',
		),
		'skype'         => array(
			'label' => 'Skype',
			'color' => '00aff0',
		),
		'slack'         => array(
			'label' => 'Slack',
			'color' => '6ecadc',
		),
		'stackexchange' => array(
			'label' => 'StackExchange',
			'color' => '376db6',
		),
		'stackoverflow' => array(
			'label' => 'StackOverflow',
			'color' => 'f48024',
		),
		'telegram'      => array(
			'label' => 'Telegram',
			'color' => '0088cc',
		),
		'threads'       => array(
			'label' => 'Threads',
			'color' => '000000',
		),
		'tiktok'        => array(
			'label' => 'TikTok',
			'color' => '010101',
		),
		'trello'        => array(
			'label' => 'Trello',
			'color' => 'f2d600',
		),
		'tumblr'        => array(
			'label' => 'Tumblr',
			'color' => '35465c',
		),
		'twitter'       => array(
			'label' => 'Twitter',
			'color' => '1da1f2',
		),
		'vimeo'         => array(
			'label' => 'Vimeo',
			'color' => '1ab7ea',
		),
		'wordpress'     => array(
			'label' => 'WordPress',
			'color' => '21759b',
		),
		'x'             => array(
			'label' => 'X',
			'color' => '14171a',
		),
		'yelp'          => array(
			'label' => 'Yelp',
			'color' => 'af0606',
		),
		'youtube'       => array(
			'label' => 'YouTube',
			'color' => 'ff0000',
		),
	);

	public function __construct() {
		$this->_profiles = coresocial_settings()->get( 'list', 'profiles' );
		$this->_profiles = $this->_profiles['items'];
	}

	public static function instance() : Profiles {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Profiles();
		}

		return $instance;
	}

	public function list_networks() : array {
		return $this->_networks;
	}

	public function list_networks_names() : array {
		return wp_list_pluck( $this->_networks, 'label' );
	}

	public function list_profiles_for_select() : array {
		$profiles = array();

		foreach ( $this->_profiles as $item ) {
			$profiles[ $item['id'] ] = $item['name'];
		}

		return $profiles;
	}

	public function get( $id ) {
		if ( isset( $this->_profiles[ $id ] ) ) {
			$profile = $this->_profiles[ $id ];

			$profile['count'] = isset( $profile['count'] ) ? absint( $profile['count'] ) : 0;
			$profile['label'] = $profile['label'] ?? 'follower';
			$profile['brand'] = $this->_networks[ $profile['network'] ]['label'];

			switch ( $profile['network'] ) {
				case 'email':
					$profile['url'] = 'mailto:' . $profile['url'];
					break;
				case 'phone':
					$profile['url'] = 'tel:' . $profile['url'];
					break;
				case 'skype':
					$profile['url'] = 'skype:' . $profile['url'];
					break;
			}

			return $profile;
		}

		return false;
	}
}
