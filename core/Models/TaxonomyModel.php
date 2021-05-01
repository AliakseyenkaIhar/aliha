<?php
/**
 * Standard taxonomy model
 *
 * This class is extended by every post type model and by default it extends Timber standard Term class - so it has full access to it's methods and properties
 *
 * Also we're using Carbon Fields - for creating metaboxes
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Models;

use Timber\Term;
use Carbon_Fields\Field;
use Carbon_Fields\Container;

abstract class TaxonomyModel extends Term
{
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Post type associated with this taxonomy
	 *
	 * @var array;
	 */
	protected $cpt;

	/**
	 * Register custom post type.
	 * This method should be called
	 */
	public function register() {
		add_action( 'init', [ $this, 'create' ] );
		add_action( 'carbon_fields_register_fields', [ $this, 'create_meta' ] );
	}

	/**
	 * Create custom post type
	 */
	public function create() {
		register_taxonomy(
			$this->name,
			$this->cpt,
			$this->get_args(),
		);
	}

	/**
	 * Set custom arguments. By default it is an empty array.
	 *
	 * @return array | array of arguments to combine with defaults.
	 */
	abstract protected function set_args();

	/**
	 * Get all arguments.
	 *
	 * @return array $args | list of arguments.
	 */
	private function get_args() {
		$args = wp_parse_args( $this->set_args(), $this->get_default_args() );
		return $args;
	}

	/**
	 * Get default arguments.
	 *
	 * @return array | default arguments for custom post type.
	 */
	private function get_default_args() {
		return [
			'label'             => null,
			'labels'            => [
				'name'              => '____',
				'singular_name'     => '____',
				'search_items'      => _x( 'Search', 'taxonomy label', 'marusia' ),
				'all_items'         => _x( 'All', 'taxonomy label', 'marusia' ),
				'view_item '        => _x( 'View', 'taxonomy label', 'marusia' ),
				'parent_item'       => _x( 'Parent', 'taxonomy label', 'marusia' ),
				'parent_item_colon' => _x( 'Parent item colon', 'taxonomy label', 'marusia' ),
				'edit_item'         => _x( 'Edit', 'taxonomy label', 'marusia' ),
				'update_item'       => _x( 'Update', 'taxonomy label', 'marusia' ),
				'add_new_item'      => _x( 'Add new', 'taxonomy label', 'marusia' ),
				'new_item_name'     => _x( 'New', 'taxonomy label', 'marusia' ),
				'menu_name'         => '____',
			],
			'description'        => '',
			'public'             => true,
			'publicly_queryable' => true,
			'show_in_nav_menus'  => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_tagcloud'      => true,
			'show_in_quick_edit' => true,
			'hierarchical'       => true,
			'rewrite'            => true,
			'meta_box_cb'        => null,
			'show_admin_column'  => true,
			'show_in_rest'       => true,
		];
	}

	/**
	 * Create post type metaboxes.
	 * Should be called in a child class.
	 */
	abstract public function create_meta();

	/**
	 * Wrapper for Container to simplify metabox creation inside a model file
	 *
	 * @param string $id | container id.
	 * @param string $label | container label for an admin area.
	 * @return object $container | container for metaboxes
	 */
	protected function container( string $id, string $label ) {
		$container = Container::make( 'term_meta', $id, $label )
						->where( 'term_taxonomy', '=', $this->name );
		return $container;
	}
}
