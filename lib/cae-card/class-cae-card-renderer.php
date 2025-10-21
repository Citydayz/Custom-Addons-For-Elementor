<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CAE Card Widget Renderer
 * 
 * Handles all rendering logic for the Card widget.
 * Separated to avoid God Object pattern.
 */
class Cae_Card_Renderer {

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
	 */
	public function render() {
		$settings = $this->widget->get_settings_for_display();

		// Mark widget as used for conditional enqueue
		Cae_Asset_Registry::mark( 'cae-card' );

		$card_image = $settings['card_image'] ?? [];
		$card_title = sanitize_text_field( $settings['card_title'] ?? '' );
		$card_text = sanitize_textarea_field( $settings['card_text'] ?? '' );
		$card_link = $settings['card_link'] ?? [];
		$hover_effect = sanitize_key( $settings['hover_effect'] ?? 'none' );
		$image_position = sanitize_key( $settings['image_position'] ?? 'cover' );
		$content_position = sanitize_key( $settings['content_position'] ?? 'middle' );
		$content_align = sanitize_key( $settings['content_align'] ?? 'center' );
		$show_button = 'yes' === ( $settings['show_button'] ?? 'no' );
		$button_text = sanitize_text_field( $settings['button_text'] ?? '' );
		$button_link = $settings['button_link'] ?? [];

		$background_style = $this->get_background_style( $card_image, $image_position );
		$link_attributes = $this->get_link_attributes( $card_link );
		$wrapper_tag = ! empty( $card_link['url'] ) ? 'a' : 'div';
		$hover_class = $this->get_hover_class( $hover_effect );
		$image_position_class = $this->get_image_position_class( $image_position );
		$hover_attributes = $this->get_hover_attributes( $settings );
		
		// Content position is handled by Elementor selectors

		?>
		<<?php echo esc_attr( $wrapper_tag ); ?> class="cae-card <?php echo esc_attr( $hover_class ); ?> <?php echo esc_attr( $image_position_class ); ?>" <?php echo $link_attributes; ?> style="<?php echo esc_attr( $background_style ); ?>" <?php echo $hover_attributes; ?> role="article" aria-label="<?php echo esc_attr( $card_title ); ?>">
			<div class="cae-card__overlay"></div>

			<?php if ( in_array( $hover_effect, [ 'fade-overlay', 'show-text' ], true ) ) : ?>
				<div class="cae-card__hover-overlay"></div>
			<?php endif; ?>

			<div class="cae-card__content">
				<?php if ( ! empty( $card_title ) ) : ?>
					<h3 class="cae-card__title"><?php echo esc_html( $card_title ); ?></h3>
				<?php endif; ?>
				
				<?php if ( ! empty( $card_text ) ) : ?>
					<div class="cae-card__text"><?php echo wp_kses_post( $card_text ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $show_button && ! empty( $button_text ) ) : ?>
				<div class="cae-card__button-wrapper">
					<?php
					$button_attributes = '';
					if ( ! empty( $button_link['url'] ) ) {
						$button_attributes = 'href="' . esc_url( $button_link['url'] ) . '"';
						if ( ! empty( $button_link['is_external'] ) ) {
							$button_attributes .= ' target="_blank" rel="noopener"';
						}
					}
					?>
					<a class="cae-card__button" <?php echo $button_attributes; ?>>
						<?php echo esc_html( $button_text ); ?>
					</a>
				</div>
			<?php endif; ?>
		</<?php echo esc_attr( $wrapper_tag ); ?>>
		<?php
	}

