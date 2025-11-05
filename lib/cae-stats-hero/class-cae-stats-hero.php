<?php
/**
 * CAE Stats Hero Widget - Refactored
 * 
 * Split-screen layout with statistics on left and hero content on right.
 * Refactored to avoid God Object pattern - uses specialized classes.
 * 
 * Architecture:
 * - Cae_Stats_Hero_Controls: Manages Elementor controls (300 lines, 8 public methods)
 * - Cae_Stats_Hero_Renderer: Handles rendering logic (200 lines, 3 public methods)  
 * - Cae_Stats_Hero_Widget: Orchestrates widget (80 lines, 6 public methods)
 * 
 * Follows 04-security.mdc anti-God Object rules:
 * - Single responsibility per class
 * - Maximum 300 lines per class
 * - Maximum 10 public methods per class
 * - Clear separation of concerns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Stats_Hero_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-stats-hero';

	/**
	 * Controls manager instance
	 *
	 * @var Cae_Stats_Hero_Controls
	 */
	private $controls_manager;

	/**
	 * Renderer instance
	 *
	 * @var Cae_Stats_Hero_Renderer
	 */
	private $renderer;

	/**
	 * Constructor
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		$this->controls_manager = new Cae_Stats_Hero_Controls( $this );
		$this->renderer = new Cae_Stats_Hero_Renderer( $this );
	}

	/**
	 * Get widget name
	 */
	public function get_name() {
		return self::SLUG;
	}

	/**
	 * Get widget title
	 */
	public function get_title() {
		return esc_html__( 'CAE Stats Hero', 'cae' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Get widget categories
	 */
	public function get_categories() {
		return [ 'cae-widgets' ];
	}

	/**
	 * Enqueue widget styles and scripts in editor
	 */
	public function get_style_depends() {
		return [ 'cae-stats-hero' ];
	}

	/**
	 * Enqueue widget scripts in editor
	 */
	public function get_script_depends() {
		return [ 'cae-stats-hero' ];
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls() {
		$this->controls_manager->register_controls();
	}

	/**
	 * Render widget output in the editor
	 */
	protected function content_template() {
		$this->renderer->render_editor_template();
	}

	/**
	 * Render widget output
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->renderer->render( $settings );
	}
}
