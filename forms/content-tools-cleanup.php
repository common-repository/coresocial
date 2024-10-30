<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php esc_html_e( 'Important Information', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
			<?php esc_html_e( 'This tool removes all posts, terms and custom URLs with no shares (online or tracked) from the share tables.', 'coresocial' ); ?>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Remove all empty Items', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[cleanup][empty]" value="on"/> <?php esc_html_e( 'All empty share Items', 'coresocial' ); ?>
            </label>
        </div>
    </div>
</div>
