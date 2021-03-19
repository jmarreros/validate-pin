<?php

namespace dcms\pin\includes;

use dcms\pin\includes\Database;

// Class for the operations of plugin
class Plugin{

    public function __construct(){
        register_activation_hook( DCMS_PIN_BASE_NAME, [ $this, 'dcms_activation_plugin'] );
        register_deactivation_hook( DCMS_PIN_BASE_NAME, [ $this, 'dcms_deactivation_plugin'] );
    }

    // Activate plugin - create options and database table
    public function dcms_activation_plugin(){
        // Default Options
        $options = get_option( 'dcms_pin_options' );

        if ( empty($options) ){
            $options = [
                'dcms_text_title_form'	    => 'Valida tus datos y obtén tu PIN',
                'dcms_text_top_des_form'	=> 'Luego de llenar los siguientes datos te enviaremos un correo con tu número de PIN',
                'dcms_subject_email'        => 'Tu PIN de abonado de Sporting',
                'dcms_text_email'	        => '<p>
                                                Felicidades, validaste tus datos correctamente, tus datos son:<br>
                                                <strong>ID:</strong>%id%<br>
                                                <strong>PIN:</strong>%pin%
                                                </p>
                                                <p> Usa este pin siempre que quieras conectarte al sitio web.</p>',
            ];
            update_option('dcms_pin_options', $options);
        }

        // Create table
        $db = new Database();
        $db->create_table();
    }

    // Deactivate plugin
    public function dcms_deactivation_plugin(){
    }

}
