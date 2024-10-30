<?php

use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-content">
    <div class="d4p-setup-wrapper">
        <div class="d4p-update-info">
			<?php

			include CORESOCIAL_PATH . 'forms/setup-database.php';
			include CORESOCIAL_PATH . 'forms/setup-counts.php';

			coresocial_settings()->set( 'install', false, 'info' );
			coresocial_settings()->set( 'update', false, 'info', true );

			?>

            <div class="d4p-install-block">
                <h4>
					<?php esc_html_e( 'All Done', 'coresocial' ); ?>
                </h4>
                <div>
					<?php esc_html_e( 'Update completed.', 'coresocial' ); ?>
                </div>
            </div>

            <div class="d4p-install-confirm">
                <a class="button-primary" href="<?php echo esc_url( panel()->a()->panel_url( 'about' ) ); ?>&update"><?php esc_html_e( 'Click here to continue', 'coresocial' ); ?></a>
            </div>
        </div>
		<?php echo coresocial()->recommend(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>
</div>
