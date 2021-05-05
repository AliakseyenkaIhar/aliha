<?php
/**
 * Handle AJAX requests
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.11
 */

namespace Marusia\Theme;

use function Marusia\config;

class Ajax
{
    public static function call() {
        $calls = config( 'ajax', 'assets' );

        if ( ! empty( $calls ) && isset( $calls ) ) {
            foreach ( $calls as $action => $callback ) {
                add_action( "wp_ajax_$action", $callback );
                add_action( "wp_ajax_nopriv_$action", $callback );
            }
        }
    }
}
