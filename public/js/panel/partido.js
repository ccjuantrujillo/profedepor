  jQuery(function() {
       $("#partido").addClass('menu2');
     $("#hora_partido").timePicker({
          step : 15
     });
     $("#fase_id").wrap("<span id='selfase'/>");
     $("#torneo_id").wrap("<span id='seltorneo'/>");
     $("#fecha_id").wrap("<span id='selfecha'/>");
     $("input:checkbox:checked").each(function(){
          this.value=1;
     });
     $("form.jqtransform").jqTransform();
     $("#equipo_local").change(function(){
          $("#equipo_visitante").find("option[value="+this.value+"]").remove();
     });
     $("#torneo_id-label").hide();
      $("#torneo_id-element").hide();
       $("#fase_id-label").hide();
       $("#fase_id-element").hide();
       $("#fecha_id-label").hide();
       $("#fecha_id-element").hide();
  });

function NuevoPartido(){
     torneo_id = $("#torneo_id").val();
     fase_id      = $("#fase_id").val();
     fecha_id   = $("#fecha_id").val();
    window.location = '/panel/partido/registro?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id+'&partido_id=';
}

function EditarPartido(partido_id){
     torneo_id = $("#torneo_id").val();
     fase_id      = $("#fase_id").val();
     fecha_id   = $("#fecha_id").val();
     $.post('/panel/partido/verpronostico','partido_id='+partido_id,function(data){
          if(!data){
               alert('Este partido no se puede Editar, ya tiene pronosticos');
               window.location = '/panel/partido?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id;
          }
          else{
               window.location = '/panel/partido/registro?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id+'&partido_id=' + partido_id;
          }
     });
  
}

function EliminarPartido(partido_id){
     torneo_id = $("#torneo_id").val();
     fase_id      = $("#fase_id").val();
     fecha_id   = $("#fecha_id").val();
     if( ! confirm("Esta seguro de elimar este partido?"))  return;
     $.post('/panel/partido/delete','partido_id='+partido_id,function(data){
          if(!data){
               alert('Este partido no se puede borrar pues ya tiene pronosticos');
          }
          window.location = '/panel/partido?torneo_id='+torneo_id+'&fase_id='+fase_id+'&fecha_id='+fecha_id;
     });
     //window.location = '/panel/partido/delete?partido_id=' + partido_id;
//     form = $('#FormPanel');
//     old_action_form = form.attr('action');
//      form.find('#partido_id').val(partido_id);
//      form.attr('action', '/panel/partido/delete');
//      form.submit();
}

function filterTorneo(elem){
     torneo = $(elem).val();
     $.post('/panel/partido/selfase', "torneo_id="+torneo,function(data){
          $("#selfase").html(data);
          fase = $("#fase_id");
          filterFase(fase);
     });
}

function filterFase(elem){
     torneo = $("#torneo_id").val();
     fase      = $(elem).val();
     $.post('/panel/partido/selfecha', "torneo_id="+torneo+"&fase_id="+fase,function(data){
          $("#selfecha").html(data);
          fecha = $("#fecha_id");
          filterFecha(fecha);
     });
}

function filterFecha(elem){
     torneo = $("#torneo_id").val();
    fase = $('#fase_id').val();
    fecha = $('#fecha_id').val();
     $.post('/panel/partido/verpartidos', "torneo_id="+torneo+"&fase_id="+fase+"&fecha_id="+fecha,function(data){
        $("#contenedor").html(data);
     });
}