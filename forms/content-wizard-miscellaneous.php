<?php

use Dev4Press\Plugin\coreSocial\Basic\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-wizard-panel-header">
    <p>
		<?php esc_html_e( 'The plugin supports multiple networks and internal methods for sharing the content. You can only use some of these, and you can later enable or disable additional networks.', 'coresocial' ); ?>
    </p>
</div>

<div class="d4p-wizard-panel-content">
    <div class="d4p-wizard-option-block d4p-wizard-block-select">
        <p><?php esc_html_e( 'Which share count value you want to display in share buttons?', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'With some networks supporting online counts, you can choose how the counts are displayed inside the buttons. Depending on the networks you use, some values can always return zero values.', 'coresocial' ); ?></em>
			<?php coresocial_wizard()->render_select( 'miscellaneous', 'counts', 'online_fallback', Helper::get_counts_methods() ); ?>
        </div>
    </div>
	<?php if ( coresocial_settings()->get( 'active', 'inline' ) ) { ?>
        <div class="d4p-wizard-option-block d4p-wizard-block-checkboxes">
            <p><?php esc_html_e( 'Select post types to auto insert inline share block.', 'coresocial' ); ?></p>
            <div>
                <em><?php esc_html_e( 'This will be done only if the single post belonging to one of the selected post types uses normal WordPress loop with default filters allowing the plugin to add the share block.', 'coresocial' ); ?></em>
				<?php coresocial_wizard()->render_checkboxes_list( 'miscellaneous', 'types', array( 'post' ), Helper::get_post_types() ); ?>
            </div>
        </div>
	<?php } ?>
	<?php if ( coresocial_settings()->get( 'active', 'floating' ) ) { ?>
        <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
            <p><?php esc_html_e( 'Which side of the screen you want to have Floating share box?', 'coresocial' ); ?></p>
            <div>
                <em><?php esc_html_e( 'Floating box can be placed on left or right side of the screen.', 'coresocial' ); ?></em>
				<?php coresocial_wizard()->render_yes_no( 'miscellaneous', 'side', 'yes', array(
					'no'  => __( 'Right', 'coresocial' ),
					'yes' => __( 'Left', 'coresocial' ),
				) ); ?>
            </div>
        </div>
	<?php } ?>
</div>
