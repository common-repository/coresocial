<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\Plugin\coreSocial\Sharing\Loader;
use Dev4Press\v50\Core\Task\Job;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Counts extends Job {
	protected function prepare() {
		$this->data = coresocial_settings()->get( 'queue', 'storage' );
	}

	protected function item() {
		$key  = array_key_first( $this->data );
		$item = $this->data[ $key ];

		Loader::instance()->online_count( $item );
		Settings::instance()->remove_from_queue( $key );

		unset( $this->data[ $key ] );
	}

	protected function finish() {
		if ( $this->has_more() ) {
			Plugin::instance()->spawn_queue_job();
		}
	}

	protected function has_more() : bool {
		return ! empty( $this->data );
	}
}
