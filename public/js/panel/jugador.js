jQuery(function(){
     $("#jugador").addClass("menu2");
});
function crearJugador(){
    url = "/panel/jugador/registro";
    dataString = $("#frmCrearjugador").serialize();
    $.post(url,dataString,function(data){
        location.href="/panel/jugador/";
    });
}

function verJugador(jugador_id){
    window.location = '/panel/jugador/ver?jugador_id=' + jugador_id;
}

function editarJugador(jugador_id){
    window.location = '/panel/jugador/editar?jugador_id=' + jugador_id;
}

function grabarJugador(){
    url = "/panel/jugador/editar";
    dataString = $("#frmEditarJugador").serialize();
    $.post(url,dataString,function(data){
        location.href="/panel/jugador/";
    });
}

function eliminarJugador(jugador_id){
    if( ! confirm("�Est� seguro de eliminar a este jugador?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/jugador/delete",
        data: "jugador_id=" + jugador_id,
        success: function(response){
            if(response.msg == 'OK'){
            	window.location = "/panel/jugador/";
            }else{
            	alert("Existen grupos creados por este jugador");
            }
        }
    });
}

function regresar(){
    window.location = '/panel/jugador/';
}