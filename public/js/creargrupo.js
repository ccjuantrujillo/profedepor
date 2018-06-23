$(document).ready(function(){
    $("#frmCrearMancha")[0].reset();
    $("#nombre").focus();
    $("#nombre").change(function() {
        var nomg = $("#nombre").val();
        if(nomg.length >= 4){
            $.ajax({
                type: "POST",
                url: "/grupo/comprobar",
                data: "nomgrupo="+ nomg,
                success: function(res){
                   $("#status").ajaxComplete(function(event, request, settings){
                        if(res.msg == 'OK'){
                            $("#comprobar").html('&nbsp');
                            $(this).html('&nbsp;<img src="/images/tick.gif" align="absmiddle">');
                            $("#descripcion").focus();
                        }else{
                            $("#comprobar").html(res.msg);
                            $("#nombre").focus();
                        }
                   });
                }
            });
        }else{
            $("#comprobar").html('El nombre de grupo debe tener al menos <strong>4</strong> caracteres.</font>');
        }
    });
    //Subir foto
    new AjaxUpload('#btnSubirFoto', {
        name: 'fileFoto',
        action: '/grupo/registrofoto/',
        onSubmit : function(file, ext){
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                //alert('Error: Solo se permiten imagenes');
                return false;
            } 
        },        
        onComplete: function(file, response){
            $('#fileFoto').attr('value', response);
            $('#fotoMancha').attr('src', '/fotos/grupo/' + response);
        }
    });
});

function crearGrupo(){
    var nomg = $("#nombre").val();
    var desc = $("#descripcion").val();
    var opci = $("input[name='tipo']:checked").val();

    if((nomg != "") && (desc != "") && (opci !== undefined)){
        url = "/grupo/registro";
        dataString = $("#frmCrearMancha").serialize();
        $.post(url,dataString,function(data){
            if(data!=0){
                grupoadmin = data.grupo;
                url2 = "/grupo/registropop";
                $.post(url2,"grupoadmin="+grupoadmin, function (response){
                     $(response).modal({onClose:function(){}});
                });
            }
        });
    }else{        
        if(nomg == ""){
            $("#comprobar").html("Ingresar un nombre");
            $("#nombre").focus();
        }else if(desc == ""){
            $("#comprobar").html("Ingresar descripcion");
            $("#descripcion").focus();
        }else if(opci === undefined){
            $("#comprobar").html("Seleccionar tipo");
        }
    }
}
