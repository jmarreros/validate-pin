<?php
    $options = get_option( 'dcms_pin_options' );
?>

<section class="container-pin">

<h3><?= $options['dcms_text_title_form'] ?></h3>
<p class="description"><?= $options['dcms_text_top_des_form'] ?></p>

<form id="frm-pin" class="frm-pin">
    <label for="number"><span class="dashicons dashicons-admin-users"></span> Número de Abonado<span>*</span></label>
    <input type="number" id="number" name="number" value="" maxlength="6" tabindex="1" required>

    <label for="ref"><span class="dashicons dashicons-media-default"></span> NIF o Referencia Abono<span>*</span></label>
    <input type="text" id="ref" name="ref" value="" maxlength="10" tabindex="2" required>

    <label for="email"><span class="dashicons dashicons-email"></span> Correo<span>*</span></label>
    <input type="email" id="email" name="email" value="" maxlength="100" tabindex="3" placeholder ="@" required>

    <label for="email2"><span class="dashicons dashicons-email"></span> Repite tu correo<span>*</span></label>
    <input type="email" id="email2" name="email2" value="" maxlength="100" tabindex="3" placeholder ="@" required>

    <div class="container-policy">
        <input type="checkbox" id="policy" name="policy" required> <label for="policy">Aceptar los <a href="/politica-de-privacidad-del-club/">Términos y Condiciones</a></label>
    </div>

    <section class="message" style="display:none;">
    </section>

    <input type="submit" class="button" id="send" name="send" value="<?php _e('Enviar', 'dcms-send-pin') ?>" />
    <!--spinner-->
    <div class="lds-ring" style="display:none;"><div></div><div></div><div></div><div></div></div>
</form>

</section>

