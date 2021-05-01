<?php
/**
 * Gutenberg blocks
 * Wrapper for Carbon Fields blocks
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.9
 */

namespace Marusia\Theme\Editor;

use Timber\Timber;
use Carbon_Fields\Block;
use function Marusia\config;

class Blocks
{

	/**
	 * Init blocks
	 */
	public function init() {
		add_action( 'carbon_fields_register_fields', [ $this, 'register' ] );
	}

	/**
	 * Register blocks using Block class
	 *
	 * @link https://docs.carbonfields.net/learn/containers/gutenberg-blocks.html
	 */
	public function register() {
		$blocks = config( 'blocks', 'blocks' );

		if ( ! empty( $blocks ) && isset( $blocks ) ) {
			foreach ( $blocks as $block ) {
				Block::make( $block['title'] )

					->add_fields( $block['fields'] )

					->set_category( 'marusia', esc_html__( 'Marusia Templates', 'marusia' ), 'carrot' )

					->set_description( $block['description'] ?? '' )

					->set_icon( $block['icon'] ?? 'admin-appearance' )

					->set_inner_blocks( $block['inner'] ?? false )

					->set_inner_blocks_template( $block['inner_templates'] ?? null )

					->set_render_callback( function( $fields, $attributes, $inner_blocks ) use ( $block ) {
						Timber::render( $block['callback_template'], compact( 'fields', 'attributes', 'inner_blocks' ) );
					} );
				}
		}
	}
}
