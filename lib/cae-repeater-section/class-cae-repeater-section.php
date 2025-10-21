<?php
/**
 * CAE Repeater Section Widget
 * Alternating layout with image left/right per item, repeater control.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Repeater_Section_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-repeater-section';

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
		return esc_html__( 'CAE Repeater Section', 'cae' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
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

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'cae' ),
				'type'  => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'cae' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'cae' ),
				'type'  => \Elementor\Controls_Manager::WYSIWYG,
			]
		);

		$repeater->add_control(
			'layout_position',
			[
				'label'   => esc_html__( 'Image Position', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => esc_html__( 'Left', 'cae' ),
					'right' => esc_html__( 'Right', 'cae' ),
				],
			]
		);

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Items', 'cae' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title'           => esc_html__( 'First Item', 'cae' ),
						'content'         => esc_html__( 'Your content goes here...', 'cae' ),
						'layout_position' => 'left',
					],
					[
						'title'           => esc_html__( 'Second Item', 'cae' ),
						'content'         => esc_html__( 'Your content goes here...', 'cae' ),
						'layout_position' => 'right',
					],
				],
				'title_field' => '{{{ title }}}',
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
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-repeater-section' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-repeater-section' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label'     => esc_html__( 'Item Spacing', 'cae' ),
				'type'      => \Elementor\Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .cae-repeater__item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-repeater__item-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Content Typography', 'cae' ),
				'selector' => '{{WRAPPER}} .cae-repeater__item-content',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'cae' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .cae-repeater-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output
	 * Outputs alternating layout with image left/right per item
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( self::SLUG );

		$items = $settings['items'];

		if ( empty( $items ) ) {
			return;
		}

		?>
		<section class="cae-repeater-section">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$image = $item['image'];
				$title = $item['title'];
				$content = $item['content'];
				$layout_position = $item['layout_position'];
				$is_even = ( $index % 2 === 0 );
				$final_position = ( 'auto' === $layout_position ) ? ( $is_even ? 'left' : 'right' ) : $layout_position;
				?>
				<article class="cae-repeater__item cae-repeater__item--<?php echo esc_attr( $final_position ); ?>">
					<div class="cae-repeater__item-content">
						<?php if ( ! empty( $title ) ) : ?>
							<h3 class="cae-repeater__item-title"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						
						<?php if ( ! empty( $content ) ) : ?>
							<div class="cae-repeater__item-text">
								<?php echo wp_kses_post( $content ); ?>
							</div>
						<?php endif; ?>
					</div>
					
					<?php if ( ! empty( $image['url'] ) ) : ?>
						<div class="cae-repeater__item-image">
							<img 
								src="<?php echo esc_url( $image['url'] ); ?>" 
								alt="<?php echo esc_attr( $image['alt'] ? $image['alt'] : $title ); ?>"
								width="<?php echo esc_attr( $image['width'] ?? 600 ); ?>"
								height="<?php echo esc_attr( $image['height'] ?? 400 ); ?>"
								loading="lazy"
							>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</section>
		<?php
	}
}
