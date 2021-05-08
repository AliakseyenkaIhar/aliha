<?php
/**
 * Core theme features.
 *
 * ? It is also a wrapper for Timber to be prepared for upcoming changes.
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia;

use Timber\Timber;
use Marusia\Routes\View;
use Marusia\Theme\Menus;
use Marusia\Theme\Images;
use Marusia\Theme\Context;
use Marusia\Theme\Widgets;
use Marusia\Theme\Shortcodes;
use Marusia\Theme\Customizer;
use Marusia\Theme\Editor\Blocks;
use Marusia\Theme\Editor\Editor;
use Marusia\Theme\Hooks\BodyClass;

use function Marusia\config;

class Marusia
{

	/**
	 * Timber instance
	 *
	 * @var class
	 */
	private $timber;

	/**
	 * Constructor
	 * Get Timber instance.
	 */
	public function __construct() {

		if ( ! class_exists( 'Timber\\Timber' ) ) {
			wp_die(
				esc_html__( 'Marusia requires Timber installed to work properly!', 'marusia' )
			);
		}

		$this->timber = new Timber();

		/**
		 * Define views folder
		 */
		$default_views          = [ 'resources/views' ];
		$this->timber::$dirname = apply_filters( 'marusia_views_folder', $default_views );

		/**
		 * Autoescape output or not?
		 */
		$this->timber::$autoescape = apply_filters( 'marusia_autoescape', false );

		/**
		 * Remove all emojis
		 */
		$emojis = new \Marusia\Theme\Assets\Emoji();
		add_action( 'init', [ $emojis, 'disable' ] );

		\Marusia\Theme\Ajax::call();
	}

	/**
	 * Run application
	 */
	public function run() {

		/**
		 * Let WordPress handle the title tag
		 */
		add_theme_support( 'title-tag' );
		$GLOBALS['content_width'] = apply_filters( 'marusia_content_width', 800 );

		/**
		 * Set Timber context and functions
		 */
		Context::set();

		/**
		 * Custom hooks
		 */
		BodyClass::init();

		/**
		 * Set menus
		 */
		$menus = new Menus();
		$menus->set();

		/**
		 * Shortcodes
		 */
		$menus = new Shortcodes();
		$menus->init();

		/**
		 * Sidebars
		 */
		if ( ! empty( \Theme\Context::sidebars() ) ) {
			$sidebars = new Widgets();
			$sidebars->init();
		}

		/**
		 * Custom post types and taxonomies
		 */
		$this->register_models();

		/**
		 * Add custom templates
		 */
		$this->add_custom_templates();

		/**
		 * Add images support
		 */
		$images = new Images();
		$images->add_support()->register();

		/**
		 * Editor features
		 */
		$this->editor();
	}

	/**
	 * We will define custom templates for pages.
	 * It is About and Contacts templates
	 */
	private function add_custom_templates() {
		$views = new View();
		$views->custom_templates();
	}

	/**
	 * Register custom post types and taxonomies
	 */
	private function register_models() {
		$models_list = MARUSIA_THEME_PATH . '/main/Models';
		$post_types  = array_diff( scandir( $models_list ), [ '.', '..' ] );

		foreach ( $post_types as $post_type ) {
			$cpt_class = '\Theme\Models\\' . str_replace( '.php', '', $post_type );

			$excludes = [ 'Page', 'Post', 'Category', 'Tag' ]; // standard WordPress.

			if ( ! in_array( $post_type, $excludes, true ) ) {
				$cpt = new $cpt_class();

				if ( method_exists( $cpt_class, 'register' ) ) {
					$cpt->register();
				}
			}
		}
	}

	/**
	 * Init Carbon Fields metaboxes
	 *
	 * @link https://docs.carbonfields.net/#/
	 */
	public function metaboxes() {
		add_action( 'after_setup_theme', [ 'Carbon_Fields\\Carbon_Fields', 'boot' ] );
		return $this;
	}

	/**
	 * Add Gutenberg blocks
	 */
	public function blocks() {

		/**
		 * Custom blocks
		 *
		 * @since 0.1.9
		 */
		$blocks = new Blocks();
		$blocks->init();

		return $this;
	}

	/**
	 * Init customizer options with Kirki Framework
	 *
	 * @link https://kirki.org/
	 */
	public function customizer() {
		if ( class_exists( 'Kirki' ) ) {
			Customizer::init();
		}
		return $this;
	}

	/**
	 * Enqueue all assets and localizing scripts
	 */
	public function assets() {
		$style = new \Marusia\Theme\Assets\Style();
		$style->enqueue();

		$script = new \Marusia\Theme\Assets\Script();
		$script->enqueue();

		return $this;
	}

	public function editor() {
		$editor = new Editor();
		$editor->support();
	}

	/**
	 * Custom actions for application
	 */
	public function creating() {
		do_action( 'marusia_creating' );
	}

	public function created() {
		do_action( 'marusia_created' );
	}

	public function before_setup() {
		do_action( 'marusia_before_setup' );
	}
}
