<?php

use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="d4p-content">
    <div class="d4p-group d4p-group-information d4p-group-export">
        <h3><?php esc_html_e( 'Important Information', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
            <p><?php esc_html_e( 'With this tool you export plugin settings into plain text file (JSON serialized content). Do not modify export file! Making changes to export file will make it unusable.', 'coresocial' ); ?></p>
            <p><?php esc_html_e( 'This tool doesn\'t export the social sharing log, or the post metadata settings stored for individual posts.', 'coresocial' ); ?></p>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
