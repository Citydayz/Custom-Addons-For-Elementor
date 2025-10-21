<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Widget Controls Base
 * 
 * Orchestrates all control sections for the Card widget.
 * Follows anti-God Object pattern - delegates to specialized classes.
 */
class Cae_Card_Controls_Base {

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
			'content' => new Cae_Card_Content_Controls( $this->widget ),
			'style'   => new Cae_Card_Style_Controls( $this->widget ),
			'button'  => new Cae_Card_Button_Controls( $this->widget ),
			'hover'   => new Cae_Card_Hover_Controls( $this->widget ),
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
