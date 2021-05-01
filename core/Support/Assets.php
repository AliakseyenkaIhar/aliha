<?php
/**
 * Get assets list
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Support;

trait Assets
{
	/**
	 * List of assets for theme
	 *
	 * @var array
	 */
	private static $assets = [];

	/**
	 * Get list of assets
	 *
	 * @return array | list of all assets related to theme
	 */
	private static function list() {

		$manifest = get_template_directory() . '/public/manifest.json';

		if ( file_exists( $manifest ) ) {
			self::$assets = (array) json_decode( file_get_contents( $manifest, true ) );
		}
		return self::$assets;
	}
}
