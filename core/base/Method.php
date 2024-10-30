<?php

namespace Dev4Press\Plugin\coreSocial\Base;

use Dev4Press\Plugin\coreSocial\Basic\Plugin;
use Dev4Press\Plugin\coreSocial\Sharing\Loader;
use Dev4Press\Plugin\coreSocial\Sharing\Render;
use Dev4Press\v50\Core\Quick\URL;
use Dev4Press\v50\Core\Quick\WPR;
use WP_Post;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @property bool   $active
 * @property string $class
 */
abstract class Method {
	protected $name = '';

	protected $settings = array();
	protected $defaults = array();

	public static $context = null;

	public function __construct() {
		$this->reset();

		add_action( 'wp_head', array( $this, 'context' ), 100000 );
	}

	public function __get( $name ) {
		if ( isset( $this->settings[ $name ] ) ) {
			return $this->settings[ $name ];
		}

		return false;
	}

	public function context() : ?array {
		if ( is_null( self::$context ) ) {
			if ( is_singular() ) {
				$post = get_post();
				$id   = $post->ID;

				if ( $post instanceof WP_Post && $id == 0 ) {
					$id = WPR::get_post_id_by_slug( $post->post_name, $post->post_type );
				}

				if ( $id > 0 ) {
					self::$context = array(
						'item'    => 'post',
						'item_id' => $id,
						'title'   => $this->get_post_title( $id ),
						'url'     => $this->get_post_url( $id ),
						'image'   => $this->get_post_image( $id ),
					);
				}
			} else if ( WPR::is_any_tax() && get_queried_object() instanceof WP_Term ) {
				$id  = get_queried_object()->term_id;
				$tax = get_queried_object()->taxonomy;

				self::$context = array(
					'item'    => 'term',
					'item_id' => $id,
					'title'   => $this->get_term_title( $id, $tax ),
					'url'     => $this->get_term_url( $id, $tax ),
					'image'   => '',
				);
			}

			if ( is_null( self::$context ) ) {
				self::$context = array(
					'item'    => 'custom',
					'item_id' => 0,
					'title'   => $this->get_current_title(),
					'url'     => $this->get_current_url(),
					'image'   => '',
				);
			}
		}

		/**
		 * Filters the current page content array.
		 *
		 * @param array $context {
		 *
		 * @type string $item    Type of the page ('post', 'term', 'custom')
		 * @type int    $item_id ID of the item - 0 for 'custom', post_id for 'post' and 'term_id' for 'term'
		 * @type string $title   Title of the page.
		 * @type string $url     URL of the page. For 'custom' item it is used to identify the item.
		 *                       }
		 */
		return apply_filters( 'coresocial_page_context', self::$context );
	}

	public function get_post_url( $post_id = 0 ) : string {
		$post = get_post( $post_id );
		$url  = get_permalink( $post->ID );

		/**
		 * Filters the post URL.
		 *
		 * @param string                                   $url  Post permalink.
		 * @param \WP_Post                                 $post Post object.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this current method object.
		 */
		return apply_filters( 'coresocial_get_post_url', $url, $post, $this );
	}

	public function get_post_image( $post_id = 0 ) : string {
		$post  = get_post( $post_id );
		$file  = get_post_thumbnail_id( $post->ID );
		$image = $file > 0 ? wp_get_attachment_image_url( $file, 'full' ) : '';

		/**
		 * Filters the post featured image URL.
		 *
		 * @param string                                   $image Post featured image URL.
		 * @param int                                      $file  Featured Image ID.
		 * @param \WP_Post                                 $post  Post object.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this  current method object.
		 */
		return apply_filters( 'coresocial_get_post_url', $image, $file, $post, $this );
	}

	public function get_post_title( $post_id = 0 ) : string {
		$post  = get_post( $post_id );
		$title = Plugin::instance()->get_page_title();

		if ( empty( $title ) || coresocial_settings()->get( 'title_source' ) == 'simple' ) {
			$title = get_the_title( $post_id );
		}

		/**
		 * Filters the post title.
		 *
		 * @param string                                   $title Post title.
		 * @param \WP_Post                                 $post  Post object.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this  current method object.
		 */
		$title = apply_filters( 'coresocial_get_post_title', $title, $post, $this );

		if ( coresocial_settings()->get( 'title_with_site_name' ) ) {
			$title .= ', ' . Plugin::instance()->get_site_title();
		}

		return $title;
	}

