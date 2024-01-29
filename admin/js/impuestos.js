

function leeimpuestos(){
    var server=servidor+'admin/includes/leeimpuestos.php';
    

    app.preloader.show();
    app.request.postJSON(server, { id:'foo'}, 
        function (data) {
            app.preloader.hide();
            //app.dialog.close();
            var obj=Object(data);
            if (obj.valid==true){
                var ids=obj.id;
                var nombres=obj.nombre;
                var porcentajes=obj.porcentaje;
                // tabla-impuestos
                var txt="";

                for(var j=0;j<ids.length;j++){
                    txt+='<div class="row no-gap">'+
                            '<div class="col-55 medium-25">'+nombres[j]+'</div>'+
                            '<div class="col-30 medium-25 text-align-right">'+porcentajes[j]+'</div>' +
                           '<div class="col-15 medium-25 text-align-center"><i class="icon f7-icons" onclick="editaimpuesto(\''+ids[j]+'\',\''+nombres[j]+'\',\''+porcentajes[j]+'\');">pencil</i></div>'+
                        '</div>';
                }
                $('#tabla-impuestos').html(txt);

            }
            else{
                app.dialog.alert('No se pudo recuperar los impuestos');
            }
        },
        function (xhr, status) {
            app.preloader.hide();   
            app.dialog.alert('No se pudo recuperar los impuestos');
    }); 
   
}

function editaimpuesto(id,nombre,porcentaje) {
    
          // Create dynamic Popup
      var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            '<div class="title">Modificar impuesto</div>'+
            '<form class="list" id="impuesto-form">'+
                '<ul>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Descripción</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Descripcion" value="'+nombre+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Porcentaje</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="porcentaje" placeholder="Porcentaje" value="'+porcentaje+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+      
                  '</ul>'+
              '</form>'+
                '<div class="block block-strong row">'+
                    '<div class="col"><a class="button button-fill popup-close"" href="#">Cancelar</a></div>'+
                    '<div class="col"><a class="button button-fill save-data" href="#">Guardar</a</div>'+
                '</div>'+
            '</div>'+
          '</div>'
         ,
        // Events
        on: {
          open: function (popup) {
            //console.log('Popup open');
          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
      });
      // Events also can be assigned on instance later
      dynamicPopup.on('close', function (popup) {
        //console.log('Popup close');
      });
      dynamicPopup.on('closed', function (popup) {
        //console.log('Popup closed');
      });
    dynamicPopup.open(); 
    $('.save-data').on('click', function () {
        var formData = app.form.convertToData('#impuesto-form');
        
        var server=servidor+'admin/includes/guardaimpuestos.php';
        app.preloader.show();
        app.request.postJSON(server, { id:id, nombre:formData['nombre'], porcentaje:formData['porcentaje']}, 
            function (data) {
                app.preloader.hide();
                var obj=Object(data);
                if (obj.valid==true){
                    leeimpuestos();
                }
                else{
                    app.dialog.alert('No se pudo guardar los impuestos');
                }
            },
            function (xhr, status) {
                app.preloader.hide();   
                app.dialog.alert('No se pudo guardar los impuestos');
        });        
        
        dynamicPopup.close();
    });
    
}