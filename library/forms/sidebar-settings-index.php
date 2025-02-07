<?php

use Dev4Press\v50\Core\Quick\KSES;
use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo KSES::strong( panel()->r()->icon( $_panel->icon ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <h3><?php echo esc_html( $_panel->title ); ?></h3>

            <div class="_info">
				<?php

				echo esc_html( $_subpanels[ $_subpanel ]['info'] );

				if ( isset( $_panel->kb ) ) {
					$url   = $_panel->kb['url'];
					$label = $_panel->kb['label'] ?? __( 'Knowledge Base', 'd4plib' );

					?>

                    <div class="_kb">
                        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $label ); ?></a>
                    </div>

					<?php
				}

				?>
            </div>
        </div>
        <div class="d4p-panel-buttons">
            <a style="text-align: center" href="<?php echo esc_url( panel()->a()->panel_url( 'settings', 'full' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Show All Settings', 'd4plib' ); ?></a>
        </div>
    </div>
</div>
