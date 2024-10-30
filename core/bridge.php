<?php

use Dev4Press\Plugin\coreSocial\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\coreSocial\Basic\AJAX;
use Dev4Press\Plugin\coreSocial\Basic\Cache;
use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\Plugin\coreSocial\Basic\Plugin;
use Dev4Press\Plugin\coreSocial\Basic\Profiles;
use Dev4Press\Plugin\coreSocial\Basic\Settings;
use Dev4Press\Plugin\coreSocial\Basic\Wizard;
use Dev4Press\Plugin\coreSocial\Sharing\Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function coresocial() : Plugin {
	return Plugin::instance();
}

function coresocial_loader() : Loader {
	return Loader::instance();
}

function coresocial_settings() : Settings {
	return Settings::instance();
}

function coresocial_db() : DB {
	return DB::instance();
}

function coresocial_admin() : AdminPlugin {
	return AdminPlugin::instance();
}

function coresocial_cache() : Cache {
	return Cache::instance();
}

function coresocial_ajax() : AJAX {
	return AJAX::instance();
}

function coresocial_profiles() : Profiles {
	return Profiles::instance();
}

function coresocial_wizard() : Wizard {
	return Wizard::instance();
}
