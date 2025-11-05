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
		$card_link_formation = ! empty( $settings['card_link_formation'] ) ? absint( $settings['card_link_formation'] ) : 0;
		$hover_effect = sanitize_key( $settings['hover_effect'] ?? 'none' );
		$image_position = sanitize_key( $settings['image_position'] ?? 'cover' );
		$content_position = sanitize_key( $settings['content_position'] ?? 'middle' );
		$content_align = sanitize_key( $settings['content_align'] ?? 'center' );
		$show_button = 'yes' === ( $settings['show_button'] ?? 'no' );
		$button_text = sanitize_text_field( $settings['button_text'] ?? '' );
		$button_link = $settings['button_link'] ?? [];
		$button_link_formation = ! empty( $settings['button_link_formation'] ) ? absint( $settings['button_link_formation'] ) : 0;

		// If Formation is selected, override the link
		if ( $card_link_formation > 0 ) {
			$formation_url = get_permalink( $card_link_formation );
			if ( $formation_url ) {
				$card_link = [ 'url' => $formation_url ];
			}
		}
		
		$background_style = $this->get_background_style( $card_image, $image_position );
		$link_attributes = $this->get_link_attributes( $card_link );
		$wrapper_tag = $this->get_wrapper_tag( $card_link );
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
					
					// If Formation is selected for button, override the link
					if ( $button_link_formation > 0 ) {
						$formation_url = get_permalink( $button_link_formation );
						if ( $formation_url ) {
							$button_link = [ 'url' => $formation_url ];
						}
					}
					
					// Handle dynamic tags - can be string or array
					$button_url = '';
					$button_is_external = false;
					$button_nofollow = false;
					
					if ( is_string( $button_link ) ) {
						// Dynamic tag returned as simple string
						$button_url = $button_link;
					} elseif ( is_array( $button_link ) && ! empty( $button_link['url'] ) ) {
						// Standard Elementor URL format
						$button_url = $button_link['url'];
						$button_is_external = ! empty( $button_link['is_external'] );
						$button_nofollow = ! empty( $button_link['nofollow'] );
					}
					
					if ( ! empty( $button_url ) && '#' !== $button_url ) {
						$button_attributes = 'href="' . esc_url( $button_url ) . '"';
						
						$button_rel_attrs = [];
						
						if ( $button_is_external ) {
							$button_rel_attrs[] = 'noopener';
							$button_rel_attrs[] = 'noreferrer';
							$button_attributes .= ' target="_blank"';
						}
						
						if ( $button_nofollow ) {
							$button_rel_attrs[] = 'nofollow';
						}
						
						if ( ! empty( $button_rel_attrs ) ) {
							$button_attributes .= ' rel="' . esc_attr( implode( ' ', $button_rel_attrs ) ) . '"';
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
		var cardLinkFormation = settings.card_link_formation || 0;
		var hoverEffect = settings.hover_effect;
		var contentPosition = settings.content_position || 'middle';
		var contentAlign = settings.content_align || 'center';
		var showButton = settings.show_button === 'yes';
		var buttonText = settings.button_text || '';
		var buttonLink = settings.button_link || {};
		var buttonLinkFormation = settings.button_link_formation || 0;
		
		// If Formation is selected, override the link
		if (cardLinkFormation && parseInt(cardLinkFormation) > 0) {
			var formationUrl = '#';
			// In editor, we can't get permalink directly, so we'll use a placeholder
			// The actual URL will be generated on frontend
			cardLink = { url: formationUrl };
		}
		
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
		
		// Link attributes - handle both string (dynamic tag) and object format
		var linkAttrs = '';
		var wrapperTag = 'div';
		var cardUrl = '';
		
		if (typeof cardLink === 'string' && cardLink && cardLink !== '#') {
			// Dynamic tag returned as simple string
			cardUrl = cardLink;
		} else if (cardLink && cardLink.url && cardLink.url !== '#') {
			// Standard Elementor URL format
			cardUrl = cardLink.url;
		}
		
		if (cardUrl) {
			wrapperTag = 'a';
			linkAttrs = 'href="' + cardUrl + '"';
			
			// Only process rel attributes if we have an object format
			if (cardLink && typeof cardLink === 'object') {
				var relAttrs = [];
				
				if (cardLink.is_external) {
					relAttrs.push('noopener');
					relAttrs.push('noreferrer');
					linkAttrs += ' target="_blank"';
				}
				
				if (cardLink.nofollow) {
					relAttrs.push('nofollow');
				}
				
				if (relAttrs.length > 0) {
					linkAttrs += ' rel="' + relAttrs.join(' ') + '"';
				}
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
					var buttonUrl = '';
					
					// If Formation is selected for button, override the link
					if (buttonLinkFormation && parseInt(buttonLinkFormation) > 0) {
						// In editor, use placeholder - actual URL generated on frontend
						buttonUrl = '#';
					}
					
					// Handle both string (dynamic tag) and object format
					if (!buttonUrl) {
						if (typeof buttonLink === 'string' && buttonLink && buttonLink !== '#') {
							// Dynamic tag returned as simple string
							buttonUrl = buttonLink;
						} else if (buttonLink && buttonLink.url && buttonLink.url !== '#') {
							// Standard Elementor URL format
							buttonUrl = buttonLink.url;
						}
					}
					
					if (buttonUrl) {
						buttonAttrs = 'href="' + buttonUrl + '"';
						
						// Only process rel attributes if we have an object format
						if (buttonLink && typeof buttonLink === 'object' && !buttonLinkFormation) {
							var buttonRelAttrs = [];
							
							if (buttonLink.is_external) {
								buttonRelAttrs.push('noopener');
								buttonRelAttrs.push('noreferrer');
								buttonAttrs += ' target="_blank"';
							}
							
							if (buttonLink.nofollow) {
								buttonRelAttrs.push('nofollow');
							}
							
							if (buttonRelAttrs.length > 0) {
								buttonAttrs += ' rel="' + buttonRelAttrs.join(' ') + '"';
							}
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
	 * @param array|string $card_link Link data (can be array or string from dynamic tags).
	 * @return string Link attributes.
	 */
	private function get_link_attributes( $card_link ) {
		$link_attrs = '';
		
		// Handle dynamic tags - can be string or array
		$url = '';
		$is_external = false;
		$nofollow = false;
		
		if ( is_string( $card_link ) ) {
			// Dynamic tag returned as simple string
			$url = $card_link;
		} elseif ( is_array( $card_link ) && ! empty( $card_link['url'] ) ) {
			// Standard Elementor URL format
			$url = $card_link['url'];
			$is_external = ! empty( $card_link['is_external'] );
			$nofollow = ! empty( $card_link['nofollow'] );
		}
		
		if ( ! empty( $url ) && '#' !== $url ) {
			$link_attrs = 'href="' . esc_url( $url ) . '"';
			
			$rel_attrs = [];
			
			if ( $is_external ) {
				$rel_attrs[] = 'noopener';
				$rel_attrs[] = 'noreferrer';
				$link_attrs .= ' target="_blank"';
			}
			
			if ( $nofollow ) {
				$rel_attrs[] = 'nofollow';
			}
			
			if ( ! empty( $rel_attrs ) ) {
				$link_attrs .= ' rel="' . esc_attr( implode( ' ', $rel_attrs ) ) . '"';
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
	 * Get wrapper tag based on link presence
	 *
	 * @param array|string $card_link Link data (can be array or string from dynamic tags).
	 * @return string 'a' or 'div'.
	 */
	private function get_wrapper_tag( $card_link ) {
		$url = '';
		
		if ( is_string( $card_link ) ) {
			// Dynamic tag returned as simple string
			$url = $card_link;
		} elseif ( is_array( $card_link ) && ! empty( $card_link['url'] ) ) {
			$url = $card_link['url'];
		}
		
		if ( ! empty( $url ) && '#' !== $url ) {
			return 'a';
		}
		
		return 'div';
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
