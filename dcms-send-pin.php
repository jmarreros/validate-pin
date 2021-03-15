<?php
/*
Plugin Name: Sporting Send Pin
Plugin URI: https://decodecms.com
Description: Send a pin number to the user
Version: 1.0
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: dcms-send-pin
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\pin;

use dcms\pin\includes\Plugin;
use dcms\pin\includes\Submenu;
use dcms\pin\includes\Settings;
use dcms\pin\includes\Enqueue;
use dcms\pin\includes\Shortcode;
use dcms\pin\includes\Process;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
**/
final class Loader{

	// Define all the constants we need
	public function define_constants(){
		define ('DCMS_PIN_VERSION', '1.0');
		define ('DCMS_PIN_PATH', plugin_dir_path( __FILE__ ));
		define ('DCMS_PIN_URL', plugin_dir_url( __FILE__ ));
		define ('DCMS_PIN_BASE_NAME', plugin_basename( __FILE__ ));
		define ('DCMS_PIN_TEXT_DOMAIN', 'dcms-send-pin' );
		define ('DCMS_PIN_SUBMENU', 'edit.php?post_type=events_sporting');
		define ('DCMS_SHORTCODE', 'form-pin');
	}

	// Load all the files we need
	public function load_includes(){
		include_once ( DCMS_PIN_PATH . '/helpers/helper.php');
		include_once ( DCMS_PIN_PATH . '/includes/plugin.php');
		include_once ( DCMS_PIN_PATH . '/includes/settings.php');
		include_once ( DCMS_PIN_PATH . '/includes/submenu.php');
		include_once ( DCMS_PIN_PATH . '/includes/enqueue.php');
		include_once ( DCMS_PIN_PATH . '/includes/shortcode.php');
		include_once ( DCMS_PIN_PATH . '/includes/process.php');
		include_once ( DCMS_PIN_PATH . '/includes/database.php');
		include_once ( DCMS_PIN_PATH . '/helpers/helper.php');
	}

	// Load tex domain
	public function load_domain(){
		add_action('plugins_loaded', function(){
			$path_languages = dirname(DCMS_PIN_BASE_NAME).'/languages/';
			load_plugin_textdomain(DCMS_PIN_TEXT_DOMAIN, false, $path_languages );
		});
	}

	// Add link to plugin list
	public function add_link_plugin(){
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ){
			$cad = (strpos(DCMS_PIN_SUBMENU,'?')) ? "&" : '?';
			return array_merge( array(
				'<a href="' . esc_url( admin_url( DCMS_PIN_SUBMENU . $cad . 'page=send-pin' ) ) . '">' . __( 'Settings', DCMS_PIN_TEXT_DOMAIN ) . '</a>'
			), $links );
		} );
	}

	// Initialize all
	public function init(){
		$this->define_constants();
		$this->load_includes();
		$this->load_domain();
		$this->add_link_plugin();
		new Plugin();
		new Settings();
		new SubMenu();
		new Enqueue();
		new Shortcode();
		new Process();
	}

}

$dcms_pin_process = new Loader();
$dcms_pin_process->init();


