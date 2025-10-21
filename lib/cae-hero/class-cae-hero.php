<?php
/**
 * CAE Hero Widget
 * Fully customizable hero section with background, overlay, text, and CTA.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Hero_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-hero';

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
		return esc_html__( 'CAE Hero', 'cae' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-banner';
	}

	/**
	 * Get widget categories
	 */
	public function get_categories() {
		return [ 'cae-widgets' ];
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
			'background_type',
			[
				'label'   => esc_html__( 'Background Type', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => [
					'image' => esc_html__( 'Image', 'cae' ),
					'color' => esc_html__( 'Color', 'cae' ),
				],
			]
		);

		$this->add_control(
			'background_image',
			[
				'label'     => esc_html__( 'Background Image', 'cae' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => [
					'background_type' => 'image',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'background_type' => 'color',
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label' => esc_html__( 'Overlay Color', 'cae' ),
				'type'  => \Elementor\Controls_Manager::COLOR,
				'alpha' => true,
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
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .cae-hero__overlay' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Your Hero Title', 'cae' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your compelling subtitle goes here', 'cae' ),
			]
		);

		$this->add_control(
			'cta_text',
			[
				'label'   => esc_html__( 'CTA Text', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Learn More', 'cae' ),
			]
		);

		$this->add_control(
			'cta_url',
			[
				'label' => esc_html__( 'CTA URL', 'cae' ),
				'type'  => \Elementor\Controls_Manager::URL,
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'cae' ),
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
					'{{WRAPPER}} .cae-hero__content' => 'text-align: {{VALUE}};',
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
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-hero__title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-hero__title',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Subtitle Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-hero__subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'label'    => esc_html__( 'Subtitle Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-hero__subtitle',
			]
		);

		$this->add_control(
			'cta_color',
			[
				'label'     => esc_html__( 'CTA Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-hero__cta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cta_bg_color',
			[
				'label'     => esc_html__( 'CTA Background', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-hero__cta' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 * Outputs semantic HTML with proper escaping and accessibility attributes
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( self::SLUG );

		// Get background style
		$background_style = '';
		if ( 'image' === $settings['background_type'] && ! empty( $settings['background_image']['url'] ) ) {
			$background_style = 'background-image: url(' . esc_url( $settings['background_image']['url'] ) . ');';
		} elseif ( 'color' === $settings['background_type'] && ! empty( $settings['background_color'] ) ) {
			$background_style = 'background-color: ' . esc_attr( $settings['background_color'] ) . ';';
		}

		// Get CTA URL
		$cta_url = '';
		if ( ! empty( $settings['cta_url']['url'] ) ) {
			$cta_url = esc_url( $settings['cta_url']['url'] );
		}

		?>
		<section class="cae-hero" role="banner" style="<?php echo esc_attr( $background_style ); ?>">
			<?php if ( ! empty( $settings['overlay_color'] ) ) : ?>
				<div class="cae-hero__overlay" style="background-color: <?php echo esc_attr( $settings['overlay_color'] ); ?>;"></div>
			<?php endif; ?>
			
			<div class="cae-hero__content">
				<?php if ( ! empty( $settings['title'] ) ) : ?>
					<h1 class="cae-hero__title"><?php echo esc_html( $settings['title'] ); ?></h1>
				<?php endif; ?>
				
				<?php if ( ! empty( $settings['subtitle'] ) ) : ?>
					<p class="cae-hero__subtitle"><?php echo esc_html( $settings['subtitle'] ); ?></p>
				<?php endif; ?>
				
				<?php if ( ! empty( $settings['cta_text'] ) && ! empty( $cta_url ) ) : ?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="cae-hero__cta" role="button" aria-label="<?php echo esc_attr( $settings['cta_text'] ); ?>">
						<?php echo esc_html( $settings['cta_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}
