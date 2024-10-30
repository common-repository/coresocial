<?php

namespace Dev4Press\Plugin\coreSocial\Blocks;

use Dev4Press\v50\Core\Blocks\Register as BaseRegister;
use WP_Block;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Register extends BaseRegister {
	public static function instance() : Register {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Register();
		}

		return $instance;
	}

	public function categories( array $categories ) : array {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'coresocial',
					'title' => __( 'coreSocial', 'coresocial' ),
				),
			)
		);
	}

	public function blocks() {
		$this->_register_script();
		$this->_register_style();

		$this->_register_profiles_block();
		$this->_register_share_block();
	}

	protected function _default_block_classes( $attributes, $block ) : array {
		$classes = array( 'coresocial-block-wrapper', 'coresocial-block-' . $block );

		if ( ! empty( $attributes['class'] ) ) {
			$classes[] = esc_attr( $attributes['class'] );
		}

		return $classes;
	}

	protected function _register_script() {
		$asset_file = include CORESOCIAL_PATH . 'build/index.asset.php';

		wp_register_script( 'coresocial-editor', CORESOCIAL_URL . 'build/index.js', $asset_file['dependencies'], $asset_file['version'] );

		wp_localize_script( 'coresocial-editor', 'coresocial_blocks', array(
			'profiles' => Data::instance()->profiles(),
			'networks' => Data::instance()->networks(),
		) );

		wp_set_script_translations( 'coresocial-editor', 'coresocial', CORESOCIAL_PATH . 'languages' );
	}

	protected function _register_style() {
		$asset_file = include CORESOCIAL_PATH . 'build/index.asset.php';

		wp_register_style( 'coresocial-editor', CORESOCIAL_URL . 'css/blocks.css', array( 'coresocial-main' ), $asset_file['version'] );
	}

	protected function _register_profiles_block() {
		register_block_type( CORESOCIAL_BLOCKS_PATH . 'profiles', array(
			'render_callback' => array( $this, 'callback_profiles' ),
		) );
	}

	protected function _register_share_block() {
		register_block_type( CORESOCIAL_BLOCKS_PATH . 'share', array(
			'render_callback' => array( $this, 'callback_share' ),
		) );
	}

	public function callback_profiles( array $attributes ) : string {
		$classes = $this->_default_block_classes( $attributes, 'profiles' );

		return '<div class="' . join( ' ', $classes ) . '">' . Render::instance()->profiles( $attributes ) . '</div>';
	}

	public function callback_share( array $attributes, string $content, WP_Block $block ) : string {
		$classes = $this->_default_block_classes( $attributes, 'share' );

		if ( $attributes['context'] == 'block' || $this->is_editor() ) {
			$post_id = $block->context['postId'] ?? 0;
		} else {
			$post_id = 0;
		}

		$attributes['font_size']   = $attributes['font_size'] . 'px';
		$attributes['button_size'] = $attributes['button_size'] . 'px';
		$attributes['button_gap']  = $attributes['button_gap'] . 'px';
		$attributes['rounded']     = $attributes['rounded'] . 'px';

		return '<div class="' . join( ' ', $classes ) . '">' . Render::instance()->share( $post_id, $attributes ) . '</div>';
	}
}
