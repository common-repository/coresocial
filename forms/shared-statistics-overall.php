<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="coresocial-overall-statistics">
    <div class="__statistics">
        <div><?php echo esc_html( $overall['share'] ); ?></div>
        <em><?php esc_html_e( 'Total Networks Shares', 'coresocial' ); ?></em>
    </div>
    <div class="__statistics">
        <div><?php echo esc_html( $overall['mailto'] ?? 0 ); ?></div>
        <em><?php esc_html_e( 'Total Email Shares', 'coresocial' ); ?></em>
    </div>
    <div class="__statistics">
        <div><?php echo esc_html( $overall['printer'] ?? 0 ); ?></div>
        <em><?php esc_html_e( 'Total Printed', 'coresocial' ); ?></em>
    </div>
</div>
