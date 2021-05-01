<?php
/**
 * Console class for making Taxonomy only
 * It is also includes helpers for creating Templates.
 *
 * It is DO NOT create post types - it assumes you're already has one
 *
 * Use it like php aliha new:taxonomy TaxonomyName PostTypeName -f
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

class MakeTaxonomy extends Command
{

	use Console;

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected static $defaultName = 'new:taxonomy';

	/**
	 * Configure user input
	 */
	protected function configure() {
		$this
			->setDescription( 'This command allows you to create a custom taxonomy for a post type' )
			->setHelp( 'Only latin letters allowed to create taxonomy' )
			->addArgument( 'taxonomy', InputArgument::REQUIRED, 'Taxonomy name' )
			->addArgument( 'post_type', InputArgument::REQUIRED, 'Post type name' )
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
		$taxonomyName = $input->getArgument( 'taxonomy' );
		$postTypeName = $input->getArgument( 'post_type' );

		/**
		 * Only letters allowed!
		 */
		if ( ! $this->letters_only( $taxonomyName ) ) {
			return $this->failure( $output, 'Only latin letters allowed for Taxonomy!' );
		}

		/**
		 * Create Taxonomy Model file
		 */
		$this->create_model_file( $taxonomyName, 'Taxonomy', $postTypeName );
		if ( $input->getOption( 'template' ) ) {
			$this->create_archive_template( $taxonomyName, $postTypeName );
		}

		/**
		 * Return success
		 */
		return $this->success( $output, 'Successfully created new taxonomy ' . $taxonomyName . ' for ' . $postTypeName );
	}

}
