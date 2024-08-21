<section class="container-validate">

    <form id="frm-validate" method="post" class="frm-validate">

        <label for="email"><span class="dashicons dashicons-email"></span> Correo<span>*</span></label>
        <input type="email" id="email" name="email" value="" maxlength="100" tabindex="0" placeholder="@" required>

        <input type="hidden" id="unique_id" name="unique_id" value="<?= $unique_id ?>">
        <input type="hidden" name="action" value="validate_email">

        <input type="submit" class="button" id="validate-email" name="send"
               value="<?php _e( 'Validar correo', 'dcms-send-pin' ) ?>"/>

        <section class="message" style="display:none;">
        </section>

        <!--spinner-->
        <div class="lds-ring" style="display:none;"><div></div><div></div><div></div><div></div></div>

    </form>

</section>

