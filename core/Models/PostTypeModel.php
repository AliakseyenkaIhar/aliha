<?php
/**
 * Standard post type model
 *
 * This class is extended by every post type model and by default it extends Timber standard Post class - so it has full access to it's methods and properties
 *
 * Also we're using Carbon Fields - for creating metaboxes
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Models;

use Timber\Post;
use Carbon_Fields\Field;
use Carbon_Fields\Container;
use WP_Query;

abstract class PostTypeModel extends Post
{
	/**
	 * Post type name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * How many posts per page show on an archive page.
	 * By default gets global query handled in admin area (posts per blog).
	 *
	 * @var int
	 */
	protected $posts_per_page;

	/**
	 * Register custom post type.
	 * This method should be called
	 */
	public function register() {
		add_action( 'init', [ $this, 'create' ] );
		add_action( 'carbon_fields_register_fields', [ $this, 'create_meta' ] );
		add_action( 'pre_get_posts', [ $this, 'query' ] );

		add_filter( 'manage_' . $this->name . '_posts_columns', [ $this, 'set_columns' ] );
		add_action( 'manage_' . $this->name . '_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );
	}

	/**
	 * Create custom post type
	 */
	public function create() {
		register_post_type(
			$this->name,
			$this->get_args(),
		);
	}

	/**
	 * Set custom arguments. By default it is an empty array.
	 */
	abstract protected function set_args();

	/**
	 * Return query for archive.
	 */
	public function query( WP_Query $query ) {
		return $query;
	}

	/**
	 * Set query arguments. For custom query, same params, as for WP_Query.
	 * Must be called inside query() method to work.
	 */
	protected function set_query( WP_Query $query, $param, $value ) {

		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->is_post_type_archive( $this->name ) ) {
			$query->set( $param, $value );
		}

		return $query;
	}

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
			'label'         => null,
			'labels'        => [
				'name'               => '____',
				'singular_name'      => '____',
				'add_new'            => _x( 'Add new', 'post type label', 'marusia' ),
				'add_new_item'       => _x( 'Add new', 'post type label', 'marusia' ),
				'edit_item'          => _x( 'Edit', 'post type label', 'marusia' ),
				'new_item'           => _x( 'New', 'post type label', 'marusia' ),
				'view_item'          => _x( 'View', 'post type label', 'marusia' ),
				'search_items'       => _x( 'Search', 'post type label', 'marusia' ),
				'not_found'          => _x( 'Not found', 'post type label', 'marusia' ),
				'not_found_in_trash' => _x( 'Not found in trash', 'post type label', 'marusia' ),
				'parent_item_colon'  => _x( 'Parent item colon', 'post type label', 'marusia' ),
				'menu_name'          => '____',
			],
			'description'   => '',
			'public'        => true,
			'show_in_menu'  => null,
			'show_in_rest'  => true,
			'rest_base'     => null,
			'menu_position' => null,
			'menu_icon'     => null,
			'hierarchical'  => false,
			'supports'      => [ 'title', 'editor' ], // 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats'.
			'taxonomies'    => [],
			'has_archive'   => true,
			'rewrite'       => true,
			'query_var'     => true,
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
		$container = Container::make( 'post_meta', $id, $label )
						->where( 'post_type', '=', $this->name );
		return $container;
	}

	abstract public function custom_column( $column, $post_id );

	abstract public function set_columns( $columns );

}
