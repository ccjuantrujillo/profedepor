jQuery(function(){
    $("#intervalo").addClass('menu2');
    $("#variable_id").wrap("<span id='selvariable'/>");
    var accion = $("#accion").val();
    if(accion == "INS"){
        $("#txt_variable_id-label").hide();
        $("#txt_variable_id-element").hide();
        $("#txt_tipovariable_id-label").hide();
        $("#txt_tipovariable_id-element").hide();
    }
    if(accion == "UPD"){
        $("#variable_id").attr('readonly', 'readonly');
        $("#variable_id-label").hide();
        $("#variable_id-element").hide();
        $("#tipovariable_id").attr('readonly', 'readonly');
        $("#tipovariable_id-label").hide();
        $("#tipovariable_id-element").hide();
        $("#txt_variable_id-label").show();
        $("#txt_variable_id-element").show();
        $("#txt_tipovariable_id-label").show();
        $("#txt_tipovariable_id-element").show();
    }
});
function EditarIntervalo(intervalo_id){
    tipovariable_id = $("#tipovariable_id").val();
    variable_id         = $("#variable_id").val();
    window.location = '/panel/intervalo/registro?tipovariable_id='+tipovariable_id+'&variable_id='+variable_id+'&id='+intervalo_id;
//     $.post('/panel/partido/verpronostico','partido_id='+partido_id,function(data){
//          if(!data){
//               alert('Este partido no se puede Editar, ya tiene pronosticos');
//               window.location = '/panel/partido?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id;
//          }
//          else{
//               window.location = '/panel/partido/registro?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id+'&partido_id=' + partido_id;
//          }
//     });

}
function EliminarIntervalo(intervalo_id){
    tipovariable_id = $("#tipovariable_id").val();
    variable_id         = $("#variable_id").val();
    if( ! confirm("Esta seguro de elimar este intervalo?"))  return;
    $.post('/panel/intervalo/delete','id='+intervalo_id,function(data){
        window.location = '/panel/intervalo?tipovariable_id='+tipovariable_id+'&variable_id='+variable_id;
    });

}

function eliminarIntervalo(intervalo_id){
    if( ! confirm("¿Está seguro de elimar este intervalo?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/intervalo/delete",
        data: "intervalo_id=" + intervalo_id,
        success: function(response){
            if(response.msg == 'OK'){
                window.location = "/panel/intervalo/";
            }else{
                alert("Existe juego asociado");
            }
        }
    });
}

function filterTipoVar(elem){
    tipoVar = $(elem).val();
    $.post('/panel/intervalo/selvariable', "tipovariable_id="+tipoVar,function(data){
        $("#selvariable").html(data);
    })
}