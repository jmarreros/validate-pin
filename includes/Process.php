<?php

namespace dcms\pin\includes;

use dcms\pin\helpers\Helper;

// Class for the operations of plugin
class Process {

	public function __construct() {
		// Frontend
		add_action( 'wp_ajax_nopriv_dcms_ajax_validate_pin', [ $this, 'process_form_send_pin' ] );
		add_action( 'wp_ajax_dcms_ajax_validate_pin', [ $this, 'process_form_send_pin' ] );

		add_action( 'wp_ajax_nopriv_dcms_ajax_validate_login', [ $this, 'process_form_login' ] );

		//Backend
		add_action( 'wp_ajax_dcms_resend_pin', [ $this, 'resend_email_pin' ] );
	}

	// Backend - Resend Email
	public function resend_email_pin(): void {
		$email    = $_POST['email'];
		$identify = $_POST['identify'];
		$pin      = $_POST['pin'];

		$this->validate_nonce( 'ajax-admin-pin' );

		if ( $this->send_email_pin( $email, $identify, $pin ) ) {
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
		$email    = strtolower( sanitize_text_field( $_POST['email'] ) );

		// validate number <> 0
		$this->validate_number( $identify );

		$data      = new Database();
		$user_meta = $data->get_data_user( $identify );

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
		$duplicate = $data->get_duplicate_email( $email, $db_id );
		$this->validate_duplicate_email( $duplicate );

		// validate reference with number
		$this->validate_reference_nif( $ref, $db_ref, $db_nif );

		// Update email database
		if ( $db_email != $email ) {
			$res = $data->update_email_user( $email, $db_id );
			$this->validate_update_email( $res );
		}

		// Send email
		$res = $this->send_email_pin( $email, $db_identify, $db_pin );
		$this->validate_send_email( $res );

		// Save log
		$data->save_log_pin_sent( $email, $db_identify, $db_pin, $db_number, $db_ref, $db_nif, $db_id );

		// If all is ok
		$res = [
			'status'  => 1,
			'message' => "✅ Solicitud correcta, en unas horas recibirás en tu email <i>$email</i> tu número de PIN. <br><br>Si no lo recibes, no olvides revisar la bandeja de no deseados, Spam, y Promociones",
		];

		wp_send_json( $res );
	}


	// Send email with identify and pin
	private function send_email_pin( $email, $identify, $pin ) {
		$options = get_option( 'dcms_pin_options' );

		$headers = [ 'Content-Type: text/html; charset=UTF-8' ];
		$subject = $options['dcms_subject_email'];
		$body    = $options['dcms_text_email'];
		$body    = str_replace( '%id%', $identify, $body );
		$body    = str_replace( '%pin%', $pin, $body );

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
