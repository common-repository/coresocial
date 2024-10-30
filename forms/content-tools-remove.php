<?php

use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php esc_html_e( 'Important Information', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
			<?php esc_html_e( 'This tool can remove plugin settings saved in the WordPress options table added by the plugin and you can remove share and likes data gathered by the plugin share blocks.', 'coresocial' ); ?>
            <br/><br/>
			<?php esc_html_e( 'Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.', 'coresocial' ); ?>
			<?php esc_html_e( 'If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.', 'coresocial' ); ?>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Remove plugin settings', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][settings]" value="on"/> <?php esc_html_e( 'Main Plugin Settings', 'coresocial' ); ?>
            </label>
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][networks]" value="on"/> <?php esc_html_e( 'Networks Settings', 'coresocial' ); ?>
            </label>
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][inline]" value="on"/> <?php esc_html_e( 'Inline Share Settings', 'coresocial' ); ?>
            </label>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Remove database data and tables', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
            <p style="font-weight: bold"><?php esc_html_e( 'This will remove all shares and likes data!', 'coresocial' ); ?></p>
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][drop]" value="on"/> <?php esc_html_e( 'Remove plugins database table and all data in them', 'coresocial' ); ?>
            </label>
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][truncate]" value="on"/> <?php esc_html_e( 'Remove all data from database table', 'coresocial' ); ?>
            </label><br/>
            <hr/>
            <p><?php esc_html_e( 'Database tables that will be affected', 'coresocial' ); ?>:</p>
            <ul style="list-style: inside disc;">
                <li><?php echo esc_html( coresocial_db()->items ); ?></li>
                <li><?php echo esc_html( coresocial_db()->log ); ?></li>
            </ul>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Disable Plugin', 'coresocial' ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="coresocial-tools[remove][disable]" value="on"/> <?php esc_html_e( 'Disable plugin', 'coresocial' ); ?>
            </label>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
