$(function (){
    var url = '/jugador/popcambiarpass/';
    $.get(url, function (response){
        response = $(response);
        response.modal({ close: false });
        var form = $('#formCambiarPass', response);

        form.validate({
            rules: {
                new_password: { required: true, minlength: 6 },
                confirm_password: { required: true, minlength: 6, equalTo: '#new_password' },
            },

            messages: {
                confirm_password: "Las Claves no coinciden",
            },

            submitHandler: function (){
                $.post(url, form.serialize(), function (response){
                    if(response.message == 'ok')
                        return $.modal.close();

                    // show error message
                    innerError(form, response);
                });
            }
        });
    });
});