<?php

namespace Dev4Press\Plugin\coreSocial\Sharing;

use Dev4Press\Plugin\coreSocial\Base\Method;
use Dev4Press\Plugin\coreSocial\Basic\Cache;
use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\v50\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Loader {
	private $available_methods = array(
		'inline'   => "Dev4Press\\Plugin\\coreSocial\\Methods\\Inline",
	);

	private $available_networks = array(
		'twitter'   => "Dev4Press\\Plugin\\coreSocial\\Networks\\Twitter",
		'facebook'  => "Dev4Press\\Plugin\\coreSocial\\Networks\\Facebook",
		'reddit'    => "Dev4Press\\Plugin\\coreSocial\\Networks\\Reddit",
		'mix'       => "Dev4Press\\Plugin\\coreSocial\\Networks\\Mix",
		'tumblr'    => "Dev4Press\\Plugin\\coreSocial\\Networks\\Tumblr",
		'linkedin'  => "Dev4Press\\Plugin\\coreSocial\\Networks\\LinkedIn",
		'pinterest' => "Dev4Press\\Plugin\\coreSocial\\Networks\\Pinterest",
		'yummly'    => "Dev4Press\\Plugin\\coreSocial\\Networks\\Yummly",
		'mailto'    => "Dev4Press\\Plugin\\coreSocial\\Networks\\MailTo",
		'printer'   => "Dev4Press\\Plugin\\coreSocial\\Networks\\Printer",
	);

	private $networks_types = array(
		'online'   => array(
			'twitter',
			'facebook',
			'reddit',
			'mix',
			'tumblr',
			'linkedin',
			'pinterest',
			'yummly',
		),
		'internal' => array(
			'mailto',
			'printer',
		),
	);

	/** @var \Dev4Press\Plugin\coreSocial\Base\Method[] */
	private $methods = array();
	/** @var \Dev4Press\Plugin\coreSocial\Base\Network[] */
	private $networks = array();
	private $online = array();
	private $online_check = true;
	private $online_check_period = - 1;
	private $online_posts_only = true;

	public function __construct() {
		$this->core();
	}

	public static function instance() : Loader {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Loader();
		}

		return $instance;
	}

	public function core() {
		if ( ! is_admin() || WordPress::instance()->is_ajax() ) {
			$this->init_networks();
			$this->init_methods();
		}
	}

	public function is_method_valid( $method ) : bool {
		return isset( $this->methods[ $method ] );
	}

	public function is_network_valid( $network ) : bool {
		return isset( $this->networks[ $network ] );
	}

	public function get_available_networks_codes() : array {
		return array_keys( $this->available_networks );
	}

	public function get_available_networks_list( string $what = 'all' ) : array {
		$list  = array();
		$input = $this->networks_types[ $what ] ?? array_keys( $this->available_networks );

		foreach ( $input as $network ) {
			$list[ $network ] = coresocial_settings()->get( $network . '_name', 'networks' );
		}

		return $list;
	}

	public function get_method( $name ) : ?Method {
		if ( isset( $this->methods[ $name ] ) ) {
			return $this->methods[ $name ];
		}

		return null;
	}

	/** @return \Dev4Press\Plugin\coreSocial\Base\Network|bool */
	public function get_network( $name ) {
		if ( isset( $this->networks[ $name ] ) ) {
			return $this->networks[ $name ];
		}

		return false;
	}

	/** @return \Dev4Press\Plugin\coreSocial\Base\Network[] */
	public function get_networks() : array {
		return $this->networks;
	}

	public function get_networks_list() : array {
		$this->init_networks();

		$list = array();

		foreach ( $this->networks as $network => $obj ) {
			$list[ $network ] = $obj->name;
		}

		return $list;
	}

	/** @return \Dev4Press\Plugin\coreSocial\Base\Method[] */
	public function get_methods() : array {
		return $this->methods;
	}

	public function maybe_add_to_queue( $obj ) : bool {
		if ( $this->online_check && in_array( $obj['network'], $this->online ) ) {
			$data = Cache::instance()->get_item_network_object( $obj['item'], $obj['item_id'], $obj['url'], $obj['network'] );
			$real = $data['id'] > 0 ? $data['id'] : Cache::instance()->get_item_id( $obj['item'], $obj['item_id'], $obj['url'] );
			$last = $data['updated'];

			$include = true;

			if ( $this->online_posts_only && $obj['item'] != 'post' ) {
				$include = false;
			}

			if ( $include && $last + $this->online_check_period < time() ) {
				return coresocial_settings()->add_to_queue( $obj['network'], $obj['url'], $obj['item'], $obj['item_id'], $real );
			}
		}

		return false;
	}

	public function maybe_add_to_queue_by_id( int $id ) {
		if ( $this->online_check ) {
			$items = DB::instance()->get_item_networks_counts( $id );

			if ( ! empty( $items ) ) {
				$item = $items[0];

				$include = true;

				if ( $this->online_posts_only && $item->item != 'post' ) {
					$include = false;
				}

				if ( $include ) {
					foreach ( $this->online as $network ) {
						$done = false;

						foreach ( $items as $i ) {
							if ( ! empty( $i->network ) && $i->network == $network ) {
								$done = true;
								$last = empty( $i->updated ) ? 0 : strtotime( $i->updated );

								if ( $last + $this->online_check_period < time() ) {
									coresocial_settings()->add_to_queue( $network, $i->url, $i->item, $i->item_id, $id );
								}

								break;
							}
						}

						if ( ! $done ) {
							coresocial_settings()->add_to_queue( $network, $item->url, $item->item, $item->item_id, $id );
						}
					}
				}
			}
		}
	}

	public function online_count( array $item ) {
		$result = $this->networks[ $item['network'] ]->get_online_count( $item['url'] );

		if ( $result['status'] == 'ok' ) {
			$id = $item['id'] > 0 ? $item['id'] : Cache::instance()->get_item_id( $item['item'], $item['item_id'], $item['url'] );

			DB::instance()->update_online_count( $id, $item['network'], $result['count'] );
		}
	}

	private function init_networks() {
		foreach ( $this->available_networks as $network => $class ) {
			if ( coresocial_settings()->get( $network . '_active', 'networks' ) ) {
				if ( class_exists( $class ) ) {
					$this->networks[ $network ] = $class::instance();

					if ( $this->networks[ $network ]->can_online_count() ) {
						$this->online[] = $network;
					}
				}
			}
		}

		$this->online_check        = coresocial_settings()->get( 'online_counts_active' );
		$this->online_check_period = coresocial_settings()->get( 'online_counts_period' ) == 'day' ? DAY_IN_SECONDS : WEEK_IN_SECONDS;
		$this->online_posts_only   = coresocial_settings()->get( 'online_counts_posts_only' );
	}

	private function init_methods() {
		foreach ( $this->available_methods as $method => $class ) {
			if ( coresocial_settings()->get( 'active', $method ) ) {
				if ( class_exists( $class ) ) {
					$this->methods[ $method ] = $class::instance();
				}
			}
		}
	}
}
