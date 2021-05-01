<?php
/**
 * Handle all custom theme scripts
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme\Assets;

use Marusia\Support\Assets;
use function Marusia\config;

class Script
{

	use Assets;

	/**
	 * List of our scripts
	 *
	 * @var array
	 */
	private $scripts = [];

	/**
	 * Get list of all custom scripts
	 */
	public function get_scripts() {
		return $this->scripts;
	}

	/**
	 * Hook into wp_enqueue_scripts
	 */
	public function enqueue() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Add new script to an array
	 */
	public function add( string $handle, array $args ) {
		$this->scripts[ $handle ] = $args;
		return $this;
	}

	/**
	 * Enqueue all scripts from $scripts
	 */
	public function enqueue_scripts() {

		$this->enqueue_scripts_from_assets_json();

		if ( ! empty( $this->scripts ) ) {
			foreach ( $this->scripts as $handle => $args ) {
				// phpcs:ignore WordPress.WP.EnqueuedResourceParameters
				wp_enqueue_script( $handle, ...$args );
			}
		}

		// Enqueue comment-reply script.
		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Localize.
		$localizations = config( 'localize', 'assets' );
		foreach ( $localizations as $handler => $options ) {
			wp_localize_script( $handler, ...$options );
		}
	}

	/**
	 * Use assets.json file as a list of scripts to be enqueued.
	 */
	private function enqueue_scripts_from_assets_json() {
		$assets     = self::list();
		$additional = config( 'scripts', 'assets' );

		foreach ( $additional as $script => $callback ) {
			if ( call_user_func( ...$callback ) ) {
				wp_enqueue_script(
					getenv( 'THEME' ) . '-' . $script,
					MARUSIA_PUBLIC_URI . $assets[ $script . '.js' ],
					[],
					time(),
					true,
				);
			}

			unset( $assets[ $script . '.js' ] );
		}

		/**
		 * Load remaining scripts from manifest.json
		 */
		if ( ! empty( $assets ) ) {
			foreach ( $assets as $handle => $path ) {
				if ( str_starts_with( $path, 'js/' ) && ! str_starts_with( $path, 'fonts/' ) && ! str_ends_with( $path, '.map' ) ) {
					$name = str_replace( '.js', '', $handle );
					wp_enqueue_script(
						getenv( 'THEME' ) . '-' . $name,
						MARUSIA_PUBLIC_URI . $path,
						[],
						time(),
						true,
					);
				}
			}
		}
	}
}
