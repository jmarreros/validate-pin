<?php
    $options = get_option( 'dcms_pin_options' );
?>

<section class="container-pin">

<h3><?= $options['dcms_text_title_form'] ?></h3>
<p class="description"><?= $options['dcms_text_top_des_form'] ?></p>

<form class="frm-pin">
    <label for="number">Número de Socio<span>*</span></label>
    <input type="text" id="number" name="number" value="" maxlength="6" tabindex="1" required>

    <label for="ref">NIF o Referencia<span>*</span></label>
    <input type="text" id="ref" name="ref" value="" maxlength="10" tabindex="2" required>


    <label for="email">Correo<span>*</span></label>
    <input type="email" id="email" name="email" value="" maxlength="100" tabindex="3" required>

    <div class="container-policy">
        <input type="checkbox" id="policy" name="policy" required> <label for="policy">Aceptar los <a href="#">Términos y Condiciones</a></label>
    </div>

    <input type="submit" class="button" id="send" name="send" value="Enviar" />

</form>

</section>

