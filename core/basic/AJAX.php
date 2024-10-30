<?php

namespace Dev4Press\Plugin\coreSocial\Basic;

use Dev4Press\v50\Core\Quick\Request;
use Dev4Press\v50\Core\Quick\Sanitize;
use Dev4Press\v50\Core\Quick\Str;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AJAX {
	public $allowed_actions = array(
		'share',
		'show',
	);

	public $allowed_items = array(
		'post',
		'term',
		'custom',
	);

	public function __construct() {
		add_action( 'wp_ajax_coresocial_live_handler', array( $this, 'handler' ) );
		add_action( 'wp_ajax_nopriv_coresocial_live_handler', array( $this, 'handler_nopriv' ) );

		add_action( 'coresocial_ajax_request_error', array( $this, 'process_error' ), 10, 7 );
	}

	public static function instance() : AJAX {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new AJAX();
		}

		return $instance;
	}

	public function handler_nopriv() {
		$this->handler( false );
	}

	public function handler( $nonce = true ) {
		if ( ! Request::is_post() ) {
			do_action( 'coresocial_ajax_request_error',
				'request_invalid_method',
				null,
				null,
				__( 'Invalid Request method.', 'coresocial' ),
				'',
				405 );
		}

		if ( $nonce ) {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'coresocial-frontend-request' ) ) {
				do_action( 'coresocial_ajax_request_error',
					'request_malformed',
					null,
					null,
					__( 'Malformed Request.', 'coresocial' ) );
			}
		}

		if ( ! isset( $_POST['req'] ) ) {
			do_action( 'coresocial_ajax_request_error',
				'request_malformed',
				null,
				null,
				__( 'Malformed Request.', 'coresocial' ) );
		}

		$prepare = Sanitize::text( $_POST['req'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$request = json_decode( $prepare );

		if ( is_null( $request ) ) {
			do_action( 'coresocial_ajax_request_error',
				'request_not_json',
				$prepare,
				null,
				__( 'Malformed Request.', 'coresocial' ) );
		}

		$process = apply_filters( 'coresocial_ajax_live_handler', false, $request );

		if ( $process === false && isset( $request->action ) && isset( $request->uid ) ) {
			do_action( 'coresocial_ajax_process_request_start', $request );

			switch ( $request->action ) {
				case 'share':
				case 'show':
				case 'like':
					$this->share( $request );
					break;
				default:
					do_action( 'coresocial_ajax_request_error',
						'request_invalid',
						$request,
						null,
						__( 'Invalid Request.', 'coresocial' ),
						$request->uid );
					break;
			}
		} else {
			do_action( 'coresocial_ajax_request_error',
				'request_incomplete',
				$request,
				null,
				__( 'Incomplete Request.', 'coresocial' ) );
		}
	}

	public function share( $request ) {
		$break = apply_filters( 'coresocial_ajax_live_handler_vote_preprocess', false, $request );
		$stop  = false;

		if ( $break !== false ) {
			$defaults = array(
				'code'    => 'request_invalid',
				'message' => __( 'Invalid Request.', 'coresocial' ),
			);

			$args = $break === true ? array() : $break;

			$args = wp_parse_args( $args, $defaults );

			do_action( 'coresocial_ajax_request_error',
				$args['code'],
				$request,
				null,
				$args['message'],
				$request->uid );

			$stop = true;
		}

		if ( ! $stop ) {
			$input = array(
				'network' => isset( $request->network ) ? sanitize_key( $request->network ) : false,
				'item'    => isset( $request->item ) ? sanitize_key( $request->item ) : false,
				'item_id' => isset( $request->id ) ? absint( $request->id ) : false,
				'action'  => isset( $request->item ) ? sanitize_key( $request->action ) : false,
				'module'  => isset( $request->module ) ? sanitize_key( $request->module ) : false,
				'url'     => isset( $request->url ) ? Sanitize::url( $request->url ) : false,
				'check'   => isset( $request->check ) ? sanitize_key( $request->check ) : false,
				'logged'  => coresocial()->datetime()->mysql_date(),
				'user_id' => get_current_user_id(),
			);

			foreach ( $input as $key => $value ) {
				if ( $value === false ) {
					do_action( 'coresocial_ajax_request_error',
						'request_invalid',
						$request,
						null,
						__( 'Invalid Request.', 'coresocial' ),
						$request->uid );

					$stop = true;
					break;
				}
			}

			if ( ! $stop ) {
				$terms = in_array( $input['action'], $this->allowed_actions ) &&
				         in_array( $input['item'], $this->allowed_items ) &&
				         coresocial_loader()->is_method_valid( $input['module'] ) &&
				         coresocial_loader()->is_network_valid( $input['network'] ) &&
				         Helper::validate_check( $input['check'], $input['action'], $input['network'], $input['item'], $input['item_id'], $input['url'] );

				if ( ! $terms ) {
					do_action( 'coresocial_ajax_request_error',
						'request_invalid',
						$request,
						null,
						__( 'Invalid Request.', 'coresocial' ),
						$request->uid );

					$stop = true;
				}

				if ( ! $stop ) {
					if ( $input['action'] == 'share' || $input['action'] == 'show' ) {
						coresocial_db()->add_share_to_log( $input );
					}

					coresocial_cache()->init_item_data( $input['item'], $input['item_id'], $input['url'], true );

					$count = coresocial_cache()->get_item_network_count( $input['item'], $input['item_id'], $input['url'], $input['network'], 1 );
					$count = coresocial_settings()->get( 'short_counts' ) ? Str::short_number_format( $count ) : $count;

					$result = array(
						'status' => 'ok',
						'count'  => $count,
						'uid'    => $request->uid,
					);

					$this->respond( wp_json_encode( $result ) );
				}
			}
		}

		$this->error( __( 'Invalid Request.', 'coresocial' ), $request->uid );
	}

	public function process_error( $error, $request = null, $item = null, $message = '', $uid = '', $code = 400, $data = null ) {
		if ( empty( $message ) ) {
			$message = __( 'Unspecified Problem.', 'coresocial' );
		}

		do_action( 'coresocial_ajax_live_handler_error', $error, $message, $code, array(
			'request' => $request,
			'item'    => $item,
			'uid'     => $uid,
			'data'    => $data,
		) );

		$this->error( $message, $uid, $code );
	}

	public function error( $message, $uid = '', $code = 400 ) {
		$result = array(
			'status'  => 'error',
			'message' => $message,
		);

		if ( ! empty( $uid ) ) {
			$result['uid'] = $uid;
		}

		$this->respond( wp_json_encode( $result ), $code );
	}

	public function respond( $response, $code = 200 ) {
		status_header( $code );

		if ( coresocial_settings()->get( 'ajax_header_no_cache' ) ) {
			nocache_headers();
		}

		header( 'Content-Type: application/json' );

		die( $response ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
