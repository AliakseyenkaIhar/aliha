<?php
/**
 * All helper functions
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia;

if ( ! function_exists( 'config' ) ) {

	/**
	 * Get keys from configuration files
	 *
	 * @param string $key | key to get.
	 * @param string $file | filename to get information from.
	 * @return configuration value.
	 */
	function config( string $key, string $file = 'theme' ) {
		$config = include MARUSIA_PATH . '/config/' . $file . '.php';

		/**
		 * If such key doesn't exists return nothing
		 * No error - that mean get default value.
		 */
		if ( ! isset( $config[ $key ] ) ) {
			return null;
		}

		return $config[ $key ];
	}
}

if ( ! function_exists( 'src' ) ) {

	/**
	 * Get path to image in a resources.
	 *
	 * @param string $img | name of the file.
	 * @return string image src.
	 */
	function src( string $img ) {

		$public_image = get_template_directory_uri() . '/resources/assets/img/' . $img;

		$manifest = get_template_directory() . '/public/manifest.json';

		if ( file_exists( $manifest ) ) {
			$assets = (array) json_decode( file_get_contents( $manifest, true ) );
		}

		$key_exist = array_key_exists( 'img/' . $img, $assets );

		if ( $key_exist ) {
			$public_image = get_template_directory_uri() . '/public/' . $assets[ 'img/' . $img ];
		}

		return $public_image;
	}
}

if ( ! function_exists( 'clear_phone' ) ) {

	/**
	 * Clear phone number
	 *
	 * @param string $phone | phone number.
	 * @return string clear phone.
	 */
	function clear_phone( string $phone ) {
		return preg_replace( '/[^0-9+]/', '', esc_attr( $phone ) );
	}
}
