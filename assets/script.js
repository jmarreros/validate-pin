(function($){

    $("#frm-pin").submit(function(e){
        e.preventDefault();

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
                $('.container-pin .lds-ring').show();
                $('.container-pin #send.button').val('Enviando ...').prop('disabled', true);;
                $('section.message').hide();
            }
        })
        .done( function(res) {
            res = JSON.parse(res);
            show_message(res);
        })
        .always( function() {
            $('.container-pin .lds-ring').hide();
            $('.container-pin #send.button').val('Enviar').prop('disabled', false);;
        });

	});

    // Aux function to show message
    function show_message(res){
        if (res.status == 0 ) {
            $('.container-pin section.message').addClass('error');
        } else {
            $('.container-pin section.message').removeClass('error');
        }

        $('.container-pin section.message').show().html(res.message);
    }

})(jQuery);