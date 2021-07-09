<?php

namespace dcms\pin\includes;

use dcms\pin\includes\Database;
use dcms\pin\helpers\Helper;

// Class for the operations of plugin
class Process{

    public function __construct(){
        // Frontend
        add_action('wp_ajax_nopriv_dcms_ajax_validate_pin',[ $this, 'process_form_send_pin' ]);
        add_action('wp_ajax_dcms_ajax_validate_pin',[ $this, 'process_form_send_pin' ]);

        add_action('wp_ajax_nopriv_dcms_ajax_validate_login',[ $this, 'process_form_login' ]);

        //Backend
        add_action('wp_ajax_dcms_resend_pin',[ $this, 'resend_email_pin' ]);
    }

    // Resend email backend
    // =====================
    public function resend_email_pin(){
        $email = $_POST['email'];
        $identify = $_POST['identify'];
        $pin = $_POST['pin'];

        $this->validate_nonce('ajax-admin-pin');

        if ( $this->send_email_pin($email, $identify, $pin ) ){
            $res = ['status' => 1];
        } else {
            $res = ['status' => 0];
        }

        echo json_encode($res);
        wp_die();
    }


    // Login Form
    // ==========

    public function process_form_login(){

        // Validate nonce
        $this->validate_nonce('ajax-nonce-login');

        // First
        $info = array();
        $info['user_login'] = $_POST['username'];
        $info['user_password'] = $_POST['password'];
        $info['remember'] = false;

        $user_signon = wp_signon( $info, false );

        if ( ! is_wp_error( $user_signon ) ){
            wp_set_current_user($user_signon->ID);
            wp_set_auth_cookie($user_signon->ID);

            // All is ok
            $res = [
                'status' => 1,
                'message' => "Redireccionando...",
            ];

        } else {
            $res = [
                'status' => 0,
                'message' => "Identificación o PIN no válido",
            ];
        }

        echo json_encode($res);

        wp_die();
    }


    // PIN Form
    // ========

    public function process_form_send_pin(){

        // Validate nonce
        $this->validate_nonce('ajax-nonce-pin');

        // Get data
        $number = intval($_POST['number']);
        $ref    = strtoupper(sanitize_text_field($_POST['ref'])); // NIF or Reference
        $email  = strtolower(sanitize_text_field($_POST['email']));

        // validate number <> 0
        $this->validate_number($number);

        $data = new Database();
        $user_meta = $data->get_data_user( $number );

        // number exists
        $this->validate_number($user_meta);

        $arr_meta =  Helper::meta_to_array($user_meta);

        $db_id       = $arr_meta['user_id'];
        $db_identify = $arr_meta['identify'];
        $db_pin      = $arr_meta['pin'];
        $db_number   = $arr_meta['number'];
        $db_ref      = strtoupper($arr_meta['reference']);
        $db_nif      = strtoupper($arr_meta['nif']);
        $db_email    = strtolower($arr_meta['email']);

        // Validate duplicate email
        $duplicate = $data->get_duplicate_email($email, $db_id);
        $this->validate_duplicate_email($duplicate);

        // validate reference with number
        $this->validate_reference_nif($ref, $db_ref, $db_nif );

        // Update email database
        if ( $db_email != $email ){
            $res = $data->update_email_user($email, $db_id);
            $this->validate_update_email($res);
        }

        // Send email
        $res = $this->send_email_pin($email, $db_identify, $db_pin );
        $this->validate_send_email($res);

        // Save log
        $data->save_log_pin_sent($email, $db_identify, $db_pin, $db_number, $db_ref, $db_nif, $db_id);

        // If all is ok
        $res = [
            'status' => 1,
            'message' => "✅ Hemos enviado un correo a <i>$email</i> con tu número de PIN",
        ];

        echo json_encode($res);
        wp_die();
    }


    // Send email with identify and pin
    private function send_email_pin( $email, $identify, $pin ){
        $options = get_option( 'dcms_pin_options' );

        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $subject = $options['dcms_subject_email'];
        $body    = $options['dcms_text_email'];
        $body = str_replace( '%id%', $identify, $body );
        $body = str_replace( '%pin%', $pin, $body );

        return wp_mail( $email, $subject, $body, $headers );
    }

    // Validate send email
    private function validate_send_email( $bol ){
        if ( ! $bol ) {
            $res = [
                'status' => 0,
                'message' => ' ✉️ Error al enviar el correo, inténtelo más tarde'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    // Security, verify nonce
    private function validate_nonce( $nonce_name ){
        if ( ! wp_verify_nonce( $_POST['nonce'], $nonce_name ) ) {
            $res = [
                'status' => 0,
                'message' => '✋ Error nonce validation!!'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    // Validate the number
    private function validate_number( $number ){
        if ( $number == 0 || empty($number) ) {
            $res = [
                'status' => 0,
                'message' => '⛔ Error número de socio no válido'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    // Validate duplicate email
    private function validate_duplicate_email( $duplicate ){
        if ( ! empty ($duplicate) ) {
            $res = [
                'status' => 0,
                'message' => '✉️ El correo esta siendo usado por otro usuario'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    // Validate reference or nif
    private function validate_reference_nif( $ref, $db_ref, $db_nif ){
        if ( empty ($ref) || ( $db_ref != $ref && $db_nif != $ref  )) {
            $res = [
                'status' => 0,
                'message' => '⛔ La Referencia o NIF no coincide con el número de socio'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    private function validate_update_email($bol){
        if ( ! $bol ) {
            $res = [
                'status' => 0,
                'message' => '✉️ Error al actualizar el correo, posiblemente el correo ya se esta usando'
            ];
            echo json_encode($res);
            wp_die();
        }
    }
}
