<?php
/**
 * This file is responsive for all theme mods configuration
 *
 * Marusia Templates require Kirki Framework to be included
 *
 * @link https://kirki.org/docs/
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme;

use Kirki;

class Customizer
{

	/**
	 * Configuration id
	 *
	 * @var string;
	 */
	private static $config;

	/**
	 * Customizer object
	 *
	 * @var array
	 */
	private static $customizer = [];

	/**
	 * Initiate customizer options
	 */
	public static function init() {
		self::config();
		self::panels();
		self::sections();
		self::options();
	}

	/**
	 * Add default configuration for kirki Framework
	 *
	 * @link https://kirki.org/docs/setup/config/
	 */
	private static function config() {

		/**
		 * Get the list of all options for customizer
		 */
		self::$customizer = include get_template_directory() . '/main/settings/customizer.php';

		self::$config = apply_filters( 'marusia_default_config_id', 'marusia_config' );

		$default_config = [
			'capability'  => 'edit_theme_options',
			'option_type' => 'theme_mod',
		];

		$config = apply_filters( 'marusia_default_config', $default_config );

		Kirki::add_config( self::$config, $config );
	}

	/**
	 * Create panels
	 *
	 * @link https://kirki.org/docs/setup/panels-sections/
	 */
	private static function panels() {
		$panels = self::$customizer['panels'];
		foreach ( $panels as $panel => $args ) {
			Kirki::add_panel( $panel, $args );
		}
	}

	/**
	 * Create sections
	 *
	 * @link https://kirki.org/docs/setup/panels-sections/
	 */
	private static function sections() {
		$sections = self::$customizer['sections'];
		foreach ( $sections as $section => $settings ) {
			Kirki::add_section( $section, $settings['args'] );

			$options = $settings['options'];
			foreach ( $options as $option ) {

				/**
				 * No need to specify option section, but if you want you can do it
				 */
				if ( empty( $option['section'] ) || ! isset( $option['section'] ) ) {
					$option['section'] = $section;
				}
				self::option( $option );
			}
		}
	}

	/**
	 * Create sections
	 *
	 * @return void
	 */
	private static function options() {
		$options = self::$customizer['options'];
		foreach ( $options as $option => $args ) {
			$args['settings'] = $option;
			self::option( $args );
		}
	}

	/**
	 * Create option
	 *
	 * @param array $args | arguments for Kirki Framework theme mod.
	 * @link https://kirki.org/docs/controls/
	 */
	private static function option( array $args ) {
		Kirki::add_field( self::$config, $args );
	}
}
