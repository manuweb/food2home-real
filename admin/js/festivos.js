/**
 * Archivo: festivos.js
 *
 * Version: 1.0.1
 * Fecha  : 03/12/2024
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/

function ajustesFestivos(){
    $('#festivos-tittle').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Festivos<span id="boton-add-fedstivo" class="button button-fill float-right" onclick="nuevoFestivo();">Nuevo</span>');
   $('#tabla-festivos').html(''); server=servidor+'admin/includes/leefestivos.php';
    
    $.ajax({
        type: "POST",
        url: server,
        data: {id:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var fecha=obj.fecha;
                var txt='';
                var anno='';
                var lafecha='';
                for (x=0;x<fecha.length;x++){
                   lafecha=fecha[x].substr(8,2)+'/'+fecha[x].substr(5,2)+'/'+fecha[x].substr(0,4);
                    if (fecha[x].substr(0,4)!=anno){
                        txt+='<div class="grid grid-cols-3 grid-gap">'+
                        '<div style="font-size: 18px;"><b>'+fecha[x].substr(0,4)+'</b></div>'+
                        '<div></div>'+
                        '<div></div>'+
                        '</div>';
                    }
                    anno=fecha[x].substr(0,4);
                    txt+='<div class="grid grid-cols-3 grid-gap">'+
                    '<div></div>'+
                    '<div style="font-size: 16px;">'+lafecha+'</div>'+
                    '<div><i class="icons f7-icons size-20" onclick="borraFestivo(\''+lafecha+'\',\''+fecha[x]+'\');">trash</i></div>'+
                    '</div>';
                }
                $('#tabla-festivos').html(txt);
            }
        },

        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
                
    
}

function borraFestivo(lafecha,fecha){
    app.dialog.confirm('¿Desea borrar <br><b>'+lafecha+'</b>?', function () {
        var server=servidor+'admin/includes/leefestivos.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{ id:'borrar',fecha:fecha},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    muestraMensaje('Festivo Borrado correctamente','Datos Guardados');
                    ajustesFestivos();
                }
                else {
                    muestraMensaje('Error borrando festivo','Error');
                }
            },

            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    });      
}

function nuevoFestivo(){
    
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
                '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Nuevo festivo</div>'+
                '<div class="list">'+
                    '<ul>'+
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Fecha</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="fecha"  readonly="readonly" id="calendarfestivo" />'+
                            '</div>'+
        
                          '</div>'+
       
                        '</div>'+
                      '</li>'+  
                    '</ul>'+
                '</div>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" href="#" id="guardagrupo-boton" onclick="guardaFestivo();">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'
         ,
        // Events
        on: {
            open: function (popup) {
                var calendar = app.calendar.create({
                    inputEl: '#calendarfestivo',
                    openIn: 'customModal',
                    toolbarCloseText:'Ok',
                    //header: true,
                    closeOnSelect:true,
                    dateFormat: 'dd/mm/yyyy'
                    //footer: true,
                });
            }
        }
    });  
    dynamicPopup.on('close', function (popup) {
        //console.log('Popup close');
        ajustesFestivos();
      });
    dynamicPopup.open();
    
}
function guardaFestivo(){
    var fecha=$('#calendarfestivo').val();
    if (fecha==''){
        return;
    }

    fecha=fecha.substr(6,4)+'-'+fecha.substr(3,2)+'-'+fecha.substr(0,2);
    var server=servidor+'admin/includes/leefestivos.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{ id:0,fecha:fecha},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                app.popup.close();
                muestraMensaje('Festivo creado correctamente','Datos Guardados');
                
                ajustesFestivos();
            }
            else {
                muestraMensaje('Error cerando festivo','Error');
            }
        },

        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });   
}