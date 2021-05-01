<?php
/**
 * {{ TAXONOMY_UCF }} taxonomy model
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Theme\Models;

use Carbon_Fields\Field;
use Carbon_Fields\Container;
use Marusia\Models\TaxonomyModel as Model;

class {{ TAXONOMY_UCF }}Model extends Model
{

	/**
	 * Taxonomy name
	 *
	 * @var string
	 */
	protected $name = '{{ TAXONOMY_LC }}';

	/**
	 * Post type associated with this taxonomy
	 *
	 * @var array;
	 */
	protected $cpt = [ '{{ POST_TYPES_ARRAY }}' ];

	/**
	 * Set arguments for taxonomy
	 * Same as for register_taxonomy()
	 *
	 * @return array | arguments.
	 */
	protected function set_args() {
		return [
			'labels'              => [
				'name'          => esc_html__( '{{ TAXONOMY_PLURAL }}', 'marusia' ),
				'singular_name' => esc_html__( '{{ TAXONOMY_UCF }}', 'marusia' ),
				'menu_name'     => esc_html__( '{{ TAXONOMY_PLURAL }}', 'marusia' ),
			],
		];
	}

	/**
	 * Create taxonomy metaboxes.
	 */
	public function create_meta() {
		// $this->container( 'marusia_{{ TAXONOMY_LC }}_container', esc_html__( '{{ TAXONOMY_UCF }} fields', 'marusia' ) )
		// 	->add_fields( [

		// 	] );
	}
}
