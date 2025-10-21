<?php
/**
 * CAE Card Widget
 * Customizable card with title positioning, text alignment, background image, overlay, and hover effects.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Card_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-card';

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
	 * Register widget controls
	 */
	protected function register_controls() {
		// Content Section
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'cae' ),
			]
		);

		$this->add_control(
			'card_image',
			[
				'label' => esc_html__( 'Background Image', 'cae' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->add_control(
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

		$this->add_control(
			'card_title',
			[
				'label'   => esc_html__( 'Title', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Card Title', 'cae' ),
			]
		);

		$this->add_control(
			'card_text',
			[
				'label'   => esc_html__( 'Text', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your card description goes here...', 'cae' ),
			]
		);

		$this->add_control(
			'card_link',
			[
				'label' => esc_html__( 'Card Link', 'cae' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);

		$this->end_controls_section();

		// Layout Section
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'cae' ),
			]
		);

		$this->add_responsive_control(
			'title_position',
			[
				'label'     => esc_html__( 'Title Position', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => [
					'top'    => esc_html__( 'Top', 'cae' ),
					'middle' => esc_html__( 'Middle', 'cae' ),
					'bottom' => esc_html__( 'Bottom', 'cae' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card__content' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
			[
				'label'     => esc_html__( 'Title Alignment', 'cae' ),
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
				'selectors' => [
					'{{WRAPPER}} .cae-card__title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'cae' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'left'      => [
						'title' => esc_html__( 'Left', 'cae' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'cae' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'cae' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify'   => [
						'title' => esc_html__( 'Justify', 'cae' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .cae-card__text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'   => [
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .cae-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__title',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-card__text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Text Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-card__text',
			]
		);

		$this->add_responsive_control(
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

		$this->add_responsive_control(
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

		$this->end_controls_section();

		// Hover Effects Section
		$this->start_controls_section(
			'section_hover',
			[
				'label' => esc_html__( 'Hover Effects', 'cae' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
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

		$this->end_controls_section();
	}

	/**
	 * Render widget output in the editor
	 * This ensures the overlay works in Elementor editor
	 */
	protected function content_template() {
		?>
		<#
		var backgroundStyle = '';
		var imagePosition = settings.image_position || 'cover';
		var cardImage = settings.card_image;
		var cardTitle = settings.card_title;
		var cardText = settings.card_text;
		var cardLink = settings.card_link;
		var hoverEffect = settings.hover_effect;
		var overlayColor = settings.overlay_color || '#000000';
		var overlayOpacity = settings.overlay_opacity?.size || 0.3;
		
		// Background style
		if (cardImage && cardImage.url) {
			backgroundStyle = 'background-image: url(' + cardImage.url + ');';
			
			switch (imagePosition) {
				case 'cover':
					backgroundStyle += ' background-size: cover; background-position: center;';
					break;
				case 'contain':
					backgroundStyle += ' background-size: contain; background-position: center; background-repeat: no-repeat;';
					break;
				case 'centered':
					backgroundStyle += ' background-size: auto; background-position: center; background-repeat: no-repeat;';
					break;
				case 'fixed':
					backgroundStyle += ' background-size: cover; background-position: center; background-attachment: fixed;';
					break;
				case 'stretch':
					backgroundStyle += ' background-size: 100% 100%; background-position: center;';
					break;
			}
		}
		
		// Hover effect classes
		var hoverClass = '';
		var imagePositionClass = '';
		
		if (hoverEffect && hoverEffect !== 'none') {
			hoverClass = 'cae-card--hover-' + hoverEffect;
		}
		
		if (imagePosition === 'fixed') {
			imagePositionClass = 'cae-card--parallax';
		}
		
		// Link attributes
		var linkAttrs = '';
		var wrapperTag = 'div';
		if (cardLink && cardLink.url) {
			wrapperTag = 'a';
			linkAttrs = 'href="' + cardLink.url + '"';
			if (cardLink.is_external) {
				linkAttrs += ' target="_blank" rel="noopener"';
			}
		}
		#>
		<{{{wrapperTag}}} class="cae-card {{{hoverClass}}} {{{imagePositionClass}}}" {{{linkAttrs}}} style="{{{backgroundStyle}}}" role="article" aria-label="{{{cardTitle}}}">
			<div class="cae-card__overlay"></div>
			
			<# if (hoverEffect === 'fade-overlay' || hoverEffect === 'show-text') { #>
				<div class="cae-card__hover-overlay"></div>
			<# } #>
			
			<div class="cae-card__content">
				<# if (cardTitle) { #>
					<h3 class="cae-card__title">{{{cardTitle}}}</h3>
				<# } #>
				
				<# if (cardText) { #>
					<div class="cae-card__text">{{{cardText}}}</div>
				<# } #>
			</div>
		</{{{wrapperTag}}}>
		<?php
	}

	/**
	 * Render widget output
	 * Outputs semantic card with customizable layout, background, overlay, and hover effects
	 * Supports title positioning (top/middle/bottom), text alignment, image positioning (cover/contain/centered/fixed/stretch), 
	 * minimum height control (px/vh/em/rem), and various hover effects (zoom/slide/flip/glow)
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( self::SLUG );

		$card_image = $settings['card_image'];
		$card_title = $settings['card_title'];
		$card_text = $settings['card_text'];
		$card_link = $settings['card_link'];
		$hover_effect = $settings['hover_effect'];

		// Get background style based on image position
		$background_style = '';
		$image_position = $settings['image_position'] ?? 'cover';
		
		if ( ! empty( $card_image['url'] ) ) {
			$background_style = 'background-image: url(' . esc_url( $card_image['url'] ) . ');';
			
			switch ( $image_position ) {
				case 'cover':
					$background_style .= ' background-size: cover; background-position: center;';
					break;
				case 'contain':
					$background_style .= ' background-size: contain; background-position: center; background-repeat: no-repeat;';
					break;
				case 'centered':
					$background_style .= ' background-size: auto; background-position: center; background-repeat: no-repeat;';
					break;
				case 'fixed':
					$background_style .= ' background-size: cover; background-position: center; background-attachment: fixed;';
					break;
				case 'stretch':
					$background_style .= ' background-size: 100% 100%; background-position: center;';
					break;
			}
		}

		// Get link attributes
		$link_attrs = '';
		$wrapper_tag = 'div';
		if ( ! empty( $card_link['url'] ) ) {
			$wrapper_tag = 'a';
			$link_attrs = 'href="' . esc_url( $card_link['url'] ) . '"';
			if ( ! empty( $card_link['is_external'] ) ) {
				$link_attrs .= ' target="_blank" rel="noopener"';
			}
		}

		// Hover effect classes
		$hover_class = '';
		$image_position_class = '';
		
		if ( 'none' !== $hover_effect ) {
			$hover_class = 'cae-card--hover-' . esc_attr( $hover_effect );
		}
		
		if ( 'fixed' === $image_position ) {
			$image_position_class = 'cae-card--parallax';
		}

		?>
		<<?php echo esc_attr( $wrapper_tag ); ?> class="cae-card <?php echo esc_attr( $hover_class ); ?> <?php echo esc_attr( $image_position_class ); ?>" <?php echo $link_attrs; ?> style="<?php echo esc_attr( $background_style ); ?>" role="article" aria-label="<?php echo esc_attr( $card_title ); ?>">
			<div class="cae-card__overlay"></div>

			<?php if ( in_array( $hover_effect, [ 'fade-overlay', 'show-text' ], true ) ) : ?>
				<div class="cae-card__hover-overlay"></div>
			<?php endif; ?>

			<div class="cae-card__content">
				<?php if ( ! empty( $card_title ) ) : ?>
					<h3 class="cae-card__title"><?php echo esc_html( $card_title ); ?></h3>
				<?php endif; ?>
				
				<?php if ( ! empty( $card_text ) ) : ?>
					<div class="cae-card__text">
						<?php echo wp_kses_post( $card_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		</<?php echo esc_attr( $wrapper_tag ); ?>>
		<?php
	}
}
