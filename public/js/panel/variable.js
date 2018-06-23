jQuery(function(){
    $("#variable").addClass('menu2');
    var accion = $("#accion").val();
    if(accion == "INS"){
        $("#txt_tipovariable_id-label").hide();
        $("#txt_tipovariable_id-element").hide();
    }
    if(accion == "UPD"){
        $("#tipovariable_id").attr('readonly', 'readonly');
        $("#tipovariable_id-label").hide();
        $("#tipovariable_id-element").hide();
        $("#txt_tipovariable_id-label").show();
        $("#txt_tipovariable_id-element").show();
    }
});

function eliminarVariable(variable_id){
    if( ! confirm("¿Está seguro de elimar esta variable?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/variable/delete",
        data: "variable_id=" + variable_id,
        success: function(response){
            if(response.msg == 'OK'){
                window.location = "/panel/variable/";
            }else{
                alert("Existe intervalo asociado");
            }
        }
    });
}