	public function get_term_url( $term_id, $taxonomy = '' ) : string {
		$term = get_term( $term_id, $taxonomy );
		$url  = get_term_link( $term );

		/**
		 * Filters the term URL.
		 *
		 * @param string                                   $url  Term permalink.
		 * @param \WP_Term                                 $post Term object.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this current method object.
		 */
		return apply_filters( 'coresocial_get_term_url', $url, $term, $this );
	}

	public function get_term_title( $term_id, $taxonomy = '' ) : string {
		$term = get_term( $term_id, $taxonomy );
		$title = Plugin::instance()->get_page_title();

		if (empty($title) || coresocial_settings()->get( 'title_source' )) {
			$title = $term instanceof WP_Term ? $term->name : Plugin::instance()->get_page_title();
		}

		/**
		 * Filters the term title.
		 *
		 * @param string                                   $title Term title.
		 * @param \WP_Term                                 $post  Term object.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this  current method object.
		 */
		$title = apply_filters( 'coresocial_get_term_title', $title, $term, $this );

		if ( coresocial_settings()->get( 'title_with_site_name' ) ) {
			$title .= ', ' . Plugin::instance()->get_site_title();
		}

		return $title;
	}

	public function get_current_url() : string {
		$exc = coresocial_settings()->get( 'skip_url_args' );
		$url = URL::current_url();
		$url = remove_query_arg( $exc, $url );

		if ( is_paged() ) {
			if ( coresocial_settings()->get( 'skip_archive_paged_pages' ) && is_archive() ) {
				global $wp_rewrite;

				if ( WPR::is_permalinks_enabled() ) {
					$regex = '/(.+?)(\/' . $wp_rewrite->pagination_base . '\/\d?)/i';

					$url = preg_replace( $regex, '$1', $url );
				} else {
					$url = remove_query_arg( 'page', $url );
				}
			}

			if ( coresocial_settings()->get( 'skip_search_paged_pages' ) && is_search() ) {
				$url = get_search_link();
			}
		}

		/**
		 * Filters the current page URL.
		 *
		 * @param string                                   $url  Current page URL.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this current method object.
		 */
		return apply_filters( 'coresocial_get_current_url', $url, $this );
	}

	public function get_current_title() : string {
		$title = Plugin::instance()->get_page_title();

		/**
		 * Filters the current page title.
		 *
		 * @param string                                   $title Current page title.
		 * @param \Dev4Press\Plugin\coreSocial\Base\Method $this  current method object.
		 */
		$title = apply_filters( 'coresocial_get_current_title', $title, $this );

		if ( coresocial_settings()->get( 'title_with_site_name' ) ) {
			$title .= ', ' . Plugin::instance()->get_site_title();
		}

		return $title;
	}

	public function reset() {
		$this->settings = coresocial_settings()->group_get( $this->name );
		$this->defaults = coresocial_settings()->group_get( $this->name, true );
	}

	public function build( string $url, string $title, string $image_url = '', string $item = 'post', int $item_id = 0, array $networks = array() ) : array {
		coresocial_cache()->init_item_data( $item, $item_id, $url );

		$list   = array();
		$queued = false;

		foreach ( coresocial_loader()->get_networks() as $code => $network ) {
			if ( empty( $networks ) || in_array( $code, $networks ) ) {
				$network->loaded();

				$queue = Loader::instance()->maybe_add_to_queue( array(
					'network' => $code,
					'url'     => $url,
					'item'    => $item,
					'item_id' => $item_id,
				) );

				if ( $queue ) {
					$queued = true;
				}

				$data = $network->prepare( $url, $title, $image_url, $item, $item_id );
				$data = array_merge( $data, $this->prepare() );

				$list[] = Render::instance()->button( $data );
			}
		}

		if ( $queued ) {
			coresocial()->s()->save( 'storage' );
			coresocial()->spawn_queue_job();
		}

		return $list;
	}

	abstract public static function instance();

	abstract public function prepare() : array;
}
