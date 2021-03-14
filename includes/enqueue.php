<?php

namespace dcms\pin\includes;

// Class for enqueue scripts
class Enqueue{

    public function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    }

    public function register_scripts(){

        wp_register_script('pin-script',
                            DCMS_PIN_URL.'/assets/script.js',
                            ['jquery'],
                            DCMS_PIN_VERSION,
                            true);

        wp_register_style('pin-style',
                            DCMS_PIN_URL.'/assets/style.css',
                            [],
                            DCMS_PIN_VERSION );

    }

}

