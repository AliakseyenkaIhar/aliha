<?php
/**
 * Console class for making Post Type
 * It is also includes helpers for creating Taxonomy and templates for both.
 *
 * Use it like php aliha new:post_type PostTypeName -t TaxonomyName -f
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.0
 */

namespace Marusia\Console;

use Marusia\Support\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakePostType extends Command
{

	use Console;

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected static $defaultName = 'new:post_type';

	/**
	 * Configure user input
	 */
	protected function configure() {
		$this
			->setDescription( 'This command allows you to create a custom post type' )
			->setHelp( 'Only latin letters allowed to create post type' )
			->addArgument( 'post_type', InputArgument::REQUIRED, 'Post type name' )
			->addOption(
				'taxonomy',
				't',
				InputOption::VALUE_REQUIRED,
				'Taxonomy name',
			)
			->addOption(
				'template',
				'f',
				InputOption::VALUE_NONE,
				'Create a template',
			);
	}

	/**
	 * Execute command
	 *
	 * @param InputInterface  $input | console input.
	 * @param OutputInterface $output | console output.
	 * @return Command response
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {

		/**
		 * Get all input data
		 */
		$postTypeName = $input->getArgument( 'post_type' );
		$taxonomyName = $input->getOption( 'taxonomy' );

		/**
		 * Only letters allowed!
		 */
		if ( ! $this->letters_only( $postTypeName ) ) {
			return $this->failure( $output, 'Only latin letters allowed for Post Type!' );
		}

		/**
		 * Create Post type Model file
		 */
		$this->create_model_file( $postTypeName );

		$additional_output = '';

		if ( $taxonomyName ) {
			$this->create_model_file( $taxonomyName, 'Taxonomy', $postTypeName );

			if ( $input->getOption( 'template' ) ) {
				$this->create_archive_template( $taxonomyName, $postTypeName );
			}

			/**
			 * Only letters allowed!
			 */
			if ( ! $this->letters_only( $taxonomyName ) ) {
				return $this->failure( $output, 'Only latin letters allowed for Taxonomy!' );
			}

			/**
			 * Inform user that taxonomy was created too
			 */
			$additional_output = ' with taxonomy ' . $taxonomyName;
		}

		/**
		 * Create twig templates
		 */
		if ( $input->getOption( 'template' ) ) {
			$this->create_archive_template( $postTypeName );
			$this->create_single_template( $postTypeName );
			$this->create_preview_template( $postTypeName );
		}

		/**
		 * Return success
		 */
		return $this->success( $output, 'Successfully created new post type ' . $postTypeName . $additional_output );
	}

}
