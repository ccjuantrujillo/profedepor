jQuery(function(){
    $("#equipo").addClass('menu2');
    $("#club_id").wrap("<span id='selclub'/>");
    var accion = $("#accion").val();
    if(accion == "INS"){
        $("#txt_torneo_id-label").hide();
        $("#txt_torneo_id-element").hide();
    }
    if(accion == "UPD"){
        $("#torneo_id").attr('readonly', 'readonly');
        $("#torneo_id-label").hide();
        $("#torneo_id-element").hide();
        $("#txt_torneo_id-label").show();
        $("#txt_torneo_id-element").show();
    }
});
function editarEquipo(equipo_id){
    window.location = '/panel/equipo/registro?equipo_id=' + equipo_id;
}

function filterTipo(){
    var torneo = $('#torneo_id').val();
    var fase = $('#fase_id').val();
    window.location = '/panel/equipo?torneo_id=' + torneo + '&fase_id=' + fase;
}

function filterTipoTorneo(elem){
    window.location = '/panel/equipo?torneo_id=' + $(elem).val();
}

function filterTipoClub(elem){
    window.location = '/panel/equipo?club_id=' + $(elem).val();
}

function filterTorneo(obj){
   url="/panel/equipo/selclub";
   dataString = $("form").serialize();
   alert(dataString);
   $.post(url, dataString,function(data){
      $("#selclub").html(data);
   });
}

function eliminarEquipo(equipo_id){
    if( ! confirm("¿Está seguro de eliminar este equipo?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/equipo/delete",
        data: "equipo_id=" + equipo_id,
        success: function(response){
            var torneo = $("#torneo_id").val();
            if(response.msg == 'OK'){
            	window.location = "/panel/equipo?torneo_id="+torneo;
            }else{
                alert("Existen partidos asociados");
            }
        }
    });
}