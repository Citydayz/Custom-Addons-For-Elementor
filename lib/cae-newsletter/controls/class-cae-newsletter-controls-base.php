<?php
/**
 * CAE Newsletter Widget Controls Base
 * Orchestrates all control sections for the Newsletter widget.
 * Follows anti-God Object pattern - delegates to specialized classes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Controls_Base
 */
class Cae_Newsletter_Controls_Base {

	/**
	 * Widget instance
	 *
	 * @var \Elementor\Widget_Base
	 */
	private $widget;

	/**
	 * Control sections
	 *
	 * @var array
	 */
	private $control_sections;

	/**
	 * Constructor
	 *
	 * @param \Elementor\Widget_Base $widget Widget instance.
	 */
	public function __construct( $widget ) {
		$this->widget = $widget;
		$this->init_control_sections();
	}

	/**
	 * Initialize control sections
	 */
	private function init_control_sections() {
		$this->control_sections = [
			'content' => new Cae_Newsletter_Content_Controls( $this->widget ),
			'style'   => new Cae_Newsletter_Style_Controls( $this->widget ),
		];
	}

	/**
	 * Register all widget controls
	 */
	public function register_controls() {
		foreach ( $this->control_sections as $section ) {
			$section->register_controls();
		}
	}
}

