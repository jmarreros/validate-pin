<?php
/** @var bool $waiting_validation */
/** @var string $unique_id */
?>
<section class="container-validate">

    <form id="frm-validate" method="post" class="frm-validate">
		<?php

		if ( $waiting_validation ) : ?>
            <section class="message-waiting">
                Ya has validado tu correo electrónico, espera unos minutos para usar el enlace que te llegará por
                correo, o genera un nuevo enlace.
            </section>
            <br>
		<?php endif; ?>

        <label for="email"><span class="dashicons dashicons-email"></span> Correo<span>*</span></label>
        <input type="email" id="email" name="email" value="" maxlength="100" tabindex="0" placeholder="@" required>

        <input type="hidden" name="unique_id" id="unique_id" value="<?= $unique_id ?>">

        <input type="submit" class="button" id="validate-email" name="send"
               value="<?php _e( 'Validar correo', 'dcms-send-pin' ) ?>"/>

        <section class="message" style="display:none;">
        </section>

        <!--spinner-->
        <div class="lds-ring" style="display:none;">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>

    </form>

</section>
<br>
