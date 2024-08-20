<?php

namespace dcms\pin\includes;

/**
 * Class for creating a dashboard submenu
 */
class Submenu {
	// Constructor
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_submenu' ] );
	}

	// Register submenu
	public function register_submenu(): void {
		add_submenu_page(
			DCMS_PIN_SUBMENU,
			__( 'Forms PIN', 'dcms-send-pin' ),
			__( 'Forms PIN', 'dcms-send-pin' ),
			'manage_options',
			'send-pin',
			[ $this, 'submenu_page_callback' ]
		);
	}

	// Callback, show view
	public function submenu_page_callback(): void {

		wp_enqueue_style( 'admin-pin-style' );
		wp_enqueue_script( 'admin-pin-script' );
		wp_localize_script( 'admin-pin-script', 'dcms_admin_pin', [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'ajax-admin-pin' )
		] );

		if ( isset( $_POST['date_start'] ) && isset( $_POST['date_end'] ) ) {
			$db        = new Database();
			$val_start = $_POST['date_start'];
			$val_end   = $_POST['date_end'];

			$rows = $db->report_pin_sent( $val_start, $val_end );
		}

		include_once( DCMS_PIN_PATH . 'backend/views/settings-main.php' );
	}
}