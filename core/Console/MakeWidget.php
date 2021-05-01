<?php
/**
 * Console class for making custom Widget
 *
 * Use it like php aliha new:widget WidgetName
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

class MakeWidget extends Command
{

	use Console;

	/**
	 * Command name
	 *
	 * @var string
	 */
	protected static $defaultName = 'new:widget';

	/**
	 * Configure user input
	 */
	protected function configure() {
		$this
			->setDescription( 'This command allows you to create a custom taxonomy for a post type' )
			->setHelp( 'Only latin letters allowed to create taxonomy' )
			->addArgument( 'widget', InputArgument::REQUIRED, 'Widget name' );
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
		$widgetName = $input->getArgument( 'widget' );

		/**
		 * Only letters allowed!
		 */
		if ( ! $this->letters_only( $widgetName ) ) {
			return $this->failure( $output, 'Only latin letters allowed for Widgets!' );
		}

		/**
		 * Create Taxonomy Model file
		 */
		$this->create_widget_template( $widgetName );

		/**
		 * Return success
		 */
		return $this->success( $output, 'Successfully created new widget ' . $widgetName );
	}

}
