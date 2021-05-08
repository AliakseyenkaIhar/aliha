<?php
/**
 * Widget for contacts
 */

namespace Theme\Widgets;

use Timber;
use Carbon_Fields\Field;
use Carbon_Fields\Widget;

class {{ WIDGET }}Widget extends Widget
{

	public function __construct() {
		$this->setup(
			'marusia_{{ WIDGET_LC }}_widget',
			esc_html__( 'Marusia | {{ WIDGET }}', 'marusia' ),
			esc_html__( '{{ WIDGET }} description', 'marusia' ),
			[
				// Field::make( 'text', 'title', 'Title' )->set_default_value( 'Hello World!' ),
				// Field::make( 'textarea', 'content', 'Content' )->set_default_value( 'Lorem Ipsum dolor sit amet' ),
			],
		);
	}

	public function widget( $args, $instance ) {
		$widget_context = array_merge(
			Timber::context(),
			[
				'args'     => $args,
				'instance' => $instance,
			]
		);

		Timber::render(
			'widgets/widget.{{ WIDGET_LC }}.twig',
			$widget_context,
		);
	}

}
