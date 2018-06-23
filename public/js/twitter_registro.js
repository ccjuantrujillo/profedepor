$(function (){
    $('#existing_account').click(function (){
        $('#box-password').toggle();
    });

    $('#btnConfirmTwitter').click(function (){
        var params = $('#formTwitter').serialize();
        $.post('/twitter/registro/', params, function (response){
            if(response.message != 'ok')
                return;

            openTerminos()
        });
    })
});