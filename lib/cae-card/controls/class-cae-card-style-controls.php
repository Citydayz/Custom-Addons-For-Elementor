<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Style Controls
 * 
 * Handles style-related controls: colors, typography, spacing, borders.
 * Separated to avoid God Object pattern.
 */
class Cae_Card_Style_Controls {

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
		$this->widget->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Card Style', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_border_controls();
		$this->register_overlay_controls();
		$this->register_typography_controls();
		$this->register_spacing_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register border controls
	 */
	private function register_border_controls() {
		$this->widget->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register overlay controls
	 */
	private function register_overlay_controls() {
		$this->widget->add_control(
			'overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .cae-card__overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'overlay_opacity',
			[
				'label'     => esc_html__( 'Overlay Opacity', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.3,
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card__overlay' => 'opacity: {{SIZE}};',
				],
			]
		);
	}

	/**
	 * Register typography controls
	 */
	private function register_typography_controls() {
		$this->widget->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__title',
			]
		);

		$this->widget->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Text Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__text',
			]
		);
	}

	/**
	 * Register spacing controls
	 */
	private function register_spacing_controls() {
		$this->widget->add_responsive_control(
			'card_min_height',
			[
				'label'      => esc_html__( 'Minimum Height', 'cae' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
						'step' => 10,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 5,
						'max' => 50,
						'step' => 0.5,
					],
					'rem' => [
						'min' => 5,
						'max' => 50,
						'step' => 0.5,
					],
				],
				'default'    => [
					'size' => 300,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-card' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'card_padding',
			[
				'label'      => esc_html__( 'Card Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-card__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
}
