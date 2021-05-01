<?php
/**
 * Widget for contacts
 */

namespace Theme\Widgets;

use Timber;
use WP_Widget;

class {{ WIDGET }}Widget extends WP_Widget
{

	public function __construct() {
		parent::__construct(
			'marusia_{{ WIDGET_LC }}_widget',
			esc_html__( 'Marusia | {{ WIDGET }}', 'marusia' )
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
			'widgets/widget.contacts.twig',
			$widget_context,
		);
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		return $instance;
	}

}
