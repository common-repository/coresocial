<?php

namespace Dev4Press\Plugin\coreSocial\Admin\Panel;

use Dev4Press\Plugin\coreSocial\Admin\Panel;
use Dev4Press\Plugin\coreSocial\Table\Log as LogTable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Log extends Panel {
	protected $table = true;
	protected $form = true;
	protected $sidebar = false;
	protected $form_method = 'get';

	public function screen_options_show() {
		$args = array(
			'label'   => __( 'Rows', 'coresocial' ),
			'default' => 20,
			'option'  => 'coresocial_log_rows_per_page',
		);

		add_screen_option( 'per_page', $args );

		new LogTable();
	}
}
