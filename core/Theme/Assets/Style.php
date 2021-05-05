<?php
/**
 * Handle all custom theme styles
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme\Assets;

use Marusia\Support\Assets;
use function Marusia\config;

class Style
{

	use Assets;

	/**
	 * List of our styles
	 *
	 * @var array
	 */
	private $styles = [];

	/**
	 * Get list of all custom styles
	 */
	public function get_styles() {
		return $this->styles;
	}

	/**
	 * Hook into wp_enqueue_scripts
	 */
	public function enqueue() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Add custom style to an array
	 */
	public function add( string $handle, array $args ) {
		$this->styles[ $handle ] = $args;
		return $this;
	}

	/**
	 * Enqueue all styles from $styles
	 */
	public function enqueue_styles() {

		$assets = self::list();

		if ( ! empty( $assets ) ) {
			foreach ( $assets as $handle => $path ) {

				if ( str_starts_with( $path, 'css/' ) ) {
					wp_enqueue_style(
						getenv( 'THEME' ) . '-' . str_replace( '.css', '', $handle ),
						MARUSIA_PUBLIC_URI . $path,
						[],
						time(),
						'all',
					);
				}
			}
		}

		if ( empty( $this->styles ) ) {
			return;
		}

		foreach ( $this->styles as $handle => $args ) {
			// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
			wp_enqueue_style( $handle, ...$args );
		}
	}

	/**
	 * Use assets.json file as a list of styles to be enqueued.
	 */
	private function enqueue_styles_from_assets_json() {

		$assets     = self::list();
		$additional = config( 'styles', 'assets' );

		foreach ( $additional as $style => $callback ) {
			if ( call_user_func( ...$callback ) ) {
				wp_enqueue_style(
					getenv( 'THEME' ) . '-' . $style,
					MARUSIA_PUBLIC_URI . $assets[ $style . '.css' ],
					[],
					time(),
					'all',
				);
			}

			unset( $assets[ $style . '.css' ] );
		}

		/**
		 * Load remaining scripts from manifest.json
		 */
		if ( ! empty( $assets ) ) {
			foreach ( $assets as $handle => $path ) {
				if ( str_starts_with( $path, 'css/' ) ) {
					$name = str_replace( '.css', '', $handle );
					wp_enqueue_style(
						getenv( 'THEME' ) . '-' . $name,
						MARUSIA_PUBLIC_URI . $path,
						[],
						time(),
						'all',
					);
				}
			}
		}
	}
}
