(function($){

    // Login Form
    $('#frm-login').submit(function(e){
        e.preventDefault();

        const sspin     = '.container-login .lds-ring';
        const sbutton   = '.container-login #submit.button';
        const smessage  = '.container-login section.message';

        $.ajax({
			url : dcms_flogin.ajaxurl,
			type: 'post',
			data: {
				action  : 'dcms_ajax_validate_login',
                nonce   : dcms_flogin.nonce,
                username: $('#username').val(),
                password: $('#password').val(),
			},
            beforeSend: function(){
                $(sspin).show();
                $(sbutton).val('Validando ...').prop('disabled', true);;
                $(smessage).hide();
            }
        })
        .done( function(res) {
            res = JSON.parse(res);
            show_message(res, smessage);

            if (res.status == 1){
                window.location.href = dcms_flogin.url;
            }
        })
        .always( function() {
            $(sspin).hide();
            $(sbutton).val('Ingresar').prop('disabled', false);;
        });

    })

    // PIN Form
    $('#frm-pin').submit(function(e){
        e.preventDefault();

        const sspin     = '.container-pin .lds-ring';
        const sbutton   = '.container-pin #send.button';
        const smessage  = '.container-pin section.message';

		$.ajax({
			url : dcms_fpin.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_validate_pin',
                nonce : dcms_fpin.nonce,
                number: $('#number').val(),
                ref   : $('#ref').val(),
                email : $('#email').val()
			},
            beforeSend: function(){
                $(sspin).show();
                $(sbutton).val('Enviando ...').prop('disabled', true);;
                $(smessage).hide();
            }
        })
        .done( function(res) {
            res = JSON.parse(res);
            show_message(res, smessage);
        })
        .always( function() {
            $(sspin).hide();
            $(sbutton).val('Enviar').prop('disabled', false);;
        });

	});

    // Aux function to show message
    function show_message(res, smessage){
        if (res.status == 0 ) {
            $(smessage).addClass('error');
        } else {
            $(smessage).removeClass('error');
        }

        $(smessage).show().html(res.message);
    }

})(jQuery);