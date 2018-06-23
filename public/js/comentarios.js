$(function(){
    $("#frmMensajes")[0].reset();
    $("#contador").html("");
    $("#comprobar").html("");

    $("#btnPublicar").click(function() {
        var mensaje = $("#mensaje").val();
        var grupo_id = $("#grupo_id").val();
        var dataString = 'mensaje=' + mensaje + '&grupo_id=' + grupo_id;

        if(mensaje == "")
        {
            $("#comprobar").html('Ingrese un comentario');
            setTimeout("$('#comprobar').hide();", 5000);
            $("#mensaje").focus();
        }
        else
        {
            $.ajax({
                type: "POST",
                url: "/grupo/guardar",
                data: dataString,
                cache: false,
                success: function(html){
                    $("#resultado").empty();
                    document.getElementById('mensaje').value='';
                    $("#resultado").append(html);
                    $("#comprobar").html("");
                    $("#contador").html("");
                }
            });
        }
        return false;
    });

    $('.btnNormal').live("click",function(){
        var ID = $(this).attr("id");
        var grupo_id = $("#grupo_id").val();
        var dataString = 'lastmuro_id=' + ID + '&grupo_id=' + grupo_id;
        if(ID){
            $.ajax({
                type: "POST",
                url: "/grupo/mostrar/",
                data: dataString,
                cache: false,
                success: function(html){
                    $("#more"+ID).hide('slow');
                    $("#mascomentarios").append(html);
                }
            });
        }else{
            $(".morebox").html('The End');
        }
        return false;
    });
    
});