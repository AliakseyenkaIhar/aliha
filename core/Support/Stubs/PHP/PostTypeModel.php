<?php
/**
 * {{ POST_TYPE_UCF }} post type model
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Theme\Models;

use Carbon_Fields\Field;
use Carbon_Fields\Container;
use Marusia\Models\PostTypeModel as Model;

class {{ POST_TYPE_UCF }}Model extends Model
{

	/**
	 * Post type name
	 *
	 * @var string
	 */
	protected $name = '{{ POST_TYPE_LC }}';

	/**
	 * Set arguments for post type
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	protected function set_args() {
		return [
			'labels'              => [
				'name'          => esc_html__( '{{ POST_TYPE_PLURAL }}', 'marusia' ),
				'singular_name' => esc_html__( '{{ POST_TYPE_UCF }}', 'marusia' ),
				'menu_name'     => esc_html__( '{{ POST_TYPE_PLURAL }}', 'marusia' ),
			],
		];
	}

	/**
	 * Create post type metaboxes.
	 */
	public function create_meta() {
		// $this->container( 'marusia_{{ POST_TYPE_LC }}_container', esc_html__( '{{ POST_TYPE_UCF }} fields', 'marusia' ) )
		// 	->add_fields( [

		// 	] );
	}

	/**
	 * Set custom query for archive.
	 * It uses same arguments, as for WP_Query.
	 * Can be chained.
	 */
	// public function query( $query ) {
	// 	$this->set_query( $query, 'posts_per_page', 3 );
	// }

	/**
	 * Set custom column in admin area
	 *
	 * @param string $column | column name.
	 * @param int    $post_id | current post id.
	 */
	public function custom_column( $column, $post_id ) {
		// ...
	}

	/**
	 * Set columns
	 *
	 * @param array $columns | old columns.
	 * @return array | new columns.
	 */
	public function set_columns( $columns ) {
		return $columns;
	}
}
