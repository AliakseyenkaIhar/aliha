<?php
/**
 * Add support for sidebars and widgets
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.7
 */

namespace Marusia\Theme;

use function Marusia\config;

class Widgets
{

	public function init() {
		add_action( 'widgets_init', [ $this, 'register' ] );
	}

	public function register() {
		$sidebars = config( 'sidebars', 'theme' );

		foreach ( $sidebars as $sidebar ) {
			register_sidebar( $sidebar );
		}

		$widgets_list = MARUSIA_THEME_PATH . '/main/Widgets';
		$widgets  = array_diff( scandir( $widgets_list ), [ '.', '..' ] );

		foreach ( $widgets as $widget ) {
			$widget_class = '\Theme\Widgets\\' . str_replace( '.php', '', $widget );
			register_widget( $widget_class );
		}
	}
}
