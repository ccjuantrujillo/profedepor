function grabar_paso1(){
	url = "/puntaje1/guardar1";
	dataString = $("#frmPaso1").serialize();
	$.post(url,dataString,function(data){
		alert(data);
		//location.href="/puntaje1/mensaje1";
	});
}