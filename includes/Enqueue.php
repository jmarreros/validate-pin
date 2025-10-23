<?php

namespace dcms\pin\includes;

// Class for enqueue scripts
class Enqueue{

    public function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'register_scripts_backend']);
    }

    // Front-end scripts
    public function register_scripts(){

        wp_register_script('forms-pin-script',
                            DCMS_PIN_URL.'/assets/script.js',
                            ['jquery'],
                            DCMS_PIN_VERSION,
                            true);

        wp_register_style('forms-pin-style',
                            DCMS_PIN_URL.'/assets/style.css',
                            [],
                            DCMS_PIN_VERSION );

        //Add dashicons
    	wp_enqueue_style( 'dashicons' );
    }

    // Backend scripts
    public function register_scripts_backend(){
        wp_register_script('admin-pin-script',
                DCMS_PIN_URL.'/backend/assets/script.js',
                ['jquery'],
                DCMS_PIN_VERSION,
                true);

        wp_register_style('admin-pin-style',
                DCMS_PIN_URL.'/backend/assets/style.css',
                [],
                DCMS_PIN_VERSION );
    }

}

