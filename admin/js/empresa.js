
function leeempresa(){
    var server=servidor+'admin/includes/leeempresa.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){

                $('#nombre-empresa').html(obj.nombre);
                $('#nombre-comercial-empresa').html(obj.nombre_comercial);
                $('#nif-empresa').html(obj.nif);
                $('#domicilio-empresa').html(obj.domicilio);
                $('#cpostal-empresa').html(obj.cod_postal);
                $('#poblacion-empresa').html(obj.poblacion);
                $('#provincia-empresa').html(obj.provincia);
                $('#telefono-empresa').html(obj.telefono);
                $('#movil-empresa').html(obj.movil);
                $('#email-empresa').html(obj.email);
                //console.log('Logo im:'+obj.logo_impresora);
                if (integracion==2){
                    $('#logo-impresora').show();
                    document.getElementById('img-impresora-empresa').src=servidor+'webapp/img/empresa/'+obj.logo_impresora;
                }

                //usuarioRevo=obj.usuario;
                //tokenRevo=obj.token;
                document.getElementById('img-empresa').src=servidor+'webapp/img/empresa/'+obj.logo;
                document.getElementById('ico-empresa').src=servidor+'webapp/img/empresa/'+obj.icono;
                //document.getElementById('img-impresora-empresa').src=servidor+'webapp/img/empresa/'+obj.logo;
                //app.dialog.alert(usuarioRevo+'-'+tokenRevo);

            }
            else{
                app.dialog.alert('No se pudo recuperar la empresa');
            }
            if (tienda!='0'){
                $('#edit-datos-empresa').hide();
            }
        }
    });
    

    
   
}  

