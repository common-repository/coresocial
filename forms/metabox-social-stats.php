<?php

use Dev4Press\Plugin\coreSocial\Basic\DB;
use Dev4Press\Plugin\coreSocial\Basic\Statistics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item_id = DB::instance()->get_item_db_id( 'post', $post_ID );

if ( $item_id > 0 ) {
	$overall  = Statistics::instance()->overall( $item_id );
	$networks = Statistics::instance()->networks( $item_id, false );
} else {
	$overall = array(
		'share'  => 0,
		'like'   => 0,
		'mailto' => 0,
		'qrcode' => 0,
	);
}

include 'shared-statistics-overall.php';

if ( $item_id > 0 ) {
	echo '<hr class="coresocial-overall-sep"/>';

	$_hide_empty = true;

	include 'shared-statistics-networks.php';
}
