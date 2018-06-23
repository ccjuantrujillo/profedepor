(function ($){
    $.fn.center = function () {
        var win = $(window);
        this.css({
            position: 'absolute',
            top: (win.height() - this.height() ) / 2 + win.scrollTop() + "px",
            left: (win.width() - this.width() ) / 2 + win.scrollLeft() + "px"
        });

        return this;
    }

    var win = null;
    $.fn.openWin = function (options){
        return $(this).click(function (){
            var _options = {
                url: '',
                name: 'openWin',
                vars: { menubar: 'no', width: 790, height: 360, toolbar: 'no' }
            };

            if(typeof options == 'string')
                options = { url: options };

            options.vars = $.extend(_options.vars, options.vars || {});
            options = $.extend(_options, options || {});

            var winOptions = [], j = 0;
            for(var i in options.vars){
                winOptions[j] = i + '=' + options.vars[i];
                j++;
            }

            winOptions = winOptions.join(',');
            // return alert(winOptions);
            win = window.open(options.url, options.name, winOptions);
        });
    };
})(jQuery);

$(function (){
    var vars = { width: 542, height: 351 };
    $('.btnShareFb').openWin({
        url: '/facebook/share/', vars: vars
    });

    $('.btnShareTw').openWin({
        url: '/twitter/share/', vars: vars
    });

    $('.btnShareMail').click(function (e){
        var url = '/ayuda/share'
        $.get(url, function (response){
            response = $(response);
            response.modal();

            var form = $('#formShare', response);
            form.validate({
                rules: {
                    my_name: { required: true },
                    my_email: { required: true, email: true },
                    his_name: { required: true },
                    his_email: { required: true, email: true }
                },

                submitHandler: function (){
                    $.post(url, form.serialize(), function (response){
                        // return alert(response);
                        if(response.message == 'ok')
                            $.modal.close();
                            $.get('/ayuda/successmail/', function (response){
                                $(response).modal();
                            });

                        innerError(form, response);
                    });
                }
            });
        });
    });
});

$.ajaxSetup({
    cache: false,
    beforeSend: function (){
        $('#box-loader').center().show();
    },

    complete: function (){
        $('#box-loader').hide();
    }
});

$.extend($.modal.defaults, {
	escClose: false // ,
    // close: false
});

function innerError(form, response){
    if(response.message && response.message.length > 0){
        if(response.elem && response.elem.length > 0)
            $('#' + response.elem, form).focus();

        $('#form-errors', form).html(response.message);
    }

    return false;
}

function viewPopPolitica(){
    $.get('/politica/', function (response){
        response = $(response);
        response.modal();
    });
}