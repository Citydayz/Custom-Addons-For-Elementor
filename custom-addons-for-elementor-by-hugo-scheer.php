<?php
/**
 * Plugin Name: Custom Addons for Elementor by Hugo Scheer
 * Description: Modular Elementor widgets (Hero, Footer, Repeater Section) with global design defaults, GDPR compliance, and conditional asset loading.
 * Version: 0.1.0
 * Author: Hugo Scheer
 * Text Domain: cae
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main plugin bootstrap.
 * Hooks into plugins_loaded to check dependencies and initialize.
 */
add_action( 'plugins_loaded', 'cae_init_plugin', 10 );

function cae_init_plugin() {
	// Check if Elementor is installed and active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'cae_missing_elementor_notice' );
		return;
	}

	// Check minimum Elementor version (3.0.0+)
	if ( ! version_compare( ELEMENTOR_VERSION, '3.0.0', '>=' ) ) {
		add_action( 'admin_notices', 'cae_elementor_version_notice' );
		return;
	}

	// Load textdomain for i18n
	load_plugin_textdomain( 'cae', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	// Define constants
	define( 'CAE_VERSION', '0.1.0' );
	define( 'CAE_PATH', plugin_dir_path( __FILE__ ) );
	define( 'CAE_URL', plugin_dir_url( __FILE__ ) );
	define( 'CAE_PLUGIN_FILE', __FILE__ );

	// Load plugin core
	require_once CAE_PATH . 'includes/class-cae-plugin.php';
	require_once CAE_PATH . 'includes/class-cae-asset-registry.php';
	require_once CAE_PATH . 'includes/class-cae-consent.php';

	// Initialize plugin
	Cae_Plugin::instance();
}

/**
 * Admin notice if Elementor is missing.
 */
function cae_missing_elementor_notice() {
	$message = sprintf(
		/* translators: %s: Elementor plugin name */
		esc_html__( 'Custom Addons for Elementor requires %s to be installed and activated.', 'cae' ),
		'<strong>' . esc_html__( 'Elementor', 'cae' ) . '</strong>'
	);
	printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
}

/**
 * Admin notice if Elementor version is too old.
 */
function cae_elementor_version_notice() {
	$message = sprintf(
		/* translators: 1: Plugin name, 2: Elementor, 3: Required version */
		esc_html__( '%1$s requires %2$s version %3$s or greater.', 'cae' ),
		'<strong>' . esc_html__( 'Custom Addons for Elementor', 'cae' ) . '</strong>',
		'<strong>' . esc_html__( 'Elementor', 'cae' ) . '</strong>',
		'3.0.0'
	);
	printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( $message ) );
}

