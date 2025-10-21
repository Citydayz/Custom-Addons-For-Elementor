<?php
/**
 * CAE Footer Widget
 * Dynamic footer with 1-4 columns, menus, logo toggle, and legal text with {year} token.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Footer_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug
	 */
	const SLUG = 'cae-footer';

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
		return esc_html__( 'CAE Footer', 'cae' );
	}

	/**
	 * Get widget icon
	 */
	public function get_icon() {
		return 'eicon-footer';
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
			'columns',
			[
				'label'   => esc_html__( 'Number of Columns', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
			]
		);

		$this->add_control(
			'show_logo',
			[
				'label'   => esc_html__( 'Show Logo', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'logo_text',
			[
				'label'     => esc_html__( 'Logo Text', 'cae' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => esc_html__( 'Your Brand', 'cae' ),
				'condition' => [
					'show_logo' => 'yes',
				],
			]
		);

		// Column 1
		$this->add_control(
			'column_1_title',
			[
				'label' => esc_html__( 'Column 1 Title', 'cae' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'column_1_menu',
			[
				'label'   => esc_html__( 'Column 1 Menu', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_nav_menus(),
			]
		);

		// Column 2
		$this->add_control(
			'column_2_title',
			[
				'label' => esc_html__( 'Column 2 Title', 'cae' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'column_2_menu',
			[
				'label'   => esc_html__( 'Column 2 Menu', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_nav_menus(),
			]
		);

		// Column 3
		$this->add_control(
			'column_3_title',
			[
				'label' => esc_html__( 'Column 3 Title', 'cae' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'column_3_menu',
			[
				'label'   => esc_html__( 'Column 3 Menu', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_nav_menus(),
			]
		);

		// Column 4
		$this->add_control(
			'column_4_title',
			[
				'label' => esc_html__( 'Column 4 Title', 'cae' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'column_4_menu',
			[
				'label'   => esc_html__( 'Column 4 Menu', 'cae' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_nav_menus(),
			]
		);

		$this->add_control(
			'legal_text',
			[
				'label'   => esc_html__( 'Legal Text', 'cae' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => sprintf(
					/* translators: %s: current year */
					esc_html__( '© %s All rights reserved', 'cae' ),
					date( 'Y' )
				),
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
					'{{WRAPPER}} .cae-footer' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-footer' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Link Color', 'cae' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cae-footer a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .cae-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get available navigation menus
	 *
	 * @return array Menu options
	 */
	private function get_nav_menus() {
		$menus = wp_get_nav_menus();
		$options = [ '' => esc_html__( '— Select —', 'cae' ) ];

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Render widget output
	 * Outputs footer with dynamic columns, menus, and legal text with year token replacement
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( self::SLUG );

		$columns = absint( $settings['columns'] );
		$legal_text = $settings['legal_text'];

		// Replace {year} token with current year
		$legal_text = str_replace( '{year}', date( 'Y' ), $legal_text );

		?>
		<footer class="cae-footer" role="contentinfo">
			<div class="cae-footer__container">
				<?php if ( 'yes' === $settings['show_logo'] && ! empty( $settings['logo_text'] ) ) : ?>
					<div class="cae-footer__logo">
						<?php echo esc_html( $settings['logo_text'] ); ?>
					</div>
				<?php endif; ?>

				<div class="cae-footer__columns cae-footer__columns--<?php echo esc_attr( $columns ); ?>">
					<?php for ( $i = 1; $i <= $columns; $i++ ) : ?>
						<?php
						$title = $settings[ "column_{$i}_title" ];
						$menu_id = absint( $settings[ "column_{$i}_menu" ] );
						?>
						<div class="cae-footer__column">
							<?php if ( ! empty( $title ) ) : ?>
								<h3 class="cae-footer__column-title"><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
							
							<?php if ( $menu_id ) : ?>
								<nav aria-label="<?php echo esc_attr( $title ? $title : sprintf( esc_html__( 'Column %d', 'cae' ), $i ) ); ?>">
									<?php
									wp_nav_menu(
										[
											'menu'       => $menu_id,
											'container'  => false,
											'menu_class' => 'cae-footer__menu',
											'fallback_cb' => false,
										]
									);
									?>
								</nav>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>

				<?php if ( ! empty( $legal_text ) ) : ?>
					<div class="cae-footer__legal">
						<?php echo wp_kses_post( $legal_text ); ?>
					</div>
				<?php endif; ?>
			</div>
		</footer>
		<?php
	}
}
