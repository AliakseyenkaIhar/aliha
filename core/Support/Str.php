<?php
/**
 * This class is a simple helper class to handle specific strings.
 * Used mostly for console commands
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Support;

class Str
{

	/**
	 * Pluralizes a word if quantity is not one.
	 *
	 * @param string $string | String to handle.
	 * @param string $plural | Plural form of word; function will attempt to deduce plural form from singular if not provided.
	 * @return string | pluralized string.
	 */
	public static function pluralize( $string, $plural = null ) {

		if ( null !== $plural ) {
			return $plural;
		}

		/**
		 * Check if it is a specific word.
		 */
		$excludes = [ 'news', 'testimonials', 'watches' ];
		if ( in_array( strtolower( $string ), $excludes, true ) ) {
			return $string;
		}

		$last_letter = strtolower( substr( $string, -1 ) );
		switch ( $last_letter ) {
			case 'y':
				$string = substr( $string, 0, -1 ) . 'ies'; // convert y to ies for plural form of word.
				break;
			case 's':
				$string = $string . 'es'; // convert s to ses for plural form of word.
				break;
			default:
				$string = $string . 's';
				break;
		}

		return $string;
	}

	/**
	 * Convert string to snake_case
	 *
	 * @param string $string | String to handle.
	 * @return string | string in snake_case.
	 */
	public static function to_snake_case( $string ) {
		return strtolower( preg_replace( '/([a-zA-Z])([A-Z])/', '$1_$2', $string ) );
	}

	/**
	 * Add space between two words
	 *
	 * @param string $string | String to handle.
	 * @return string with a space between two parts.
	 */
	public function add_space_between( $string ) {
		return preg_replace( '/([a-zA-Z])([A-Z])/', '$1 $2', $string );
	}
}
