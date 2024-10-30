<?php

use Dev4Press\Plugin\coreSocial\Basic\Statistics;
use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$overall  = Statistics::instance()->overall();
$networks = Statistics::instance()->networks();

?>

<div class="d4p-group d4p-dashboard-card d4p-card-double">
    <h3><?php esc_html_e( 'Overall Statistics', 'coresocial' ); ?></h3>
    <div class="d4p-group-inner">
		<?php include 'shared-statistics-overall.php'; ?>
        <hr class="coresocial-overall-sep"/>
		<?php include 'shared-statistics-networks.php'; ?>
    </div>
    <div class="d4p-group-footer">
        <a class="button-primary" href="<?php echo esc_url( panel()->a()->panel_url( 'items', '', 'filter-counts=counted' ) ); ?>"><?php esc_html_e( 'Items with Shares', 'coresocial' ); ?></a>
    </div>
</div>
