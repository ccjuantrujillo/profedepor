$(document).ready(function(){    
	$("#articulo").addClass('menu2');
    $('#mensaje').html();
    $("#titulo").focus();    
});

function ingresarNoticia(){	
	var titulo = $('#titulo').val();
	var descripcion = $('#descripcion').val();
	var contenido = $('#contenido').val();
	if(titulo != "" && $.trim(titulo) != '' && descripcion != "" && contenido != ""){		
		url = "/panel/articulo/registro";
		dataString = $("#frmIngresarArticulo").serialize();
		$.post(url,dataString,function(response){			
	        if(response.id != null){
	        	$('#btnIngresarArt').removeAttr('onclick');
	        	location.href="/panel/articulo/ver?id="+response.id;
	        }	        	
		});
	}else{
		if(titulo == ""){
			$('#mensaje').html('Ingrese titulo');
			$("#titulo").focus();
		}else if($.trim(titulo) == ""){
			$('#mensaje').html('Ingrese titulo');
			$("#titulo").val("");
			$("#titulo").focus();
		}else if(descripcion == ""){
			$('#mensaje').html('Ingrese descripcion');
			$('#descripcion').focus();
		}else if(contenido == ""){
			$('#mensaje').html('Ingrese contenido');
			$('#contenido').focus();			
		}
	}
	
}

function actualizarNoticia(){
	var titulo = $('#titulo').val();
	var descripcion = $('#descripcion').val();
	var contenido = $('#contenido').val();
	if(titulo != "" && descripcion != "" && contenido != ""){
		articulo = $('#articulo_id').val();
		url = "/panel/articulo/update";
		dataString = $("#frmUpdateArticulo").serialize();
		$.post(url,dataString,function(){
			location.href="/panel/articulo/ver?id="+articulo;
		});
	}else{
		if(titulo == ""){
			$('#mensaje').html('Ingrese titulo');
			$("#titulo").focus();
		}else if(descripcion == ""){
			$('#mensaje').html('Ingrese descripcion');
			$('#descripcion').focus();
		}else if(contenido == ""){
			$('#mensaje').html('Ingrese contenido');
			$('#contenido').focus();			
		}
	}
	
}

function verNoticia(articulo){
	window.location = "/panel/articulo/ver?id="+articulo;
}

function editarNoticia(articulo){
    window.location = "/panel/articulo/editar?id="+articulo;
}

function editarVerNoticia(articulo){
    window.location = "/panel/articulo/editar?id="+articulo;
}

function eliminarNoticia(articulo_id){
	if( ! confirm("Esta seguro de elimar este articulo?"))
	        return;
	$.ajax({
		type: "POST",
	    url: "/panel/articulo/delete",
	    data: "articulo_id=" + articulo_id,
	    success: function(response){
	    	if(response.msg == 'OK'){
	    		window.location = "/panel/articulo/";
	        }else{                
	            alert("Existen comentarios asociados");
	        }
	    }
	});
}

function filterTipo(elem){
    window.location = '/panel/articulo?tipoarticulo_id=' + $(elem).val();
}

function limita(obj, elEvento, maxi){
    var elem = obj;
    var evento = elEvento || window.event;
    var cod = evento.charCode || evento.keyCode;
    if(cod == 37 || cod == 38 || cod == 39
    || cod == 40 || cod == 8 || cod == 46){
        return true;
    }
    if(elem.value.length < maxi ){return true;}
    return false;
}

function cuenta(obj, evento, maxi, div){
    var elem = obj.value;
    var info = document.getElementById(div);
    if((maxi-elem.length) <= 0){
        info.innerHTML = "Ingresar máximo " + maxi + " caracteres";
    }else{
        info.innerHTML = "";
    }
}