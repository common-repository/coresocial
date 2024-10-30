<?php

use Dev4Press\Plugin\coreSocial\Sharing\Loader;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="d4p-wizard-panel-header">
    <p>
		<?php esc_html_e( 'The plugin supports multiple networks and internal methods for sharing the content. You can only use some of these, and you can later enable or disable additional networks. All networks enabled here will be enabled for configuration, and will be included in all the available share methods by default. You can change that later on with the individual share methods settings.', 'coresocial' ); ?>
    </p>
</div>

<div class="d4p-wizard-panel-content">
    <div class="d4p-wizard-option-block d4p-wizard-block-checkboxes">
        <p><?php esc_html_e( 'Select which online services and networks you want to use.', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Both online services and internal share methods will appear in the share block. They are split here for convenience. Some online methods may support getting actual online share count, provided by the network.', 'coresocial' ); ?></em>
			<?php coresocial_wizard()->render_checkboxes_list( 'networks', 'list', array(
				'twitter',
				'facebook',
				'linkedin',
				'pinterest',
			), Loader::instance()->get_available_networks_list( 'online' ) ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-checkboxes">
        <p><?php esc_html_e( 'Select which internal share and like methods you want to use.', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Internal like and share methods will rely only on the statistics gathered by the plugin.', 'coresocial' ); ?></em>
			<?php coresocial_wizard()->render_checkboxes_list( 'networks', 'list', array(
				'like',
				'qrcode',
			), Loader::instance()->get_available_networks_list( 'internal' ) ); ?>
        </div>
    </div>
    <div class="d4p-wizard-option-block d4p-wizard-block-yesno">
        <p><?php esc_html_e( 'Do you want to enable getting online counts from supported networks?', 'coresocial' ); ?></p>
        <div>
            <em><?php esc_html_e( 'Only some networks have API methods to get actual counts of shared URLs, and even that is not always reliable. The process of getting online data is done in the background to avoid disrupting the page loads. Currently only these networks support this: Facebook (requires API token!), Tumbler, Pinterest and Yummly.', 'coresocial' ); ?></em>
			<?php coresocial_wizard()->render_yes_no( 'networks', 'online', 'no' ); ?>
        </div>
    </div>
</div>
