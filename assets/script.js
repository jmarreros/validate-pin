(function ($) {

    // Login Form
    $('#frm-login').submit(function (e) {
        e.preventDefault();

        const sspin = '.container-login .lds-ring';
        const sbutton = '.container-login #submit.button';
        const smessage = '.container-login section.message';

        $.ajax({
            url: dcms_flogin.ajaxurl,
            type: 'post',
            data: {
                action: 'dcms_ajax_validate_login',
                nonce: dcms_flogin.nonce,
                username: $('#username').val(),
                password: $('#password').val(),
            },
            beforeSend: function () {
                $(sspin).show();
                $(sbutton).val('Validando ...').prop('disabled', true);
                $(smessage).hide();
            }
        })
            .done(function (res) {

                // res = JSON.parse(res);
                show_message(res, smessage);

                if (res.status === 1) {
                    window.location.href = dcms_flogin.url;
                }
            })
            .always(function () {
                $(sspin).hide();
                $(sbutton).val('Ingresar').prop('disabled', false);
            });

    })

    // PIN Form
    $('#frm-pin').submit(function (e) {
        e.preventDefault();

        const sspin = '.container-pin .lds-ring';
        const sbutton = '.container-pin #send.button';
        const smessage = '.container-pin section.message';

        // Validate same email
        if (!has_same_email()) {
            const res = {status: 0, message: "Los correos tienen que ser iguales"}
            show_message(res, smessage);
            return;
        }

        $.ajax({
            url: dcms_fpin.ajaxurl,
            type: 'post',
            data: {
                action: 'dcms_ajax_validate_pin',
                nonce: dcms_fpin.nonce,
                identify: $('#identify').val(),
                ref: $('#ref').val(),
                email: $('#email').val()
            },
            beforeSend: function () {
                $(sspin).show();
                $(sbutton).val('Enviando ...').prop('disabled', true);
                $(smessage).hide();
            }
        })
            .done(function (res) {
                show_message(res, smessage);
                $(sbutton).hide();
            })
            .always(function () {
                $(sspin).hide();
            });

    });


    // Login Form
    $('#frm-validate').submit(function (e) {
        e.preventDefault();

        const sspin = '.container-validate .lds-ring';
        const sbutton = '.container-validate .button';
        const smessage = '.container-validate section.message';

        $.ajax({
            url: dcms_fvalidation.ajaxurl,
            type: 'post',
            data: {
                action: 'dcms_ajax_validate_email',
                nonce: dcms_fvalidation.nonce,
                email: $('#email').val(),
                unique_id: $('#unique_id').val(),
            },
            beforeSend: function () {
                $(sspin).show();
                $(sbutton).val('Enviando ...').prop('disabled', true);
                $(smessage).hide();
            }
        })
            .done(function (res) {
                show_message(res, smessage);
                if (res.status === 1) {
                    $(sbutton).hide();
                    $('#email').prop('disabled', true);
                }
            })
            .always(function () {
                $(sspin).hide();
            });

    })


    // Aux function to show message
    function show_message(res, smessage) {
        if (res.status === 0) {
            $(smessage).addClass('error');
        } else {
            $(smessage).removeClass('error');
        }

        $(smessage).show().html(res.message);
    }

    // Aux function to compare emails
    function has_same_email() {
        const email = $('#email').val();
        const email2 = $('#email2').val();
        return email === email2;
    }

})(jQuery);