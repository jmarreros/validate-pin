<?php

namespace dcms\pin\includes;

class Database{
    private $wpdb;
    private $table_name;
    private $user_meta;

    public function __construct(){
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->user_meta = $this->wpdb->prefix.'usermeta';

        $this->table_name = $this->wpdb->prefix . 'dcms_pin_sent';
    }


    // Get data user based in number
    public function get_data_user( $number ){
        $sql = "SELECT * FROM $this->user_meta WHERE
                user_id in
                (SELECT user_id FROM $this->user_meta  WHERE meta_key = 'number' AND meta_value = $number)
                AND meta_key in ('identify', 'number', 'reference', 'nif', 'pin', 'email')";

        return $this->wpdb->get_results($sql);
    }

    // Get duplicate email validation
    public function get_duplicate_email( $email, $not_id ){
        $sql = "SELECT user_id FROM $this->user_meta
                WHERE meta_key = 'email' AND meta_value = '$email' AND user_id <> $not_id";

        return $this->wpdb->get_var($sql);
    }

    // Update email user
    public function update_email_user( $email, $user_id){
        $res = wp_update_user( ['ID' => $user_id, 'user_email' => $email] );

        if ( is_wp_error($res) ) {
            error_log($res->get_error_message());
            return false;
        }

        // update user meta email
        $result = update_user_meta($user_id, 'email', $email);

        return $result;
    }

    // Insert log table
    public function save_log_pin_sent($email, $identify, $pin, $db_id){
        $row = [];
        $row['id_user'] = $db_id;
        $row['identify'] = $identify;
        $row['pin'] = $pin;
        $row['email'] = $email;

        $res = $this->wpdb->insert($this->table_name, $row);
        if ( ! $res) error_log("Ocurrió un error al insertar en $this->table_name");

        return $res;
    }

    // Select the custom table, lastest rows
    public function select_log_table( $qty, $order = 'DESC' ){
        $str = $qty <> 0 ? 'LIMIT '.$qty : '';

        $sql = "SELECT * FROM {$this->table_name} ORDER BY id {$order} {$str}";

        return $this->wpdb->get_results($sql);
    }

    // Init activation create table
    public function create_table(){

        $sql = " CREATE TABLE IF NOT EXISTS {$this->table_name} (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `id_user` int(10) unsigned DEFAULT NULL,
                    `identify` int(10) unsigned DEFAULT NULL,
                    `pin` int(10) unsigned DEFAULT NULL,
                    `email` varchar(100) DEFAULT NULL,
                    `date` datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
          )";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
