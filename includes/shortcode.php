<?php

namespace dcms\pin\includes;

/**
 * Class for creating the shortcode
 */
class Shortcode {
	//constructor
	public function __construct() {
		add_action( 'init', [ $this, 'create_forms_shortcode' ] );
	}

	// Create shortcode
	public function create_forms_shortcode(): void {
		add_shortcode( DCMS_SHORTCODE_FORM_PIN, [ $this, 'show_pin_form' ] );
		add_shortcode( DCMS_SHORTCODE_FORM_LOGIN, [ $this, 'show_login_form' ] );
		add_shortcode( DCMS_SHORTCODE_FORM_VALIDATION_EMAIL, [ $this, 'show_validation_email_form' ] );
	}

	// Show form
	public function show_pin_form(): string {
		wp_localize_script( 'forms-pin-script',
			'dcms_fpin',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax-nonce-pin' )
			] );
		wp_enqueue_script( 'forms-pin-script' );

		wp_enqueue_style( 'forms-pin-style' );

		ob_start();
		include_once DCMS_PIN_PATH . '/views/form-pin.php';
		$html_code = ob_get_contents();
		ob_end_clean();

		return $html_code;
	}

	// Show login form
	public function show_login_form( $atts ): string {
		global $wp;
		$html_code = '';

		if ( ! is_user_logged_in() ) {

			$atts = shortcode_atts( [
				'redirect' => home_url(),
				'register' => home_url() . '/enviar-pin/'
			], $atts, DCMS_SHORTCODE_FORM_LOGIN );

			$url_redirect = $atts['redirect'];
			$url_register = $atts['register'];

			if ( $url_redirect === 'current' ) {
				$url_redirect = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
			}

			wp_localize_script( 'forms-pin-script',
				'dcms_flogin',
				[
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'ajax-nonce-login' ),
					'url'     => $url_redirect
				] );
			wp_enqueue_script( 'forms-pin-script' );
			wp_enqueue_style( 'forms-pin-style' );

			ob_start();
			include_once DCMS_PIN_PATH . '/views/form-login.php';
			$html_code = ob_get_contents();
			ob_end_clean();

		}

		return $html_code;

	}


	public function show_validation_email_form(): string {

		wp_localize_script( 'forms-pin-script',
			'dcms_fvalidation',
			[
				'ajaxurl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'ajax-nonce-validation-email' ),
				'url_redirect' => home_url(),
			] );

		wp_enqueue_script( 'forms-pin-script' );
		wp_enqueue_style( 'forms-pin-style' );

		// Logout user
		if ( ! is_user_logged_in() ) {
			return "<h4>Tienes que estar conectado para acceder a esta página</h4>";
		}

		// Show/hide message waiting validation
		$db                 = new Database();
		$data               = $db->get_log_validation_email( get_current_user_id() );
		$waiting_validation = ! empty( $data ) && $data['validated'] == 0;

		ob_start();
		include_once DCMS_PIN_PATH . '/views/form-validate.php';
		$html_code = ob_get_contents();
		ob_end_clean();

		return $html_code;
	}
}