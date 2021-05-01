<?php
/**
 * Bootstrap application
 *
 * In this file we just initialize application, add Symfony VarDumper component and Kirki Framework.
 * There are some extra settings also (for Kirki).
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

use Marusia\Marusia;
use HelloNico\Twig\DumpExtension;
use function Marusia\config;

/**
 * Make sure composer is installed
 */
$autoload = MARUSIA_PATH . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {

	require_once $autoload;

	/**
	 * Include Kirki Framework
	 */
	$kirki_file = MARUSIA_PATH . '/includes/kirki/installer.php';

	/**
	 * Check, if Kirki Framework already installed as a plugin
	 */
	if ( file_exists( $kirki_file ) && ! is_plugin_active( 'kirki' ) ) {
		include_once $kirki_file;
	}

	/**
	 * Define main theme constants
	 * Have to be defined before theme instance
	 */
	$definitions = config( 'definitions', 'theme' );

	foreach ( $definitions as $definition => $value ) {
		if ( ! defined( $definition ) ) {
			define( $definition, $value );
		}
	}

	/**
	 * App version
	 */
	$theme = wp_get_theme();
	if ( ! defined( 'MARUSIA_VERSION' ) ) {
		define( 'MARUSIA_VERSION', $theme->get( 'Version' ) );
	}

	/**
	 * Create application instance
	 */
	$app = new Marusia();

	$app->creating();

	/**
	 * Init main theme features, such as metaboxes, options, styles and scripts.
	 * All will be called in 'after_setup_theme' hook
	 */
	$app
		->assets()
		->metaboxes()
		->blocks()
		->customizer();

	$app->before_setup();

	/**
	 * Run application.
	 *
	 * Set priority to 1 - make sure it loaded first.
	 */
	add_action( 'after_setup_theme', [ $app, 'run' ], 1 );

	$app->created();

	/**
	 * Add debug bar
	 * There is already exists debugbar for Timber but it is not working in our case so we "modify" it
	 *
	 * @link https://github.com/nlemoine/timber-dump-extension
	 * @param object $twig | Twig instance.
	 * @return $twig.
	 */
	function add_dump_extension( $twig ) {
		$twig->addExtension( new DumpExtension() );
		return $twig;
	}

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && function_exists( 'add_filter' ) ) {
		add_filter( 'timber/loader/twig', 'add_dump_extension' );
	}
}
