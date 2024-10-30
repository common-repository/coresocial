<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use DateInterval;
use DatePeriod;
use DateTime;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Statistics {
	public function __construct() {
	}

	public static function instance() : Statistics {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Statistics();
		}

		return $instance;
	}

	public function weekly() : array {
		$data = DB::instance()->get_latest_network_statistics();

		$from     = new DateTime( "7 days ago" );
		$interval = new DateInterval( 'P1D' );
		$period   = new DatePeriod( $from, $interval, 7 );

		$stats = array(
			'days'   => array(),
			'totals' => array(),
			'max'    => 0,
		);
		foreach ( $period as $day ) {
			$key = $day->format( 'Y-m-d' );

			$stats['days'][ $key ]   = $data[ $key ] ?? array();
			$stats['totals'][ $key ] = 0;

			if ( ! empty( $stats['days'][ $key ] ) ) {
				foreach ( $stats['days'][ $key ] as &$count ) {
					$count = absint( $count );

					$stats['totals'][ $key ] += $count;
				}

				if ( $stats['totals'][ $key ] > $stats['max'] ) {
					$stats['max'] = $stats['totals'][ $key ];
				}
			}
		}

		return $stats;
	}

	public function networks( int $item_id = 0, bool $include_empty = true ) : array {
		$data  = DB::instance()->get_overall_network_statistics( $item_id );
		$stats = array(
			'networks'     => array(),
			'max_internal' => 0,
			'max_online'   => 0,
		);

		foreach ( coresocial_loader()->get_networks_list() as $network => $label ) {
			$single = $data[ $network ] ?? array(
				'internal' => 0,
				'online'   => 0,
			);
			$count  = $single['internal'] + $single['online'];

			if ( $count > 0 || $include_empty ) {
				$stats['networks'][ $network ] = array(
					'total'    => $count,
					'internal' => $single['internal'],
					'online'   => $single['online'],
					'label'    => $label,
					'icon'     => $network == 'copyurl' ? 'clipboard' : $network,
				);

				if ( $single['internal'] > $stats['max_internal'] ) {
					$stats['max_internal'] = $single['internal'];
				}

				if ( $single['online'] > $stats['max_online'] ) {
					$stats['max_online'] = $single['online'];
				}
			}
		}

		return $stats;
	}

	public function overall( int $item_id = 0 ) : array {
		$data  = DB::instance()->get_overall_network_statistics( $item_id );
		$stats = array(
			'share'   => 0,
			'like'    => 0,
			'mailto'  => 0,
			'printer' => 0,
			'qrcode'  => 0,
		);

		foreach ( $data as $item => $value ) {
			$value = absint( $value['internal'] );

			if ( $item == 'qrcode' ) {
				$stats['qrcode'] += $value;
			} else if ( $item == 'like' ) {
				$stats['like'] += $value;
			} else if ( $item == 'copyurl' ) {
				$stats['copyurl'] += $value;
			} else if ( $item == 'mailto' ) {
				$stats['mailto'] += $value;
			} else if ( $item == 'printer' ) {
				$stats['printer'] += $value;
			} else {
				$stats['share'] += $value;
			}
		}

		return $stats;
	}
}
