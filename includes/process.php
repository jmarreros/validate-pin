<?php

namespace dcms\pin\includes;

use dcms\pin\includes\Database;

// Class for the operations of plugin
class Process{

    public function __construct(){
        add_action('wp_ajax_nopriv_dcms_ajax_validate_pin',[ $this, 'validate_data_send_pin' ]);
        add_action('wp_ajax_dcms_ajax_validate_pin',[ $this, 'validate_data_send_pin' ]);
    }

    public function validate_data_send_pin(){

        // Validate nonce
        $this->validate_nonce();

        // Get data
        $number = intval($_POST['number']);
        $ref    = sanitize_text_field($_POST['ref']); // NIF or Reference
        $email  = strtolower(sanitize_text_field($_POST['email']));

        // validate number <> 0
        $this->validate_number($number);

        $data = new Database();
        $user_meta = $data->get_data_user( $number );

        // number exists
        $this->validate_number($user_meta);

        $arr_meta =  meta_to_array($user_meta);

        $db_id  = $arr_meta['user_id'];
        $db_pin = $arr_meta['pin'];
        $db_nif = $arr_meta['nif'];
        $db_ref = $arr_meta['reference'];
        $db_email = $arr_meta['email'];

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


        // If all is ok
        $res = [
            'status' => 1,
            'message' => "✅ Hemos enviado un correo a <i>$email</i> con tu número de PIN",
        ];

        echo json_encode($res);
        wp_die();
    }



    // Security, verify nonce
    private function validate_nonce(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce-pin' ) ) {
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

    private function validate_update_email($res){
        if ( ! $res ) {
            $res = [
                'status' => 0,
                'message' => '✉️ Error al actualizar el correo, posiblemente el correo ya se esta usando'
            ];
            echo json_encode($res);
            wp_die();
        }
    }
}
