<?php
/**
 * Set global context of application
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme;

use Twig;
use Timber;
use function Marusia\config;

class Context
{
	/**
	 * Set context
	 */
	public static function set() {
		add_filter( 'timber/context', [ __CLASS__, 'set_global_context' ] );
		add_filter( 'timber/twig', [ __CLASS__, 'add_to_twig' ] );
		add_filter( 'timber/loader/loader', [ __CLASS__, 'loader' ] );
	}

	/**
	 * Setup global menu context
	 *
	 * @param array $context | Timber global context.
	 * @return $context | New Timber global context.
	 */
	public static function set_global_context( $context ) {
		/**
		 * Add menu locations to context
		 */
		$menu_context = [];
		foreach ( config( 'menus', 'theme' ) as $menu => $label ) {
			$menu_context[ $menu . '_menu' ] = new \Timber\Menu( $menu ); // TODO will change!
		}
		$context = array_merge( $context, $menu_context );

		/**
		 * Add sidebars
		 *
		 * @since 0.1.6
		 */
		$sidebar_context = [];
		foreach ( config( 'sidebars', 'theme' ) as $sidebar ) {
			$sidebar_context[ str_replace( '-', '_', $sidebar['id'] ) ] = Timber::get_widgets( $sidebar['id'] );
		}
		$context = array_merge( $context, $sidebar_context );

		/**
		 * Return global context
		 */
		$context = apply_filters( 'marusia_global_context', $context );
		return $context;
	}

	/**
	 * This is where you can add your own functions and filters to twig.
	 *
	 * @param Twig\Environment $twig get extension.
	 */
	public static function add_to_twig( Twig\Environment $twig ) {
		foreach ( config( 'twig_functions' ) as $name => $callback ) {
			$twig->addFunction( new Timber\Twig_Function( $name, $callback ) );
		}

		$twig->addFunction( new Timber\Twig_Function( 'env', 'getenv' ) );

		foreach ( config( 'twig_filters' ) as $name => $callback ) {
			$twig->addFilter( new Twig\TwigFilter( $name, $callback ) );
		}

		return $twig;
	}

	/**
	 * Add namespaces to view
	 */
	public static function loader( $loader ){
		foreach ( config( 'twig_namespaces' ) as $namespace => $path ) {
			$loader->addPath( $path, $namespace );
		}
		return $loader;
	}
}
