<?php

namespace Theme\Shortcodes;

use Timber\Timber;

class {{ SHORTCODE }}Shortcode
{
	public function register() {
		add_shortcode( '{{ SHORTCODE_LC }}', [ $this, 'callback' ] );
	}

	public function callback( $atts ) {
		return Timber::compile( 'shortcodes/shortcode.{{ SHORTCODE_LC }}.twig', [] );
	}
}
