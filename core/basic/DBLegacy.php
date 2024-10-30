<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Plugins\DB as BaseDB;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $items
 * @property string $log
 * @property string $counts
 */
class DBLegacy extends BaseDB {
	protected $plugin_name = 'coresocial';
	protected $plugin_instance = 'legacy';
	public $_prefix = 'coresocial';
	public $_tables = array( 'log', 'items', 'counts' );

	public function get_item_counts_from_log( string $item, int $item_id, string $item_url, string $item_hash = '' ) : array {
		$id = coresocial_cache()->get_item_id( $item, $item_id, $item_url, $item_hash );

		if ( $id > 0 ) {
			$sql = $this->prepare( "SELECT `network`, COUNT(*) AS `counts` FROM " . $this->log . " WHERE `item_id` = %d GROUP BY `network`", $id );
			$raw = $this->get_results( $sql );

			return wp_list_pluck( $raw, 'counts', 'network' );
		} else {
			return array();
		}
	}

	public function get_overall_network_statistics( int $item_id = 0 ) : array {
		$sql = 'SELECT `network`, COUNT(*) AS `counter` FROM ' . $this->log;

		if ( $item_id > 0 ) {
			$sql .= $this->prepare( ' WHERE `item_id` = %d', $item_id );
		}

		$sql .= ' GROUP BY `network`';

		$raw = $this->transient_query( $sql, 'coresocial-network-statistics-' . $item_id, 'results', OBJECT, 0, 0, 60 );

		return empty( $raw ) ? array() : wp_list_pluck( $raw, 'counter', 'network' );
	}
}
