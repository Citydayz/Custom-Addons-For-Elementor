<?php
/**
 * CAE Newsletter AJAX Handler
 * Handles newsletter subscription AJAX requests.
 * Separated to avoid God Object pattern.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cae_Newsletter_Handler
 */
class Cae_Newsletter_Handler {

	/**
	 * Initialize handler and register AJAX hooks
	 */
	public function __construct() {
		add_action( 'wp_ajax_cae_newsletter_subscribe', [ $this, 'handle_subscribe' ] );
		add_action( 'wp_ajax_nopriv_cae_newsletter_subscribe', [ $this, 'handle_subscribe' ] );
	}

	/**
	 * Handle newsletter subscription AJAX request
	 */
	public function handle_subscribe() {
		// Verify nonce (check the correct field name from wp_nonce_field)
		if ( ! isset( $_POST['_cae_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_cae_newsletter_nonce'] ) ), 'cae_newsletter_subscribe' ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Security check failed.', 'cae' ),
				]
			);
		}

		// Check rate limiting to prevent spam
		$rate_limit_check = $this->check_rate_limit();
		if ( is_wp_error( $rate_limit_check ) ) {
			wp_send_json_error(
				[
					'message' => $rate_limit_check->get_error_message(),
					'field'   => 'email',
				]
			);
		}

		// Get and sanitize email
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		
		// Validate email
		if ( empty( $email ) || ! is_email( $email ) ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Please enter a valid email address.', 'cae' ),
					'field'   => 'email',
				]
			);
		}

		// Check consent
		$consent = isset( $_POST['consent'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['consent'] ) );
		if ( ! $consent ) {
			wp_send_json_error(
				[
					'message' => esc_html__( 'Please accept the terms to continue.', 'cae' ),
					'field'   => 'consent',
				]
			);
		}

		// Process subscription
		$result = $this->process_subscription( $email );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error(
				[
					'message' => esc_html( $result->get_error_message() ),
					'field'   => 'email',
				]
			);
		}

		// Success response
		wp_send_json_success(
			[
				'message' => esc_html__( 'Thank you for subscribing!', 'cae' ),
			]
		);
	}

	/**
	 * Process newsletter subscription
	 *
	 * @param string $email Email address.
	 * @return true|\WP_Error True on success, WP_Error on failure.
	 */
	private function process_subscription( $email ) {
		// Get existing subscriptions
		$subscriptions = get_option( 'cae_newsletter_subscriptions', [] );
		$subscription_dates = get_option( 'cae_newsletter_subscription_dates', [] );
		
		// Limit total subscriptions to prevent database bloat (max 10000)
		if ( count( $subscriptions ) >= 10000 ) {
			return new \WP_Error( 'limit_reached', esc_html__( 'Subscription service is temporarily unavailable. Please try again later.', 'cae' ) );
		}
		
		// Check if email already exists
		if ( in_array( $email, $subscriptions, true ) ) {
			return new \WP_Error( 'duplicate', esc_html__( 'This email is already subscribed.', 'cae' ) );
		}

		// Add email to subscriptions
		$subscriptions[] = $email;
		$updated = update_option( 'cae_newsletter_subscriptions', $subscriptions );

		if ( false === $updated ) {
			return new \WP_Error( 'database_error', esc_html__( 'Failed to save subscription.', 'cae' ) );
		}

		// Store subscription date for GDPR compliance
		if ( ! is_array( $subscription_dates ) ) {
			$subscription_dates = [];
		}
		$subscription_dates[ $email ] = time();
		update_option( 'cae_newsletter_subscription_dates', $subscription_dates );

		// Send notification email to admin
		$this->send_admin_notification( $email );

		return true;
	}

	/**
	 * Send notification email to admin
	 *
	 * @param string $email Subscriber email address.
	 */
	private function send_admin_notification( $email ) {
		$admin_email = get_option( 'admin_email' );
		if ( ! $admin_email ) {
			return;
		}

		$subject = sprintf(
			/* translators: %s: site name */
			esc_html__( 'New newsletter subscription on %s', 'cae' ),
			get_bloginfo( 'name' )
		);

		$message = sprintf(
			/* translators: %1$s: newline, %2$s: email address */
			esc_html__( 'New newsletter subscription:%1$sEmail: %2$s', 'cae' ),
			"\n",
			esc_html( $email )
		);

		$mail_sent = wp_mail( $admin_email, $subject, $message );
		
		// Log email failure only in debug mode (never log in production)
		if ( ! $mail_sent && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'CAE Newsletter: Failed to send admin notification email for ' . esc_html( $email ) );
		}
	}

	/**
	 * Check rate limiting to prevent spam
	 *
	 * @return true|\WP_Error True if allowed, WP_Error if rate limited.
	 */
	private function check_rate_limit() {
		$ip_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
		$transient_key = 'cae_newsletter_rate_' . md5( $ip_address );
		$attempts = get_transient( $transient_key );

		// Allow maximum 5 attempts per 15 minutes
		if ( $attempts && $attempts >= 5 ) {
			return new \WP_Error( 'rate_limit', esc_html__( 'Too many subscription attempts. Please try again later.', 'cae' ) );
		}

		// Increment attempt counter
		set_transient( $transient_key, ( $attempts ? $attempts + 1 : 1 ), 900 ); // 15 minutes

		return true;
	}

	/**
	 * Purge old subscriptions (GDPR compliance)
	 * Should be called periodically via cron or on plugin deactivation
	 *
	 * @param int $retention_days Number of days to retain subscriptions (default: 730 = 2 years).
	 * @return int Number of subscriptions purged.
	 */
	public static function purge_old_subscriptions( $retention_days = 730 ) {
		// Get subscriptions with metadata (if available)
		$subscriptions = get_option( 'cae_newsletter_subscriptions', [] );
		$subscription_dates = get_option( 'cae_newsletter_subscription_dates', [] );
		
		if ( empty( $subscriptions ) ) {
			return 0;
		}

		$cutoff_date = time() - ( $retention_days * DAY_IN_SECONDS );
		$purged_count = 0;
		$purged_emails = [];

		// If we have dates, use them; otherwise assume all are old enough
		if ( ! empty( $subscription_dates ) && is_array( $subscription_dates ) ) {
			foreach ( $subscription_dates as $email => $timestamp ) {
				if ( $timestamp < $cutoff_date ) {
					$purged_emails[] = $email;
					unset( $subscription_dates[ $email ] );
					$purged_count++;
				}
			}
		}

		// Remove purged emails from subscriptions
		if ( ! empty( $purged_emails ) ) {
			$subscriptions = array_values( array_diff( $subscriptions, $purged_emails ) );
			update_option( 'cae_newsletter_subscriptions', $subscriptions );
			update_option( 'cae_newsletter_subscription_dates', $subscription_dates );
		}

		return $purged_count;
	}
}

