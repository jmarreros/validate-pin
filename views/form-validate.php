<section class="container-pin">

    <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" class="frm-validate">

        <label for="email"><span class="dashicons dashicons-email"></span> Correo<span>*</span></label>
        <input type="email" id="email" name="email" value="" maxlength="100" tabindex="0" placeholder="@" required>

        <input type="hidden" name="unique_id" value="<?= $unique_id ?>">
        <input type="hidden" name="action" value="validate_email">

        <input type="submit" class="button" id="send" name="send"
               value="<?php _e( 'Validar correo', 'dcms-send-pin' ) ?>"/>
    </form>

</section>

