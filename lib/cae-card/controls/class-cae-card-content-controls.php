<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Content Controls
 * 
 * Handles content-related controls: image, title, text, positioning.
 * Separated to avoid God Object pattern.
 */
class Cae_Card_Content_Controls {

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
	 * Register content controls
	 */
	public function register_controls() {
		$this->widget->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'cae' ),
			]
		);

		$this->register_image_controls();
		$this->register_text_controls();
		$this->register_positioning_controls();
		$this->register_link_controls();
		$this->register_button_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register image controls
	 */
	private function register_image_controls() {
		$this->widget->add_control(
			'card_image',
			[
				'label' => esc_html__( 'Background Image', 'cae' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->widget->add_control(
			'image_position',
			[
				'label'     => esc_html__( 'Image Position', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'     => esc_html__( 'Cover', 'cae' ),
					'contain'   => esc_html__( 'Contain', 'cae' ),
					'centered'  => esc_html__( 'Centered', 'cae' ),
					'fixed'     => esc_html__( 'Fixed (Parallax)', 'cae' ),
					'stretch'   => esc_html__( 'Stretch', 'cae' ),
				],
				'condition' => [
					'card_image[url]!' => '',
				],
			]
		);
	}

	/**
	 * Register text controls
	 */
	private function register_text_controls() {
		$this->widget->add_control(
			'card_title',
			[
				'label'   => esc_html__( 'Title', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Card Title', 'cae' ),
			]
		);

		$this->widget->add_control(
			'card_text',
			[
				'label'   => esc_html__( 'Text', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This is a short description for the card content.', 'cae' ),
				'rows'    => 5,
			]
		);
	}

	/**
	 * Register positioning controls
	 */
	private function register_positioning_controls() {
		$this->widget->add_control(
			'content_position',
			[
				'label'   => esc_html__( 'Content Position', 'cae' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top'    => [
						'title' => esc_html__( 'Top', 'cae' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'cae' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'cae' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default' => 'middle',
				'toggle'  => false,
				'selectors' => [
					'{{WRAPPER}} .cae-card__content' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
			]
		);

		$this->widget->add_control(
			'content_align',
			[
				'label'   => esc_html__( 'Content Alignment', 'cae' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'cae' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'cae' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'cae' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .cae-card__content' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
	}

	/**
	 * Register link controls
	 */
	private function register_link_controls() {
		$this->widget->add_control(
			'card_link',
			[
				'label'       => esc_html__( 'Card Link', 'cae' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'cae' ),
			]
		);
	}

	/**
	 * Register button controls
	 */
	private function register_button_controls() {
		$this->widget->add_control(
			'show_button',
			[
				'label'     => esc_html__( 'Show Button', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'cae' ),
				'label_off' => esc_html__( 'Hide', 'cae' ),
				'default'   => 'no',
			]
		);

		$this->widget->add_control(
			'button_text',
			[
				'label'     => esc_html__( 'Button Text', 'cae' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Learn More', 'cae' ),
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'button_link',
			[
				'label'     => esc_html__( 'Button Link', 'cae' ),
				'type'      => \Elementor\Controls_Manager::URL,
				'condition' => [
					'show_button' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'button_align',
			[
				'label'     => esc_html__( 'Button Alignment', 'cae' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'cae' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cae' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'cae' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => false,
				'condition' => [
					'show_button' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card__button-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);
	}
}
