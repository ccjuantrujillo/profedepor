function cambiar_provincia(self){
    $.get('/ubigeo/distrito/', {
        departamento: $('#departamento').val(),
        provincia: $(self).val()
    }, function (response){
        $('#distrito-label').html(response.html);
    });
}

$(function (){
    $('#departamento').change(function (){
        $.get('/ubigeo/provincia/', {
            departamento: $(this).val()
        }, function (response){
            $('#provincia-label').html(response.html);
            var provincia = $('#provincia');
            cambiar_provincia();
        });
    });

    $('#registro').validate();
});