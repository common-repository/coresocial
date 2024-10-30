<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-wizard-panel-header">
    <p>
		<?php esc_html_e( 'Welcome to the setup wizard for coreSocial plugin! Here you can quickly set up the plugin, and if you need to adjust all the plugin features in more detail, you can do that later through various plugin panels.', 'coresocial' ); ?>
    </p>
    <p>
		<?php esc_html_e( 'Using this wizard will reconfigure the plugin. Each option might affect one or more plugin settings.', 'coresocial' ); ?>
    </p>
    <p>
		<?php esc_html_e( 'Let\'s start with few basics.', 'coresocial' ); ?>
    </p>
</div>

<div class="d4p-wizard-panel-content">
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to add share block inline for posts?', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'By default, inline share block will be added at the bottom of the post (if the post uses normal WordPress loop where we can hook up and add share block).', 'coresocial' ); ?></em>
			<?php coresocial_wizard()->render_yes_no( 'intro', 'inline' ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to enable Floating share block site wide?', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Floating share block will be added to every page, and will be attached to the left or right side of the screen, or, for small devices, it will be attached to the bottom of the screen.', 'coresocial' ); ?></em>
            <em><strong><?php esc_html_e( 'This feature is available in coreSocial Pro edition only!', 'coresocial' ); ?></strong></em>
            <a target="_blank" class="button-secondary" href="https://plugins.dev4press.com/coresocial/"><?php esc_html_e( 'Upgrade to coreSocial Pro', 'coresocial' ); ?></a>
        </div>
    </div>
</div>
