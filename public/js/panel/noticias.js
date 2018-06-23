function mostrar_noticia(articulo_id){
	url = "/panel/articulo/ver";
	dataString = "articulo_id="+articulo_id;
	$.post(url,dataString,function(response){
            tinyMCE.activeEditor.setContent(response);
	});
}

function ingresar_noticia(){
	url = "/panel/articulo/guardar";
	dataString = $("#frmIngresarArticulo").serialize();
	$.post(url,dataString,function(response){
            if(response.id != null)
		location.href="/panel/articulo/ver";
	});
}

function actualizar_noticia(){
	url = "/panel/articulo/update";
	dataString = $("#frmUpdateArticulo").serialize();
	$.post(url,dataString,function(){
		location.href="/panel/articulo/editar";
	});
}

function eliminar_noticia(articulo_id){
    if(confirm('Está seguro que desea eliminar esta noticia?')){
	url = "/panel/articulo/delete";
	dataString = "articulo_id="+articulo_id;
	$.post(url,dataString,function(){
		location.href="/panel/articulo/editar";
	});
    }
}