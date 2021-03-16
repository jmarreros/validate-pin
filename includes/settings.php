<?php

namespace dcms\pin\includes;

/**
 * Class for creating the settings
 */
class Settings{

    public function __construct(){
        add_action('admin_init', [$this, 'init_configuration']);
    }

    // Register seccions and fields
    public function init_configuration(){
        register_setting('dcms_send_pin_options_bd', 'dcms_pin_options' );


        // Excel Fields section
        add_settings_section('dcms_form_pin_section',
                            __('Form Text', DCMS_PIN_TEXT_DOMAIN),
                            [$this,'dcms_section_cb'],
                            'dcms_pin_sfields' );

        add_settings_field('dcms_text_title_form',
                            __('Form title', DCMS_PIN_TEXT_DOMAIN),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_pin_sfields',
                            'dcms_form_pin_section',
                            ['label_for' => 'dcms_text_title_form',
                             'required' => true]
        );


        add_settings_field('dcms_text_top_des_form',
                            __('Form Description', DCMS_PIN_TEXT_DOMAIN),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_pin_sfields',
                            'dcms_form_pin_section',
                            ['label_for' => 'dcms_text_top_des_form',
                             'required' => false]
        );


        add_settings_section('dcms_email_section',
                        __('Default Text email', DCMS_PIN_TEXT_DOMAIN),
                                [$this,'dcms_section_cb'],
                                'dcms_pin_sfields' );

        add_settings_field('dcms_subject_email',
                            __('Subject email', DCMS_PIN_TEXT_DOMAIN),
                            [$this, 'dcms_section_input_cb'],
                            'dcms_pin_sfields',
                            'dcms_email_section',
                            ['label_for' => 'dcms_subject_email',
                                'required' => true]
        );

        add_settings_field('dcms_text_email',
                            __('Email Text', DCMS_PIN_TEXT_DOMAIN),
                            [$this, 'dcms_section_textarea_field'],
                            'dcms_pin_sfields',
                            'dcms_email_section',
                            ['label_for' => 'dcms_text_email',
                             'description' => __('You can use <strong>%pin%</strong> to include the PIN number between the text', DCMS_PIN_TEXT_DOMAIN)]
        );
    }

    // Callback section
    public function dcms_section_cb(){
		echo '<hr/>';
	}

    // Callback input field callback
    public function dcms_section_input_cb($args){
        $id = $args['label_for'];
        $req = isset($args['required']) ? 'required' : '';
        $class = isset($args['class']) ? "class='".$args['class']."'" : '';
        $desc = isset($args['description']) ? $args['description'] : '';

        $options = get_option( 'dcms_pin_options' );
        $val = isset( $options[$id] ) ? $options[$id] : '';

        printf("<input id='%s' name='dcms_pin_options[%s]' class='regular-text' type='text' value='%s' %s %s>",
                $id, $id, $val, $req, $class);

        if ( $desc ) printf("<p class='description'>%s</p> ", $desc);

    }


    public function dcms_section_textarea_field( $args ){

        $id = $args['label_for'];
        $desc = isset($args['description']) ? $args['description'] : '';
        $options = get_option( 'dcms_pin_options' );
        $val = $options[$id];
        printf("<textarea id='%s' name='dcms_pin_options[%s]' rows='5' cols='80' >%s</textarea><p class='description'>%s</p>", $id, $id, $val, $desc);
	}

}
