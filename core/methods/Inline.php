<?php

namespace Dev4Press\Plugin\coreSocial\Methods;

use Dev4Press\Plugin\coreSocial\Base\Method;
use Dev4Press\Plugin\coreSocial\Basic\Helper;
use Dev4Press\Plugin\coreSocial\Sharing\Post;
use Dev4Press\Plugin\coreSocial\Sharing\Render;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property string   $location
 * @property string[] $post_types
 * @property string[] $networks
 * @property string   $align
 * @property string   $layout
 * @property string   $color
 * @property string   $font_size
 * @property string   $button_size
 * @property string   $button_gap
 * @property string   $rounded
 * @property string   $styling
 * @property bool     $share_count_active
 * @property bool     $share_count_hide_if_zero
 */
class Inline extends Method {
	protected $name = 'inline';

	public function __construct() {
		parent::__construct();

		add_filter( 'coresocial_css_variables', array( $this, 'vars' ) );
		add_filter( 'the_content', array( $this, 'content' ), 20 );
	}

	public static function instance() : Inline {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Inline();
		}

		return $instance;
	}

	public function id( $block_id = '' ) : string {
		if ( empty( $block_id ) ) {
			return 'coresocial-block-' . str_replace( '.', '-', microtime( true ) ) . '-' . wp_rand( 100000, 999999 );
		} else {
			return 'coresocial-block-' . $block_id;
		}
	}

	public function vars( $vars = array() ) {
		if ( $this->font_size != $this->defaults['font_size'] ) {
			$vars['--coresocial-inline-font-size'] = $this->font_size;
		}

		if ( $this->button_size != $this->defaults['button_size'] ) {
			$vars['--coresocial-inline-button-size'] = $this->button_size;
		}

		if ( $this->button_gap != $this->defaults['button_gap'] ) {
			$vars['--coresocial-inline-button-gap'] = $this->button_gap;
		}

		if ( $this->rounded != $this->defaults['rounded'] ) {
			$vars['--coresocial-inline-rounded'] = $this->rounded;
		}

		return $vars;
	}

	public function block( $post_id = 0, $args = array() ) : string {
		foreach ( $args as $key => $value ) {
			$this->settings[ $key ] = $value;
		}

		if ( $post_id == 0 ) {
			$data = $this->context();
		} else {
			$data = array(
				'item'    => 'post',
				'item_id' => $post_id,
				'title'   => $this->get_post_title( $post_id ),
				'url'     => $this->get_post_url( $post_id ),
				'image'   => $this->get_post_image( $post_id ),
			);
		}

		$id   = $this->id( ( $args['block_id'] ?? '' ) );
		$vars = $this->vars();

		$buttons = $this->build( $data['url'], $data['title'], $data['image'], $data['item'], $data['item_id'], $this->networks );
		$render  = $this->render( $buttons, 'block', array(
			'id' => $id,
		) );

		$this->reset();

		return $render . Helper::render_vars( $vars, $id . '-style', '#' . $id );
	}

	public function content( $content ) {
		$location = $this->location;
		$override = Post::instance( get_the_ID() )->inline_location;

		if ( $override !== 'inherit' ) {
			$location = $override;
		}

		/**
		 * Filters the Inline block location on page.
		 *
		 * @param string $location It can have values: 'top', 'bottom' and 'both'.
		 */
		$location = apply_filters( 'coresocial_inline_the_content_location', $location );
		$show     = is_main_query() && is_singular( $this->post_types ) && $location != 'hide';

		/**
		 * Filters the Inline block visibility
		 *
		 * @param bool $visibility By default, TRUE, and the inline block will be added to the page. Return FALSE to hide it.
		 */
		if ( apply_filters( 'coresocial_inline_the_content_show', $show ) ) {
			$buttons = $this->build( $this->get_post_url(), $this->get_post_title(), $this->get_post_image(), 'post', get_the_ID(), $this->networks );

			if ( $location == 'top' || $location == 'both' ) {
				$content = $this->render( $buttons, 'top', array( 'id' => $this->id() ) ) . $content;
			}

			if ( $location == 'bottom' || $location == 'both' ) {
				$content = $content . $this->render( $buttons, 'bottom', array( 'id' => $this->id() ) );
			}
		}

		return $content;
	}

	public function render( $buttons, $location = 'bottom', $args = array() ) : string {
		$defaults = array(
			'id'      => '',
			'align'   => $this->align,
			'layout'  => $this->layout,
			'color'   => $this->color,
			'styling' => $this->styling,
			'classes' => $this->class,
		);

		$args = wp_parse_args( $args, $defaults );

		$classes = array(
			'coresocial_share_block',
			'coresocial_share_inline',
			'__inline_' . $location,
			'__layout_' . $args['layout'],
			'__color_' . $args['color'],
		);

		if ( $args['align'] != 'none' ) {
			$classes[] = '__align_' . $args['align'];
		}

		if ( $args['styling'] != 'normal' ) {
			$classes[] = '__styling_' . $args['styling'];
		}

		if ( $args['layout'] != 'icon' ) {
			$classes[] = '__has_label';
		}

		if ( ! empty( $args['classes'] ) ) {
			$classes[] = $args['classes'];
		}

		return Render::instance()->block( $buttons, $classes, $args['id'] );
	}

	public function prepare() : array {
		return array(
			'module'           => 'inline',
			'show_icon'        => $this->layout == 'right' ? 'right' : 'left',
			'show_label'       => $this->layout != 'icon',
			'show_count'       => $this->share_count_active,
			'hide_empty_count' => $this->share_count_hide_if_zero,
		);
	}

	/**
	 * Display the share inline block using default styling and layout settings.
	 *
	 * @param array $args    {
	 *                       Optional. Array with the manual render parameters.
	 *
	 * @type string $item    Type of the page ('post', 'term', 'custom')
	 * @type int    $item_id ID of the item - 0 for 'custom', post_id for 'post' and 'term_id' for 'term'
	 * @type string $title   Title of the page.
	 * @type string $url     URL of the page. For 'custom' item it is used to identify the item.
	 * @type string $id      Unique ID for the rendered block wrapper, it can be empty.
	 * @type string $align   Block alignment: 'left', 'right', 'center', 'justify', 'none'.
	 * @type string $color   Button color scheme: 'fill', 'plain'.
	 * @type string $styling Button styling: 'normal', 'rounded', 'round'.
	 * @type string $class   Additional CSS class name to apply to the block.
	 *                       }
	 *
	 * @return string
	 */
	public function manual( array $args = array() ) : string {
		$args = wp_parse_args( $args, $this->context() );

		$buttons = $this->build( $args['url'], $args['title'], $args['image'], $args['item'], $args['item_id'] );

		return $this->render( $buttons, 'manual', $args );
	}
}
