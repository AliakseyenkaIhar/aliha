<?php
/**
 * Console class for making custom Shortocdes
 *
 * Use it like php aliha new:shortcode ShortcodeName
 *
 * @package WordPress
 * @subpackage Marusia
 * @since 0.1.7
 */

namespace Marusia\Console;

use Marusia\Support\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeShortcode extends Command
{

	use Console;

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected static $defaultName = 'new:shortcode';

	/**
	 * Configure user input
	 */
	protected function configure() {
		$this
			->setDescription( 'This command allows you to create a custom taxonomy for a post type' )
			->setHelp( 'Only latin letters allowed to create taxonomy' )
			->addArgument( 'shortcode', InputArgument::REQUIRED, 'Shortcode tag' );
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
		$shortcodeName = $input->getArgument( 'shortcode' );

		/**
		 * Only letters allowed!
		 */
		if ( ! $this->letters_only( $shortcodeName ) ) {
			return $this->failure( $output, 'Only latin letters allowed for Shortocdes!' );
		}

		/**
		 * Create Taxonomy Model file
		 */
		$this->create_shortcode_template( $shortcodeName );

		/**
		 * Return success
		 */
		return $this->success( $output, 'Successfully created new shortcode ' . $shortcodeName );
	}

}
