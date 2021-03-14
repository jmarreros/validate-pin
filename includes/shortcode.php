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
        add_shortcode( DCMS_SHORTCODE, [$this, 'show_pin_form'] );
    }

    // Show form
    public function show_pin_form($atts , $content){
        include_once DCMS_PIN_PATH.'/views/form-shortcode.php';
    }

}