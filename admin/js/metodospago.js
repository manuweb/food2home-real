

function leemetodospago(){
    var server=servidor+'admin/includes/leemetodospago.php';
    

    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{id:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                
                var nombres=obj.nombre;
                var activo=obj.activo;
                var revo=obj.idrevo;
                var esRedsys=obj.esRedsys;
                // tabla-impuestos
                var txt="";
                var chk="";
                var disable="";
                for(var j=0;j<id.length;j++){
                    if (activo[j]=='1'){
                        chk='checked';
                    }
                    else {
                        chk='';
                    }
                    if (esRedsys[j]=='1'){
                        ers='checked';
                    }
                    else {
                        ers='';
                    }
                    if (revo[j]==''){
                        
                        disable="";
                    }
                    else {
                        disable='onclick="cambiaMetodoActivo(this);"';
                    }
                    txt+='<div class="grid grid-cols-5 grid-gap">'+
                            '<div class="">'+nombres[j]+'</div>'+
                            '<div class="text-align-right"><label class="checkbox"><input type="checkbox" name="activo_'+id[j]+'" data-id="'+id[j]+'" '+chk+' '+disable+'/><i class="icon-checkbox"></i></label></div>' +
                            '<div class="text-align-right">'+revo[j]+'</div>' +
                            '<div class="text-align-right"><label class="checkbox"><input type="checkbox" '+ers+' disabled/><i class="icon-checkbox"></i></label></div>' +
                           '<div class="text-align-center"><i class="icon f7-icons" onclick="editametodo(\''+id[j]+'\',\''+nombres[j]+'\',\''+activo[j]+'\',\''+revo[j]+'\',\''+esRedsys[j]+'\');">pencil</i></div>'+
                        '</div>';
                }
                $('#tabla-pagos').html(txt);

            }
            else{
                app.dialog.alert('No se pudo recuperar los métodos de pago');
            }
        }
    });
    
    
}

function cambiaMetodoActivo(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivometodo.php';
    
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{id:id, valor:valor},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){  
                  
            }
            else{
                leemetodospago();
                app.dialog.alert('No se pudo cambiar');
            }
        }
    });
             
}

function editametodo(id,nombre, activo,idrevo,esRedsys){
    var chk="";
    var ers=""
    if (activo=='1'){
        chk='checked';
    }
    if (esRedsys=='1'){
        ers='checked';
    }

    
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Método de pago</div>'+
                '<form>'+
                '<div class="list">'+
                    '<ul>'+
                       '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Nombre</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="nombre" placeholder="Nombre" value="'+nombre+'"/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                        
                  
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Id Revo</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" onchange="miraidrevo();" name="idrevo" placeholder="Id Revo" value="'+idrevo+'"/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                    '<li class="grid grid-cols-2">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Activo</div>'+
                            '<div class="item-input-wrap">'+
                              '<label class="checkbox"><input type="checkbox" '+chk+' id="metodoactivo"/><i class="icon-checkbox"></i></label>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Lanza RedSys</div>'+
                            '<div class="item-input-wrap">'+
                              '<label class="checkbox"><input type="checkbox" '+ers+' id="lanzaredsys"/><i class="icon-checkbox"></i></label>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                        
                    '</ul>'+   
                '</div>'+
                '<div id="redsys">'+
                '<div class="title"><b>Configuración RedSys</b><br></div>'+
                '<div>URL de notificación: <span id="urlnoti"></span></div>'+
                '<div>URL OK : <span id="urlok"></span></div>'+
                '<div>URL KO: <span id="urlko"></span></div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Nº de comercio</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="MerchantCode" placeholder="numero" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Su clave de comercio</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="MerchantKey" placeholder="clave" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                    '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Nº Terminal</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="terminal" placeholder="terminal" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                    '<li class="">'+
                        '<label class="item-content">'+
                          '<div class="item-inner">'+
                            '<div class="item-title">Bizum</div>'+
                            '<div class="item-after">'+
                            '<div class="toggle toggle-init">'+
                              '<input type="checkbox"  id="usar_bizum" >'+
                              '<span class="toggle-icon"></span>'+
                            '</div>'+
                            '</div>'+
                          '</div>'+
                        '</li>'+
                    '</ul>'+   
                '</div>'+
                '</div>'+
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" data-id="'+id+'" onclick="guardametodo(this);" href="#">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'  ,
       on: {
            open: function (popup) {
              //<textarea placeholder="Bio"></textarea>  id="editor-contenido"
                var url=servidor+'webapp/includes/';
                $('#urlnoti').html('<b>'+url+'tpv_noti.php</b>');
                $('#urlok').html('<b>'+url+'tpv_ok.php</b>');
                $('#urlko').html('<b>'+url+'tpv_ko.php</b>');
                
                var server=servidor+'admin/includes/leeredsys.php';
                var urlCompleta = server;


                $.ajax({
                    type: "POST",
                    url: server,
                    dataType:"json",
                    data:{id:'foo'},
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            $('input[name=MerchantKey]').val(obj.MerchantKey);
                            $('input[name=MerchantCode]').val(obj.MerchantCode);
                            $('input[name=terminal]').val(obj.terminal);
                            if (obj.bizum==1){
                                $('#usar_bizum').prop('checked',true);
                            }
                        }   
                    },
                    
                    error: function (xhr, ajaxOptions, thrownError){
                        console.log("xhr:"+xhr.status);
                        console.log("thrownError:"+thrownError);
                        console.log("ajaxOptions:"+ajaxOptions);
                        //leemetodospago();
                    }
                    
                });


                var idrevo=$('input[name=idrevo]').val();
                if (esRedsys!='1'){
                    $('#redsys').hide()
                }
                
                $('#lanzaredsys').on('click',function(){
                    if($('#lanzaredsys').prop('checked')){
                        $('#redsys').show();
                    }
                    else {
                        $('#redsys').hide();
                    }
                });

          },
        }
      
    });  
    
    dynamicPopup.open(); 
   
    
    
}
function miraidrevo() {
    idrevo=$('input[name=idrevo]').val();
    
    if (idrevo!='1'){
        $('#redsys').hide();
    }else {
        $('#redsys').show();
    }
}


function guardametodo(e) {
    var id=e.getAttribute('data-id');
    var nombre=$('input[name=nombre]').val();

    var idrevo=$('input[name=idrevo]').val();
    var MerchantKey=$('input[name=MerchantKey]').val();
    var MerchantCode=$('input[name=MerchantCode]').val();
    var terminal=$('input[name=terminal]').val();
    var bizum=0;
    var metodoactivo=0;
    if($('#metodoactivo').prop('checked')){
        metodoactivo=1;
    }
    var esRedsys=0;
    if($('#lanzaredsys').prop('checked')){
        esRedsys=1;
        if ($('#usar_bizum').prop('checked')){
            bizum=1;
        }
    }
    var server=servidor+'admin/includes/guardametodo.php';   
    $.ajax({
                    
        type: "POST",
        url: server,
        dataType: "json",
        data: {id:id, nombre:nombre, idrevo:idrevo, activo:metodoactivo, esRedsys:esRedsys, MerchantKey:MerchantKey, MerchantCode:MerchantCode, terminal:terminal,bizum:bizum},
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                
                app.popup.close('.popup');
                
                leemetodospago();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            leemetodospago();
        }
 
    });  
     
    

 
}
