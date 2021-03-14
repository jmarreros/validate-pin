<?php

namespace dcms\pin\includes;

/**
 * Class for creating a dashboard submenu
 */
class Submenu{
    // Constructor
    public function __construct(){
        add_action('admin_menu', [$this, 'register_submenu']);
    }

    // Register submenu
    public function register_submenu(){
        add_submenu_page(
            DCMS_PIN_SUBMENU,
            __('Send Pin',DCMS_PIN_TEXT_DOMAIN),
            __('Send Pin',DCMS_PIN_TEXT_DOMAIN),
            'manage_options',
            'send-pin',
            [$this, 'submenu_page_callback']
        );
    }

    // Callback, show view
    public function submenu_page_callback(){
        include_once (DCMS_PIN_PATH. '/views/main-screen.php');
    }
}