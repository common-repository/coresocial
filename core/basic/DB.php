<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Plugins\DB as BaseDB;
use Dev4Press\v50\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string $items
 * @property string $log
 * @property string $counts
 */
class DB extends BaseDB {
	protected $plugin_name = 'coresocial';
	public $_prefix = 'coresocial';
	public $_tables = array( 'log', 'items', 'counts' );

	public function get_item_db_id( string $item = 'post', int $item_id = 0, string $item_url = '', string $item_hash = '' ) : int {
		$item_url = WPR::remove_site_url( $item_url );

		if ( substr( $item_url, 0, 9 ) == '/wp-json/' ) {
			return 0;
		}

		$where = array(
			$this->prepare( "`item` = %s", $item ),
		);

		if ( $item == 'custom' ) {
			if ( empty( $item_hash ) ) {
				$item_hash = md5( $item_url );
			}

			$where[] = $this->prepare( "`item_hash` = %s", $item_hash );
		} else {
			$where[] = $this->prepare( "`item_id` = %d", $item_id );
		}

		$sql = "SELECT `id` FROM " . $this->items . " WHERE " . join( " AND ", $where ) . " LIMIT 0, 1";
		$raw = absint( $this->get_var( $sql ) );

		if ( $raw > 0 ) {
			return $raw;
		} else if ( ! empty( $item_url ) ) {
			$insert = array(
				'item'      => $item,
				'item_id'   => absint( $item_id ),
				'item_hash' => $item == 'custom' ? $item_hash : '',
				'url'       => $item_url,
			);

			if ( $this->insert( $this->items, $insert ) ) {
				return $this->get_insert_id();
			}
		}

		return 0;
	}

	public function get_item_by_id( int $id ) {
		$sql = $this->prepare( "SELECT * FROM " . $this->items . " WHERE `id` = %d", $id );

		return $this->get_row( $sql );
	}

	public function get_item_networks_counts( int $id ) {
		$sql = $this->prepare( "SELECT i.*, network, updated FROM " . $this->items . " i LEFT JOIN " . $this->counts . " c ON c.item_id = i.id WHERE i.`id` = %d", $id );

		return $this->get_results( $sql );
	}

	public function get_item_counts( string $item, int $item_id, string $item_url, string $item_hash = '' ) : array {
		$id = coresocial_cache()->get_item_id( $item, $item_id, $item_url, $item_hash );

		$results = array();

		if ( $id > 0 ) {
			$sql = $this->prepare( "SELECT `network`, `internal`, `online`, `updated` FROM " . $this->counts . " WHERE `item_id` = %d", $id );
			$raw = $this->get_results( $sql );

			if ( ! empty( $raw ) ) {
				foreach ( $raw as $row ) {
					$results[ $row->network ] = array(
						'id'       => $id,
						'item'     => array(
							'id'   => $item_id,
							'url'  => $item_url,
							'hash' => $item_hash,
						),
						'network'  => $row->network,
						'internal' => absint( $row->internal ),
						'online'   => absint( $row->online ),
						'updated'  => empty( $row->updated ) ? 0 : strtotime( $row->updated ),
					);
				}
			}
		}

		return $results;
	}

	public function get_item_network_count( int $id, string $network ) : int {
		$sql = $this->prepare( "SELECT `internal` FROM " . $this->counts . " WHERE `item_id` = %d AND `network` = %s", $id, $network );
		$raw = $this->get_results( $sql );

		if ( empty( $raw ) ) {
			return - 1;
		}

		return absint( $raw[0]->internal );
	}

	public function has_liked( int $id, int $user_id = 0, string $user_hash = '' ) : bool {
		if ( $user_id == 0 && empty( $user_hash ) ) {
			return false;
		}

		$where = array(
			$this->prepare( 'item_id = %d', $id ),
			"network = 'like'",
			"action = 'like'",
		);

		if ( $user_id > 0 ) {
			$where[] = $this->prepare( 'user_id = %d', $user_id );
		} else {
			if ( ! empty( $user_hash ) ) {
				$where[] = $this->prepare( 'user_hash = %s', $user_hash );
			}
		}

		$sql = "SELECT COUNT(*) FROM " . $this->log . " WHERE " . join( " AND ", $where );

		return absint( $this->get_var( $sql ) ) > 0;
	}

	public function add_like_to_log( array $input ) {
		$id = coresocial_cache()->get_item_id( $input['item'], $input['item_id'], $input['url'] );

		if ( $id > 0 && ! $this->has_liked( $id, $input['user_id'], $input['user_hash'] ) ) {
			$add = array(
				'item_id'   => $id,
				'user_id'   => $input['user_id'],
				'user_hash' => $input['user_hash'],
				'network'   => $input['network'],
				'action'    => $input['action'],
				'module'    => $input['module'],
				'logged'    => $input['logged'],
			);

			$this->insert( $this->log, $add );

			$this->update_counts( $id, $input['network'] );
		}
	}

	public function add_share_to_log( array $input ) {
		$id = coresocial_cache()->get_item_id( $input['item'], $input['item_id'], $input['url'] );

		if ( $id > 0 ) {
			$add = array(
				'item_id' => $id,
				'user_id' => $input['user_id'],
				'network' => $input['network'],
				'action'  => $input['action'],
				'module'  => $input['module'],
				'logged'  => $input['logged'],
			);

			$this->insert( $this->log, $add );

			$this->update_counts( $id, $input['network'] );
		}
	}

