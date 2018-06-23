$(function (){
    $('#q').focus();
    var form = $('#formBuscar');
    form.validate();
    $('#btnBuscar').click(function (e){
        form.submit();
    });
});

function unirseGrupo(grupo_id){    
    $.post('/grupo/unirte',{
        'grupoid' : grupo_id
    },function(data){
        url2 = "/grupo/unirtepop";
        $.post(url2,"mensaje="+data.msg, function (response){
            $(response).modal({onClose:function(){}});
        });
    });
}

function limita(obj, elEvento, maxi){
    var elem = obj;
    var evento = elEvento || window.event;
    var cod = evento.charCode || evento.keyCode;
    if(cod == 37 || cod == 38 || cod == 39
    || cod == 40 || cod == 8 || cod == 46){
        return true;
    }
    if(elem.value.length < maxi ){return true;}
    return false;
}

function cuenta(obj, evento, maxi, div){
    var elem = obj.value;
    var info = document.getElementById(div);
    if((maxi-elem.length) <= 0){
        info.innerHTML = "Ingresar maximo " + maxi + " caracteres";
    }else{
        info.innerHTML = "";
    }
}

function verSolicitud(id){
    url = "/grupo/aprobarpop";
    dataString = "grupo_id="+id;
    $.post(url,dataString, function (response){
        $(response).modal();
        $("#btnAprobarSolicitud").click(function(){
                       data = $("#frmAprobarSolicitud").serialize();
             $.modal.close();
                       url2 = "/grupo/confirmarpop";
                       $.post(url2,data, function (response2){
                           $(response2).modal();
                       });
             });
    });
}
 
$( function() {
    $( '.derLink' ).live( 'click', function() {
        $( '.cb-element' ).each( function() {
                $( this ).attr( 'checked', $( this ).is( ':checked' ) ? '' : 'checked' );
        }).trigger( 'change' );

    });
    $( '.cb-element' ).live( 'change', function() {
        $( '.cb-element' ).length == $( '.cb-element:checked' ).length ? $( '.checkAll' ).attr( 'checked', 'checked' ).next().text( 'Uncheck All' ) : $( '.checkAll' ).attr( 'checked', '' ).next().text( 'Check All' );

    });
});
