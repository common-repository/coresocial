<?php

use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\Plugin\coreSocial\Basic\Statistics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item_id = DB::instance()->get_item_db_id( 'post', $post_ID );

if ( $item_id > 0 ) {
	$networks = Statistics::instance()->networks( $item_id, false );

	$_count_key  = 'online';
	$_hide_empty = true;

	include 'shared-statistics-networks.php';
}
