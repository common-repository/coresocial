<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\Plugin\coreSocial\Sharing\Loader;
use Dev4Press\v50\Core\Plugins\Wizard as CoreWizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wizard extends CoreWizard {
	public $types = array(
		'networks'      => array(
			'list' => 'checkboxes',
		),
		'miscellaneous' => array(
			'types' => 'checkboxes',
		),
	);
	public $allowed = array(
		'networks'      => array(
			'list' => array(),
		),
		'miscellaneous' => array(
			'types' => array(),
		),
	);
	public $default = array(
		'intro'         => array(
			'inline'   => array(
				array(
					'inline',
					'active',
					array(
						'yes' => true,
						'no'  => false,
					),
				),
			),
		),
		'networks'      => array(
			'list'   => array(
				array(
					'inline',
					'networks',
				),
			),
			'online' => array(),
		),
		'miscellaneous' => array(
			'types' => array(
				array(
					'inline',
					'post_types',
				),
			),
			'side'  => array(
				array(
					'floating',
					'location',
					array(
						'yes' => 'right',
						'no'  => 'left',
					),
				),
			),
		),
		'finish'        => array(
			'wizard' => array(
				array(
					'settings',
					'show_setup_wizard',
					array(
						'yes' => false,
						'no'  => true,
					),
				),
			),
		),
	);

	public function a() {
		return coresocial_admin();
	}

	protected function init_panels() {
		$this->panels = array(
			'intro'         => array( 'label' => __( 'Intro', 'coresocial' ) ),
			'networks'      => array( 'label' => __( 'Networks', 'coresocial' ) ),
			'miscellaneous' => array( 'label' => __( 'Miscellaneous', 'coresocial' ) ),
			'finish'        => array( 'label' => __( 'Finish', 'coresocial' ) ),
		);

		$this->setup_panel( $this->a()->subpanel );
	}

	protected function init_data() {
		$this->allowed['networks']['list']       = Loader::instance()->get_available_networks_codes();
		$this->allowed['miscellaneous']['types'] = array_keys( Helper::get_post_types() );
	}

	protected function postback_custom( $panel, $data ) {
		if ( $panel == 'networks' ) {
			if ( isset( $this->storage['networks']['list'] ) ) {
				foreach ( $this->allowed['networks']['list'] as $network ) {
					$active = in_array( $network, $this->storage['networks']['list'] );
					$this->a()->settings()->set( $network . '_active', $active, 'networks' );
				}
			}

			if ( isset( $this->storage['networks']['online'] ) ) {
				foreach ( array( 'facebook', 'tumblr', 'pinterest', 'yummly' ) as $network ) {
					$active = $this->storage['networks']['online'];
					$this->a()->settings()->set( $network . '_online', $active, 'networks' );
				}
			}

			$this->a()->settings()->save( 'networks' );
		}
	}
}
