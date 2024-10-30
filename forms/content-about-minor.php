<?php

use Dev4Press\v50\Core\Quick\KSES;
use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-about-minor">
    <h3><?php esc_html_e( 'Maintenance and Security Releases', 'coresocial' ); ?></h3>
    <p>
        <strong><?php esc_html_e( 'Version', 'coresocial' ); ?> <span>1.1</span></strong> &minus;
        Library Updated.
    </p>
    <p>
		<?php

		/* translators: Changelog subpanel information. %s: Subpanel URL. */
		echo KSES::standard( sprintf( __( 'For more information, see <a href=\'%s\'>the changelog</a>.', 'coresocial' ), esc_url( panel()->a()->panel_url( 'about', 'changelog' ) ) ) );  // phpcs:ignore WordPress.Security.EscapeOutput

		?>
    </p>
</div>
