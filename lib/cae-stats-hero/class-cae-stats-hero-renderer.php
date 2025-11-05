<?php
/**
 * CAE Stats Hero Renderer
 * 
 * Handles all rendering logic for the Stats Hero widget.
 * Follows single responsibility principle - only handles rendering.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cae_Stats_Hero_Renderer {

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
	 * Render widget output
	 *
	 * @param array $settings Widget settings.
	 */
	public function render( $settings ) {
		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( 'cae-stats-hero' );

		$background_style = $this->get_background_style( $settings );
		$link_attrs = $this->get_link_attributes( $settings );

		?>
		<div class="cae-stats-hero">
			<div class="cae-stats-hero__left">
				<?php $this->render_statistics( $settings ); ?>
			</div>
			<div class="cae-stats-hero__right" style="<?php echo esc_attr( $background_style ); ?>">
				<div class="cae-stats-hero__overlay"></div>
				<div class="cae-stats-hero__content">
					<div class="cae-stats-hero__text-column">
						<?php $this->render_hero_text( $settings ); ?>
					</div>
					<div class="cae-stats-hero__button-column">
						<?php $this->render_hero_button( $settings, $link_attrs ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render editor template
	 */
	public function render_editor_template() {
		?>
		<#
		var backgroundStyle = '';
		var backgroundImage = settings.background_image;
		var stat1Number = settings.stat_1_number;
		var stat1Label = settings.stat_1_label;
		var stat2Number = settings.stat_2_number;
		var stat2Label = settings.stat_2_label;
		var stat3Number = settings.stat_3_number;
		var stat3Label = settings.stat_3_label;
		var heroText = settings.hero_text;
		var buttonText = settings.button_text;
		var buttonLink = settings.button_link;
		
		// Background style
		if (backgroundImage && backgroundImage.url) {
			backgroundStyle = 'background-image: url(' + backgroundImage.url + '); background-size: cover; background-position: center;';
		}
		
		// Link attributes
		var linkAttrs = '';
		if (buttonLink && buttonLink.url) {
			linkAttrs = 'href="' + buttonLink.url + '"';
			if (buttonLink.is_external) {
				linkAttrs += ' target="_blank" rel="noopener"';
			}
		}
		
		// Animation data
		var animationData = '';
		if (settings.enable_counter_animation === 'yes') {
			var duration = settings.animation_duration ? settings.animation_duration.size : 2000;
			var delay = settings.animation_delay ? settings.animation_delay.size : 200;
			var easing = settings.animation_easing || 'easeOutCubic';
			animationData = ' data-cae-animation="true" data-cae-duration="' + duration + '" data-cae-delay="' + delay + '" data-cae-easing="' + easing + '"';
		}
		#>
		<div class="cae-stats-hero">
			<div class="cae-stats-hero__left">
				<div class="cae-stats-hero__stat">
					<span class="cae-stats-hero__number"{{{animationData}}}>{{{stat1Number}}}</span>
					<span class="cae-stats-hero__label">{{{stat1Label}}}</span>
				</div>
				<div class="cae-stats-hero__stat">
					<span class="cae-stats-hero__number"{{{animationData}}}>{{{stat2Number}}}</span>
					<span class="cae-stats-hero__label">{{{stat2Label}}}</span>
				</div>
				<div class="cae-stats-hero__stat">
					<span class="cae-stats-hero__number"{{{animationData}}}>{{{stat3Number}}}</span>
					<span class="cae-stats-hero__label">{{{stat3Label}}}</span>
				</div>
			</div>
			<div class="cae-stats-hero__right" style="{{{backgroundStyle}}}">
				<div class="cae-stats-hero__overlay"></div>
				<div class="cae-stats-hero__content">
					<div class="cae-stats-hero__text-column">
						<p class="cae-stats-hero__text">{{{heroText}}}</p>
					</div>
					<div class="cae-stats-hero__button-column">
						<# if (buttonText) { #>
							<# if (buttonLink && buttonLink.url) { #>
								<a class="cae-stats-hero__button" {{{linkAttrs}}}>{{{buttonText}}}</a>
							<# } else { #>
								<span class="cae-stats-hero__button">{{{buttonText}}}</span>
							<# } #>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get background style
	 *
	 * @param array $settings Widget settings.
	 * @return string Background style.
	 */
	private function get_background_style( $settings ) {
		$background_image = $settings['background_image'] ?? [];
		
		if ( empty( $background_image['url'] ) ) {
			return '';
		}

		return 'background-image: url(' . esc_url( $background_image['url'] ) . '); background-size: cover; background-position: center;';
	}

	/**
	 * Get link attributes
	 *
	 * @param array $settings Widget settings.
	 * @return string Link attributes.
	 */
	private function get_link_attributes( $settings ) {
		$button_link = $settings['button_link'] ?? [];
		
		if ( empty( $button_link['url'] ) ) {
			return '';
		}

		$link_attrs = 'href="' . esc_url( $button_link['url'] ) . '"';
		
		if ( ! empty( $button_link['is_external'] ) ) {
			$link_attrs .= ' target="_blank" rel="noopener"';
		}

		return $link_attrs;
	}

	/**
	 * Render statistics
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_statistics( $settings ) {
		// Add animation data attributes
		$animation_data = '';
		if ( ! empty( $settings['enable_counter_animation'] ) && $settings['enable_counter_animation'] === 'yes' ) {
			$duration = $settings['animation_duration']['size'] ?? 2000;
			$delay = $settings['animation_delay']['size'] ?? 200;
			$easing = $settings['animation_easing'] ?? 'easeOutCubic';
			$animation_data = sprintf(
				' data-cae-animation="true" data-cae-duration="%d" data-cae-delay="%d" data-cae-easing="%s"',
				esc_attr( $duration ),
				esc_attr( $delay ),
				esc_attr( $easing )
			);
		}

		for ( $i = 1; $i <= 3; $i++ ) {
			$number = $settings["stat_{$i}_number"] ?? '';
			$label = $settings["stat_{$i}_label"] ?? '';
			
			if ( empty( $number ) && empty( $label ) ) {
				continue;
			}

			?>
			<div class="cae-stats-hero__stat">
				<?php if ( ! empty( $number ) ) : ?>
					<span class="cae-stats-hero__number"<?php echo $animation_data; ?>><?php echo esc_html( $number ); ?></span>
				<?php endif; ?>
				
				<?php if ( ! empty( $label ) ) : ?>
					<span class="cae-stats-hero__label"><?php echo esc_html( $label ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}
	}

	/**
	 * Render hero text
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_hero_text( $settings ) {
		$hero_text = $settings['hero_text'] ?? '';
		
		if ( ! empty( $hero_text ) ) {
			?>
			<p class="cae-stats-hero__text"><?php echo esc_html( $hero_text ); ?></p>
			<?php
		}
	}

	/**
	 * Render hero button
	 *
	 * @param array  $settings Widget settings.
	 * @param string $link_attrs Link attributes.
	 */
	private function render_hero_button( $settings, $link_attrs ) {
		$button_text = $settings['button_text'] ?? '';
		$button_link = $settings['button_link'] ?? [];

		if ( ! empty( $button_text ) ) {
			if ( ! empty( $button_link['url'] ) ) {
				?>
				<a class="cae-stats-hero__button" <?php echo $link_attrs; ?>><?php echo esc_html( $button_text ); ?></a>
				<?php
			} else {
				?>
				<span class="cae-stats-hero__button"><?php echo esc_html( $button_text ); ?></span>
				<?php
			}
		}
	}
}
