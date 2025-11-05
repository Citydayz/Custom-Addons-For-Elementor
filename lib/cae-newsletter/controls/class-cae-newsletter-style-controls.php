<?php
/**
 * CAE Newsletter Style Controls
 * Handles style-related controls: colors, spacing, form styles.
 * Separated to avoid God Object pattern.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Style_Controls
 */
class Cae_Newsletter_Style_Controls {

	/**
	 * Widget instance
	 *
	 * @var \Elementor\Widget_Base
	 */
	private $widget;

	/**
	 * Constructor
	 *
	 * @param \Elementor\Widget_Base $widget Widget instance.
	 */
	public function __construct( $widget ) {
		$this->widget = $widget;
	}

	/**
	 * Register style controls
	 */
	public function register_controls() {
		$this->register_section_style_controls();
		$this->register_form_style_controls();
	}

	/**
	 * Register section style controls
	 */
	private function register_section_style_controls() {
		$this->widget->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_color_controls();
		$this->register_spacing_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register color controls
	 */
	private function register_color_controls() {
		$this->widget->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Description Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__description' => 'color: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Register spacing controls
	 */
	private function register_spacing_controls() {
		$this->widget->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-newsletter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'margin',
			[
				'label'      => esc_html__( 'Margin', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-newsletter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register form style controls
	 */
	private function register_form_style_controls() {
		$this->widget->start_controls_section(
			'section_form_style',
			[
				'label' => esc_html__( 'Form Style', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_input_style_controls();
		$this->register_button_style_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register input style controls
	 */
	private function register_input_style_controls() {
		$this->widget->add_control(
			'input_background_color',
			[
				'label'     => esc_html__( 'Input Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__input' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'input_text_color',
			[
				'label'     => esc_html__( 'Input Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'input_border_color',
			[
				'label'     => esc_html__( 'Input Border Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__input' => 'border-color: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Register button style controls
	 */
	private function register_button_style_controls() {
		$this->widget->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Button Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Button Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-newsletter__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Button Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-newsletter__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
}

