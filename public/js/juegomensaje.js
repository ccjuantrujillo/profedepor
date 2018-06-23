$(function (){
    var registro = $('.featured').val
    var posicion = $('.faseHeadLeft').offset();
    $('#globo1').floating({targetX: posicion.left+30, targetY: posicion.top-120});
    $('#globo1').html("<img src='images/btnCerrarGlobo.png' width='23' height='22' />");
    setTimeout("$('#globo1').hide();", 5000);
    $('#globo1').html("");
});