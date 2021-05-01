<?php
/**
 * Gutenberg editor
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.9
 */

namespace Marusia\Theme\Editor;

use function Marusia\config;
class Editor
{
	public function support() {
		$this->editor_colors();
		$this->editor_fonts();
		$this->editor_wide();
	}

	private function editor_colors() {
		$colors = config( 'colors', 'theme' );
		add_theme_support( 'editor-color-palette', $colors );
	}

	private function editor_fonts() {
		$fonts = config( 'fonts', 'theme' );
		add_theme_support( 'editor-font-sizes', $fonts );
	}

	private function editor_wide() {
		add_theme_support( 'align-wide' );
	}
}
