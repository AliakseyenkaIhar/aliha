<?php
/**
 * Global context by Timber
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Support;

use Timber\Timber;
use function Marusia\config;

trait Context
{

	/**
	 * Context
	 *
	 * @var array
	 */
	private static $context = [];

	/**
	 * Views to render.
	 * By default it is content/content.twig for all pages
	 *
	 * @var array
	 */
	private static $views = [ 'content/content.twig' ];

	private static function set_context() {
		self::$context = array_merge( Timber::context(), self::$context );
	}

	/**
	 * Get Timber context
	 */
	private static function get_context() {

		/**
		 * Set Timber context
		 */
		self::set_context();

		/**
		 * Include context from configuration file
		 */
		$ctx = \Theme\Context::global();

		/**
		 * Merge together our and default contexts
		 */
		self::$context = array_merge( self::$context, $ctx );

		/**
		 * Finally add local context from config file
		 */
		$local_ctx = \Theme\Context::local();
		foreach ( $local_ctx as $ctx_name => $ctx_value ) {

			$callback = $ctx_value['callback'];

			if ( call_user_func( ...$callback ) ) {
				self::$context[ $ctx_name ] = $ctx_value['value'];
			}
		}

		if ( get_theme_mod( 'marusia_breadcrumbs_show', false ) ) {
			self::breadcrumbs();
		}

		return self::$context;
	}

	/**
	 * Set breadcrumbs.
	 * Specifically 'ancestors' and 'current_title' global context
	 *
	 * TODO: add more crumbs
	 */
	private static function breadcrumbs() {

		$qo = get_queried_object();

		/**
		 * Get current title
		 */
		$currentTitleCrumbs = apply_filters(
			'marusia_current_crumbs_title',
			[
				'is_archive'           => $qo->name ?? '',
				'is_post_type_archive' => $qo->label ?? '',
				'is_search'            => get_search_query(),
				'is_404'               => get_theme_mod( 'marusia_breadcrumbs_404_title', __( '404', 'marusia' ) ),
				'is_singular'          => get_the_title(),
				'is_home'              => get_theme_mod( 'marusia_breadcrumbs_blog_title', __( 'Blog', 'marusia' ) ),
			]
		);

		foreach ( $currentTitleCrumbs as $callback => $crumb ) {
			if ( call_user_func( $callback ) ) {
				self::$context['current_title'] = $crumb;
			}
		}

		/**
		 * Ancestors for current page.
		 * It could be parent pages, archive links, year, month, blog page or anything else
		 */
		$ancestors = [];

		/**
		 * Nothing to return as an ancestor on these pages
		 */
		if ( is_404() || is_search() || is_post_type_archive() ) {
			return;
		}

		if ( is_archive() ) {

			/**
			 * Set ancestors for archive pages
			 */
			$terms = get_ancestors( $qo->term_id, $qo->taxonomy );
			foreach ( $terms as $term_id ) {
				$ancestors[ $term_id ]['link']  = get_term_link( $term_id );
				$ancestors[ $term_id ]['title'] = get_term( $term_id )->name;
			}
		} else {

			/**
			 * Add post type archive as a parent for non-pages and non-posts
			 */
			if ( ! in_array( get_post_type(), [ 'post', 'page' ], true ) ) {
				$ancestors[0]['link']  = get_post_type_archive_link( $qo->post_type );
				$ancestors[0]['title'] = get_post_type_object( $qo->post_type )->labels->name;
			} else {

				/**
				 * Otherwise set page parents
				 */
				$post_parents = get_post_ancestors( get_the_ID() );

				if ( ! in_array( get_option( 'page_on_front' ), $post_parents, true ) ) {
					foreach ( $post_parents as $parent ) {
						$ancestors[ $parent ]['link']  = get_permalink( $parent );
						$ancestors[ $parent ]['title'] = get_the_title( $parent );
					}
				}

				/**
				 * And blog posts ancestor (blog page itself)
				 */
				if ( is_single() && get_theme_mod( 'marusia_show_blog_ancestor', true ) ) {
					$ancestors[0]['link']  = get_post_type_archive_link( 'post' );
					$ancestors[0]['title'] = get_theme_mod( 'marusia_breadcrumbs_blog_title', __( 'Blog', 'marusia' ) );
				}
			}
		}

		/**
		 * Reverse array, since parents are going last
		 *
		 * All ancestors must have id as an a key plus title and link inside
		 *
		 * Ex:
		 * [123] => [
		 *   'link' => '/link/to/object',
		 *   'title' => 'Object title',
		 * ]
		 */
		self::$context['ancestors'] = array_reverse(
			/**
			 * Also, you can hook anything here
			 * But remember, it will be reversed!
			 */
			apply_filters(
				'marusia_breadcrumbs_ancestors',
				$ancestors
			)
		);
	}

}
