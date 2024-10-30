<?php

use function Dev4Press\v50\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include CORESOCIAL_PATH . 'forms/content-about-minor.php';

?>

<div class="d4p-about-whatsnew">
    <div class="d4p-whatsnew-section d4p-whatsnew-heading">
        <div class="d4p-layout-grid">
            <div class="d4p-layout-unit whole align-center">
                <h2>Easy social networks sharing</h2>
                <p class="lead-description">
                    coreSocial Lite is a new plugin supporting many social networks.
                </p>
                <p>
                    The plugin is the culmination of the years of (very slow) development process for the plugin first started way back in 2017 and used only internally.
                </p>

				<?php if ( isset( $_GET['install'] ) && sanitize_key( $_GET['install'] ) === 'on' ) { // phpcs:ignore WordPress.Security.NonceVerification ?>
                    <a class="button-primary" href="<?php echo esc_url( panel()->a()->panel_url( 'wizard' ) ); ?>"><?php esc_html_e( 'Run Setup Wizard', 'coresocial' ); ?></a>
				<?php } ?>
                <div class="coresocial-about-counters">
                    <div><i class="d4p-icon d4p-ui-folder d4p-icon-fw"></i> <strong>8</strong> Sharing Networks</div>
                    <div><i class="d4p-icon d4p-ui-plug d4p-icon-fw"></i> <strong>2</strong> Additional Sharing</div>
                    <div><i class="d4p-icon d4p-ui-radar d4p-icon-fw"></i> <strong>39</strong> Online Profiles</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d4p-whatsnew-section core-grid">
        <div class="core-row">
            <div class="core-col-sm-12 core-col-md-6">
                <h3>Share and Link</h3>
                <p>
                    Easy way for your visitors to share content on popular social networks, with the built-in tracking of shares and a lot of customization options.
                </p>
                <p>
                    Share block can be added automatically, or you can use block (as well as block in the sidebar), shortcode or function. You can have share for posts and terms archives, or any custom URL on the website, and you can also include Email and Print buttons.
                </p>
            </div>
            <div class="core-col-sm-12 core-col-md-6">
                <h3>Blocks</h3>
                <p>
                    Use blocks in the block editor or with Full Site Editing themes to add social network profiles and inline share buttons.
                </p>
                <p>
                    Both blocks include a lot of settings to select the networks, control colors, display layout, size and many other things to make the blocks look the way you need them. Using the alignment option, you can reshape the way the buttons are displayed. And, as expected, blocks are completely responsive.
                </p>
            </div>
        </div>
    </div>

    <div class="d4p-whatsnew-section core-grid">
        <div class="core-row">
            <div class="core-col-sm-12 core-col-md-6">
                <h3 style="margin-top: 0;">Layouts and Styling</h3>
                <p>
                    The plugin has various styling and layout options, you can control globally, and you can adjust things via blocks too. You can change colors for share blocks, and you hae a lot of variables in CSS for finer control.
                </p>
            </div>
            <div class="core-col-sm-12 core-col-md-6">
                <h3 style="margin-top: 0;">Share Logs</h3>
                <p>
                    Each time the user or visitor clicks the share or like buttons, the action is logged into the database. You can easily get share stats for every post or URL, and same stats are available in metabox for individual posts.
                </p>
            </div>
        </div>
    </div>
</div>
