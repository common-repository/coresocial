<?php

namespace Dev4Press\Plugin\coreSocial\Blocks;

use Dev4Press\Plugin\coreSocial\Basic\Profiles;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Data {
	public function __construct() {
	}

	public static function instance() : Data {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Data();
		}

		return $instance;
	}

	public function profiles() : array {
		$data  = array();
		$types = Profiles::instance()->list_profiles_for_select();

		foreach ( $types as $key => $name ) {
			$data[] = array(
				'label' => $name,
				'value' => $key,
			);
		}

		return $data;
	}

	public function networks() : array {
		$data  = array();
		$types = coresocial_loader()->get_networks_list();

		foreach ( $types as $key => $name ) {
			$data[] = array(
				'label' => $name,
				'value' => $key,
			);
		}

		return $data;
	}
}
