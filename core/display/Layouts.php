<?php

namespace Dev4Press\Plugin\coreSocial\Display;

use Dev4Press\Plugin\coreSocial\Basic\Profiles;
use Dev4Press\v50\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Layouts {
	public $seq = 0;
	public $instance = array();

	public $defaults = array(
		'profiles'                     => array(),
		'block_id'                     => '',
		'show'                         => 'all',
		'layout'                       => 'icon',
		'style'                        => 'plain',
		'label'                        => 'name',
		'align'                        => 'center',
		'target'                       => '_blank',
		'rel'                          => 'noopener',
		'item_align'                   => 'center',
		'icon_size'                    => 20,
		'font_size'                    => 16,
		'icon_border_radius'           => 0,
		'icon_background'              => 'transparent',
		'icon_background_custom'       => '#FFFFFF',
		'icon_text'                    => 'inherit',
		'icon_text_custom'             => '#000000',
		'icon_hover_background'        => 'transparent',
		'icon_hover_background_custom' => '#FFFFFF',
		'icon_hover_text'              => 'brand',
		'icon_hover_text_custom'       => '#000000',
	);

	public function __construct() {
	}

	public static function instance() : Layouts {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new Layouts();
		}

		return $instance;
	}

	public function prepare( $instance ) : Layouts {
		$instance = $this->_prepare_from_shortcode( (array) $instance );

		$this->instance = wp_parse_args( (array) $instance, $this->_get_defaults() );

		return $this;
	}

	public function icons() : string {
		$instance = $this->instance;
		$profiles = $instance['show'] == 'all' ? array_keys( Profiles::instance()->list_profiles_for_select() ) : $instance['profiles'];

		if ( $instance['layout'] == 'icons' ) {
			$instance['layout'] = 'icon';
		}

		$wrapper   = 'coresocial-profile-list-' . $this->_sequence_id() . '-wrapper';
		$alignment = $instance['align'] != 'none' ? ' coresocial-list-align-' . $instance['align'] : '';
		$classes   = array(
			'coresocial-profiles-list',
			$wrapper,
			$alignment,
			'coresocial-layout-' . $instance['layout'],
			'coresocial-label-' . $instance['label'],
			'coresocial-style-' . $instance['style'],
		);

		if ( $instance['columns'] > 0 && $instance['align'] == 'justify' ) {
			$classes[] = 'coresocial-columns-' . min( $instance['columns'], 4 );
		}

		$render = '<ul class="' . Sanitize::html_classes( $classes ) . '">';

		$styles = array(
			'ul.' . $wrapper . ' li a { border-radius: ' . $instance['icon_border_radius'] . 'px; font-size: ' . $instance['font_size'] . 'px; }',
			'ul.' . $wrapper . ' li a > i { font-size: ' . $instance['icon_size'] . 'px; }',
		);

		foreach ( $profiles as $id ) {
			$profile = coresocial_profiles()->get( $id );

			if ( $profile !== false ) {
				$item_class   = 'coresocial-profile-' . $profile['network'] . '-' . $profile['id'];
				$item_classes = array( $item_class, 'coresocial-profile-align-' . $instance['item_align'] );
				$item_target  = ! empty( $instance['target'] ) ? ' target="' . $instance['target'] . '"' : '';
				$item_rel     = ! empty( $instance['rel'] ) ? ' rel="' . $instance['rel'] . '"' : '';
				$item_title   = $profile['name'];
				$item_label   = '<span class="__name">' . $profile['name'] . '</span>';

				if ( $profile['count'] > 0 ) {
					$count      = absint( $profile['count'] );
					$followers  = $this->_process_followers( $count, $profile['label'] ?? 'follower' );
					$item_title .= ' (' . wp_strip_all_tags( $followers ) . ')';
					$followers  = '<span class="__followers">' . $followers . '</span>';

					if ( $instance['label'] == 'followers' ) {
						$item_label = $followers;
					} else if ( $instance['label'] == 'name-followers' ) {
						$item_label .= '<br/>' . $followers;
					}
				}

				$icon  = '<i class="coresocial-icon coresocial-icon-' . $profile['network'] . '"></i>';
				$label = '<span>' . $item_label . '</span>';

				switch ( $instance['layout'] ) {
					case 'icon':
						$content = $icon;
						break;
					case 'label':
						$content = $label;
						break;
					default:
						$content = $icon . $label;
						break;
				}

				$render .= '<li class="' . Sanitize::html_classes( $item_classes ) . '">';
				$render .= '<a title="' . $item_title . '"' . $item_target . $item_rel . ' href="' . $profile['url'] . '">' . $content . '</a>';
				$render .= '</li>';

				$normal = array();
				$hover  = array();

				$value = $this->_process_color( 'background', 'background-color', $profile['network'], $instance );
				if ( ! empty( $value ) ) {
					$normal[] = $value;
				}

				$value = $this->_process_color( 'text', 'color', $profile['network'], $instance );
				if ( ! empty( $value ) ) {
					$normal[] = $value;
				}

				$value = $this->_process_color( 'hover_background', 'background-color', $profile['network'], $instance );
				if ( ! empty( $value ) ) {
					$hover[] = $value;
				}

				$value = $this->_process_color( 'hover_text', 'color', $profile['network'], $instance );
				if ( ! empty( $value ) ) {
					$hover[] = $value;
				}

				if ( ! empty( $normal ) ) {
					$styles[] = '.' . $wrapper . ' .' . $item_class . ' a {' . join( ' ', $normal ) . '}';
				}

				if ( ! empty( $hover ) ) {
					$styles[] = '.' . $wrapper . ' .' . $item_class . ' a:hover {' . join( ' ', $hover ) . '}';
				}
			}
		}

		$render .= '</ul>';

		$render .= '<style>' . join( PHP_EOL, $styles ) . '</style>';

		return $render;
	}

	private function _sequence_id() {
		$this->seq ++;

		return empty( $this->instance['block_id'] ) ? $this->seq : $this->instance['block_id'];
	}

	private function _prepare_from_shortcode( $instance ) : array {
		foreach ( array( 'profiles' ) as $key ) {
			$value = $instance[ $key ] ?? array();

			if ( is_string( $value ) ) {
				$value = explode( ',', $value );
				$value = array_map( 'trim', $value );
				$value = Sanitize::ids_list( $value );
			}

			$instance[ $key ] = $value;

			if ( empty( $value ) ) {
				$instance['show'] = 'all';
			}
		}

		return $instance;
	}

	private function _get_defaults() : array {
		return $this->defaults;
	}

	private function _process_color( $name, $property, $brand, $instance ) : string {
		$value = '';

		$input = $instance[ 'icon_' . $name ];
		$color = $instance[ 'icon_' . $name . '_custom' ];

		switch ( $input ) {
			case 'brand':
				$value = $property . ': var(--coresocial-color-brand-' . $brand . ');';
				break;
			case 'transparent':
				$value = $property . ': transparent;';
				break;
			case 'custom':
				$value = $property . ': ' . $color . ';';
				break;
		}

		return $value;
	}

	private function _process_followers( $count, $label ) : string {
		switch ( $label ) {
			default:
			case 'follower':
				/* translators: Profile button counts for Follower. %s: Number of followers. */
				return sprintf( _n( '%s Follower', '%s Followers', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'fan':
				/* translators: Profile button counts for Fan. %s: Number of fans. */
				return sprintf( _n( '%s Fan', '%s Fans', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'subscriber':
				/* translators: Profile button counts for Subscriber. %s: Number of subscribers. */
				return sprintf( _n( '%s Subscriber', '%s subscribers', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'user':
				/* translators: Profile button counts for Users. %s: Number of users. */
				return sprintf( _n( '%s User', '%s Users', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'member':
				/* translators: Profile button counts for Member. %s: Number of members. */
				return sprintf( _n( '%s Member', '%s Members', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'friend':
				/* translators: Profile button counts for Friend. %s: Number of friends. */
				return sprintf( _n( '%s Friend', '%s Friends', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'star':
				/* translators: Profile button counts for Star. %s: Number of stars. */
				return sprintf( _n( '%s Star', '%s Stars', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
			case 'patron':
				/* translators: Profile button counts for Patron. %s: Number of patrons. */
				return sprintf( _n( '%s Patron', '%s Patrons', $count, 'coresocial' ), '<strong>' . $count . '</strong>' );
		}
	}
}
