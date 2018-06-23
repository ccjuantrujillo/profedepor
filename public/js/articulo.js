$(function(){
    $("#btnEnviarCom").click(function() {
        $("#msgVoto").html('');
        var comentario = $("#comentario").val();
        var articulo_id = $("#articulo_id").val();
        var tipoarticulo_id = $("#tipoarticulo_id").val();
        var dataString = 'comentario=' + comentario + '&articulo_id=' + articulo_id + '&tipoarticulo_id=' + tipoarticulo_id;

        if(comentario=='')
        {
            $("#mostrar").html("Ingrese un comentario");
            setTimeout("$('#mostrar').hide();", 5000);
            $("#comentario").focus();
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "/articulo/guardar",
                data: dataString,
                cache: false,
                success: function(html){
                    $("#resultado").empty();
                    $("#comentario").val("");
                    $("#resultado").append(html);
                    $("#contador").html("");
                }
            });
        }
        return false;
    });
});

function votarTipo(articulo_id, tipo){
    $.post('/articulo/votar', {
        'articulo_id' : articulo_id,
        'tipo' : tipo
    }, function (response){
        if(response.dhora != 0){
            $(".cont"+tipo).empty();
            $(".cont"+tipo).append(response.total);
        }else{
            $("#msgVoto").html('Ya registro su voto el dia de hoy.');
            setTimeout("$('#msgVoto').hide();", 5000);
        }
        $("#val1").removeAttr('onclick');
        $("#val2").removeAttr('onclick');
        $("#val3").removeAttr('onclick');
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
        info.innerHTML = "Ingresar máximo " + maxi + " caracteres";
    }else{
        info.innerHTML = "";
    }    
}