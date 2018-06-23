$(function (){		
    var tooltipjuego = $('p.tooltipjuego');

    tooltipjuego.click(function (e){
        tooltipjuego.qtip('hide');
        $(this).siblings('input').trigger('click');
    }).qtip({
        content: {
            text: 'Ver este equipo'
        },
        show: {
            solo: true
        },
        hide: {
            delay: 7000
        },
        position: {
            corner: {
                target: 'bottomMiddle',
                tooltip: 'topMiddle'
            },
            adjust: {
                resize: true,
                scroll: true
            }
        },
        style: {
            name: 'cream',
            padding: '7px 13px',
            width: {
                max: 210,
                min: 0
            },
            tip: true
        },
        effect: {
            length: 0
        }
    });

    $('.contFix0 a').click(function (e){
        $('#box-loader').center().show();
        e.preventDefault();

        window.location = $(this).attr('href');
        return false;
    });       
     
});

function grabar_paso1(){
    qDisabled = $("#chksFase1 input:checkbox:disabled").size();
    qChecked = $('#chksFase1  input:checkbox:checked').size();
     if(qChecked>0 && qDisabled!=24){
          url = "/juego/partidosxfase";
          dataString = $("#frmPaso1").serialize();
          $.post(url, "fase=2", function(data){
               $("#btnGrabarFase1").removeAttr('onclick');
               if(data.resultado==0){
                    url2 = "/juego/mensaje1solo";
                    $.post(url2,dataString, function (response){
                         $(response).modal({onClose:function(){}});
                    });
               }
               else{
                    url2 = "/juego/mensaje1";
                    $.post(url2,dataString, function (response){
                         $(response).modal({onClose:function(){
                                   irFase2 = $("#irFase2").val();
                                   if(irFase2==1){
                                        $.modal.close();
                                        $.post("/juego/advertencia1",dataString,function(response2){
                                             $(response2).modal();
                                        });
                                   }
                                   else{
                                        guardar1();
                                        $.modal.close();
                                   }
                         }});
                    });
               }
          });
     }
     else if(qDisabled==24){
          alert('No puede realizar pronosticos.');
     }
     else if(qChecked==0){
          alert('Debe ingresar su pronostico para al menos un partido.');
     }
}
function guardar1(){
	url = "/juego/guardar1";
    $.post(url,function(data){
         cadena = "No se guardaron los sgtes partidos, \nla session expiro:\n";
         if(data.length>0){
              for(i=0;i<data.length;i++){
                   cadena = cadena + data[i]+"\n";
              }
              alert(cadena);
          }
        location.href="/juego";
    });
}
function guardar1solo(dataString){
     url = "/juego/guardar1solo";
     $.post(url,dataString,function(data){
     location.href="/juego";
     });
}
function grabar_paso2(){
     if($('#chksFase2 input:checkbox:checked').size()>0){
          url = "/juego/partidosxfase";
          dataString = $("#frmPaso2").serialize();
          $.post(url, "fase=3", function(data){
               if(data.resultado==0){
                    url2 = "/juego/mensaje2solo";
                    $.post(url2,dataString, function (response){
                         $(response).modal({onClose:function(){}});
                    });
               }
               else{
                    url2 = "/juego/mensaje2";
                    $.post(url2,dataString, function (response){
                         $(response).modal({onClose:function(){
                                   guardar2();
                                   $.modal.close();
                         }});
                    });
               }
          });
     }
     else{
          alert('Debe ingresar su pronostico  para al menos un partido.');
     }
}
function guardar2(){
	url = "/juego/guardar2";
    $.post(url,function(data){
      location.href="/juego";
    });
}
function guardar2solo(dataString){
     url = "/juego/guardar2solo";
     $.post(url,dataString,function(data){
     location.href="/juego";
     });
}
function grabar_paso3(){
    url = "/juego/guardar3bd";
    dataString = $("#frmPaso3_grande").serialize();
    $.post(url,dataString,function(data){
         url2 = "/juego/mensaje3";
         $.post(url2,function(response){
               $(response).modal({onClose:function(){
                         //location.href="/juego";
               }});
         });
    });
}
function grabar3pop(){
    n1 = $("#categoria1 input:checkbox:checked").size();
    n2 = $("#categoria2 input:checkbox:checked").size();
    n3 = $("#categoria3 input:checkbox:checked").size();
    n4 = $("#categoria4 input:checkbox:checked").size();
    if(n1==0 || n2==0 || n3==0 || n4==0){
         alert('Debe seleccionar al menos un intervalo por categoria.');
    }
    else{
         url = "/juego/guardar3";
         dataString = $("#frmPaso3").serialize();
         $.ajaxSetup({
              complete: function (){
                  $('#box-loader').show();
              }
         });
         $.post(url,dataString,function(response){
         location.href="/juego/fase3";
         });
    }
}
function pulsar(obj,n){
    var self = $(obj);
    if(self.not(':checked')){
         self.attr('checked', true);
    }
    a = "intervalos["+n+"][2]";
    b = "intervalos["+n+"][3]";
    c = "intervalos["+n+"][4]";
    dd = 'fecha_registro['+n+']';
    document.getElementById(dd).value="";
    if(!obj.checked) return;
    elem_a = document.getElementById(a);
    elem_b = document.getElementById(b);
    elem_c = document.getElementById(c);
    elem_a.checked = false;
    elem_b.checked = false;
    elem_c.checked = false;
    obj.checked = true;
}
function pulsar2(obj,n){
    var self = $(obj);
    if(self.not(':checked')){
         self.attr('checked', true);
    }
    a = "intervalos["+n+"][6]";
    b = "intervalos["+n+"][7]";
    c = "intervalos["+n+"][8]";
    d = "intervalos["+n+"][9]";
    e = "intervalos["+n+"][10]";
    f = "intervalos["+n+"][11]";
    if(!obj.checked) return;
    if(document.getElementById(a)){elem_a = document.getElementById(a);elem_a.checked = false;}
   if(document.getElementById(b)){elem_b = document.getElementById(b);elem_b.checked = false;}
   if(document.getElementById(c)){elem_c  = document.getElementById(c);elem_c.checked = false;}
   if(document.getElementById(d)){elem_d = document.getElementById(d);elem_d.checked = false;}
   if(document.getElementById(e)){elem_e = document.getElementById(e);elem_e.checked = false;}
   if(document.getElementById(f)){elem_f  = document.getElementById(f);elem_f.checked = false;}
    obj.checked = true;
}
function pulsar3(obj,n){
     $('#categoria'+n+' :checkbox').attr('checked', false);
     $(obj).attr('checked', true);
}
function mostrarfase3(partido,puntajejuego,tipo,indice){
    url = "/juego/fase3pop";
    dataString = "partido="+partido+"&puntajejuego_id="+puntajejuego+"&tipo="+tipo+"&indice="+indice;
    $.post(url,dataString, function (response){
        $(response).modal();
    });
}
function ir_fase(n){
    //$('#box-loader').show();
    $(".modalCloseImg").hide();
    location.href="/juego/fase"+n;
    $("#btnIrFase2").removeAttr('onclick');
}