$("#edit-datos-empresa").on('click', function () {
      var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title">Modificar Empresa</div>'+
          
            '<form class="list" id="empresa-form" enctype="multipart/form-data">'+
          '<input type="hidden" name="idportes"  value=""/>'+
                '<ul>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Nombre" value="'+$('#nombre-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre comercial</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre_comercial" placeholder="Nombre comercial" value="'+$('#nombre-comercial-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+  
                 '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Cif/Nif</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nif" placeholder="Cif/Nif" value="'+$('#nif-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 

                 '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">C.Postal</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="cpostal" placeholder="Código postal" value="'+$('#cpostal-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Domicilio</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="domicilio" placeholder="Domicilio" value="'+$('#domicilio-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                    '</li>'+  

                  '</li>'+  
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Población</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="poblacion" placeholder="Población" value="'+$('#poblacion-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+  
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Provincia</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="provincia" placeholder="Provincia" value="'+$('#provincia-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+  
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Teléfono</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="telefono" placeholder="Teléfono" value="'+$('#telefono-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+  
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Móvil</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="movil" placeholder="Móvil" value="'+$('#movil-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Email</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="email" placeholder="email" value="'+$('#email-empresa').html()+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                    
                  '</ul>'+
                    '<div style="text-align:center;">'+
                  '<p><span style="font-size:12px;">Logo: </span></p>'+
                  '<img name="imagen" id="img-logo" src="'+document.getElementById('img-empresa').src+'" width="80%" height="auto"/>'+  
                  '<input id="input-imagen" type="file" accept="image/*" onchange="loadFileImg(event,\'#img-logo\');$(\'#guarda-imagen\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-imagen" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-imagen" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+ 
          '<div style="text-align:center;">'+
                  '<p><span style="font-size:12px;">Icono: </span></p>'+
                  '<img name="icono" id="img-icono" src="'+document.getElementById('ico-empresa').src+'" width="64" height="auto"/>'+  
                  '<input id="input-icono" type="file" accept="image/*" onchange="loadFileImg(event,\'#img-icono\');$(\'#guarda-icono\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-icono" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-icono" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+ 
          
          '<div style="text-align:center;display:none;" id="div-img-impresora">'+
                  '<p><span style="font-size:12px;">Logo impresora: </span></p>'+
                  '<img name="imagen-impresora" id="img-logo-impresora" src="'+document.getElementById('img-impresora-empresa').src+'" width="80%" height="auto"/>'+  
                  '<input id="input-imagen-impresora" type="file" accept="image/*" onchange="loadFileImg(event,\'#img-logo-impresora\');$(\'#guarda-imagen-impresora\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-imagen-impresora" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-imagen-impresora" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+   
              '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close"" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#">Guardar</a></div>'+
                '</div>'+
         
            '</div>'+
          '</div>'
         ,
        // Events
        on: {
          open: function (popup) {
              if (document.getElementById('img-impresora-empresa').src!=''){
                  $('#div-img-impresora').show();
              }
              if (integracion==1){
                  $('#div-img-impresora').hide();
              }
            
            //console.log('Popup open');
          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
    dynamicPopup.on('close', function (popup) {
        //console.log('Popup close');
      });
      dynamicPopup.on('closed', function (popup) {
        //console.log('Popup closed');
          leeempresa();
      });
    dynamicPopup.open();
    
    
    $('.save-data').on('click', function () {
        var formData = app.form.convertToData('#empresa-form');
        
        var server=servidor+'admin/includes/guardaempresa.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{nombre:formData['nombre'], nombre_comercial:formData['nombre_comercial'], nif:formData['nif'], domicilio:formData['domicilio'],cpostal:formData['cpostal'], poblacion:formData['poblacion'], provincia:formData['provincia'], telefono:formData['telefono'], movil:formData['movil'],email:formData['email']},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    leeempresa();
                    app.dialog.alert('Datos guardados');
                }
                else{
                    app.dialog.alert('No se pudo guardar la empresa');
                }
            }
        });
             
        
        dynamicPopup.close();
    });

        
    $("#cambia-imagen").on('click', function () {
        $('#input-imagen').show(); 
        $("#cambia-imagen").hide();
        $('#guarda-imagen').show();
     
    });
    $("#cambia-icono").on('click', function () {
        $('#input-icono').show(); 
        $("#cambia-icono").hide();
        $('#guarda-icono').show();
     
    });
    $("#cambia-imagen-impresora").on('click', function () {
        $('#input-imagen-impresora').show(); 
        $("#cambia-imagen-impresora").hide();
        $('#guarda-imagen-impresora').show();
     
    });
    
    $("#guarda-imagen").on('click', function () {

        var f=document.getElementById('input-imagen').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        
        
        
        var server=servidor+'admin/includes/guardaimgempresa.php';
        $.ajax({
            type: "POST",
            url: server,
            data: FData,
            cache: false, 
            dataType: 'application/json',
            crossDomain: true,      
            processData: true, 
            contentType: false,
            processData: false,
            success: function (data){
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leealergenos();
                    $('#input-imagen').hide();
                    $('#guarda-imagen').hide(); 
                    $("#cambia-imagen").show();
                }
                else{
                    app.dialog.alert('No se pudo guardar logo');
                }        
            }
        });
    
 
    });
    
    $("#guarda-icono").on('click', function () {

        var f=document.getElementById('input-icono').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        
        
        
        var server=servidor+'admin/includes/guardaicoempresa.php';
        $.ajax({
            type: "POST",
            url: server,
            data: FData,
            cache: false, 
            dataType: 'application/json',
            crossDomain: true,      
            processData: true, 
            contentType: false,
            processData: false,
            success: function (data){
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leealergenos();
                    $('#input-icono').hide();
                    $('#guarda-icono').hide(); 
                    $("#cambia-iconi").show();
                }
                else{
                    app.dialog.alert('No se pudo guardar icono');
                }        
            }
        });
    
 
    });
    
    $("#guarda-imagen-impresora").on('click', function () {

        var f=document.getElementById('input-imagen-impresora').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        

        
        var server=servidor+'admin/includes/guardaimgimpresora.php';
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
                    //leealergenos();
                    $('#input-imagen-impresora').hide();
                    $('#guarda-imagen-impresora').hide(); 
                    $("#cambia-imagen-impresora").show();
                    document.getElementById('img-logo-impresora').src=servidor+'webapp/img/empresa/'+obj.logo;

                }
                else{
                    app.dialog.alert('No se pudo guardar logo');
                }        
            }
        });
 
    });
    
});
