<?php

namespace dcms\pin\includes;

/**
 * Class for creating the shortcode
 */
class Shortcode{
    //constructor
    public function __construct(){
        add_action('init', [$this, 'create_form_shortcode']);
    }

    // Create shortcode
    public function create_form_shortcode(){
        add_shortcode( DCMS_SHORTCODE_FORM_PIN, [$this, 'show_pin_form'] );
    }

    // Show form
    public function show_pin_form($atts , $content){
	    wp_localize_script('pin-script',
                            'dcms_fpin',
                            [ 'ajaxurl'=>admin_url('admin-ajax.php'),
                              'nonce' => wp_create_nonce('ajax-nonce-pin')]);
        wp_enqueue_script('pin-script');

        wp_enqueue_style('pin-style');
        include_once DCMS_PIN_PATH.'/views/form-shortcode.php';
    }

}
