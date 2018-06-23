jQuery(function(){
    $("#fecha").addClass('menu2');
    var accion = $("#accion").val();
    if(accion == "INS"){
        $("#txt_torneo_id-label").hide();
        $("#txt_torneo_id-element").hide();
        $("#txt_fase_id-label").hide();
        $("#txt_fase_id-element").hide();
    }
    if(accion == "UPD"){
        $("#torneo_id").attr('readonly', 'readonly');
        $("#torneo_id-label").hide();
        $("#torneo_id-element").hide();
        $("#fase_id").attr('readonly', 'readonly');
        $("#fase_id-label").hide();
        $("#fase_id-element").hide();
        $("#txt_torneo_id-label").show();
        $("#txt_torneo_id-element").show();
        $("#txt_fase_id-label").show();
        $("#txt_fase_id-element").show();
    }
});

function nuevaFecha(){
    var torneo_id = $("#torneo_id").val();
    var fase_id   = $("#fase_id").val();
    window.location = '/panel/fecha/registro?torneo_id='+torneo_id+'&fase_id='+fase_id;
}

function editarFecha(fecha_id){
    window.location = '/panel/fecha/registro?fecha_id=' + fecha_id;
}

function filterTorneo(elem){
    var torneo = $(elem).val();
    $.post('/panel/fecha/selfase', "torneo_id="+torneo,function(data){
        $("#selfase").html(data);
        var fase = $("#fase_id");
        filterFase(fase);
    });
}

function filterFase(elem){
    var torneo = $("#torneo_id").val();
    var fase   = $(elem).val();
    $.post('/panel/fecha/verfechas', "torneo_id="+torneo+"&fase_id="+fase,function(data){
        $("#contenedor").html(data);
    });
}

function eliminarFecha(fecha_id){
    var torneo_id = $("#torneo_id").val();
    var fase_id   = $("#fase_id").val();
    if( ! confirm("Esta seguro de elimar esta fecha?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/fecha/delete",
        data: "fecha_id=" + fecha_id,
        success: function(response){
            if(response.msg == 'OK'){
                window.location = '/panel/fecha?torneo_id='+torneo_id+'&fase_id='+fase_id;
            }else{                
                alert("Existen partidos asociados");
            }
        }
    });
}