<?php

namespace dcms\pin\includes;

use dcms\pin\helpers\Helper;

// Class for the operations of plugin
class Process {

	public function __construct() {
		// Login
		add_action( 'wp_ajax_nopriv_dcms_ajax_validate_login', [ $this, 'process_form_login' ] );

		// PIN remember
		add_action( 'wp_ajax_nopriv_dcms_ajax_validate_pin', [ $this, 'process_form_send_pin' ] );
		add_action( 'wp_ajax_dcms_ajax_validate_pin', [ $this, 'process_form_send_pin' ] );

		// Validate Email
		add_action( 'wp_ajax_nopriv_dcms_ajax_validate_email', [ $this, 'process_form_validate_email' ] );
		add_action( 'wp_ajax_dcms_ajax_validate_email', [ $this, 'process_form_validate_email' ] );
		add_action( 'template_redirect', [ $this, 'process_url_validate_email' ] );

		//Backend resend mail pin
		add_action( 'wp_ajax_dcms_resend_pin', [ $this, 'resend_email_pin' ] );
	}

	// Backend - Resend Email
	public function resend_email_pin(): void {
		$email    = $_POST['email'];
		$identify = $_POST['identify'];
		$pin      = $_POST['pin'];

		$this->validate_nonce( 'ajax-admin-pin' );

		if ( $this->send_email_pin_form( $email, $identify, $pin ) ) {
			$res = [ 'status' => 1 ];
		} else {
			$res = [ 'status' => 0 ];
		}

		wp_send_json( $res );
	}


	// Login Form
	public function process_form_login(): void {
		// Validate nonce
		$this->validate_nonce( 'ajax-nonce-login' );

		// First
		$info                  = array();
		$info['user_login']    = $_POST['username'];
		$info['user_password'] = str_pad( $_POST['password'], 4, "0", STR_PAD_LEFT );
		$info['remember']      = false;

		$user_signon = wp_signon( $info, false );

		if ( ! is_wp_error( $user_signon ) ) {
			wp_set_current_user( $user_signon->ID );
			wp_set_auth_cookie( $user_signon->ID );

			// All is ok
			$res = [
				'status'  => 1,
				'message' => "Redireccionando...",
			];

			// Check if validate email is active
			$options = get_option( 'dcms_pin_options' );

			if ( isset( $options['dcms_check_validate_email'] ) ) {
				$db                       = new Database();
				$require_validation_email = $db->user_require_validation_email( $user_signon->ID );
				if ( $require_validation_email ) {
					$url_validate        = home_url( $options['dcms_slug_page_validation_email'] );
					$res['url_validate'] = $url_validate;
				}
			}

		} else {
			$res = [
				'status'  => 0,
				'message' => "Identificación o PIN no válido",
			];
		}

		wp_send_json( $res );
	}


	// PIN Form
	public function process_form_send_pin(): void {

		// Validate nonce
		$this->validate_nonce( 'ajax-nonce-pin' );

		// Get data
		$identify = $_POST['identify'];
		$ref      = strtoupper( sanitize_text_field( $_POST['ref'] ) ); // NIF or Reference
		$email    = strtolower( sanitize_email( $_POST['email'] ) );

		// validate number <> 0
		$this->validate_number( $identify );

		$db        = new Database();
		$user_meta = $db->get_data_user( $identify );

		// number exists
		$this->validate_number( $user_meta );

		$arr_meta = Helper::meta_to_array( $user_meta );

		$db_id       = $arr_meta['user_id'];
		$db_identify = $arr_meta['identify'];
		$db_pin      = $arr_meta['pin'];
		$db_number   = $arr_meta['number'];
		$db_ref      = strtoupper( $arr_meta['reference'] );
		$db_nif      = strtoupper( $arr_meta['nif'] );
		$db_email    = strtolower( $arr_meta['email'] );

		// Validate duplicate email
		$duplicate = $db->get_duplicate_email( $email, $db_id );
		$this->validate_duplicate_email( $duplicate );

		// validate reference with number
		$this->validate_reference_nif( $ref, $db_ref, $db_nif );

		// Update email database
		if ( $db_email != $email ) {
			$res = $db->update_email_user( $email, $db_id );
			$this->validate_update_email( $res );
		}

		// Send email
		$res = $this->send_email_pin_form( $email, $db_identify, $db_pin );
		$this->validate_send_email( $res );

		// Save log
		$db->save_log_pin_sent( $email, $db_identify, $db_pin, $db_number, $db_ref, $db_nif, $db_id );

		// Check is validate email is active
		$options               = get_option( 'dcms_pin_options' );
		$validate_email_active = isset( $options['dcms_check_validate_email'] );

		if ( $validate_email_active ) {
			$db->generate_unique_id_validation_email( $db_id, $email );
			$db->update_log_validation_email( $db_id );
		}

		// If all is ok
		$res = [
			'status'  => 1,
			'message' => "✅ Solicitud correcta, en unas horas recibirás en tu email <i>$email</i> tu número de PIN. <br><br>Si no lo recibes, no olvides revisar la bandeja de no deseados, Spam, y Promociones",
		];

		wp_send_json( $res );
	}


