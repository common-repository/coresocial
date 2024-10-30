<?php

namespace Dev4Press\Plugin\coreSocial\Sharing;

use Dev4Press\Plugin\coreSocial\Basic\Helper;
use Dev4Press\v50\Core\Quick\Str;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Render {
	public static $uid = 0;

	public function __construct() {
	}

	public static function instance() : Render {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Render();
		}

		return $instance;
	}

	public function block( $buttons, array $classes = array(), string $id = '', string $top = '', string $bottom = '' ) : string {
		$classes = array_merge( array( 'coresocial_share_block' ), $classes );

		$render = '<div' . ( ! empty( $id ) ? ' id="' . $id . '"' : '' ) . ' class="' . join( ' ', $classes ) . '">';
		$render .= $top . join( '', $buttons ) . $bottom;
		$render .= '</div>';

		return $render;
	}

	public function button( $args = array() ) : string {
		$defaults = array(
			'network'          => '',
			'module'           => '',
			'url'              => '',
			'item'             => 'post',
			'item_id'          => 0,
			'real'             => '',
			'title'            => '',
			'icon'             => '',
			'name'             => '',
			'label'            => '',
			'action'           => 'share',
			'show_icon'        => 'left',
			'show_label'       => true,
			'show_count'       => true,
			'hide_empty_count' => true,
			'count'            => 0,
			'popup_width'      => coresocial_settings()->get( 'popup_width' ),
			'popup_height'     => coresocial_settings()->get( 'popup_height' ),
			'method'           => 'inline',
			'data'             => array(),
		);

		$args = wp_parse_args( $args, $defaults );

		self::$uid ++;
		$uid = 'coresocial-' . time() . '-' . str_pad( self::$uid, 6, '0', STR_PAD_LEFT );

		$show_counts = $args['show_count'] && ( ! $args['hide_empty_count'] || $args['count'] > 0 || $args['module'] == 'floating' );

		$classes = array(
			'coresocial_social_network',
			'coresocial_network_' . $args['network'],
		);

		if ( $show_counts || $args['show_label'] ) {
			$classes[] = '__has_count_or_label';
		}

		if ( ! $args['show_label'] ) {
			$classes[] = '__no_label';
		}

		if ( $args['show_icon'] === 'left' ) {
			$classes[] = '__icon_is_left';
		} else if ( $args['show_icon'] === 'right' ) {
			$classes[] = '__icon_is_right';
		}

		$data = array(
			'data-network="' . esc_attr( $args['network'] ) . '"',
			'data-item="' . esc_attr( $args['item'] ) . '"',
			'data-action="' . esc_attr( $args['action'] ) . '"',
			'data-module="' . esc_attr( $args['module'] ) . '"',
			'data-id="' . esc_attr( $args['item_id'] ) . '"',
			'data-url="' . esc_attr( $args['real'] ) . '"',
			'data-title="' . esc_attr( $args['title'] ) . '"',
		);

		if ( $args['action'] == 'share' ) {
			$data[] = 'data-popup-width="' . esc_attr( $args['popup_width'] ) . '"';
			$data[] = 'data-popup-height="' . esc_attr( $args['popup_height'] ) . '"';
		}

		if ( $args['action'] != 'none' ) {
			$data[] = 'data-check="' . Helper::generate_check( $args['action'], $args['network'], $args['item'], $args['item_id'], $args['real'] ) . '"';
		} else {
			$classes[] = '__not_active';
		}

		foreach ( $args['data'] as $key => $value ) {
			$data[] = 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		$args['title'] = ! $args['show_label'] ? $args['label'] : $args['name'];

		$_count = coresocial_settings()->get( 'short_counts' ) ? Str::short_number_format( $args['count'] ) : $args['count'];

		$render = '<div id="' . $uid . '" class="' . join( ' ', $classes ) . '">';
		$render .= '<a title="' . $args['title'] . '" rel="nofollow" href="' . esc_url( $args['url'] ) . '" ' . join( ' ', $data ) . '>';

		if ( $args['show_icon'] === 'left' || $args['show_icon'] === 'center' ) {
			$render .= $args['icon'];
		}

		if ( $args['show_label'] || $show_counts ) {
			if ( $args['module'] === 'floating' && $args['hide_empty_count'] && $args['count'] == 0 ) {
				$render .= '<span class="__empty">';
			} else {
				$render .= '<span>';
			}

			if ( $args['show_label'] ) {
				$render .= '<span class="__label">' . $args['label'] . '</span>';
			}

			if ( $show_counts ) {
				$render .= '<span class="__count">' . $_count . '</span>';
			}

			$render .= '</span>';
		}

		if ( $args['show_icon'] === 'right' ) {
			$render .= $args['icon'];
		}

		$render .= '</a>';
		$render .= '</div>';

		return $render;
	}
}
