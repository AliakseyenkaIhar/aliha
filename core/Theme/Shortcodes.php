<?php
/**
 * Add support shortocodes
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.8
 */

namespace Marusia\Theme;

class Shortcodes
{
	public function init() {
		$shortcodes_list = MARUSIA_THEME_PATH . '/main/Shortcodes';
		$shortcodes      = array_diff( scandir( $shortcodes_list ), [ '.', '..' ] );

		if ( isset( $shortcodes ) && ! empty( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcode ) {
				$shortcode_class = '\Theme\Shortcodes\\' . str_replace( '.php', '', $shortcode );
				( new $shortcode_class() )->register();
			}
		}
	}
}
