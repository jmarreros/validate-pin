<?php

namespace dcms\pin\includes;

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
                        'dcms_text_email'	        => 'Felicidades, validaste tus datos correctamente, el PIN que usarás será: %pin%, <br/> usa este pin siempre que quieras conectarte al sitio web',
                    ];
                    update_option('dcms_pin_options', $options);
                }
    }

    // Deactivate plugin
    public function dcms_deactivation_plugin(){
    }

}
