<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dev4press_plugin_coresocial_autoload( $class ) {
	$path = __DIR__ . '/';
	$base = 'Dev4Press\\Plugin\\coreSocial\\';

	dev4press_v50_autoload_for_plugin( $class, $base, $path );
}

spl_autoload_register( 'dev4press_plugin_coresocial_autoload' );
