<?php
/**
 * CAE Stats Hero Controls
 * 
 * Manages all Elementor controls for the Stats Hero widget.
 * Follows single responsibility principle - only handles controls.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Stats_Hero_Controls {

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
	 * Register all widget controls
	 */
	public function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	/**
	 * Register content controls
	 */
	private function register_content_controls() {
		// Background Section
		$this->widget->start_controls_section(
			'section_background',
			[
				'label' => esc_html__( 'Image de fond', 'cae' ),
			]
		);

		$this->widget->add_control(
			'background_image',
			[
				'label' => esc_html__( 'Image de fond', 'cae' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->widget->end_controls_section();

		// Statistics Section
		$this->widget->start_controls_section(
			'section_statistics',
			[
				'label' => esc_html__( 'Statistiques', 'cae' ),
			]
		);

		// Animation controls
		$this->widget->add_control(
			'enable_counter_animation',
			[
				'label'        => esc_html__( 'Activer l\'animation de compteur', 'cae' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Oui', 'cae' ),
				'label_off'    => esc_html__( 'Non', 'cae' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->widget->add_control(
			'animation_duration',
			[
				'label'      => esc_html__( 'Durée de l\'animation (ms)', 'cae' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'ms' ],
				'range'      => [
					'ms' => [
						'min'  => 500,
						'max'  => 5000,
						'step' => 100,
					],
				],
				'default'    => [
					'unit' => 'ms',
					'size' => 2000,
				],
				'condition'  => [
					'enable_counter_animation' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'animation_delay',
			[
				'label'      => esc_html__( 'Délai entre les animations (ms)', 'cae' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'ms' ],
				'range'      => [
					'ms' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 50,
					],
				],
				'default'    => [
					'unit' => 'ms',
					'size' => 200,
				],
				'condition'  => [
					'enable_counter_animation' => 'yes',
				],
			]
		);

		$this->widget->add_control(
			'animation_easing',
			[
				'label'     => esc_html__( 'Type d\'animation', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'linear'         => esc_html__( 'Linéaire', 'cae' ),
					'easeInQuad'     => esc_html__( 'Accélération douce', 'cae' ),
					'easeOutQuad'    => esc_html__( 'Décélération douce', 'cae' ),
					'easeInOutQuad'  => esc_html__( 'Accélération/décélération', 'cae' ),
					'easeInCubic'    => esc_html__( 'Accélération forte', 'cae' ),
					'easeOutCubic'   => esc_html__( 'Décélération forte', 'cae' ),
					'easeInOutCubic' => esc_html__( 'Accélération/décélération forte', 'cae' ),
					'easeInQuart'    => esc_html__( 'Accélération très forte', 'cae' ),
					'easeOutQuart'   => esc_html__( 'Décélération très forte', 'cae' ),
					'easeInOutQuart' => esc_html__( 'Accélération/décélération très forte', 'cae' ),
					'easeInQuint'    => esc_html__( 'Accélération extrême', 'cae' ),
					'easeOutQuint'   => esc_html__( 'Décélération extrême', 'cae' ),
					'easeInOutQuint' => esc_html__( 'Accélération/décélération extrême', 'cae' ),
				],
				'default'   => 'easeOutCubic',
				'condition' => [
					'enable_counter_animation' => 'yes',
				],
			]
		);


		$this->add_statistic_controls( 1 );
		$this->add_statistic_controls( 2 );
		$this->add_statistic_controls( 3 );

		$this->widget->end_controls_section();

		// Hero Content Section
		$this->widget->start_controls_section(
			'section_hero_content',
			[
				'label' => esc_html__( 'Contenu principal', 'cae' ),
			]
		);

		$this->widget->add_control(
			'hero_text',
			[
				'label'   => esc_html__( 'Texte principal', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'MODALITÉS FINANCIÈRES, D\'INSCRIPTIONS, D\'ACCÈS ET INFORMATIONS GÉNÉRALES', 'cae' ),
			]
		);

		$this->widget->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Texte du bouton', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'EN SAVOIR PLUS', 'cae' ),
			]
		);

		$this->widget->add_control(
			'button_link',
			[
				'label' => esc_html__( 'Lien du bouton', 'cae' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Add controls for a specific statistic
	 *
	 * @param int $number Statistic number (1, 2, or 3).
	 */
	private function add_statistic_controls( $number ) {
		$this->widget->add_control(
			"stat_{$number}_number",
			[
				'label'   => esc_html__( "Statistique {$number} - Nombre", 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $this->get_default_stat_number( $number ),
			]
		);

		$this->widget->add_control(
			"stat_{$number}_label",
			[
				'label'   => esc_html__( "Statistique {$number} - Texte", 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $this->get_default_stat_label( $number ),
			]
		);
	}

	/**
	 * Get default statistic number
	 *
	 * @param int $number Statistic number.
	 * @return string Default value.
	 */
	private function get_default_stat_number( $number ) {
		$defaults = [ '+280', '85%', '5/5' ];
		return $defaults[ $number - 1 ] ?? '';
	}

	/**
	 * Get default statistic label
	 *
	 * @param int $number Statistic number.
	 * @return string Default value.
	 */
	private function get_default_stat_label( $number ) {
		$defaults = [
			'PERSONNES FORMÉES DEPUIS 2013',
			'TAUX DE RÉUSSITE DEPUIS 2015',
			'NOTE MOYENNE FRANCE TRAVAIL'
		];
		return $defaults[ $number - 1 ] ?? '';
	}

	/**
	 * Register style controls
	 */
	private function register_style_controls() {
		$this->register_layout_controls();
		$this->register_left_section_controls();
		$this->register_statistics_controls();
		$this->register_hero_content_controls();
		$this->register_button_controls();
	}

	/**
	 * Register layout controls
	 */
	private function register_layout_controls() {
		$this->widget->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Mise en page', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->widget->add_responsive_control(
			'widget_height',
			[
				'label'      => esc_html__( 'Hauteur du widget', 'cae' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 800,
						'step' => 10,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 400,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'widget_border_radius',
			[
				'label'      => esc_html__( 'Coins arrondis', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Register left section controls
	 */
	private function register_left_section_controls() {
		$this->widget->start_controls_section(
			'section_left_style',
			[
				'label' => esc_html__( 'Section gauche', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->widget->add_control(
			'left_section_bg',
			[
				'label'     => esc_html__( 'Couleur de fond', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#D4AF37',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__left' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Register statistics controls
	 */
	private function register_statistics_controls() {
		$this->widget->start_controls_section(
			'section_statistics_style',
			[
				'label' => esc_html__( 'Statistiques', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->widget->add_control(
			'stat_number_color',
			[
				'label'     => esc_html__( 'Couleur des nombres', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'stat_number_typography',
				'label'    => esc_html__( 'Typographie des nombres', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-stats-hero__number',
			]
		);

		$this->widget->add_control(
			'stat_label_color',
			[
				'label'     => esc_html__( 'Couleur des labels', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'stat_label_typography',
				'label'    => esc_html__( 'Typographie des labels', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-stats-hero__label',
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Register hero content controls
	 */
	private function register_hero_content_controls() {
		$this->register_content_layout_controls();
		$this->register_content_style_controls();
	}

	/**
	 * Register content layout controls
	 */
	private function register_content_layout_controls() {
		$this->widget->start_controls_section(
			'section_content_layout',
			[
				'label' => esc_html__( 'Positionnement', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Direction (comme Elementor)
		$this->widget->add_responsive_control(
			'content_direction',
			[
				'label'     => esc_html__( 'Direction', 'cae' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'row' => [
						'title' => esc_html__( 'Ligne', 'cae' ),
						'icon'  => 'eicon-arrow-right',
					],
					'column' => [
						'title' => esc_html__( 'Colonne', 'cae' ),
						'icon'  => 'eicon-arrow-down',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Ligne inversée', 'cae' ),
						'icon'  => 'eicon-arrow-left',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Colonne inversée', 'cae' ),
						'icon'  => 'eicon-arrow-up',
					],
				],
				'default'   => 'row',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__content' => 'flex-direction: {{VALUE}} !important;',
				],
			]
		);

		// Justifier le contenu (comme Elementor)
		$this->widget->add_responsive_control(
			'content_justify',
			[
				'label'     => esc_html__( 'Justifier le contenu', 'cae' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Début', 'cae' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Centre', 'cae' ),
						'icon'  => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Fin', 'cae' ),
						'icon'  => 'eicon-h-align-right',
					],
					'space-between' => [
						'title' => esc_html__( 'Espace entre', 'cae' ),
						'icon'  => 'eicon-h-align-stretch',
					],
					'space-around' => [
						'title' => esc_html__( 'Espace autour', 'cae' ),
						'icon'  => 'eicon-h-align-stretch',
					],
					'space-evenly' => [
						'title' => esc_html__( 'Espace égal', 'cae' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'default'   => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__content' => 'justify-content: {{VALUE}} !important;',
				],
			]
		);

		// Aligner les éléments (comme Elementor)
		$this->widget->add_responsive_control(
			'content_align',
			[
				'label'     => esc_html__( 'Aligner les éléments', 'cae' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Début', 'cae' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Centre', 'cae' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Fin', 'cae' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => esc_html__( 'Étirer', 'cae' ),
						'icon'  => 'eicon-v-align-stretch',
					],
				],
				'default'   => 'flex-end',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__content' => 'align-items: {{VALUE}} !important;',
				],
			]
		);


		$this->widget->end_controls_section();
	}

	/**
	 * Register content style controls
	 */
	private function register_content_style_controls() {
		$this->widget->start_controls_section(
			'section_hero_style',
			[
				'label' => esc_html__( 'Style du contenu', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->widget->add_control(
			'hero_text_color',
			[
				'label'     => esc_html__( 'Couleur du texte', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'hero_text_typography',
				'label'    => esc_html__( 'Typographie du texte', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-stats-hero__text',
			]
		);

		$this->widget->end_controls_section();
	}

	/**
	 * Register button controls
	 */
	private function register_button_controls() {
		$this->widget->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Bouton', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->widget->add_control(
			'button_bg_color',
			[
				'label'     => esc_html__( 'Couleur de fond', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => 'transparent',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Couleur du texte', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_control(
			'button_border_color',
			[
				'label'     => esc_html__( 'Couleur de la bordure', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typographie du bouton', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-stats-hero__button',
			]
		);

		$this->add_button_spacing_controls();
		$this->add_button_border_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Add button spacing controls
	 */
	private function add_button_spacing_controls() {
		$this->widget->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Espacement interne', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'top'    => 12,
					'right'  => 24,
					'bottom' => 12,
					'left'   => 24,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Espacement externe', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'top'    => 0,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Add button border controls
	 */
	private function add_button_border_controls() {
		$this->widget->add_responsive_control(
			'button_border_width',
			[
				'label'      => esc_html__( 'Épaisseur de la bordure', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'default'    => [
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->widget->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Coins arrondis du bouton', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
					'unit'   => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .cae-stats-hero__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
}
