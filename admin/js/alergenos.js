
 $("#imgs-alergeno").on('click', function () {
     var output=document.getElementById("#imgs-alergeno");
     
 });
var img="";
var loadFile = function(event) {
    
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('imgs-alergeno');
      output.src = reader.result;
        $("#imgs-alergeno").show();
        
    };
    reader.readAsDataURL(event.target.files[0]);
    img=event.target.files[0];
    
    
};



function leealergenos(){
    var server=servidor+'admin/includes/leealergenos.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data: {id:'foo'} ,
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var ids=obj.id;
                var nombres=obj.nombre;
                var imagen=obj.imagen;
                // tabla-impuestos
                var txt="";

                for(var j=0;j<ids.length;j++){
                    txt+='<div class="grid grid-cols-3 grid-gap">'+
                            '<div class="">'+nombres[j]+'</div>'+
                            '<div class="text-align-right"><img src="../webapp/img/alergenos/'+imagen[j]+'" width="28px" height="auto"/></div>' +
                           '<div class="text-align-center"><i class="icon f7-icons" onclick="editaalergeno(\''+ids[j]+'\',\''+nombres[j]+'\',\''+imagen[j]+'\');">pencil</i></div>'+
                        '</div>';
                }
                $('#tabla-alergenos').html(txt);

            }
            else{
                app.dialog.alert('No se pudo recuperar los alergenos');
            }
        }
    });
    
   
}


function editaalergeno(id,nombre,imagen) {
    
          // Create dynamic Popup
      var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            '<div class="title">Modificar Alergeno</div>'+
            '<form class="list" id="alergeno-form" enctype="multipart/form-data">'+
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
                      
                  '</ul>'+
                  '<div style="text-align:center;">'+
                  
                  '<img name="imagen" id="imgs-alergeno" width="64" height="64" src="../webapp/img/alergenos/'+imagen+'"/>'+  
                  '<input id="input-imagen" type="file" accept="image/*" onchange="loadFileImg(event,\'#imgs-alergeno\');$(\'#guarda-imagen\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-imagen" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-imagen" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+
              '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#">Guardar</a</div>'+
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
          leealergenos();
      });
    dynamicPopup.open(); 
    $('.save-data').on('click', function () {
        var formData = app.form.convertToData('#alergeno-form');
        
        var server=servidor+'admin/includes/guardaalergenos.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data: {id:'foo'} ,
            success: function(data){
                if (obj.valid==true){
                    leealergenos();
                }
                else{
                    app.dialog.alert('No se pudo guardar los alergenos');
                }
            }
        });
        
        dynamicPopup.close();
    });
    
    $("#cambia-imagen").on('click', function () {
        $('#input-imagen').show(); 
        $("#cambia-imagen").hide();
     
    });
    
    $("#guarda-imagen").on('click', function () {
        
        var f=document.getElementById('input-imagen').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        FData.append('id',id);   
 
        var server=servidor+'admin/includes/guardaimgalergenos.php';
        $.ajax({
            type: "POST",
            url: server,
            data: FData,
            cache: false, 
            //dataType: 'application/json',
            crossDomain: true,      
            processData: true, 
            contentType: false,
            processData: false,
            success: function (data){
                var obj= JSON.parse(data);
                if (obj.valid==true){
                     $('#input-imagen').hide();
                    $('#guarda-imagen').hide(); 
                    $("#cambia-imagen").show();
                    //leealergenos();
                }
                else{
                    app.dialog.alert('No se pudo guardar los alergenos');
                } 
            },
            
            error: function (request, status, error) {
                console.log(error);
            }
        });
 
    });
}