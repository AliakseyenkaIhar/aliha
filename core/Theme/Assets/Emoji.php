<?php
/**
 * Handle emojis.
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Theme\Assets;

class Emoji
{

	/**
	 * Disable all emojis. We usually don't need them.
	 */
	public function disable() {
		if ( (bool) apply_filters( 'marusia_disable_emojis', true ) ) {

			// all actions related to emojis.
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

			// Remove DNS prefetch.
			add_filter( 'emoji_svg_url', '__return_false' );
		}
	}
}
