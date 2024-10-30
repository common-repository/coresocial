<?php

namespace Dev4Press\Plugin\coreSocial\Admin\Panel;

use Dev4Press\v50\Core\UI\Admin\PanelSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends PanelSettings {
	public $settings_class = '\\Dev4Press\\Plugin\\coreSocial\\Admin\\Settings';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->subpanels = $this->subpanels + array(
				'basic'          => array(
					'title'      => __( 'Basics', 'coresocial' ),
					'icon'       => 'ui-paper-plane',
					'break'      => __( 'Standard', 'coresocial' ),
					'break-icon' => 'ui-tasks',
					'info'       => __( 'Basic settings for the plugin.', 'coresocial' ),
				),
				'data'           => array(
					'title' => __( 'Data', 'coresocial' ),
					'icon'  => 'ui-database',
					'info'  => __( 'Control over the logging of sharing data into the database.', 'coresocial' ),
				),
				'files'          => array(
					'title' => __( 'CSS and JS Files', 'coresocial' ),
					'icon'  => 'ui-palette',
					'info'  => __( 'Control how and when the JS and CSS files are included in the page.', 'coresocial' ),
				),
				'display'        => array(
					'title' => __( 'Display', 'coresocial' ),
					'icon'  => 'ui-object-ungroup',
					'info'  => __( 'Control several global display related options', 'coresocial' ),
				),
				'advanced'       => array(
					'title' => __( 'Advanced', 'coresocial' ),
					'icon'  => 'ui-warning-triangle',
					'info'  => __( 'More advanced settings that should not be changed for most websites.', 'coresocial' ),
				),
				'networks'       => array(
					'title'      => __( 'Networks', 'coresocial' ),
					'break'      => __( 'Social Networks', 'coresocial' ),
					'break-icon' => 'ui-network',
					'icon'       => 'ui-tasks',
					'info'       => __( 'Setup all the supported social networks, including colors and labels.', 'coresocial' ),
				),
				'internal'       => array(
					'title' => __( 'Internal', 'coresocial' ),
					'icon'  => 'ui-play',
					'info'  => __( 'Setup additional elements controlled internally, not the external social networks.', 'coresocial' ),
				),
				'profiles'       => array(
					'title' => __( 'Profiles', 'coresocial' ),
					'icon'  => 'ui-user',
					'info'  => __( 'Setup all the social networks profiles you might have and want to use.', 'coresocial' ),
				),
				'inline'         => array(
					'title'      => __( 'Inline Styling', 'coresocial' ),
					'icon'       => 'ui-sliders-base-hor',
					'break'      => __( 'Inline Share Method', 'coresocial' ),
					'break-icon' => 'ui-bell',
					'info'       => __( 'Control over the default styling for the Inline display method', 'coresocial' ),
				),
				'inline-embed'   => array(
					'title' => __( 'Inline Auto Embed', 'coresocial' ),
					'icon'  => 'ui-sliders-base-hor',
					'info'  => __( 'Control over the auto embedding of the Inline share block.', 'coresocial' ),
				),
			);
	}
}
