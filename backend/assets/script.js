(function( $ ) {
	'use strict';

    //Resend email new user, change seats
    // ------------------------------------
    $('.dcms-table .resend').click(function(e){
        e.preventDefault();

        const email = $(e.target).data('email');
        const identify = $(e.target).data('identify');
        const pin = $(e.target).data('pin');

        const confirmation = confirm("¿Reenviar correo a: " + email + "?");

        if ( confirmation ){
            $.ajax({
                url : dcms_admin_pin.ajaxurl,
                type: 'post',
                data: {
                    action: 'dcms_resend_pin',
                    nonce   : dcms_admin_pin.nonce,
                    email,
                    identify,
                    pin
                }
            })
            .done( function(res) {
                res = JSON.parse(res);
                if (res.status == 0){
                    alert('Hubo algún error al enviar el correo');
                }else {
                    $(e.target).text('✅');
                }
            });
        }

    });

})( jQuery );