	/**
	 * Render widget output in the editor
	 */
	public function render_editor() {
		?>
		<#
		var backgroundStyle = '';
		var imagePosition = settings.image_position || 'cover';
		var cardImage = settings.card_image;
		var cardTitle = settings.card_title;
		var cardText = settings.card_text;
		var cardLink = settings.card_link;
		var hoverEffect = settings.hover_effect;
		var contentPosition = settings.content_position || 'middle';
		var contentAlign = settings.content_align || 'center';
		var showButton = settings.show_button === 'yes';
		var buttonText = settings.button_text || '';
		var buttonLink = settings.button_link || {};
		
		// Hover effect attributes
		var hoverAttrs = '';
		if (hoverEffect === 'glow') {
			var glowColor = settings.glow_color || '#007cba';
			var glowIntensity = settings.glow_intensity?.size || 20;
			var glowOpacity = settings.glow_opacity?.size || 0.6;
			hoverAttrs += ' data-glow-color="' + glowColor + '"';
		}
		if (hoverEffect === 'flip') {
			var flipDirection = settings.flip_direction || 'rotateY';
			hoverAttrs += ' data-flip-direction="' + flipDirection + '"';
		}
		
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
		<{{{wrapperTag}}} class="cae-card {{{hoverClass}}} {{{imagePositionClass}}}" {{{linkAttrs}}} style="{{{backgroundStyle}}}" {{{hoverAttrs}}} role="article" aria-label="{{{cardTitle}}}">
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

			<# if (showButton && buttonText) { #>
				<div class="cae-card__button-wrapper">
					<# var buttonAttrs = '';
					if (buttonLink && buttonLink.url) {
						buttonAttrs = 'href="' + buttonLink.url + '"';
						if (buttonLink.is_external) {
							buttonAttrs += ' target="_blank" rel="noopener"';
						}
					}
					#>
					<a class="cae-card__button" {{{buttonAttrs}}}>
						{{{buttonText}}}
					</a>
				</div>
			<# } #>
		</{{{wrapperTag}}}>
		<?php
	}

	/**
	 * Get background style based on image position
	 *
	 * @param array  $card_image Image data.
	 * @param string $image_position Position type.
	 * @return string Background style.
	 */
	private function get_background_style( $card_image, $image_position ) {
		$background_style = '';
		
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
		
		return $background_style;
	}

	/**
	 * Get link attributes
	 *
	 * @param array $card_link Link data.
	 * @return string Link attributes.
	 */
	private function get_link_attributes( $card_link ) {
		$link_attrs = '';
		
		if ( ! empty( $card_link['url'] ) ) {
			$link_attrs = 'href="' . esc_url( $card_link['url'] ) . '"';
			
			if ( ! empty( $card_link['is_external'] ) ) {
				$link_attrs .= ' target="_blank" rel="noopener"';
			}
		}
		
		return $link_attrs;
	}

	/**
	 * Get hover effect class
	 *
	 * @param string $hover_effect Effect type.
	 * @return string Hover class.
	 */
	private function get_hover_class( $hover_effect ) {
		if ( 'none' !== $hover_effect ) {
			return 'cae-card--hover-' . esc_attr( $hover_effect );
		}
		
		return '';
	}

	/**
	 * Get image position class
	 *
	 * @param string $image_position Position type.
	 * @return string Position class.
	 */
	private function get_image_position_class( $image_position ) {
		if ( 'fixed' === $image_position ) {
			return 'cae-card--parallax';
		}
		
		return '';
	}

	/**
	 * Get hover effect attributes for dynamic CSS
	 *
	 * @param array $settings Widget settings.
	 * @return string
	 */
	private function get_hover_attributes( $settings ) {
		$hover_effect = sanitize_key( $settings['hover_effect'] ?? 'none' );
		$attributes = '';

		if ( 'glow' === $hover_effect ) {
			$glow_color = sanitize_hex_color( $settings['glow_color'] ?? '#007cba' );
			$glow_intensity = absint( $settings['glow_intensity']['size'] ?? 20 );
			$glow_opacity = floatval( $settings['glow_opacity']['size'] ?? 0.6 );
			
			// Convert hex to RGB for CSS custom properties
			$rgb = $this->hex_to_rgb( $glow_color );
			$attributes .= sprintf( ' data-glow-color="%s"', esc_attr( $glow_color ) );
			$attributes .= sprintf( ' style="--glow-color: %d,%d,%d; --glow-intensity: %dpx; --glow-opacity: %.1f;"', 
				$rgb['r'], $rgb['g'], $rgb['b'], $glow_intensity, $glow_opacity );
		}

		if ( 'flip' === $hover_effect ) {
			$flip_direction = sanitize_key( $settings['flip_direction'] ?? 'rotateY' );
			$attributes .= sprintf( ' data-flip-direction="%s"', esc_attr( $flip_direction ) );
		}

		return $attributes;
	}

	/**
	 * Convert hex color to RGB
	 *
	 * @param string $hex Hex color.
	 * @return array RGB values.
	 */
	private function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );
		return [
			'r' => hexdec( substr( $hex, 0, 2 ) ),
			'g' => hexdec( substr( $hex, 2, 2 ) ),
			'b' => hexdec( substr( $hex, 4, 2 ) ),
		];
	}
}
