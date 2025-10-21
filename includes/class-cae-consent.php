<?php
/**
 * GDPR Consent Helper
 * Provides helper functions for consent-gated scripts and integrations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Cae_Consent {

	/**
	 * Generate data attribute for consent-gated elements
	 *
	 * @param string $category Consent category: 'analytics', 'marketing', 'functional'.
	 * @return string HTML data attribute.
	 */
	public static function privacy_gate_attr( $category ) {
		$category = sanitize_key( $category );
		return 'data-cae-consent="' . esc_attr( $category ) . '"';
	}

	/**
	 * Output a consent-gated script placeholder
	 *
	 * @param string $category Consent category.
	 * @param string $script_content Script content to gate.
	 */
	public static function gated_script( $category, $script_content ) {
		echo '<script type="text/plain" ' . self::privacy_gate_attr( $category ) . '>';
		echo $script_content; // Already validated/sanitized by caller
		echo '</script>';
	}
}

