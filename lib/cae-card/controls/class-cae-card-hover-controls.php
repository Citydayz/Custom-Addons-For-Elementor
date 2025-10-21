<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Hover Controls
 * 
 * Handles hover effect controls: zoom, slide, glow, flip, etc.
 * Separated to avoid God Object pattern.
 */
class Cae_Card_Hover_Controls {

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
	 * Register hover controls
	 */
	public function register_controls() {
		$this->widget->start_controls_section(
			'section_hover_effects',
			[
				'label' => esc_html__( 'Hover Effects', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_main_effect_control();
		$this->register_zoom_controls();
		$this->register_slide_controls();
		$this->register_glow_controls();
		$this->register_flip_controls();
		$this->register_overlay_controls();
		$this->register_animation_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register main effect control
	 */
	private function register_main_effect_control() {
		$this->widget->add_control(
			'hover_effect',
			[
				'label'   => esc_html__( 'Hover Effect', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'           => esc_html__( 'None', 'cae' ),
					'zoom'           => esc_html__( 'Zoom', 'cae' ),
					'slide-up'       => esc_html__( 'Slide Up', 'cae' ),
					'slide-down'     => esc_html__( 'Slide Down', 'cae' ),
					'slide-left'     => esc_html__( 'Slide Left', 'cae' ),
					'slide-right'    => esc_html__( 'Slide Right', 'cae' ),
					'fade-overlay'   => esc_html__( 'Fade Overlay', 'cae' ),
					'show-text'      => esc_html__( 'Show Text on Hover', 'cae' ),
					'flip'           => esc_html__( 'Flip', 'cae' ),
					'glow'           => esc_html__( 'Glow', 'cae' ),
				],
			]
		);
	}

	/**
	 * Register zoom effect controls
	 */
	private function register_zoom_controls() {
		$this->widget->add_control(
			'zoom_scale',
			[
				'label'     => esc_html__( 'Zoom Scale', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 1.0,
						'max'  => 2.0,
						'step' => 0.05,
					],
				],
				'default'   => [
					'size' => 1.05,
				],
				'condition' => [
					'hover_effect' => 'zoom',
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card--hover-zoom:hover' => 'transform: scale({{SIZE}});',
				],
			]
		);
	}

	/**
	 * Register slide effect controls
	 */
	private function register_slide_controls() {
		$this->widget->add_control(
			'slide_distance',
			[
				'label'     => esc_html__( 'Slide Distance', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'condition' => [
					'hover_effect' => [ 'slide-up', 'slide-down', 'slide-left', 'slide-right' ],
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card--hover-slide-up:hover' => 'transform: translateY(-{{SIZE}}px);',
					'{{WRAPPER}} .cae-card--hover-slide-down:hover' => 'transform: translateY({{SIZE}}px);',
					'{{WRAPPER}} .cae-card--hover-slide-left:hover' => 'transform: translateX(-{{SIZE}}px);',
					'{{WRAPPER}} .cae-card--hover-slide-right:hover' => 'transform: translateX({{SIZE}}px);',
				],
			]
		);
	}

	/**
	 * Register glow effect controls
	 */
	private function register_glow_controls() {
		$this->widget->add_control(
			'glow_color',
			[
				'label'     => esc_html__( 'Glow Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#007cba',
				'condition' => [
					'hover_effect' => 'glow',
				],
			]
		);

		$this->widget->add_control(
			'glow_intensity',
			[
				'label'     => esc_html__( 'Glow Intensity', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 5,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 20,
				],
				'condition' => [
					'hover_effect' => 'glow',
				],
			]
		);

		$this->widget->add_control(
			'glow_opacity',
			[
				'label'     => esc_html__( 'Glow Opacity', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0.1,
						'max'  => 1.0,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.6,
				],
				'condition' => [
					'hover_effect' => 'glow',
				],
			]
		);
	}

	/**
	 * Register flip effect controls
	 */
	private function register_flip_controls() {
		$this->widget->add_control(
			'flip_direction',
			[
				'label'     => esc_html__( 'Flip Direction', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'rotateY',
				'options'   => [
					'rotateY' => esc_html__( 'Horizontal (Y-axis)', 'cae' ),
					'rotateX' => esc_html__( 'Vertical (X-axis)', 'cae' ),
					'rotateZ' => esc_html__( 'Z-axis', 'cae' ),
				],
				'condition' => [
					'hover_effect' => 'flip',
				],
			]
		);

		$this->widget->add_control(
			'flip_duration',
			[
				'label'     => esc_html__( 'Flip Duration', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0.2,
						'max'  => 2.0,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.6,
				],
				'condition' => [
					'hover_effect' => 'flip',
				],
			]
		);
	}

	/**
	 * Register overlay controls
	 */
	private function register_overlay_controls() {
		$this->widget->add_control(
			'hover_overlay_color',
			[
				'label'     => esc_html__( 'Hover Overlay Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'alpha'     => true,
				'condition' => [
					'hover_effect' => [ 'fade-overlay', 'show-text' ],
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card__hover-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'hover_overlay_opacity',
			[
				'label'     => esc_html__( 'Hover Overlay Opacity', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.7,
				],
				'condition' => [
					'hover_effect' => [ 'fade-overlay', 'show-text' ],
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card:hover .cae-card__hover-overlay' => 'opacity: {{SIZE}};',
				],
			]
		);
	}

	/**
	 * Register animation controls
	 */
	private function register_animation_controls() {
		$this->widget->add_control(
			'animation_duration',
			[
				'label'     => esc_html__( 'Animation Duration', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0.1,
						'max'  => 2.0,
						'step' => 0.1,
					],
				],
				'default'   => [
					'size' => 0.3,
				],
				'condition' => [
					'hover_effect!' => [ 'none', 'flip' ],
				],
			]
		);
	}
}
