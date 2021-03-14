<?php

namespace dcms\pin\includes;

// Class for the operations of plugin
class Process{

    public function __construct(){
        add_action('wp_ajax_nopriv_dcms_ajax_validate_pin',[ $this, 'validate_data_send_pin' ]);
        add_action('wp_ajax_dcms_ajax_validate_pin',[ $this, 'validate_data_send_pin' ]);
    }

    public function validate_data_send_pin(){

        // Validate nonce
        $this->verify_nonce();

        // Get data
        $number = $_POST['number'];
        $ref    = $_POST['ref']; // NIF or Reference
        $email  = $_POST['email'];

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
    private function verify_nonce(){
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce-pin' ) ) {
            $res = [
                'status' => 0,
                'message' => '✋ Error nonce validation!!'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

}
