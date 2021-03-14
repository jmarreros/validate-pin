(function($){

    $("#frm-pin").submit(function(e){
        e.preventDefault();

		$.ajax({
			url : dcms_vars.ajaxurl,
			type: 'post',
			data: {
				action : 'dcms_ajax_validate_pin',
                nonce : dcms_vars.nonce,
                number: $('#number').val(),
                ref   : $('#ref').val(),
                email : $('#email').val()
			},
            beforeSend: function(){
                $('.lds-ring').show();
                $('#send.button').val('Enviando ...').prop('disabled', true);;
                $('section.message').hide();
            }
        })
        .done( function(res) {
            res = JSON.parse(res);
            console.log(res);
            show_message(res);
        })
        .always( function() {
            $('.lds-ring').hide();
            $('#send.button').val('Enviar').prop('disabled', false);;
        });

	});

    // Aux function to show message
    function show_message(res){
        if (res.status == 0 ) {
            $('section.message').addClass('error');
        } else {
            $('section.message').removeClass('error');
        }

        $('section.message').show().html(res.message);
    }

})(jQuery);