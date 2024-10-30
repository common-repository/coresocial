<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Plugins\Information as BaseInformation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Information extends BaseInformation {
	public $code = 'coresocial';

	public $version = '1.1';
	public $build = 110;
	public $edition = 'lite';
	public $status = 'stable';
	public $updated = '2024.08.19';
	public $released = '2024.05.06';
}
