<?php
/**
 * Helper for console commands.
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Support;

use Marusia\Support\Str;
use Symfony\Component\Console\Command\Command;

trait Console
{

	/**
	 * Theme name
	 *
	 * @var string
	 */
	private $theme = 'marusia';

	/**
	 * Return success
	 */
	private function success( $output, string $message ) {
		$output->writeln( $message );
		return Command::SUCCESS;
	}

	/**
	 * Return failure
	 */
	private function failure( $output, string $message ) {
		$output->writeln( $message );
		return Command::FAILURE;
	}

	/**
	 * Check only letters were passed in a console
	 */
	private function letters_only( $check ) {
		return ! empty( $check ) && preg_match( '/^[a-zA-Z]+$/i', $check );
	}

	/**
	 * Create model file
	 */
	private function create_model_file( $model, $type = 'PostType', $post_types = '' ) {

		/**
		 * Pluralize model name
		 */
		$plural = Str::pluralize( $model );

		/**
		 * Gather main info such as content to replace, content for replace and stub model.
		 */
		$replaceContent = [ '{{ POST_TYPE_PLURAL }}', '{{ POST_TYPE_UCF }}', '{{ POST_TYPE_LC }}' ];
		$replaceTo      = [ $plural, ucfirst( $model ), strtolower( $model ) ];
		$ModelStub      = __DIR__ . './Stubs/PHP/PostTypeModel.php';

		/**
		 * If it is taxonomy, replace data
		 */
		if ( 'Taxonomy' === $type ) {
			$replaceContent = [ '{{ TAXONOMY_PLURAL }}', '{{ TAXONOMY_UCF }}', '{{ TAXONOMY_LC }}', '{{ POST_TYPES_ARRAY }}' ];
			$replaceTo      = [ $plural, ucfirst( $model ), strtolower( $model ), strtolower( $post_types ) ];
			$ModelStub      = __DIR__ . './Stubs/PHP/TaxonomyModel.php';
		}

		/**
		 * Model name - this is where your model would be saved
		 */
		$ModelFile = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/main/Models/' . ucfirst( $model ) . 'Model.php';

		if ( ! file_exists( $ModelStub ) || ! copy( $ModelStub, $ModelFile ) ) {
			die( 'Unable to create file!' );
		}

		/**
		 * And put new content into that file
		 */
		$content    = file_get_contents( $ModelFile );
		$newContent = str_replace(
			$replaceContent,
			$replaceTo,
			$content
		);
		file_put_contents( $ModelFile, $newContent );
	}

	/**
	 * Create archive views
	 */
	private function create_archive_template( $model, $cpt = '' ) {
		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/content/' . strtolower( $model );

		$ModelStub = __DIR__ . '/Stubs/Twig/archive.twig';
		$ModelFile = $dirname . '/content.archive.twig';

		/**
		 * Create directory
		 */
		$this->make_dir( $dirname );

		$this->die_if_unable( $ModelStub, $ModelFile );

		/**
		 * And put new content into that file
		 */
		$replaceTo = ! empty( $cpt ) ? $cpt : $model;
		$content    = file_get_contents( $ModelFile );
		$newContent = str_replace(
			'{{ MODEL }}',
			strtolower( $replaceTo ),
			$content
		);
		file_put_contents( $ModelFile, $newContent );
	}

	/**
	 * Create single
	 */
	private function create_single_template( $model ) {
		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/content/' . strtolower( $model );

		$ModelStub = __DIR__ . '/Stubs/Twig/single.twig';
		$ModelFile = $dirname . '/content.single.twig';

		/**
		 * Create directory
		 */
		$this->make_dir( $dirname );

		$this->die_if_unable( $ModelStub, $ModelFile );

		/**
		 * And put new content into that file
		 */
		$content = file_get_contents( $ModelFile );
		file_put_contents( $ModelFile, $content );
	}

	/**
	 * Create preview
	 */
	private function create_preview_template( $model ) {

		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/content/' . strtolower( $model );

		$ModelStub = __DIR__ . '/Stubs/Twig/preview.twig';
		$ModelFile = $dirname . '/content.preview.twig';

		/**
		 * Create directory
		 */
		$this->make_dir( $dirname );

		$this->die_if_unable( $ModelStub, $ModelFile );

		/**
		 * And put new content into that file
		 */
		$content = file_get_contents( $ModelFile );
		file_put_contents( $ModelFile, $content );
	}

	/**
	 * Create custom template
	 */
	private function create_custom_template( $slug ) {

		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/templates';

		$ModelStub = __DIR__ . '/Stubs/Twig/template.twig';
		$ModelFile = $dirname . '/template.' . strtolower( $slug ) . '.twig';

		$this->die_if_unable( $ModelStub, $ModelFile );

		/**
		 * And put new content into that file
		 */
		$content    = file_get_contents( $ModelFile );
		$newContent = str_replace(
			'{{ SLUG }}',
			strtolower( $slug ),
			$content
		);
		file_put_contents( $ModelFile, $newContent );
	}

	/**
	 * Template for widget
	 */
	private function create_widget_template( $widget ) {

		/**
		 * Gather main info such as content to replace, content for replace and stub model.
		 */
		$replaceContent = [ '{{ WIDGET }}', '{{ WIDGET_LC }}' ];
		$replaceTo      = [ ucfirst( $widget ), strtolower( $widget ) ];
		$ModelStub      = __DIR__ . './Stubs/PHP/WidgetModel.php';

		/**
		 * Model name - this is where your model would be saved
		 */
		$ModelFile = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/main/Widgets/' . ucfirst( $widget ) . 'Widget.php';

		if ( ! file_exists( $ModelStub ) || ! copy( $ModelStub, $ModelFile ) ) {
			die( 'Unable to create file!' );
		}

		/**
		 * And put new content into that file
		 */
		$content    = file_get_contents( $ModelFile );
		$newContent = str_replace(
			$replaceContent,
			$replaceTo,
			$content
		);
		file_put_contents( $ModelFile, $newContent );

		/**
		 * Add template
		 */
		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/widgets/';

		$templateStub = __DIR__ . '/Stubs/Twig/widget.twig';
		$templateFile = $dirname . 'widget.' . strtolower( $widget ) . '.twig';

		/**
		 * Create directory
		 */
		$this->make_dir( $dirname );

		$this->die_if_unable( $templateStub, $templateFile );

		/**
		 * And put new content into that file
		 */
		$templateContent = file_get_contents( $templateFile );
		file_put_contents( $templateFile, $templateContent );
	}

	/**
	 * Template for shortcode
	 */
	private function create_shortcode_template( $shortcode ) {

		/**
		 * Gather main info such as content to replace, content for replace and stub model.
		 */
		$replaceContent = [ '{{ SHORTCODE }}', '{{ SHORTCODE_LC }}' ];
		$replaceTo      = [ ucfirst( $shortcode ), strtolower( $shortcode ) ];
		$ModelStub      = __DIR__ . './Stubs/PHP/ShortcodeModel.php';

		/**
		 * Model name - this is where your model would be saved
		 */
		$ModelFile = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/main/Shortcodes/' . ucfirst( $shortcode ) . 'Shortcode.php';

		if ( ! file_exists( $ModelStub ) || ! copy( $ModelStub, $ModelFile ) ) {
			die( 'Unable to create file!' );
		}

		/**
		 * And put new content into that file
		 */
		$content    = file_get_contents( $ModelFile );
		$newContent = str_replace(
			$replaceContent,
			$replaceTo,
			$content
		);
		file_put_contents( $ModelFile, $newContent );

		/**
		 * Add template
		 */
		$dirname = dirname( __DIR__, 5 ) . './web/app/themes/' . $this->theme . '/resources/views/shortcodes/';

		$templateStub = __DIR__ . '/Stubs/Twig/shortcode.twig';
		$templateFile = $dirname . 'widget.' . strtolower( $shortcode ) . '.twig';

		/**
		 * Create directory
		 */
		$this->make_dir( $dirname );

		$this->die_if_unable( $templateStub, $templateFile );

		/**
		 * And put new content into that file
		 */
		$templateContent = file_get_contents( $templateFile );
		file_put_contents( $templateFile, $templateContent );
	}

	/**
	 * Create directory if it's not exists
	 */
	private function make_dir( $path ) {
		if ( ! file_exists( $path ) ) {
			mkdir( $path, 0777, true );
		}
	}

	/**
	 * Die if unable to create file
	 */
	private function die_if_unable( $stub, $file ) {
		if ( ! file_exists( $stub ) || ! copy( $stub, $file ) ) {
			die( 'Unable to create file!' );
		}
	}
}
