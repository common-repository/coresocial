<?php

use Dev4Press\Plugin\coreSocial\Basic\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$problems = array();
$actions  = array();

if ( empty( $problems ) ) {
	$problems[] = '<span class="d4p-card-badge d4p-badge-ok"><i class="d4p-icon d4p-ui-check-square d4p-icon-fw"></i>' . esc_html__( 'OK', 'coresocial' ) . '</span><div class="d4p-status-message">' . esc_html__( 'Everything appears to be in order.', 'coresocial' ) . '</div>';
}

?>
<div class="d4p-group d4p-dashboard-card d4p-card-double d4p-dashboard-status">
    <h3><?php esc_html_e( 'Plugin Status', 'coresocial' ); ?></h3>
    <div class="d4p-group-inner">
        <div>
			<?php echo join( '</div><div>', $problems ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </div>
	<?php if ( ! empty( $actions ) ) { ?>
        <div class="d4p-group-footer">
			<?php echo join( '', $actions ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
	<?php } ?>
</div>
