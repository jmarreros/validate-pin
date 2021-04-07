<?php

namespace dcms\pin\includes;

/**
 * Class for creating the shortcode
 */
class Shortcode{
    //constructor
    public function __construct(){
        add_action('init', [$this, 'create_forms_shortcode']);
    }

    // Create shortcode
    public function create_forms_shortcode(){
        add_shortcode( DCMS_SHORTCODE_FORM_PIN, [$this, 'show_pin_form'] );
        add_shortcode( DCMS_SHORTCODE_FORM_LOGIN, [$this, 'show_login_form'] );
    }

    // Show form
    public function show_pin_form($atts , $content){
	    wp_localize_script('forms-script',
                            'dcms_fpin',
                            [ 'ajaxurl'=>admin_url('admin-ajax.php'),
                              'nonce' => wp_create_nonce('ajax-nonce-pin')]);
        wp_enqueue_script('forms-script');

        wp_enqueue_style('forms-style');

        ob_start();
            include_once DCMS_PIN_PATH.'/views/form-pin.php';
            $html_code = ob_get_contents();
        ob_end_clean();

        return $html_code;
    }

    // Show login form
    public function show_login_form($atts , $content){

        if ( ! is_user_logged_in() ){

            $atts = shortcode_atts(['redirect' => home_url(),
                                    'register' => home_url().'/enviar-pin/'], $atts, DCMS_SHORTCODE_FORM_LOGIN );

            $url_redirect = $atts['redirect'];
            $url_register = $atts['register'];

            wp_localize_script('forms-script',
                    'dcms_flogin',
                    [   'ajaxurl'=>admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('ajax-nonce-login'),
                        'url' => $url_redirect
                    ]);
            wp_enqueue_script('forms-script');
            wp_enqueue_style('forms-style');

            ob_start();
                include_once DCMS_PIN_PATH.'/views/form-login.php';
                $html_code = ob_get_contents();
            ob_end_clean();

            return $html_code;

        }

    }
}
