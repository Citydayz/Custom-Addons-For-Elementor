<?php
/**
 * CAE Newsletter Widget Renderer
 * Handles all rendering logic for the Newsletter widget.
 * Separated to avoid God Object pattern.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Renderer
 */
class Cae_Newsletter_Renderer {

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
		Cae_Asset_Registry::mark( 'cae-newsletter' );

		// Get privacy policy URL
		$privacy_url = ! empty( $settings['privacy_policy_url']['url'] ) ? esc_url( $settings['privacy_policy_url']['url'] ) : '';

		$this->render_markup( $settings, $privacy_url );
		$this->render_config_script( $settings );
	}

	/**
	 * Render HTML markup
	 *
	 * @param array  $settings    Widget settings.
	 * @param string $privacy_url Privacy policy URL.
	 */
	private function render_markup( $settings, $privacy_url ) {
		?>
		<section class="cae-newsletter" role="region" aria-label="<?php echo esc_attr__( 'Newsletter subscription', 'cae' ); ?>">
			<div class="cae-newsletter__container">
				<?php $this->render_title( $settings ); ?>
				<?php $this->render_description( $settings ); ?>
				<?php $this->render_form( $settings, $privacy_url ); ?>
			</div>
		</section>
		<?php
	}

	/**
	 * Render title
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_title( $settings ) {
		if ( empty( $settings['title'] ) ) {
			return;
		}
		?>
		<h2 class="cae-newsletter__title"><?php echo esc_html( $settings['title'] ); ?></h2>
		<?php
	}

	/**
	 * Render description
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_description( $settings ) {
		if ( empty( $settings['description'] ) ) {
			return;
		}
		?>
		<p class="cae-newsletter__description"><?php echo esc_html( $settings['description'] ); ?></p>
		<?php
	}

	/**
	 * Render form
	 *
	 * @param array  $settings    Widget settings.
	 * @param string $privacy_url Privacy policy URL.
	 */
	private function render_form( $settings, $privacy_url ) {
		?>
		<form class="cae-newsletter__form" method="post" novalidate aria-label="<?php echo esc_attr__( 'Newsletter subscription form', 'cae' ); ?>">
			<?php wp_nonce_field( 'cae_newsletter_subscribe', '_cae_newsletter_nonce', true, true ); ?>
			
			<?php $this->render_email_field( $settings ); ?>
			<?php $this->render_consent_field( $settings, $privacy_url ); ?>
			<?php $this->render_submit_button( $settings ); ?>
			<?php $this->render_message_container(); ?>
		</form>
		<?php
	}

	/**
	 * Render email input field
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_email_field( $settings ) {
		$field_id = 'cae-newsletter-email-' . esc_attr( $this->widget->get_id() );
		$placeholder = ! empty( $settings['email_placeholder'] ) ? esc_attr( $settings['email_placeholder'] ) : '';
		?>
		<div class="cae-newsletter__field-group">
			<label for="<?php echo esc_attr( $field_id ); ?>" class="visually-hidden">
				<?php echo esc_html__( 'Email address', 'cae' ); ?>
			</label>
			<input
				type="email"
				id="<?php echo esc_attr( $field_id ); ?>"
				name="email"
				class="cae-newsletter__input"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				required
				aria-required="true"
				aria-invalid="false"
			/>
		</div>
		<?php
	}

	/**
	 * Render consent checkbox field
	 *
	 * @param array  $settings    Widget settings.
	 * @param string $privacy_url Privacy policy URL.
	 */
	private function render_consent_field( $settings, $privacy_url ) {
		$consent_text = ! empty( $settings['consent_text'] ) ? esc_html( $settings['consent_text'] ) : '';
		?>
		<div class="cae-newsletter__field-group">
			<label class="cae-newsletter__checkbox-label">
				<input
					type="checkbox"
					name="consent"
					class="cae-newsletter__checkbox"
					required
					aria-required="true"
				/>
				<span class="cae-newsletter__consent-text">
					<?php
					if ( ! empty( $privacy_url ) ) {
						printf(
							/* translators: %1$s: consent text, %2$s: privacy policy URL */
							esc_html__( '%1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">Privacy Policy</a>', 'cae' ),
							esc_html( $consent_text ),
							esc_url( $privacy_url )
						);
					} else {
						echo esc_html( $consent_text );
					}
					?>
				</span>
			</label>
		</div>
		<?php
	}

	/**
	 * Render submit button
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_submit_button( $settings ) {
		$button_text = ! empty( $settings['button_text'] ) ? esc_html( $settings['button_text'] ) : esc_html__( 'Subscribe', 'cae' );
		?>
		<div class="cae-newsletter__field-group">
			<button type="submit" class="cae-newsletter__button">
				<?php echo esc_html( $button_text ); ?>
			</button>
		</div>
		<?php
	}

	/**
	 * Render message container
	 */
	private function render_message_container() {
		?>
		<div class="cae-newsletter__message" role="status" aria-live="polite" aria-atomic="true"></div>
		<?php
	}

	/**
	 * Render configuration script
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_config_script( $settings ) {
		$success_message = ! empty( $settings['success_message'] ) ? esc_html( $settings['success_message'] ) : esc_html__( 'Thank you for subscribing!', 'cae' );
		?>
		<script type="application/json" class="cae-newsletter-config">
			<?php
			echo wp_json_encode(
				[
					'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
					'successMessage' => $success_message,
					'errorMessage'   => esc_html__( 'An error occurred. Please try again.', 'cae' ),
					'widgetId'        => $this->widget->get_id(),
				]
			);
			?>
		</script>
		<?php
	}
}

