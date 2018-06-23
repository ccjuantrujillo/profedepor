$(function (){
    $('#login_email').focus();

    $('#formLogin').validate();

    $('#btnRegistrate').click(function (){
        var url = '/auth/registro/';

        $.get(url, function (response){
            response = $(response);
            response.modal();
            var form = $('#formRegistro');

            form.validate({
                rules: {
                    confirm_email: { required: true, equalTo : '#email' },
                    password: { required: true, minlength: 6 },
                    confirm_password: { required: true, equalTo: '#password' }
                },

                messages: {
                    confirm_email: { equalTo: 'Los correos no coinciden' },
                    confirm_password: { equalTo: 'Las claves no coinciden' }
                },

                submitHandler: function (){
                    var params = form.serialize();
                    $.post(url, params, function (response){
                        if(response.message == 'ok'){
                            $.modal.close();
                            $.get('/auth/success/', function (response){
                                $(response).modal();
                            });

                            return false;
                        }

                        // show error message
                        innerError(form, response);
                    });
                }
            });
        });
    });

    $('#btnGoogle').openWin({
        url: '/google/redirect?type=login',
        vars: { height: 400 },
        name: 'Google'
    });

    $('#btnTwitter').openWin({
        url: '/twitter/redirect?type=login',
        name: 'Twitter'
    });

    $('#btnFacebook').openWin({
        url: '/facebook/redirect?type=login',
        name: 'Facebook'
    });
});

function recoverPassword(){
    var url = '/auth/recoverpass/';
    $.get(url, function (response){
        response = $(response);
        response.modal();

        var form = $('#formRPassword', response);

        form.validate({
            submitHandler: function (){
                var params = form.serialize();

                $.post(url, params, function (response){
                    if(response.message == 'ok')
                        return $.modal.close();

                    // show error message
                    innerError(form, response);
                });
            }
        });
    });
}

function openTerminos(modal){
    $.get('/politica/red/', function (response){
        response = $(response);
        modal && modal.html ? modal.html(response) : response.modal();
        response.find('#btnConfirm').click(function (){
            $('#formConfirm').submit();
        });
    });
}

function openRegistroTwitter(){
    var url = '/twitter/registro/';
    $.get(url, function (response){
        response = $(response);
        var modal = response.modal();

        var form = $('#formTwitter');
        form.validate({
            rules: {
                email: { required: true, email: true }
            },

            submitHandler: function (){
                $.post(url, form.serialize(), function (response){
                    if(response.message != 'ok')
                        return innerError(form, response);

                    $.modal.close();
                    openTerminos();
                });
            }
        });
    });
}