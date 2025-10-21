<?php
/**
 * Main Plugin Loader
 * Handles widget registration, asset registration, and plugin initialization.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Cae_Plugin {

	/**
	 * Singleton instance
	 *
	 * @var Cae_Plugin
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @return Cae_Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_widget_category' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 9 );
	}

	/**
	 * Register custom widget category
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function register_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'cae-widgets',
			[
				'title' => esc_html__( 'Custom Addons by Hugo Scheer', 'cae' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * Register widgets with Elementor
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		// Load widget classes
		require_once CAE_PATH . 'lib/cae-hero/class-cae-hero.php';
		require_once CAE_PATH . 'lib/cae-footer/class-cae-footer.php';
		require_once CAE_PATH . 'lib/cae-repeater-section/class-cae-repeater-section.php';
		
		// Load refactored card widget and its dependencies
		require_once CAE_PATH . 'lib/cae-card/controls/class-cae-card-controls-base.php';
		require_once CAE_PATH . 'lib/cae-card/controls/class-cae-card-content-controls.php';
		require_once CAE_PATH . 'lib/cae-card/controls/class-cae-card-style-controls.php';
		require_once CAE_PATH . 'lib/cae-card/controls/class-cae-card-button-controls.php';
		require_once CAE_PATH . 'lib/cae-card/controls/class-cae-card-hover-controls.php';
		require_once CAE_PATH . 'lib/cae-card/class-cae-card-controls.php';
		require_once CAE_PATH . 'lib/cae-card/class-cae-card-renderer.php';
		require_once CAE_PATH . 'lib/cae-card/class-cae-card-refactored.php';

		// Register widgets
		$widgets_manager->register( new Cae_Hero_Widget() );
		$widgets_manager->register( new Cae_Footer_Widget() );
		$widgets_manager->register( new Cae_Repeater_Section_Widget() );
		$widgets_manager->register( new Cae_Card_Widget_Refactored() );
	}

	/**
	 * Register global and per-widget assets
	 * Assets are registered here but enqueued conditionally via Cae_Asset_Registry
	 */
	public function register_assets() {
		// Enqueue assets in Elementor editor
		add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'enqueue_editor_assets' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_assets' ] );
		// Global assets (minimal utilities)
		wp_register_style(
			'cae-global',
			CAE_URL . 'assets/css/global.css',
			[],
			CAE_VERSION
		);

		wp_register_script(
			'cae-global',
			CAE_URL . 'assets/js/global.js',
			[],
			CAE_VERSION,
			true
		);

		// GDPR consent gate script
		wp_register_script(
			'cae-consent-gate',
			CAE_URL . 'assets/js/consent-gate.js',
			[],
			CAE_VERSION,
			true
		);

		// Widget-specific assets
		$widgets = [ 'cae-hero', 'cae-footer', 'cae-repeater-section', 'cae-card' ];
		foreach ( $widgets as $slug ) {
			wp_register_style(
				$slug,
				CAE_URL . 'assets/css/' . $slug . '.css',
				[],
				CAE_VERSION
			);

			wp_register_script(
				$slug,
				CAE_URL . 'assets/js/' . $slug . '.js',
				[],
				CAE_VERSION,
				true
			);
		}

		// Add defer attribute to non-critical scripts
		add_filter( 'script_loader_tag', [ $this, 'add_defer_attribute' ], 10, 2 );
	}

	/**
	 * Add defer attribute to non-critical scripts
	 *
	 * @param string $tag Script tag.
	 * @param string $handle Script handle.
	 * @return string Modified script tag.
	 */
	public function add_defer_attribute( $tag, $handle ) {
		$defer_scripts = [ 'cae-hero', 'cae-footer', 'cae-repeater-section', 'cae-card', 'cae-consent-gate' ];
		
		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}
		
		return $tag;
	}

	/**
	 * Enqueue assets in Elementor editor
	 */
	public function enqueue_editor_assets() {
		// Enqueue widget styles in editor
		wp_enqueue_style( 'cae-card', CAE_URL . 'assets/css/cae-card.css', [], CAE_VERSION );
		wp_enqueue_style( 'cae-hero', CAE_URL . 'assets/css/cae-hero.css', [], CAE_VERSION );
		wp_enqueue_style( 'cae-footer', CAE_URL . 'assets/css/cae-footer.css', [], CAE_VERSION );
		wp_enqueue_style( 'cae-repeater-section', CAE_URL . 'assets/css/cae-repeater-section.css', [], CAE_VERSION );
	}
}

