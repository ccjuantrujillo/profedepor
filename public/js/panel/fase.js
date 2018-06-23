jQuery(function(){
    $("#fase").addClass('menu2');
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

function editarFase(fase_id){
    window.location = '/panel/fase/registro?fase_id=' + fase_id;
}

function eliminarFase(fase_id){
    if( ! confirm("¿Está seguro de eliminar esta fase?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/fase/delete",
        data: "fase_id=" + fase_id,
        success: function(response){
            var torneo = $("#torneo_id").val();
            if(response.msg == 'OK'){
                window.location = "/panel/fase?torneo_id="+torneo;
            }else{
                alert("Existen fechas asociadas");
            }
        }
    });
}

function filterTipo(elem){
    window.location = '/panel/fase?torneo_id=' + $(elem).val();
}
