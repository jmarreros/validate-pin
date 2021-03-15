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
        $ref    = $_POST['ref']; // NIF or Reference
        $email  = $_POST['email'];

        // validate number
        $this->validate_number($number);

        $db = new Database();
        $user_meta = $db->get_data_user( $number );


        error_log(print_r($user_meta,true));

        // $key = array_search('email', array_column($user_meta, 'meta_key'));
        // TODO
        // Hacer pruebas de email repetido
        $result = search_object_in_array('email', $user_meta);

        error_log(print_r($result, true));


        $res = [
            'status' => 1,
            'message' => 'Todo 👍',
            'number' => $number,
            'ref'   => $ref,
            'email' => $email
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
        if ( $number == 0 ) {
            $res = [
                'status' => 0,
                'message' => '✋ Error número de socio no válido!'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

}
