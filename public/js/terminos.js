function openTerminos(){
    $.get('/politica/red/', function (response){
        response = $(response);
        response.modal();
        response.find('#btnConfirm').click(function (){
            $('#formConfirm').submit();
        });
    });
}