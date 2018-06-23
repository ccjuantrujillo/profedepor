$(function (){
    $.get('/ayuda/primero/', function (response){
        response = $(response);
        response.modal();
        response.find('#next').click(function (){
            $.modal.close();
            $.get('/ayuda/segundo/', function (response){
                $(response).modal();
            });
        });
    });
});