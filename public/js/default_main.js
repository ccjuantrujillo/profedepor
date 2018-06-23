(function (){
    var ajax = $.ajax, isLoginTime = $.now(),
               expirationMiliSeconds = expimin * 1000 * 60;

    $.ajax = function (url, options){
        if(($.now() - isLoginTime) < expirationMiliSeconds)
            jXHR = ajax(url, options);
        else {
            var _ajax = $.ajaxSettings.xhr(), jXHR = null;
            $('#box-loader').center().show();
            _ajax.open('get', '/auth/islogin', false);
            _ajax.onreadystatechange = function (){
                if(_ajax.readyState == 4){
                    var response = $.parseJSON(_ajax.responseText);
                    if(response && response.message && response.message == 'error'){
                        document.location = '/';
                        return false;
                    }

                    jXHR = ajax(url, options);
                }
            };

            _ajax.send(null);
        }

        isLoginTime = $.now();
        return jXHR;
    };
})();

function bindFbFriends(container){
    $('.masc_friend.onclick', container).click(function (e){
        var _this = $(this),
            checkbox = _this.children(':checkbox:first'),
            flag = ( ! checkbox.attr('checked'));

        _this[flag ? 'addClass' : 'removeClass']('checked');
        checkbox.attr('checked', flag);
    }).removeClass('onclick');
}

function getRedFriendsModal(){
    $.modal.close();
    $.get('/red/getfriendsmodal/', function (response){
        response = $(response);
        response.modal();
        bindFbFriends(response);
    });
}

function getRedFriends(pag){
    $.get('/red/getfriends', {
        pag: pag
    }, function (response){
        $('#btnVerMas').remove();
        var container = $('#fb-friends');
        container.append(response);
        bindFbFriends(container);
    });
}

function publishRedFriends(){
    datos = $('#formFbFriends').serialize();
    $.post('/red/publish', $('#formFbFriends').serialize(), function (response){
        $.modal.close();
        // alert(response);
    });
}

function bindRedLinks(container){
    $('#btnFbLink.activo', container).openWin({
        url: '/facebook/redirect?type=link',
        name: 'Facebook'
    });

    $('#btnTwLink.activo', container).openWin({
        url: '/twitter/redirect?type=link',
        name: 'Twitter'
    });

    $('#btnGgLink.activo', container).openWin({
        url: '/google/redirect?type=link',
        vars: { height: 400 },
        name: 'Google'
    });
}

function NetWorkLink(){
    $.get('/jugador/link', function (response){
        var container = $('#containerLinks');

        container.html(response);
        bindRedLinks(container);
    });
}

function cambiar_departamento(self){
    $.get('/ubigeo/provincia/', {
        departamento: $(self).val()
    }, function (response){
        $('#provincia-label').html(response.html);
        cambiar_provincia();
    });
}

function cambiar_provincia(self){
    $.get('/ubigeo/distrito/', {
        departamento: $('#departamento').val(),
        provincia: $(self).val()
    }, function (response){
        $('#distrito-label').html(response.html);
    });
}

function sendDatosJugador(url, form){
    // guardar datos jugador
    $.post(url, form.serialize(), function(response){
        // return alert(response);
        $('#foto', form).val('');
        if(response.message != 'ok')
            return innerError(form, response);

        $.modal.close();

         $.get('/jugador/actualizado/', function (response){
             response = $(response);
             response.modal();
             $('#btnClose', response).click(function (e){
                 $.modal.close();
             });
         });

        $.get('/jugador/loggedinas/', function (response){
            $('#loggedinas').html(response);
        });
    });
}

function openWindowDatosJugador(){
    var url = '/jugador/editar/';
    $.get(url, function (response){
        // return alert(response);

        response = $(response);
        response.modal();

        bindRedLinks(response);

        // return alert($('#telefono').size());

        var form = $('#formDatosJugador', response);
        $('#numero_doc, #telefono, #dni_apoderado', form).number();

        form.validate({
            rules: {
                telefono: {
                    digits: true,
                    maxlength: 9,
                    minlength: 7
                },

                numero_doc: {
                    required: true,
                    minlength: 8,
                    maxlength: 8
                },

                dni_apoderado: {
                    minlength: 8,
                    maxlength: 8
                }
            },

            submitHandler: function (){
                sendDatosJugador(url, form);
            }
        });

        // agregar emails
        $('#btnAddEmail', form).click(function (e){
            var url_email = '/jugador/email',
                email = $('#otro_email');

            $.post(url_email, { otro_email: email.val() }, function (response){
                if(response.message != 'ok')
                    return innerError(form, response);

                email.val('');
                $.get(url_email, function (response){
                    $('#container-emails', form).html(response);
                });
            });
        });

        $('#btnGuardarDatosJugador', response).click(function (){
            form.submit();
        });

        new AjaxUpload('#btnJugadorFoto', {
            // autoSubmit: false,
            // responseType: 'json',
            name: 'fotoJugador',
            action: '/jugador/registrofoto/',
            onSubmit : function(file, ext){
                if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                    var response = { message : 'Error: Solo se permiten imagenes' };
                    // alert(response.message);
                    return false;
                }
            },

            onChange: function (file, extension){},

            onComplete: function(file, response){
                $('#foto', form).val(response);
                $('#fotoJugador').attr('src', '/fotos/' + response + '?l=' + Math.floor(Math.random()*11));
            }
        });
    });
}

function guardarDatosJugador(){
    $.modal.close();
}

function viewPopAyuda(){
    $.get('/ayuda/modal/', function (response){
        response = $(response);
        response.modal();
    });
}

function viewPopMapa(){
    $.get('/ayuda/mapa/', function (response){
        response = $(response);
        response.modal();

        response.find('#lnkAyuda').click(function (e){
            $.modal.close();
            viewPopAyuda();
        });

        response.find('#lnkPolitica').click(function (e){
            $.modal.close();
            viewPopPolitica();
        });

        response.find('#lnkEditarPerfil').click(function (e){
            $.modal.close();
            openWindowDatosJugador();
        });
    });
}