jQuery(function(){
     $("#torneo").addClass('menu2');
});
function editarTorneo(torneo_id){
    window.location = '/panel/torneo/registro?torneo_id=' + torneo_id;
}

function eliminarTorneo(torneo_id){
    if( ! confirm("¿Está seguro de eliminar este campeonato?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/torneo/delete",
        data: "torneo_id=" + torneo_id,
        success: function(response){
            if(response.msg == 'OK'){
            	window.location = "/panel/torneo/";
            }else{
                alert("Existen Fases asociadas");
            }
        }
    });
}