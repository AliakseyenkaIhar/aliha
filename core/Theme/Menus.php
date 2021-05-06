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
		$this->menus = \Theme\Context::menus();
	}

	/**
	 * Set up menu
	 */
	public function set() {
		if ( ! empty( $this->menus ) ) {
			add_action( 'after_setup_theme', [ $this, 'register' ] );
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
}
