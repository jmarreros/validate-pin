<?php

namespace dcms\pin\includes;

// Class for enqueue scripts
class Enqueue{

    public function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    }

    public function register_scripts(){

        wp_register_script('forms-script',
                            DCMS_PIN_URL.'/assets/script.js',
                            ['jquery'],
                            DCMS_PIN_VERSION,
                            true);

        wp_register_style('forms-style',
                            DCMS_PIN_URL.'/assets/style.css',
                            [],
                            DCMS_PIN_VERSION );

        //Add dashicons
    	wp_enqueue_style( 'dashicons' );
    }

}

