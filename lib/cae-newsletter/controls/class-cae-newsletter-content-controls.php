<?php
/**
 * CAE Newsletter Content Controls
 * Handles content-related controls: title, description, form fields, messages.
 * Separated to avoid God Object pattern.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Content_Controls
 */
class Cae_Newsletter_Content_Controls {

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
	 * Register content controls
	 */
	public function register_controls() {
		$this->widget->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'cae' ),
			]
		);

		$this->register_text_controls();
		$this->register_form_controls();
		$this->register_messages_controls();
		$this->register_privacy_controls();

		$this->widget->end_controls_section();
	}

	/**
	 * Register text controls (title, description)
	 */
	private function register_text_controls() {
		$this->widget->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Subscribe to our newsletter', 'cae' ),
				'label_block' => true,
			]
		);

		$this->widget->add_control(
			'description',
			[
				'label'       => esc_html__( 'Description', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Stay updated with our latest news and offers.', 'cae' ),
				'rows'        => 3,
				'label_block' => true,
			]
		);
	}

	/**
	 * Register form controls (placeholder, button text)
	 */
	private function register_form_controls() {
		$this->widget->add_control(
			'email_placeholder',
			[
				'label'       => esc_html__( 'Email Placeholder', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Enter your email address', 'cae' ),
				'label_block' => true,
			]
		);

		$this->widget->add_control(
			'button_text',
			[
				'label'       => esc_html__( 'Button Text', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Subscribe', 'cae' ),
				'label_block' => true,
			]
		);
	}

	/**
	 * Register message controls (success message)
	 */
	private function register_messages_controls() {
		$this->widget->add_control(
			'success_message',
			[
				'label'       => esc_html__( 'Success Message', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Thank you for subscribing!', 'cae' ),
				'label_block' => true,
			]
		);
	}

	/**
	 * Register privacy/consent controls
	 */
	private function register_privacy_controls() {
		$this->widget->add_control(
			'consent_text',
			[
				'label'       => esc_html__( 'Consent Text', 'cae' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'I agree to receive marketing emails. I can unsubscribe at any time.', 'cae' ),
				'rows'        => 2,
				'label_block' => true,
			]
		);

		$this->widget->add_control(
			'privacy_policy_url',
			[
				'label'         => esc_html__( 'Privacy Policy URL', 'cae' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://example.com/privacy', 'cae' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);
	}
}

