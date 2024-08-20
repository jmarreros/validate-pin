<?php

namespace dcms\pin\includes;

// Class for the operations of plugin
class Plugin{

    public function __construct(){
        // Mail configurations
        add_filter( 'wp_mail_from', [ $this, 'dcms_sender_email'] );
        add_filter( 'wp_mail_from_name', [ $this, 'dcms_sender_name'] );

        // Hide admin bar
        add_action('after_setup_theme', [ $this, 'dcms_hide_admin_bar' ]);

        // Activation/Deactivation
        register_activation_hook( DCMS_PIN_BASE_NAME, [ $this, 'dcms_activation_plugin'] );
//        register_deactivation_hook( DCMS_PIN_BASE_NAME, [ $this, 'dcms_deactivation_plugin'] );
    }


    // Hide Admin bar for suscribers
    public function dcms_hide_admin_bar() :void{
        if ( current_user_can('subscriber') ) {
            add_filter( 'show_admin_bar', '__return_false' );
        }
    }

    // Sender email configuration
    public function dcms_sender_email($original_email_address){
        $options = get_option( 'dcms_pin_options' );
        return $options['dcms_sender_email'];
    }

    // Sender name configuration
    public function dcms_sender_name($original_email_from){
        $options = get_option( 'dcms_pin_options' );
        return $options['dcms_sender_name'];
    }

    // Activate plugin - create options and database table
    public function dcms_activation_plugin():void{
        // Default Options
        $options = get_option( 'dcms_pin_options' );

        if ( empty($options) ){
            $options = [
                'dcms_sender_email'         => 'admin@gestionabonados.realsporting.com',
                'dcms_sender_name'          => 'Real Sporting de Gijón',
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
//    public function dcms_deactivation_plugin(){
//    }

}
