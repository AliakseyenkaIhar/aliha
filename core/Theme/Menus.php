<?php
/**
 * Create menus and add theme into context.
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme;

use Timber\Menu;
use function Marusia\config;

class Menus
{

	/**
	 * List of menus
	 *
	 * @var array
	 */
	private $menus = [];

	/**
	 * Define menus
	 */
	public function __construct() {
		$this->menus = config( 'menus', 'theme' );
	}

	/**
	 * Set up menu
	 */
	public function set() {
		if ( ! empty( $this->menus ) ) {
			add_action( 'after_setup_theme', [ $this, 'register' ] );
			add_filter( 'timber/context', [ $this, 'add_menus_to_context' ] );
		}
	}

	/**
	 * Register all menus
	 */
	public function register() {
		register_nav_menus(
			apply_filters(
				'marusia_register_nav_menus',
				$this->menus
			)
		);
	}

	/**
	 * Add menus to context
	 *
	 * @param array $context | global context.
	 */
	public function add_menus_to_context( $context ) {
		/**
		 * Add menu locations to context
		 */
		$menu_context = [];
		foreach ( $this->menus as $menu => $label ) {
			$menu_context[ $menu . '_menu' ] = new Menu( $menu );
		}

		return $context;
	}
}
