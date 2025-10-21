<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Widget Controls Manager - Refactored
 * 
 * Orchestrates specialized control classes to avoid God Object pattern.
 * Each control section is handled by a dedicated class.
 */
class Cae_Card_Controls {

	/**
	 * Widget instance
	 *
	 * @var \Elementor\Widget_Base
	 */
	private $widget;

	/**
	 * Controls base instance
	 *
	 * @var Cae_Card_Controls_Base
	 */
	private $controls_base;

	/**
	 * Constructor
	 *
	 * @param \Elementor\Widget_Base $widget Widget instance.
	 */
	public function __construct( $widget ) {
		$this->widget = $widget;
		$this->controls_base = new Cae_Card_Controls_Base( $widget );
	}

	/**
	 * Register all widget controls
	 */
	public function register_controls() {
		$this->controls_base->register_controls();
	}
}