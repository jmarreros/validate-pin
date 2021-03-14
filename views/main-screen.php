<div class="wrap">

<style>
.dcms-shortcode{
    background-color: #0073AA;
    border-radius:4px;
    Padding:10px 20px;
    color:white;
}
</style>

<h1><?php _e('Pin Form Settings', DCMS_PIN_TEXT_DOMAIN) ?></h1>

<h2>Shortcode</h2>
<hr>
<section class="dcms-shortcode">
<small><?php _e('You can use this shortcode to show the form: ') ?></small>
<strong>[<?php echo DCMS_SHORTCODE ?>]</strong>
</section>

<form action="options.php" method="post">
    <?php
        settings_fields('dcms_send_pin_options_bd');
        do_settings_sections('dcms_pin_sfields');
        submit_button();
    ?>
</form>

</div>