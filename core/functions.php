<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display the share inline block using default styling and layout settings.
 *
 * @param array $args settings for the manual display. See Dev4Press\Plugin\coreSocial\Methods\Inline::manual() for all available arguments.
 * @param bool  $echo control if the rendered content will be echoed.
 *
 * @return string rendered share block, if the $echo is true
 * @see Dev4Press\Plugin\coreSocial\Methods\Inline::manual()
 *
 */
function coresocial_display_inline( array $args = array(), bool $echo = true ) : string {
	$method = coresocial_loader()->get_method( 'inline' );
	$render = $method ? $method->manual( $args ) : '';

	if ( $echo ) {
		echo $render; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	return $render;
}

function coresocial_error_log( $object ) {
	$print = print_r( $object, true );

	error_log( $print );
}
