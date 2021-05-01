<?php
/**
 * Handle images
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme;

use function Marusia\config;

class Images
{

	/**
	 * Add theme support for images
	 */
	public function add_support() {

		/**
		 * Add support for thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add theme support for logo
		 */
		$custom_logo_args = config( 'logo', 'theme' );
		add_theme_support(
			'custom-logo',
			apply_filters(
				'marusia_custom_logo_args',
				$custom_logo_args
			)
		);

		/**
		 * Add theme support for header image
		 */
		$custom_header_args = config( 'header_image', 'theme' );
		add_theme_support(
			'custom-header',
			apply_filters(
				'marusia_custom_header_args',
				$custom_header_args
			)
		);

		/**
		 * Add theme support for body image
		 */
		$custom_backround_args = config( 'background_image', 'theme' );
		add_theme_support(
			'custom-background',
			apply_filters(
				'marusia_custom_background_args',
				$custom_backround_args
			)
		);

		return $this;
	}

	/**
	 * Show body image only on frontpage
	 */
	public static function show_bg_on_front_only() {
		if ( is_front_page() ) {
			_custom_background_cb();
		}
	}

	/**
	 * Register custom image sizes
	 */
	public function register() {
		$sizes = config( 'image_sizes', 'theme' );
		if ( ! empty( $sizes ) ) {
			foreach ( $sizes as $name => $params ) {
				add_image_size( $name, ...$params );
			}
		}
	}
}
