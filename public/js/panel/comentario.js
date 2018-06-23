jQuery(function(){
     tipo_articulo = $("#tipo_articulo").val();
     if(tipo_articulo==1)     $("#comentario1").addClass('menu2');
     if(tipo_articulo==2)     $("#comentario2").addClass('menu2');
     if(tipo_articulo==3)     $("#comentario3").addClass('menu2');
});
function eliminar_comentario(comentario_id, articulo, tipo){
    if(confirm('Esta seguro que desea eliminar este comentario?')){
	url = "/panel/comentario/delete";
	dataString = "comentario_id="+comentario_id;
	$.post(url,dataString,function(){
		location.href="/panel/comentario/editar/tipo/"+tipo+"/id/"+articulo;
	});
    }
}

function verComentarios(tipo, articulo){
	var url = "../../editar/tipo/"+tipo+"/id/"+articulo;
	window.location = url;
}

function verComentariosArchivo(tipo, articulo){
	var url = "../../../../../../editar/tipo/"+tipo+"/id/"+articulo;
	window.location = url;
}

function verArchivoPrincipal(tipo, mes, anio){
	var url = "../../archivo/id/"+tipo+"/mes/"+mes+"/anio/"+anio;
	window.location = url;
}

function verArchivoEditar(tipo, mes, anio){
	var url = "../../../../archivo/id/"+tipo+"/mes/"+mes+"/anio/"+anio;
	window.location = url;
}

function verArchivo(tipo, mes, anio){
	var url = "../../../../../../archivo/id/"+tipo+"/mes/"+mes+"/anio/"+anio;
	window.location = url;
}