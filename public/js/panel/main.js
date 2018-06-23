var form = null,
    form_action = null,
    form_action_rpl = null,
    hiddenId = 'id';

$(function (){
    form = $('#FormPanel');
    if(0 >= form.size())
        return false;

    // form.focus();
    form_action = form.attr('action');
    form_action_rpl = form_action.replace(/\/$/, '');

    window['Editar'] = function (id){
        window.location = form_action + 'registro?id=' + id;
    };

    window['Eliminar'] = function (id){
        if( ! confirm("Esta seguro de eliminar este registro?"))
            return;

        $('#' + hiddenId, form).val(id);
        form.attr('action', form_action + 'delete');

        // send
        form.submit();
    };

    form.prepend('<input type="hidden" id="' + hiddenId + '" name="' + hiddenId + '" />');
    form.bind('change', function (e){
        window.location = form_action_rpl + '?' + $(':enabled:not(#' + hiddenId + ')', form).serialize();
    });
});