jQuery(function(){
     $(".numeros").number();
     $("#torneo").removeClass('menu2');
});
function grabar_resultado_partido(){
	url = "/panel/resultado/grabar";
	dataString = $("#frmResultadoPartido").serialize();
	$.post(url,dataString,function(data){
		location.href="/panel/resultado";
	});
}
function califica_local(){
    form = $('#frmLocal');
    form.attr('action','/panel/resultado/grabacalificacion');
    form.submit();
}
function califica_visita(){
     $('#box-loader').show();
    form = $('#frmVisita');
    form.attr('action','/panel/resultado/grabacalificacion');
    form.submit();
}
function calcular_puntajes(n){
     a = "fecha["+n+"]";
     fecha_id = document.getElementById(a).value;
     if(confirm('Este proceso tardara unos segundos. Esta seguro que desea continuar?')){
          dataString = "fecha_id="+fecha_id;
          url    = "/panel/puntaje/calcular";
          $.post(url, dataString, function(data){
              if(data){
                  location.href = '/panel/puntaje';
              }
              else{
                  alert('No puede calcular.\n Primero ingrese los resultados de la fecha');
              }
          });
     }
}
function calcular_puntajes_x_partido(n1,n2){
     a = "partido["+n1+"]["+n2+"]";
     partido_id  = document.getElementById(a).value;
     fila_id       = document.getElementById('fila_id').value;
     if(confirm('Este proceso calculara o recalculara el puntaje por partido. Esta seguro que desea continuar?')){
          data = "partido_id="+partido_id;
          url    = "/panel/puntaje/calculardetalle";
          $.post(url, data, function(){
               location.href = '/panel/puntaje/index/id/'+fila_id;
          });
     }
}
function onlynumbers(e){
     key = e.charCode || e.keyCode || 0;
     return (key==8 || key==9 || key==46 || (key>=37 && key<=40) || (key>=48 && key<=57) || (key>=96 && key<=105));
}