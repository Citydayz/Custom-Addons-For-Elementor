<?php
/**
 * CAE Newsletter Widget
 * Main widget class - delegates to specialized classes.
 * Follows anti-God Object pattern.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Widget
 */
class Cae_Newsletter_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-newsletter';

	/**
	 * Controls instance
	 *
	 * @var Cae_Newsletter_Controls_Base
	 */
	private $controls;

	/**
	 * Renderer instance
	 *
	 * @var Cae_Newsletter_Renderer
	 */
	private $renderer;

	/**
	 * Get widget name
	 *
	 * @return string
	 */
	public function get_name() {
		return self::SLUG;
	}

	/**
	 * Get widget title
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'CAE Newsletter', 'cae' );
	}

	/**
	 * Get widget icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-mail';
	}

	/**
	 * Get widget categories
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'cae-widgets' ];
	}

	/**
	 * Register widget controls
	 * Delegates to specialized controls classes.
	 */
	protected function register_controls() {
		$this->controls = new Cae_Newsletter_Controls_Base( $this );
		$this->controls->register_controls();
	}

	/**
	 * Render widget output
	 * Delegates to specialized renderer class.
	 */
	protected function render() {
		$this->renderer = new Cae_Newsletter_Renderer( $this );
		$this->renderer->render();
	}
}
