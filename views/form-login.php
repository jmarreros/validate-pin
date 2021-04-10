<?php
// $url_redirect
// $url_register
?>
<section class="container-login">

<form id="frm-login" class="frm-login">

        <h3><?= __('Introduce tus datos para acceder', 'dcms-send-pin') ?></h3>
        <label for="username"><?= __('Identificativo', 'dcms-send-pin') ?> <span>*</span></label>
        <input id="username" type="text" name="username" required>

        <label for="password"><?= __('PIN', 'dcms-send-pin') ?> <span>*</span></label>
        <input id="password" type="password" name="password" required>

        <section class="message" style="display:none;">
        </section>

        <section>
            <input class="button" type="submit" name="submit" id="submit" value="<?= __('Ingresar', 'dcms-send-pin') ?>">
            <a class="button register" href="<?= $url_register ?>" name="register" id="register" >
                <?= __('Solicita tu PIN', 'dcms-send-pin') ?>
            </a>
        </section>

        <!--spinner-->
        <div class="lds-ring" style="display:none;"><div></div><div></div><div></div><div></div></div>
    </form>

</section>