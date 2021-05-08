<?php
/**
 * This file is responsive for rendering your views files
 * The entry point is index.php in the root of the theme provided by WordPress itself
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Routes;

use Timber\Timber;
use Marusia\Support\Context;
use function Marusia\config;

class View
{

	use Context;

	/**
	 * Render any view
	 *
	 * @param array $layout | list of all view files including backups.
	 */
	public static function render( $layout ) {
		self::set_context();
		array_unshift( self::$views, $layout );
		Timber::render( self::$views, self::get_context() );
	}

	/**
	 * Add template to a views array.
	 *
	 * @param string $layout | template to render.
	 */
	public static function template( string $layout ) {
		array_unshift( self::$views, $layout );
		return new static();
	}

	/**
	 * Set global context for template.
	 *
	 * @param array $context | additional context.
	 */
	public static function with_context( array $context = [] ) {
		Timber::render( self::$views, array_merge( self::get_context(), $context ) );
	}

	/**
	 * Create custom templates
	 */
	public function custom_templates() {
		add_filter( 'theme_page_templates', [ $this, 'add_custom_templates' ], 11, 3 );
	}

	/**
	 * Load custom templates from config file
	 * Same parameters as for theme_page_templates hook
	 *
	 * @param array  $page_templates | Array of page templates.
	 * @param object $wp_theme | Theme object.
	 * @param object $post | Post object.
	 *
	 * @return $page_templates | New array of page templates.
	 */
	public function add_custom_templates( $page_templates, $wp_theme, $post ) {
		foreach ( config( 'templates', 'theme' ) as $template ) {
			if ( ! isset( $page_templates[ $template['slug'] ] ) ) {
				$page_templates[ $template['slug'] ] = $template['label'];
			}
		}
		return $page_templates;
	}
}
