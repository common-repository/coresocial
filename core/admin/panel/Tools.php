<?php

namespace Dev4Press\Plugin\coreSocial\Admin\Panel;

use Dev4Press\v50\Core\UI\Admin\PanelTools;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tools extends PanelTools {
	protected function init_default_subpanels() {
		parent::init_default_subpanels();

		$this->subpanels = array_slice( $this->subpanels, 0, 2 ) +
		                   array(
			                   'cleanup' => array(
				                   'title'        => __( 'Items Cleanup', 'coresocial' ),
				                   'icon'         => 'ui-trash',
				                   'method'       => 'post',
				                   'button_label' => __( 'Cleanup', 'coresocial' ),
				                   'info'         => __( 'Using this tool, can remove all pages from database with no shares logged.', 'coresocial' ),
			                   ),
		                   ) +
		                   array_slice( $this->subpanels, 2 );
	}
}
