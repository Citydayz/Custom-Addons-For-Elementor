<?php
/**
 * Conditional Asset Registry
 * Tracks which widgets are rendered on a page and enqueues their assets conditionally.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Cae_Asset_Registry {

	/**
	 * Widgets used on current page
	 *
	 * @var array
	 */
	private static $widgets_used = [];

	/**
	 * Mark a widget as used on the current page
	 *
	 * @param string $slug Widget slug.
	 */
	public static function mark( $slug ) {
		self::$widgets_used[ $slug ] = true;
	}

	/**
	 * Enqueue assets for widgets used on the page
	 * Hooked to wp_footer to run after all widgets have rendered.
	 */
	public static function enqueue_assets() {
		// Always enqueue global assets
		wp_enqueue_style( 'cae-global' );
		wp_enqueue_script( 'cae-global' );

		// Enqueue GDPR consent gate if any widget is present
		if ( ! empty( self::$widgets_used ) ) {
			wp_enqueue_script( 'cae-consent-gate' );
		}

		// Enqueue per-widget assets
		foreach ( array_keys( self::$widgets_used ) as $slug ) {
			if ( wp_style_is( $slug, 'registered' ) ) {
				wp_enqueue_style( $slug );
			}
			if ( wp_script_is( $slug, 'registered' ) ) {
				wp_enqueue_script( $slug );
			}
		}
	}
}

// Hook enqueue to wp_footer
add_action( 'wp_footer', [ 'Cae_Asset_Registry', 'enqueue_assets' ], 5 );

