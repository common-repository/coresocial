<?php

namespace Dev4Press\Plugin\coreSocial\Admin\Panel;

use Dev4Press\v50\Core\UI\Admin\PanelWizard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wizard extends PanelWizard {
	protected function init_default_subpanels() {
		$this->subpanels = array(
			'intro'         => array(
				'title' => __( 'Intro', 'coresocial' ),
			),
			'networks'      => array(
				'title' => __( 'Networks', 'coresocial' ),
			),
			'miscellaneous' => array(
				'title' => __( 'Miscellaneous', 'coresocial' ),
			),
			'finish'        => array(
				'title' => __( 'Finish', 'coresocial' ),
			),
		);
	}
}
