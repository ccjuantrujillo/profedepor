jQuery(function(){
    $("#config").addClass("menu2");
});

function editarConfig(config_id){
    window.location = '/panel/config/editar/id/' + config_id;
}

function guardarConfig(){
    url = "/panel/config/editar";
    dataString = $("#frmConfig").serialize();
    $.post(url,dataString,function(data){
        if(data.config != null){
            location.href="/panel/config/";
        }
    });
}

function filterTipoTorneo(){
    var torneo = $("#torneo_id").val();
    var conf = $("#configuracion_id").val();
    window.location = '/panel/config/editar/id/' + conf + '/torneo/' + torneo;
}