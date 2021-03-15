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
    }


    // Get data user based in number
    public function get_data_user( $number ){
        $sql = "SELECT * FROM $this->user_meta WHERE
                user_id in
                (SELECT user_id FROM $this->user_meta  WHERE meta_key = 'number' AND meta_value = $number)
                AND meta_key in ('number', 'reference', 'nif', 'pin', 'email')";

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

        return update_user_meta($user_id, 'email', $email);
    }
}