	// Validate Email Form
	public function process_form_validate_email(): void {
		$email     = strtolower( sanitize_email( $_POST['email'] ?? '' ) );
		$unique_id = $_POST['unique_id'] ?? '';

		if ( ! $unique_id || ! $email ) {
			return;
		}

		$db = new Database();

		// Get user id from unique_id
		$user_data = $db->get_user_data_by_unique_id( $unique_id );
		$user_id   = $user_data['id_user'] ?? 0;

		if ( ! $user_id ) {
			return;
		}

		// Validate duplicate email
		$duplicate = $db->get_duplicate_email( $email, $user_id );
		$this->validate_duplicate_email( $duplicate );

		// Generate a new unique_id to send mail
		$unique_id = $db->generate_unique_id_validation_email( $user_id, $email );

		// Send email to user with the url to validate
		$res = $this->send_email_validate_email_form( $email, $unique_id );
		$this->validate_send_email( $res );


		$res = [
			'status'  => 1,
			'message' => '✅ En unos minutos recibirás un correo para confirmar tu cuenta. <a href="' . home_url() . '">Regresar</a>'
		];

		wp_send_json( $res );
	}

	// Process URL sent by email
	public function process_url_validate_email(): void {
		$unique_id = $_GET['unique_id'] ?? '';
		if ( ! $unique_id ) {
			return;
		}

		$options = get_option( 'dcms_pin_options' );
		$slug    = $options['dcms_slug_page_validation_email'];
		if ( ! is_page( $slug ) ) {
			return;
		}

		$db        = new Database();
		$user_data = $db->get_user_data_by_unique_id( $unique_id );

		// Update email if is different
		if ( $user_data['email'] !== $user_data['current_email'] ) {
			$res = $db->update_email_user( $user_data['email'], $user_data['id_user'] );
			$this->validate_update_email( $res );
		}

		// Update log table
		$db->update_log_validation_email( $user_data['id_user'] );

		// Redirect to home
		wp_redirect( home_url() );
	}

	// Send email with identify and pin
	private function send_email_pin_form( $email, $identify, $pin ) {
		$options = get_option( 'dcms_pin_options' );

		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		$subject = $options['dcms_subject_email'];
		$body    = $options['dcms_text_email'];
		$body    = str_replace( '%id%', $identify, $body );
		$body    = str_replace( '%pin%', $pin, $body );

		return wp_mail( $email, $subject, $body, $headers );
	}

	// Send email with identify and pin
	private function send_email_validate_email_form( $email, $unique_id ) {
		$options = get_option( 'dcms_pin_options' );
		$slug    = $options['dcms_slug_page_validation_email'];

		$url_validate = home_url( $slug ) . '/?unique_id=' . $unique_id;
		$url_validate = "<a href='$url_validate'>$url_validate</a>";

		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		$subject = $options['dcms_subject_email_validation'];
		$body    = $options['dcms_text_email_validation'];
		$body    = str_replace( '%url%', $url_validate, $body );

		return wp_mail( $email, $subject, $body, $headers );
	}

	// Validate send email
	private function validate_send_email( $bol ): void {
		if ( ! $bol ) {
			$res = [
				'status'  => 0,
				'message' => ' ✉️ Error al enviar el correo, inténtelo más tarde'
			];
			wp_send_json( $res );
		}
	}

	// Security, verify nonce
	private function validate_nonce( $nonce_name ): void {
		if ( ! wp_verify_nonce( $_POST['nonce'], $nonce_name ) ) {
			$res = [
				'status'  => 0,
				'message' => '✋ Error nonce validation!!'
			];
			wp_send_json( $res );
		}
	}

	// Validate the number
	private function validate_number( $identify ): void {
		if ( $identify == 0 || empty( $identify ) ) {
			$res = [
				'status'  => 0,
				'message' => '⛔ Error identificativo de socio no válido'
			];
			wp_send_json( $res );
		}
	}

	// Validate duplicate email
	private function validate_duplicate_email( $duplicate ): void {
		if ( ! empty ( $duplicate ) ) {
			$res = [
				'status'  => 0,
				'message' => '✉️ El correo esta siendo usado por otro usuario'
			];
			wp_send_json( $res );
		}
	}

	// Validate reference or nif
	private function validate_reference_nif( $ref, $db_ref, $db_nif ): void {
		if ( empty ( $ref ) || ( $db_ref != $ref && $db_nif != $ref ) ) {
			$res = [
				'status'  => 0,
				'message' => '⛔ La Referencia o NIF no coincide con el identificativo del socio'
			];
			wp_send_json( $res );
		}
	}

	private function validate_update_email( $bol ): void {
		if ( ! $bol ) {
			$res = [
				'status'  => 0,
				'message' => '✉️ Error al actualizar el correo, posiblemente el correo ya se esta usando'
			];
			wp_send_json( $res );
		}
	}
}
