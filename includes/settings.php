<?php

namespace dcms\pin\includes;

/**
 * Class for creating the settings
 */
class Settings {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'init_configuration' ] );
	}

	// Register sections and fields
	public function init_configuration(): void {
		register_setting( 'dcms_send_pin_options_bd', 'dcms_pin_options' );

		// Section for validate email
		add_settings_section( 'dcms_validate_email_section',
			__( 'Validation', 'dcms-send-pin' ),
			[ $this, 'dcms_section_cb' ],
			'dcms_pin_sfields' );

		add_settings_field( 'dcms_check_validation_email',
			__( 'Activate validation email', 'dcms-send-pin' ),
			[ $this, 'dcms_section_checkbox_cb' ],
			'dcms_pin_sfields',
			'dcms_validate_email_section',
			[ 'label_for' => 'dcms_check_validate_email' ]
		);

		// Section for form
		add_settings_section( 'dcms_form_pin_section',
			__( 'Form Text', 'dcms-send-pin' ),
			[ $this, 'dcms_section_cb' ],
			'dcms_pin_sfields' );


		add_settings_field( 'dcms_text_title_form',
			__( 'Form title', 'dcms-send-pin' ),
			[ $this, 'dcms_section_input_cb' ],
			'dcms_pin_sfields',
			'dcms_form_pin_section',
			[
				'label_for' => 'dcms_text_title_form',
				'required'  => true
			]
		);

		add_settings_field( 'dcms_text_top_des_form',
			__( 'Form Description', 'dcms-send-pin' ),
			[ $this, 'dcms_section_input_cb' ],
			'dcms_pin_sfields',
			'dcms_form_pin_section',
			[
				'label_for' => 'dcms_text_top_des_form',
				'required'  => false
			]
		);

		add_settings_section( 'dcms_email_section',
			__( 'Default Text email', 'dcms-send-pin' ),
			[ $this, 'dcms_section_cb' ],
			'dcms_pin_sfields' );

		add_settings_field( 'dcms_sender_email',
			__( 'Sender email', 'dcms-send-pin' ),
			[ $this, 'dcms_section_input_cb' ],
			'dcms_pin_sfields',
			'dcms_email_section',
			[
				'label_for' => 'dcms_sender_email',
				'required'  => true
			]
		);

		add_settings_field( 'dcms_sender_name',
			__( 'Sender name', 'dcms-send-pin' ),
			[ $this, 'dcms_section_input_cb' ],
			'dcms_pin_sfields',
			'dcms_email_section',
			[
				'label_for' => 'dcms_sender_name',
				'required'  => true
			]
		);

		add_settings_field( 'dcms_subject_email',
			__( 'Subject email', 'dcms-send-pin' ),
			[ $this, 'dcms_section_input_cb' ],
			'dcms_pin_sfields',
			'dcms_email_section',
			[
				'label_for' => 'dcms_subject_email',
				'required'  => true
			]
		);

		add_settings_field( 'dcms_text_email',
			__( 'Email Text', 'dcms-send-pin' ),
			[ $this, 'dcms_section_textarea_field' ],
			'dcms_pin_sfields',
			'dcms_email_section',
			[
				'label_for'   => 'dcms_text_email',
				'description' => __( 'You can use <strong>%id%</strong> and <strong>%pin%</strong> to include the Identify and the PIN number between the text', 'dcms-send-pin' )
			]
		);
	}

	// Callback section
	public function dcms_section_cb(): void {
		echo '<hr/>';
	}

	// Callback input field callback
	public function dcms_section_input_cb( $args ): void {
		$id   = $args['label_for'];
		$req  = isset( $args['required'] ) && $args['required'] === true ? 'required' : '';
		$desc = $args['description'] ?? '';

		$options = get_option( 'dcms_pin_options' );
		$val     = isset( $options[ $id ] ) ? $options[ $id ] : '';

		printf( "<input id='%s' name='dcms_pin_options[%s]' class='regular-text' type='text' value='%s' %s>",
			$id, $id, $val, $req );

		if ( $desc ) {
			printf( "<p class='description'>%s</p> ", $desc );
		}

	}

	// Callback checkbox field callback
	public function dcms_section_checkbox_cb( $args ): void {
		$id   = $args['label_for'];
		$desc = $args['description'] ?? '';

		$options = get_option( 'dcms_pin_options' );
		$val     = checked( $options[ $id ] ?? '', 'on', false );

		printf( "<input id='%s' name='dcms_pin_options[%s]' type='checkbox' %s>",
			$id, $id, $val );

		if ( $desc ) {
			printf( "<p class='description'>%s</p> ", $desc );
		}

	}

	// Callback textarea field callback
	public function dcms_section_textarea_field( $args ) :void {
		$id      = $args['label_for'];
		$desc    = $args['description'] ?? '';
		$options = get_option( 'dcms_pin_options' );
		$val     = $options[ $id ];
		printf( "<textarea id='%s' name='dcms_pin_options[%s]' rows='5' cols='80' >%s</textarea><p class='description'>%s</p>", $id, $id, $val, $desc );
	}

}
