/**
 * @author Russell Kid Benancio Flores
 * @since 07/04/2011 11:50am
 * @email ruzzll@hotmail.com
 * @version 1.0
 */
(function ($){
    // MENSAJES ERROR
    var messages = {
        required: "El campo %elem% es obligatorio",
        // email: "El campo %elem% es incorrecto",
        email: "Escribe un correo válido, por ejemplo: micorreo@mail.com",
        number: "El campo %elem% solo admite números",
        // number: "Por favor, escribe un número entero válido.",
        equalTo: "Por favor, escribe el mismo valor de nuevo.",
        // digits: "Por favor, escribe sólo dígitos.",
        // minlength: "Por favor, no escribas menos de %param% caracteres."
        minlength: "El campo %elem% no es correcto"
        // remote: ""
    };

    // Filtros
    var filters = {
        required: function(el){
            return (el.val() != '' && el.val() != -1);
        },
        email: function(el){
            return /^[A-Za-z][A-Za-z0-9_\-\.]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/.test(el.val());
        },
        number: function(el){
            return /^\d*$/.test(el.val());
        },
        equalTo: function (el, param){
            var target = $(param);
            return el.val() == target.val();
        },
        minlength: function (el, param){
            var rt = ! filters.required(el) || $.trim(el.val()).length >= param;
            if( ! rt)
                messages.minlength = messages.minlength.replace('%param%', param);

            return rt;
        }// ,
        // remote: function (el, param){
        //    console.log(arguments);
        // }
    };

    // Extensiones
    $.extend({
    	stop: function(e){
            if (e.preventDefault) e.preventDefault();
            if (e.stopPropagation) e.stopPropagation();
        }
    });

    $.fn.validate = function (options){
        var _default = {
            filters: filters,
            messages: {},
            rules: {},
            submitHandler: null,
            containerErrorId: 'form-errors',
            prefixLabel: 'label-'
        };

        options = $.extend(_default, options || {});

        var form = $(this),
            elements = form.find(":text, :password, textarea, select");

        var formPress = 'formPress';
        if(0 >= $(':submit', form).size()){
            if(formPress in $.fn)
                form[formPress]();

            var btn = $('#submit', form);
            if(btn.size() > 0){
                btn.click(function (){
                    form.submit();
                });
            }
        }

        var content_error = $('#' + options.containerErrorId, form);
        if(0 >= content_error.size())
            form.prepend('<div id="' + options.containerErrorId + '"></div>');

        // leer reglas, mensajes y añadir eventos keypress
	    elements.each(function(i){
	        var elem = $(this),
                id = elem.attr('id');

	        if (elem.attr("className") != 'undefined') {
	            options.rules[id] = options.rules[id] || {};
    	        $.each(new String(elem.attr("className")).split(/\s+/), function(x, klass){
    	           if(klass.length > 0){
                       options.rules[id][klass] = true;

                       if(klass in $.fn)
                           elem[klass]();
                   }
    	        });
	        }
	    });

        form.bind("submit", function(e){
    		if (typeof options.filters == 'undefined') return;

            var break2 = false,
                isValid = false,
                error = '',
                label = '';

            elements.each(function (){
                var elem = $(this),
                    id = elem.attr('id');

                $.each(options.rules[id], function (filter, param){
                    // break2 = true;
                    if ($.isFunction(options.filters[filter]) && param){
                        if( ! options.filters[filter](elem, param)){
    	                   try {
    	                       label = $('#' + options.prefixLabel + id, form).html();
                               label = label.replace(/:+/, '');
                               label = label.replace(/\r\n+/, '');
                               label = label.replace(/<br\s*(\/*)>+/i, ' ');
    	                   } catch(e){}

                           var m = options.messages[id], message = '';
                           if(m && m[filter])
                               message = m[filter];

                           if($.isFunction(message))
                               message = message(elem, param, label);
                           else if(0 >= new String(message).length)
                               message = messages[filter].replace('%elem%', "'" + label + "'");

    	                   content_error.html(message);
    	                   elem.focus();

                           break2 = true;
                           isValid = false;
                           return false;
                        }
                    }
                });

                if(break2) return false;

                isValid = true;

            });

    		if ( ! isValid){
    			$.stop(e || window.event);
    			return false;
    		}

            if(isValid && $.isFunction(options.submitHandler)){
    			$.stop(e || window.event);
                content_error.html('');
                options.submitHandler.call(form);
    			return false;
            }

    	    return true;
    	});

        return form;

    }
})(jQuery);