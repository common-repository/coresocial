<?php

namespace Dev4Press\Plugin\coreSocial\Base;

use Dev4Press\v50\Core\Quick\Misc;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property bool   $active
 * @property string $color_primary
 * @property string $color_text
 * @property string $color_icon
 * @property string $name
 * @property string $label
 */
abstract class Network {
	protected string $network = '';
	protected string $action = 'share';
	protected array $settings = array();
	protected array $defaults = array();
	protected bool $online_counts = false;

	public function __construct() {
		$this->settings = coresocial_settings()->prefix_get( $this->network . '_', 'networks' );
		$this->defaults = coresocial_settings()->prefix_get( $this->network . '_', 'networks', true );
	}

	public function __get( $name ) {
		if ( isset( $this->settings[ $name ] ) ) {
			return $this->settings[ $name ];
		}

		return false;
	}

	public function icon() : string {
		return '<i class="coresocial-icon coresocial-icon-' . $this->get_default_icon_name() . '"></i>';
	}

	public function loaded() {
	}

	public function prepare( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : array {
		return array(
			'network' => $this->network,
			'count'   => coresocial_cache()->get_item_network_count( $item, $item_id, $url, $this->network ),
			'item'    => $item,
			'item_id' => $item_id,
			'label'   => $this->label,
			'name'    => $this->name,
			'action'  => $this->action,
			'url'     => $this->get_share_link( $url, $title, $image_url, 'post', $item_id ),
			'real'    => $url,
			'title'   => $title,
			'icon'    => $this->icon(),
			'data'    => array(),
		);
	}

	public function get_vars_overrides() : array {
		$vars = array();

		foreach ( array( 'primary', 'text', 'icon' ) as $key ) {
			$real = 'color_' . $key;

			if ( $this->settings[ $real ] != $this->defaults[ $real ] ) {
				$vars[ '--coresocial-color-' . $this->network . '-' . $key ] = Misc::hex_to_rgba( $this->settings[ $real ] );
			}
		}

		return $vars;
	}

	public function can_online_count() : bool {
		return $this->online_counts;
	}

	public function get_online_count( string $url ) : array {
		return $this->get_default_online_result( $url );
	}

	protected function get_default_icon_name() : string {
		return $this->network;
	}

	protected function get_default_online_result( string $url ) : array {
		return array(
			'url'     => $url,
			'network' => $this->network,
			'status'  => 'ok',
			'error'   => '',
			'count'   => 0,
		);
	}

	abstract public static function instance();

	abstract public function get_share_link( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0 ) : string;
}
