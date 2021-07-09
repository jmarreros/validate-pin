<?php

namespace dcms\pin\includes;

use dcms\pin\includes\Database;

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
            __('Forms PIN','dcms-send-pin'),
            __('Forms PIN','dcms-send-pin'),
            'manage_options',
            'send-pin',
            [$this, 'submenu_page_callback']
        );
    }

    // Callback, show view
    public function submenu_page_callback(){
        $db = new Database();

        $val_start  = $_POST['date_start']??date('Y-m-d');
        $val_end    = $_POST['date_end']??date('Y-m-d');

        $rows = $db->select_log_table($val_start, $val_end);

        include_once (DCMS_PIN_PATH. '/views/settings-main.php');
    }
}