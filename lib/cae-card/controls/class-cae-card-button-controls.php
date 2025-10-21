<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Button Controls
 * 
 * Handles button-related controls: visibility, text, link, styling.
 * Separated to avoid God Object pattern.
 */
class Cae_Card_Button_Controls {

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
	 * Register button controls
	 */
	public function register_controls() {
		$this->register_style_controls();
	}

	/**
	 * Register button style controls
	 */
	private function register_style_controls() {
		$this->widget->start_controls_section(
			'section_button_style',
			[
				'label'     => esc_html__( 'Button Style', 'cae' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->register_color_controls();
		$this->register_typography_controls();
		$this->register_spacing_controls();
		$this->register_border_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register color controls
	 */
	private function register_color_controls() {
		$this->widget->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_hover_background_color',
			[
				'label'     => esc_html__( 'Hover Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_hover_text_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__button:hover' => 'color: {{VALUE}};',
				],
			]
		);
	}

	/**
	 * Register typography controls
	 */
	private function register_typography_controls() {
		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__button',
			]
		);
	}

	/**
	 * Register spacing controls
	 */
	private function register_spacing_controls() {
		$this->widget->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-card__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register border controls
	 */
	private function register_border_controls() {
		$this->widget->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-card__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => esc_html__( 'Border', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__button',
			]
		);
	}
}