	public function update_counts( int $id, string $network, int $add = 1 ) {
		$count = $this->get_item_network_count( $id, $network );

		if ( $count == - 1 ) {
			$this->insert( $this->counts, array(
				'item_id'  => $id,
				'network'  => $network,
				'internal' => abs( $add ),
			), array( '%d', '%s', '%d' ) );
		} else {
			$this->update( $this->counts, array(
				'internal' => $count + $add,
			), array(
				'item_id' => $id,
				'network' => $network,
			), array( '%d' ), array( '%d', '%s' ) );
		}
	}

	public function update_online_count( int $id, string $network, int $count ) {
		$check = $this->get_item_network_count( $id, $network );

		if ( $check == - 1 ) {
			$this->insert( $this->counts, array(
				'item_id' => $id,
				'network' => $network,
				'online'  => $count,
				'updated' => $this->datetime(),
			), array( '%d', '%s', '%d', '%s' ) );
		} else {
			$this->update( $this->counts, array(
				'online'  => $count,
				'updated' => $this->datetime(),
			), array(
				'item_id' => $id,
				'network' => $network,
			), array( '%d', '%s' ), array( '%d', '%s' ) );
		}
	}

	public function get_overall_network_statistics( int $item_id = 0 ) : array {
		$sql = 'SELECT `network`, SUM(`internal`) AS `internal`, SUM(`online`) AS `online` FROM ' . $this->counts;

		if ( $item_id > 0 ) {
			$sql .= $this->prepare( ' WHERE `item_id` = %d', $item_id );
		}

		$sql .= ' GROUP BY `network`';

		$raw = $this->transient_query( $sql, 'coresocial-network-statistics-' . $item_id, 'results', ARRAY_A, 0, 0, 60 );

		return empty( $raw ) ? array() : $this->index( $raw, 'network', false );
	}

	public function get_latest_network_statistics( int $days = 7 ) : array {
		$sql  = $this->prepare( 'SELECT `network`, DATE(`logged`) AS `day`, COUNT(*) AS `counter` FROM ' . $this->log . ' 
				WHERE `logged` >  now() - INTERVAL %d DAY GROUP BY `network`, DATE(`logged`) ORDER BY `day`, `counter` ASC', $days );
		$raw  = $this->get_results( $sql );
		$days = array();

		foreach ( $raw as $row ) {
			if ( ! isset( $days[ $row->day ] ) ) {
				$days[ $row->day ] = array();
			}

			$days[ $row->day ][ $row->network ] = $row->counter;
		}

		return $days;
	}

	public function update_network_counts() : int {
		$sql = "UPDATE " . $this->counts . " c
				INNER JOIN (SELECT `item_id`, `network`, COUNT(*) AS `internal` FROM " . $this->log . " GROUP BY `item_id`, `network`) AS l
				ON l.`item_id` = c.`item_id` AND l.`network` = c.`network` SET c.`internal` = l.`internal` WHERE l.`internal` != c.`internal`";
		$this->query( $sql );

		return $this->rows_affected();
	}

	public function insert_network_counts() : int {
		$sql = "INSERT IGNORE INTO " . $this->counts . " (`item_id`, `network`, `internal`)
				SELECT `item_id`, `network`, COUNT(*) AS internal FROM " . $this->log . " GROUP BY `item_id`, `network`";
		$this->query( $sql );

		return $this->rows_affected();
	}

	public function delete_log_entries( array $ids ) {
		$sql = "SELECT `id`, `item_id`, `network` FROM " . $this->log . " WHERE `id` IN (" . $this->prepare_in_list( $ids, '%d' ) . ")";
		$raw = $this->get_results( $sql );

		foreach ( $raw as $row ) {
			$this->update_counts( $row->item_id, $row->network, - 1 );
		}

		$sql = "DELETE FROM " . $this->log . " WHERE `id` IN (" . $this->prepare_in_list( $ids, '%d' ) . ")";
		$this->query( $sql );
	}

	public function delete_item( int $id ) {
		$this->delete( $this->log, array( 'item_id' => $id ), array( '%d' ) );
		$this->delete( $this->counts, array( 'item_id' => $id ), array( '%d' ) );
		$this->delete( $this->items, array( 'id' => $id ), array( '%d' ) );
	}

	public function clear_item( int $id ) {
		$this->delete( $this->log, array( 'item_id' => $id ), array( '%d' ) );
		$this->update( $this->counts, array(
			'internal' => 0,
		), array(
			'item_id' => $id,
		), array( '%d' ), array( '%d' ) );
	}

	public function remove_empty_items() {
		$sql = "DELETE FROM " . $this->counts . " WHERE item_id IN (
				SELECT item_id FROM (SELECT item_id, SUM(internal) + SUM(online) AS counters FROM " . $this->counts . " 
				GROUP BY item_id HAVING counters = 0) AS clean);";
		$this->query( $sql );

		$sql = "DELETE i FROM " . $this->items . " i 
				LEFT JOIN " . $this->counts . " c ON c.item_id = i.id
				WHERE c.item_id IS NULL";
		$this->query( $sql );
	}
}
