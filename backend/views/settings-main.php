<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! current_user_can( 'manage_options' ) ) {
	return;
} // only administrator

$plugin_tabs                          = [];
$plugin_tabs['report-pin-sent']       = __( "Report pin Sent", 'dcms-send-pin' );
$plugin_tabs['report-validate-email'] = __( "Report validate Mail", 'dcms-send-pin' );
$plugin_tabs['settings-main']              = __( "Settings", 'dcms-send-pin' );

// Get Current tab
$current_tab = $_GET['tab'] ?? 'report-pin-sent';

?>
    <div class="wrap">

        <h1><?php _e( 'Form PIN options', 'dcms-send-pin' ) ?></h1>

		<?php
		plugin_options_tabs( $current_tab, $plugin_tabs );

		switch ( $current_tab ) {
			case 'report-pin-sent':
				include_once( 'report-pin-sent.php' );
				break;
			case 'report-validate-email':
				include_once( 'report-validate-email.php' );
				break;
			case 'settings-main':
				tab_settings_main();
				break;
		}

		?>
    </div><!--wrap -->


<?php
// Settings tab
function tab_settings_main(): void { ?>
    <style>
        .dcms-shortcode {
            background-color: #0073AA;
            border-radius: 4px;
            Padding: 10px 20px;
            color: white;
        }
    </style>

    <h2>Shortcodes</h2>
    <hr>
    <section class="dcms-shortcode">
        <small><?php _e( 'You can use this shortcode to show the form: ' ) ?></small>
        <strong>[<?php echo DCMS_SHORTCODE_FORM_PIN ?>]</strong>
    </section>

    <hr>
    <section class="dcms-shortcode">
        <small><?php _e( 'You can use this shortcode to show the login form: ' ) ?></small>
        <strong>[<?php echo DCMS_SHORTCODE_FORM_LOGIN ?>] </strong>
        <p>
            <strong>Optional:</strong> redirect='#' register='#'
        </p>
        <p>
            Example:<br>
            [sporting-form-login redirect="/" register="/enviar-pin"]
        </p>
    </section>

    <form action="options.php" method="post">
		<?php
		settings_fields( 'dcms_send_pin_options_bd' );
		do_settings_sections( 'dcms_pin_sfields' );
		submit_button();
		?>
    </form>
<?php } ?>

<?php
// Create tabs and activate current tab
function plugin_options_tabs( $current_tab, $plugin_tabs ): void {
	$cad = ( strpos( DCMS_PIN_SUBMENU, '?' ) ) ? "&" : '?';

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $plugin_tabs as $tab_key => $tab_caption ) {
		$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
		echo "<a class='nav-tab " . $active . "' href='" . admin_url( DCMS_PIN_SUBMENU . $cad . "page=send-pin&tab=" . $tab_key ) . "'>" . $tab_caption . '</a>';
	}
	echo '</h2>';
}