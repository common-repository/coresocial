<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\Plugin\coreSocial\Blocks\Register;
use Dev4Press\Plugin\coreSocial\Sharing\Loader;
use Dev4Press\v50\Core\Plugins\Core;
use Dev4Press\v50\Core\Shared\Enqueue;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin extends Core {
	public $svg_icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NDAgNTEyIj48cGF0aCBmaWxsPSJibGFjayIgZD0iTTY0LDQ0Nkw2NCw1MEM2NCwyNS43MTYgODMuNzE2LDYgMTA4LDZMNTMyLDZDNTU2LjI4NCw2IDU3NiwyNS43MTYgNTc2LDUwTDU3NiwzMTYuMzQ0QzU2OS4yMzUsMzEzLjE3MyA1NjIuMDk2LDMxMC42NyA1NTQuNjY3LDMwOC45MjFMNTU0LjY2Nyw1MEM1NTQuNjY3LDM3LjQ5IDU0NC41MSwyNy4zMzMgNTMyLDI3LjMzM0wxMDgsMjcuMzMzQzk1LjQ5LDI3LjMzMyA4NS4zMzMsMzcuNDkgODUuMzMzLDUwQzg1LjMzMyw1MCA4NS4zMzMsNjEuODQ5IDg1LjMzMyw4MS4zMzNMNDEyLDgxLjMzM0w0MTIsMTAyLjY2N0w4NS4zMzMsMTAyLjY2N0M4NS4zMzMsMjA1Ljg1MyA4NS4zMzMsNDI0LjY2NyA4NS4zMzMsNDI0LjY2N0w0MTkuNzM3LDQyNC42NjdDNDIwLjMxLDQzMi4wMDIgNDIxLjYwNCw0MzkuMTM3IDQyMy41NDgsNDQ2TDY0LDQ0NlpNMTA2LDY1LjY2N0wxMDYsNDQuMzMzTDE3Niw0NC4zMzNMMTc2LDY1LjY2N0wxMDYsNjUuNjY3Wk0yMDYsNjUuNjY3TDIwNiw0NC4zMzNMMjU2LDQ0LjMzM0wyNTYsNjUuNjY3TDIwNiw2NS42NjdaTTI4Niw2NS42NjdMMjg2LDQ0LjMzM0wzMTYsNDQuMzMzTDMxNiw2NS42NjdMMjg2LDY1LjY2N1pNNTI5LjQsMzI2QzU3OS4wNzIsMzI2IDYxOS40LDM2Ni4zMjggNjE5LjQsNDE2QzYxOS40LDQ2NS42NzIgNTc5LjA3Miw1MDYgNTI5LjQsNTA2QzQ3OS43MjgsNTA2IDQzOS40LDQ2NS42NzIgNDM5LjQsNDE2QzQzOS40LDM2Ni4zMjggNDc5LjcyOCwzMjYgNTI5LjQsMzI2Wk01MjkuNCwzNDcuMzMzQzQ5MS41MDIsMzQ3LjMzMyA0NjAuNzMzLDM3OC4xMDIgNDYwLjczMyw0MTZDNDYwLjczMyw0NTMuODk4IDQ5MS41MDIsNDg0LjY2NyA1MjkuNCw0ODQuNjY3QzU2Ny4yOTgsNDg0LjY2NyA1OTguMDY3LDQ1My44OTggNTk4LjA2Nyw0MTZDNTk4LjA2NywzNzguMTAyIDU2Ny4yOTgsMzQ3LjMzMyA1MjkuNCwzNDcuMzMzWk0zMDQuNjEzLDM1NS44NjdDMzAzLjgxMSwzNDYuNjk1IDMwMy43OTQsMzM3LjMyOCAzMDQuNjI0LDMyNy44NDFDMzEyLjMyLDIzOS44NzEgMzg5Ljk5LDE3NC42OTkgNDc3Ljk2LDE4Mi4zOTVDNDg3LjMwMywxODMuMjEzIDQ5Ni4zODgsMTg0LjgxOSA1MDUuMTU3LDE4Ny4xNDRMNTA3LjI1OSwyMDguNjg5QzQ5Ny4zODEsMjA1LjQ4NiA0ODYuOTkyLDIwMy4zNCA0NzYuMjEsMjAyLjM5N0MzOTkuMjc5LDE5NS42NjcgMzMxLjM1NywyNTIuNjYgMzI0LjYyNiwzMjkuNTkxQzMyMy42NzEsMzQwLjUwNyAzMjMuOTk5LDM1MS4yNDIgMzI1LjQ4NSwzNjEuNjQ2TDMwNC42MTMsMzU1Ljg2N1pNMjcyLjIxNSwzNDYuODk3QzI3MC40NjUsMzM0LjA1IDI3MC4xMDUsMzIwLjgxMiAyNzEuMjgyLDMwNy4zNThDMjc5LjgyMSwyMDkuNzY1IDM2NS45ODUsMTM3LjQ2NSA0NjMuNTc3LDE0Ni4wMDNDNDc2Ljg2MywxNDcuMTY1IDQ4OS42NzksMTQ5Ljc2NiA1MDEuODg3LDE1My42MzlMNTA0LjI3MywxNzguMDg3QzQ5MC45MTcsMTcyLjkwNiA0NzYuNjE3LDE2OS41MDQgNDYxLjYzNiwxNjguMTkzQzM3Ni4yOSwxNjAuNzI2IDMwMC45MzksMjIzLjk1NCAyOTMuNDcyLDMwOS4yOTlDMjkyLjE0NywzMjQuNDQgMjkzLjA0OCwzMzkuMjY2IDI5NS45MDIsMzUzLjQ1NUwyNzIuMjE1LDM0Ni44OTdaTTMzNy40NzQsMzY0Ljk2NkMzMzcuMzE3LDM1OS40NzkgMzM3LjQ3NiwzNTMuOTI1IDMzNy45NjYsMzQ4LjMyNUMzNDQuODIsMjY5Ljk3NyA0MTMuOTk0LDIxMS45MzMgNDkyLjM0MywyMTguNzg3QzQ5Ny44MjQsMjE5LjI2NyA1MDMuMjA2LDIyMC4wNTEgNTA4LjQ3MywyMjEuMTIzTDUxMC41NDgsMjQyLjM5NEM1MDQuMDk5LDI0MC43MzEgNDk3LjQyOCwyMzkuNTc2IDQ5MC41NzcsMjM4Ljk3N0M0MjMuMzcxLDIzMy4wOTcgMzY0LjAzNSwyODIuODg2IDM1OC4xNTUsMzUwLjA5MkMzNTcuNTQ2LDM1Ny4wNTIgMzU3LjUzNCwzNjMuOTI5IDM1OC4wNzcsMzcwLjY3TDMzNy40NzQsMzY0Ljk2NlpNNDA1LjI3OCwzODMuNzM5QzQxMi44NTcsMzI5LjM3OCA0NjAuNDgsMjg5LjM1NCA1MTUuMzEzLDI5MS4yMTlMNTE3LjM2LDMxMi4xOTNDNDcxLjEsMzA5LjE2OCA0MzAuNTUsMzQzLjI0OSA0MjUuNTg4LDM4OS4zNjNMNDA1LjI3OCwzODMuNzM5Wk0zNzAuOTQ5LDM3NC4yMzRDMzcxLjAyOSwzNzIuNDMyIDM3MS4xNDgsMzcwLjYyMyAzNzEuMzA3LDM2OC44MDlDMzc3LjMyLDMwMC4wODIgNDM3Ljk5OSwyNDkuMTY2IDUwNi43MjYsMjU1LjE3OUM1MDguNDQ1LDI1NS4zMyA1MTAuMTU0LDI1NS41MTQgNTExLjg1LDI1NS43MzJMNTEzLjkwNSwyNzYuNzg2QzUxMC45NiwyNzYuMjcyIDUwNy45NywyNzUuODc5IDUwNC45MzgsMjc1LjYxNEM0NDcuNDg5LDI3MC41ODggMzk2Ljc2OCwzMTMuMTQ4IDM5MS43NDIsMzcwLjU5N0MzOTEuNDY5LDM3My43MTMgMzkxLjMzNywzNzYuODA5IDM5MS4zMzksMzc5Ljg4TDM3MC45NDksMzc0LjIzNFpNMjA1LjgyNSw0MTdDMjA4LjAwNSw0MTIuMTUyIDIwOS43MzEsNDA3LjEwNCAyMTAuOTc2LDQwMS45MjFMMjEyLjY2NCwzOTQuODg4QzIxMi42NjQsMzk0Ljg4OCAyMzYuOTMsMzkxLjM4MSAyMzYuOTMsMzkxLjM4MUMyMzcuNDY4LDM4NS4xMzkgMjM3LjQ2OCwzNzguODYyIDIzNi45MywzNzIuNjE5TDIxMi42NjQsMzY5LjExMkwyMTAuOTc2LDM2Mi4wNzlDMjA4Ljg4NSwzNTMuMzcyIDIwNS40MzcsMzQ1LjA0OCAyMDAuNzU5LDMzNy40MTNMMTk2Ljk4LDMzMS4yNDdDMTk2Ljk4LDMzMS4yNDcgMjExLjY1OSwzMTEuNjA4IDIxMS42NTksMzExLjYwOEMyMDcuNjI1LDMwNi44MTQgMjAzLjE4NiwzMDIuMzc1IDE5OC4zOTIsMjk4LjM0MUwxNzguNzUzLDMxMy4wMkwxNzIuNTg3LDMwOS4yNDFDMTY0Ljk1MiwzMDQuNTYzIDE1Ni42MjgsMzAxLjExNSAxNDcuOTIxLDI5OS4wMjRMMTQwLjg4OCwyOTcuMzM2TDEzNy4zODEsMjczLjA3QzEzMS4xMzksMjcyLjUzMiAxMjQuODYxLDI3Mi41MzIgMTE4LjYxOSwyNzMuMDdMMTE1LjExMiwyOTcuMzM2TDEwOC4wNzksMjk5LjAyNEMxMDIuODk2LDMwMC4yNjkgOTcuODQ4LDMwMS45OTUgOTMsMzA0LjE3NUw5MywyOTIuNjA4Qzk3LjA5NSwyOTEuMDA0IDEwMS4zMDEsMjg5LjY4MiAxMDUuNTg5LDI4OC42NTJMMTA5LjIyOCwyNjMuNDc3QzEyMS42NjUsMjYxLjUwOCAxMzQuMzM1LDI2MS41MDggMTQ2Ljc3MiwyNjMuNDc3TDE1MC40MTEsMjg4LjY1MkMxNjAuMjA2LDI5MS4wMDQgMTY5LjU3MSwyOTQuODgzIDE3OC4xNiwzMDAuMTQ3TDE5OC41MzQsMjg0LjkxOEMyMDguNzIyLDI5Mi4zMiAyMTcuNjgsMzAxLjI3OCAyMjUuMDgyLDMxMS40NjZMMjA5Ljg1MywzMzEuODRDMjE1LjExNywzNDAuNDI5IDIxOC45OTYsMzQ5Ljc5NCAyMjEuMzQ4LDM1OS41ODlMMjQ2LjUyMywzNjMuMjI4QzI0OC40OTIsMzc1LjY2NSAyNDguNDkyLDM4OC4zMzUgMjQ2LjUyMyw0MDAuNzcyTDIyMS4zNDgsNDA0LjQxMUMyMjAuMzE4LDQwOC42OTkgMjE4Ljk5Niw0MTIuOTA1IDIxNy4zOTIsNDE3TDIwNS44MjUsNDE3Wk0xMjgsMzQ3LjMzM0MxNDcuMTMzLDM0Ny4zMzMgMTYyLjY2NywzNjIuODY3IDE2Mi42NjcsMzgyQzE2Mi42NjcsNDAxLjEzMyAxNDcuMTMzLDQxNi42NjcgMTI4LDQxNi42NjdDMTA4Ljg2Nyw0MTYuNjY3IDkzLjMzMyw0MDEuMTMzIDkzLjMzMywzODJDOTMuMzMzLDM2Mi44NjcgMTA4Ljg2NywzNDcuMzMzIDEyOCwzNDcuMzMzWk0xMjgsMzU4QzExNC43NTQsMzU4IDEwNCwzNjguNzU0IDEwNCwzODJDMTA0LDM5NS4yNDYgMTE0Ljc1NCw0MDYgMTI4LDQwNkMxNDEuMjQ2LDQwNiAxNTIsMzk1LjI0NiAxNTIsMzgyQzE1MiwzNjguNzU0IDE0MS4yNDYsMzU4IDEyOCwzNThaIi8+PC9zdmc+';

	public $plugin = 'coresocial';

	private $_page_title = null;

	public function __construct() {
		$this->url  = CORESOCIAL_URL;
		$this->path = CORESOCIAL_PATH;

		parent::__construct();
	}

	public function s() {
		return coresocial_settings();
	}

	public function f() {
		return null;
	}

	public function l() {
		return null;
	}

	public function b() {
		return null;
	}

	public function run() {
		do_action( 'coresocial_load_settings' );

		Enqueue::init();

		add_action( 'd4plib_shared_enqueue_prepare', array( $this, 'register_css_and_js' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_files' ) );
		add_filter( 'pre_get_document_title', array( $this, 'store_page_title' ), 100000 );
		add_filter( 'document_title', array( $this, 'store_page_title' ), 100000 );
		add_action( 'wp_head', array( $this, 'vars_styling_override' ), 110000 );

		do_action( 'coresocial_plugin_core_ready' );

		Loader::instance();
		Shortcodes::instance();
		Register::instance();
	}

	public function register_css_and_js() {
		if ( coresocial_settings()->get( 'css_font_packed' ) ) {
			$_font_file = coresocial_settings()->get( 'css_font_embedded' ) ? 'pack-embed' : 'pack';

			Enqueue::i()->add_css( 'coresocial-main', array(
				'lib'  => false,
				'url'  => $this->url . 'css/',
				'file' => $_font_file,
				'ver'  => $this->s()->file_version(),
				'ext'  => 'css',
				'min'  => true,
				'int'  => array(),
			) );
		} else {
			$_font_file = coresocial_settings()->get( 'css_font_embedded' ) ? 'icons-embed' : 'icons';

			Enqueue::i()->add_css( 'coresocial-icons', array(
				'lib'  => false,
				'url'  => $this->url . 'css/',
				'file' => $_font_file,
				'ver'  => $this->s()->file_version(),
				'ext'  => 'css',
				'min'  => true,
				'int'  => array(),
			) );

			Enqueue::i()->add_css( 'coresocial-main', array(
				'lib'  => false,
				'url'  => $this->url . 'css/',
				'file' => 'core',
				'ver'  => $this->s()->file_version(),
				'ext'  => 'css',
				'min'  => true,
				'int'  => array( 'coresocial-icons' ),
			) );
		}

		Enqueue::i()->add_js( 'coresocial-main', array(
			'lib'      => false,
			'url'      => $this->url . 'js/',
			'file'     => 'core',
			'ver'      => $this->s()->file_version(),
			'ext'      => 'js',
			'min'      => true,
			'footer'   => true,
			'localize' => true,
			'req'      => array( 'jquery', 'wp-hooks' ),
			'int'      => array(),
		) );
	}

	public function enqueue_files() {
		Enqueue::i()->enqueue( 'js', 'coresocial-main' );
		Enqueue::i()->enqueue( 'css', 'coresocial-main' );

		wp_localize_script( 'coresocial-main', 'coresocial_sharing_data', array(
			'url'     => admin_url( 'admin-ajax.php' ),
			'handler' => 'coresocial_live_handler',
			'nonce'   => is_user_logged_in() ? wp_create_nonce( 'coresocial-frontend-request' ) : '',
		) );
	}

	public function get_page_title() : ?string {
		return $this->_page_title;
	}

	public function get_site_title() : ?string {
		return get_bloginfo( 'name', 'display' );
	}

	public function store_page_title( $title ) {
		if ( ! empty( $title ) && is_null( $this->_page_title ) ) {
			$this->_page_title = $title;
		}

		return $title;
	}

	public function vars_styling_override() {
		$vars = array();

		foreach ( coresocial_loader()->get_networks() as $obj ) {
			$_network_vars = $obj->get_vars_overrides();

			if ( ! empty( $_network_vars ) ) {
				$vars = array_merge( $vars, $_network_vars );
			}
		}

		/**
		 * Filter all the generated CSS variables for styling override for each supported network.
		 *
		 * @param array $vars Array key is the name of the CSS variable, and value is the value to be assigned to that variable.
		 */
		$vars = apply_filters( 'coresocial_css_variables', $vars );

		echo Helper::render_vars( $vars ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function after_setup_theme() {
		add_action( 'coresocial_online_counts_queue_processor', array( $this, 'queue' ) );
	}

	public function queue() {
		if ( coresocial_settings()->get( 'online_counts_active' ) ) {
			Counts::instance()->run();
		}
	}

	public function spawn_queue_job( $time = 10 ) {
		if ( ! empty( $this->s()->current['storage']['queue'] ) ) {
			wp_schedule_single_event( time() + $time, 'coresocial_online_counts_queue_processor' );
		}
	}
}
