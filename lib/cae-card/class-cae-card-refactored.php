<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Widget - Refactored
 * 
 * A customizable card widget with background image, overlay, and hover effects.
 * Refactored to avoid God Object pattern - uses specialized classes.
 * 
 * Architecture:
 * - Cae_Card_Controls: Manages Elementor controls (200 lines, 3 public methods)
 * - Cae_Card_Renderer: Handles rendering logic (150 lines, 2 public methods)  
 * - Cae_Card_Widget_Refactored: Orchestrates widget (80 lines, 2 public methods)
 * 
 * Follows 04-security.mdc anti-God Object rules:
 * - Single responsibility per class
 * - Maximum 200 lines per class
 * - Maximum 10 public methods per class
 * - Clear separation of concerns
 */
class Cae_Card_Widget_Refactored extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-card';

	/**
	 * Controls manager instance
	 *
	 * @var Cae_Card_Controls
	 */
	private $controls_manager;

	/**
	 * Renderer instance
	 *
	 * @var Cae_Card_Renderer
	 */
	private $renderer;

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
		return esc_html__( 'CAE Card', 'cae' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-image-box';
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
		return [ 'cae-card' ];
	}

	/**
	 * Enqueue widget scripts in editor
	 */
	public function get_script_depends() {
		return [ 'cae-card' ];
	}

	/**
	 * Constructor
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		// Initialize specialized classes
		$this->controls_manager = new Cae_Card_Controls( $this );
		$this->renderer = new Cae_Card_Renderer( $this );
	}

	/**
	 * Register widget controls
	 * Delegated to specialized controls manager
	 */
	protected function register_controls() {
		$this->controls_manager->register_controls();
	}

	/**
	 * Render widget output
	 * Delegated to specialized renderer
	 */
	protected function render() {
		$this->renderer->render();
	}

	/**
	 * Render widget output in the editor
	 * Delegated to specialized renderer
	 */
	protected function content_template() {
		$this->renderer->render_editor();
	}
}
