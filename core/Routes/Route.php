<?php
/**
 * Custom routing methods. Handle rendering in Controller, if any specific condition was met.
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Routes;

use Timber\Post;

class Route
{

	/**
	 * Define template was already rendered or not.
	 *
	 * @var boolean
	 */
	private static $__template_was_found = false;

	/**
	 * Get any route matches condition
	 *
	 * @param mixed $condition | condition to be matched.
	 * @param mixed $callback | function to handle view.
	 */
	public static function get( $condition, $callback ) {

		/**
		 * If no arguments passed, set second parameter as an empty array of values.
		 */
		$passed_callback = self::check_callback( $callback );

		if ( is_array( $condition ) ) {
			if ( call_user_func( ...$condition ) && ! self::$__template_was_found ) {
				call_user_func_array( ...$passed_callback );
				self::$__template_was_found = true;
			}
		} else {
			if ( call_user_func( $condition ) && ! self::$__template_was_found ) {
				call_user_func_array( ...$passed_callback );
				self::$__template_was_found = true;
			}
		}
	}

	/**
	 * If any condition was met, render view.
	 *
	 * @param array $conditions | array of conditions.
	 * @param mixed $callback | function to handle view.
	 */
	public static function any( array $conditions, $callback ) {

		/**
		 * If no arguments passed, set second parameter as an empty array of values.
		 */
		$passed_callback = self::check_callback( $callback );

		$any = false;
		foreach ( $conditions as $condition ) {
			if ( call_user_func( $condition ) ) {
				$any = true;
				break;
			}
		}

		if ( $any && ! self::$__template_was_found ) {
			call_user_func_array( ...$passed_callback );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Both conditions have to return true in order to render a view.
	 *
	 * @param array $conditions | array of conditions.
	 * @param mixed $callback | function to handle view.
	 */
	public static function both( array $conditions, $callback ) {

		/**
		 * If no arguments passed, set second parameter as an empty array of values.
		 */
		$passed_callback = self::check_callback( $callback );

		$total = count( $conditions );
		$i     = 0;
		foreach ( $conditions as $condition ) {
			if ( call_user_func( $condition ) ) {
				$i++;
			}
		}

		if ( $i === $total && ! self::$__template_was_found ) {
			call_user_func_array( ...$passed_callback );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Shortcut function for rendering templates with default post content.
	 *
	 * @param string $template | template slug.
	 */
	public static function template( string $template ) {
		if ( is_page_template( $template ) && ! self::$__template_was_found ) {
			$post = new Post();
			View::template( "templates/template.$template.twig" )->with_context( compact( 'post' ) );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Shortcut function for rendering front page content.
	 */
	public static function front() {
		if ( is_front_page() && ! self::$__template_was_found ) {
			View::render( 'content/content.front.twig' );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Shortcut function for rendering blog page content.
	 */
	public static function home() {
		if ( is_home() && ! self::$__template_was_found ) {
			View::render( 'content/post/content.home.twig' );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Shortcut function for rendering search results content.
	 */
	public static function search() {
		if ( is_search() && ! self::$__template_was_found ) {
			View::render( 'content/content.search.twig' );
			self::$__template_was_found = true;
		}
	}

	/**
	 * Check callback - is it array or not.
	 *
	 * @param mixed $callback | callback function, passed by user.
	 * @return array $passed_callback | callback array.
	 */
	private static function check_callback( $callback ) {
		$passed_callback = $callback;
		if ( ! is_array( $callback ) ) {
			$passed_callback    = [];
			$passed_callback[0] = $callback;
			$passed_callback[1] = [];
		}

		return $passed_callback;
	}
}
