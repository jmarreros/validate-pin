<?php
$options = get_option( 'dcms_pin_options' );
?>

<section class="container-pin">

	<form id="frm-pin" class="frm-pin">
		<label for="email"><span class="dashicons dashicons-email"></span> Correo<span>*</span></label>
		<input type="email" id="email" name="email" value="" maxlength="100" tabindex="3" placeholder ="@" required>


		<section class="message" style="display:none;">
		</section>

		<input type="submit" class="button" id="send" name="send" value="<?php _e('Validar correo', 'dcms-send-pin') ?>" />
		<!--spinner-->
		<div class="lds-ring" style="display:none;"><div></div><div></div><div></div><div></div></div>
	</form>

</section>

