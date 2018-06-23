$(document).ready(function(){
     $("#club").addClass('menu2');
    new AjaxUpload('#btnSubirImagen', {
        name: 'fotoClub',
        action: '/panel/club/registroimagen/',
        onSubmit : function(file, ext){
            if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){
                //alert('Error: Solo se permiten imagenes');
                return false;
            }
        },
        onComplete: function(file, response){
            $('#imagenClub').attr('value', file);
            $('#fotoClub').attr('value', response);
            $('#fotoCreada').attr('src', '/images/' + file);
        }
    });

});

function crearClub(){
	var desc = $('#descripcion').val();
	var foto = $('#fotoClub').val();
	var imagen = $('#imagenClub').val();
    
	if((desc != "") && (foto != "") && (imagen != "")){
		url = "/panel/club/registro";
	    dataString = $("#frmCrearClub").serialize();
	    $.post(url,dataString,function(data){
	        location.href="/panel/club/";
	    });
	}else{
		if(desc == ""){
			$("#mensaje").html("Ingresar descripcion");
			desc.focus();
		}else if((foto == "") || (imagen == "")){
			$("#mensaje").html("Subir una foto");
		}
		
	}
	
}

function editarClub(club_id){
    window.location = '/panel/club/editar?club_id=' + club_id;
}

function grabarClub(){
    url = "/panel/club/editar";
    dataString = $("#frmEditarClub").serialize();
    $.post(url,dataString,function(data){
        location.href="/panel/club/";
    });
}

function eliminarClub(club_id){
    if( ! confirm("¿Está seguro de eliminar este club?"))
        return;
    $.ajax({
        type: "POST",
        url: "/panel/club/delete",
        data: "club_id=" + club_id,
        success: function(response){
            if(response.msg == 'OK'){
                window.location = "/panel/club/";
            }else{
                alert("Existen equipos asociados");
            }
        }
    });
}