function integration() {
    var server=servidor+'admin/includes/integracion.php';
    $.ajax({
        type: "POST",
        data: {id:'foo'},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var txt_delivery=''+
                    '<div class="list block" >'+
                          '<ul>'+
                    '<li class="item-content item-input" id="li-copias-tickets">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title item-label">Copias tickets</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="copias_tickets" value="'+obj.copias_tickets+'" placeholder="Copias"/>'+
                                    '</div>'+
                                 ' </div>'+
                                '</label>'+
                                '</li>'+
                    '<li class="">'+
                                '<label class="item-content">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title">¿Quiosco?</div>'+
                                    '<div class="item-after">'+
                                    '<div class="toggle toggle-init">'+
                                      '<input type="checkbox"  id="usar_modo_quiosco">'+
                                      '<span class="toggle-icon"></span>'+
                                    '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</li>'+
                            '<li class="">'+
                                '<label class="item-content">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title">Usar empresa delivery</div>'+
                                    '<div class="item-after">'+
                                    '<div class="toggle toggle-init">'+
                                      '<input type="checkbox"  id="usar_delivery" onclick="usarDelivery();">'+
                                      '<span class="toggle-icon"></span>'+
                                    '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</li>'+
                            ' </ul>'+
                        '</div>'+
                    '<br><div id="delivery-txt" style="border:1px solid;">'+
                    
                    '</div><br>';
                
                if (obj.integracion==2){
                    //star
                    txt=''+
                    '<div class="block-title block-title-medium">Integración Star<span id="button-cambia-Integración" class="button button-fill float-right" onclick="cambiaaRevo();">Cambiar a Revo</span> </div>'+
                    '<div class="block" style="font-size: 1.3em;"><h4>Configuración impresora</h4>'+
                    '<div id="deviceListIntegra">Buscando . . . </div>'+
                    '<p>Para conectar un nuevo dispositivo, configure su URL de CloudPRNT en:</p><br/>'+
                    '<div style="background-color: whitesmoke; font-weight: bold;border-color: black;border-style: solid;border-width: 1px;border-radius: 4px;padding: 4px;display: inline-block;"><span id="cpurl-integra">...</span></div></div>'+
                        '<div class="row"><button onclick="guardaStar();" class="button button-fill" style="margin:auto;width: 60%;">Guardar</button></div>';
                    $('#integra-page').html(txt+txt_delivery);
                    var url=window.location.href.replace("admin/", "webapp/printer/cloudprnt.php");
                    url=url.replace("index.php", "");
                    $("#cpurl-integra").html(url);
                    UpdateDeviceTableIntegra();
                }
                else {
                    txt=''+
                        '<div class="block-title block-title-medium">Integración Revo<span id="button-cambia-Integración" class="button button-fill float-right" onclick="cambiaaStar();">Cambiar a Star</span> </div>'+
                        '<div class="list block">'+
                          '<ul>'+
                                '<li class="item-content item-input">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title item-label">Usuario Revo Revo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="usuario_revo_integra" value="'+obj.usuario+'" placeholder="Usuario Revo"/>'+
                                    '</div>'+
                                 ' </div>'+
                                '</label>'+
                                '</li>'+
                                '<li class="item-content item-input">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title item-label">Token Revo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="token_revo_integra" value="'+obj.token+'" placeholder="Token Revo"/>'+
                                   ' </div>'+
                                  '</div>'+
                                '</li>'+
                                '<li class="">'+
                                '<label class="item-content">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title">Usar numero Revo en pedidos</div>'+
                                    '<div class="item-after">'+
                                    '<div class="toggle toggle-init">'+
                                      '<input type="checkbox"  id="usar_numero_revo">'+
                                      '<span class="toggle-icon"></span>'+
                                    '</div>'+
                                    '</div>'+
                                  '</div>'+
                                '</li>'+
                            
                           ' </ul>'+
                        '</div>'+txt_delivery+
                        '<div class="row"><button onclick="guardaRevo();" class="button button-fill" style="margin:auto;width: 60%;">Guardar</button></div>';
                    
                    $('#integra-page').html(txt);
                    $('#li-copias-tickets').hide();
                    if (obj.usar_numero_revo==1){
                        $('#usar_numero_revo').prop('checked',true);
                    }
                    if (obj.usar_modo_quiosco==1){
                        $('#usar_modo_quiosco').prop('checked',true);
                    }
                    

                }
                $('#usar_delivery').val(obj.delivery);
                if (obj.delivery>0){
                    $('#usar_delivery').prop('checked',true);
                    usarDelivery();
                }
                else {
                    $('#delivery-txt').hide();
                }
                
            }
            else {
                
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}

function usarDelivery(){
    var estado=$('#usar_delivery').prop('checked');
    var valor=$('#usar_delivery').val();
    var txtdel=$('#delivery-txt').html();
    var botonguardar='<div class="grid grid-cols-2"><div><button onclick="cambiardelivery();" class="button button-fill" style="margin:auto;width: 60%;">Cambiar delivery</button></div><div><button onclick="guardadelivery();" class="button button-fill" style="margin:auto;width: 60%;">Guardar delivery</button></div><br>';
    $('#delivery-txt').show();
    if (estado && valor>0) {
        var server=servidor+'admin/includes/delivery.php';
        $.ajax({
            type: "POST",
            data: {id:valor},
            url: server,
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    
                    delivery=valor;
                    var txt='<div class="list block">'+
                        '<span><img src="img/delivery/'+obj.logo[0]+'"style="float:right;width:100px;height:auto;"></span>'+
                        '<ul>';
                    var lineas_logica=obj.logica[0].split('**||**');
                    
                    //console.log(lineas_logica);
                    for (x=0;x<lineas_logica.length;x++){
                        var logica=(lineas_logica[x]).split("#||#");
                        var variables=logica[0].replace(/{|}/g,'').split('|');
                        //console.log(variables);
                        txt+='<li class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">'+variables[1]+'</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="'+variables[0]+'" value="'+logica[1]+'" placeholder="'+variables[2]+'"/>'+
                            '</div>'+
                         ' </div>'+
                        '</label>'+
                        '</li>';
                        
                    }
                    txt+='</ul></div>';
                   
                    $('#delivery-txt').html(txt+botonguardar);
                    
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
    if (estado && valor==0){
        cambiardelivery();
        
    }
    
    if(!estado){
        $('#delivery-txt').hide();
        // guardar integracion con delivery=0
        var server=servidor+'admin/includes/delivery.php';
        $.ajax({
            type: "POST",
            data: {id:'foo'},
            url: server,
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    delivery=0;
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
}
    

function cambiardelivery(){
        
    var server=servidor+'admin/includes/delivery.php';
    $.ajax({
        type: "POST",
        data: {id:0},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                
                var dynamicPopup = app.popup.create({
                    content: ''+
                      '<div class="popup">'+
                        '<div class="block page-content">'+
                          '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><h2>Selecionar delivery</h2>'+
                            '<div id="empresasdelivery"></div>'+
                        '</div>'+
                      '</div>'  ,
                    on: {
                        open: function (popup) {
                            var txt='<div class="list list-outline-ios list-strong-ios list-dividers-ios">'+
                                '<ul>';
                            
                            for (x=0;x<obj.nombre.length;x++){
                               txt+='<li>'+
                              '<a class="item-link item-content" onclick="poneempresadelivery(this);" data-id="'+obj.id[x]+'" data-logo="'+obj.logo[x]+'" data-nombre="'+obj.nombre[x]+'" data-logica="'+obj.logica[x]+'">'+
                                '<div class="item-media">'+
                                  '<img src="img/delivery/'+obj.logo[x]+'" style="width:100px;height:auto; />'+
                                '</div>'+
                                '<div class="item-inner">'+
                                  '<div class="item-title-row">'+
                                    '<div class="item-title"><h3>'+obj.nombre[x]+'</h3></div>'+
                                  '</div>'+
                                '</div>'+
                              '</a>'+
                            '</li>';
                                
                            }
                            txt+='</ul>'+
                                '</div>';
                            $('#empresasdelivery').html(txt);
                        },
                        opened: function (popup) {
                        //console.log('Popup opened');
                        },
                    }
                }); 
                dynamicPopup.open(); 

            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function poneempresadelivery(elem){
    app.popup.close();
    var txtdel=$('#delivery-txt').html();
    var botonguardar='<div class="grid grid-cols-2"><div><button onclick="cambiardelivery();" class="button button-fill" style="margin:auto;width: 60%;">Cambiar delivery</button></div><div><button onclick="guardadelivery();" class="button button-fill" style="margin:auto;width: 60%;">Guardar delivery</button></div><br>';
    
    var logic=$(elem).attr('data-logica');
    var logo=$(elem).attr('data-logo');
    var nombre=$(elem).attr('data-nombre');
    var id=$(elem).attr('data-id');
    $('#usar_delivery').val(id);
    
    var txt='<div class="list block">'+
        '<span><img src="img/delivery/'+logo+'"style="float:right;width:100px;height:auto;"></span>'+
        '<ul>';
   
    var lineas_logica=logic.split('**||**');

    
    for (x=0;x<lineas_logica.length;x++){
        var logica=(lineas_logica[x]).split("#||#");
        
        var variables=logica[0].replace(/{|}/g,'').split('|');
        //console.log(variables);
        txt+='<li class="item-content item-input">'+
          '<div class="item-inner">'+
            '<div class="item-title item-label">'+variables[1]+'</div>'+
            '<div class="item-input-wrap">'+
              '<input type="text" name="'+variables[0]+'" value="'+logica[1]+'" placeholder="'+variables[2]+'"/>'+
            '</div>'+
         ' </div>'+
        '</label>'+
        '</li>';

    }
    txt+='</ul></div>';

    $('#delivery-txt').html(txt+botonguardar);
}

function guardadelivery() {
    var valores=new Array();
    $('#delivery-txt :input[type="text"]').each(function(){
    
        //console.log($(this).attr("name")+':'+$(this).val());
        valores.push({'variable':$(this).attr("name"),'valor':$(this).val()});
    });
     //console.log(valores);   
    var server=servidor+'admin/includes/guardadelivery.php';
    $.ajax({
        type: "POST",
        data: {id:$('#usar_delivery').val(),valores:valores},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                 delivery=$('#usar_delivery').val();
               app.dialog.alert('Delivery guardado');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}

function guardaRevo(){
    usuario=$('input:text[name=usuario_revo_integra]').val();
    token=$('input:text[name=token_revo_integra]').val();
    var usar_numero_revo=0;
    if ($('#usar_numero_revo').prop('checked')){
        usar_numero_revo=1;
    }
    var usar_modo_quiosco=0;
    if ($('#usar_modo_quiosco').prop('checked')){
        usar_modo_quiosco=1;
    }
    if (usuario=='' || token==''){
        app.dialog.alert('Usuario o Token erroneo');
        return;
    }
    var server=servidor+'admin/includes/integracion.php';
    $.ajax({
        type: "POST",
        data: {id:1,usuario:usuario,token:token, usar_numero_revo:usar_numero_revo,usar_modo_quiosco:usar_modo_quiosco},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function guardaStar(){
    var copias=$('input:text[name=copias_tickets]').val();
    var usar_modo_quiosco=0;
    if ($('#usar_modo_quiosco').prop('checked')){
        usar_modo_quiosco=1;
    }
    
    var server=servidor+'admin/includes/integracion.php';
    $.ajax({
        type: "POST",
        data: {id:'star',usar_modo_quiosco:usar_modo_quiosco,copias:copias},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}
           
function cambiaaRevo(){
    app.dialog.confirm('¿Seguro que desea cambia a Revo?<br>Eso supone, entre otras cosas, perder todos los productos ya guardados.','Cambio a Revo', function () {
            console.log('cambio a revo');
    }); 
    return;
    
}

function cambiaaStar(){
    app.dialog.confirm('¿Seguro que desea cambia a Star?<br>Eso supone, entre otras cosas, perder todos los productos ya guardados.','Cambio a Star', function () {
            console.log('cambio a revo');
    }); 
    return;
}

function UpdateDeviceTableIntegra() {
    
    $.get("devices.php" )
        .done(function(data) {
            var obj=JSON.parse(Object(data));
            if (obj.mac!='') {

                var table = "<table style='border-collapse: collapse; text-align: left;display: inline-block; background: #fff; overflow: hidden; border: 1px solid #000000; border-radius: 8px; font-size:.8em;'>"
                table += "<thead style='padding: 4px 7px; background:#00495e;color:#ffffff'><tr><th>Impresora</th><th>Status</th><th>Client Type (v)</th><th>Last Connection</th><th></th></thead>";
                table += '';
                var statuscolor='color:green;';
                $('#status-imprespra-icon').html('<i class="f7-icons" style="color:green;">printer</i>');
                $('#printer-name').html(obj.clientType);
                if (obj.status!='200 OK'){
                    statuscolor='color:red;';
                    $('#status-imprespra-icon').html('<i class="f7-icons" style="color:red;">printer</i>');
                }
                
                //var device = data;
                //var lastConnect = new Date(device.lastConnection);
                var lastConnect = new Date(1970, 0, 1);
                lastConnect.setSeconds(obj.lastConnection);
                lastConnect.setHours(lastConnect.getHours()+2);
                table += "<tr>";
                table += "<td style='padding: 4px 7px;"+statuscolor+"'>" + obj.mac + "</td>";
                
                table += "<td style='padding: 4px 7px;"+statuscolor+"'>" + obj.status + "</td>";
                table += "<td style='padding: 4px 7px;"+statuscolor+"'>" + obj.clientType + " (" + obj.clientVersion + ")</td>";
                table += "<td style='padding: 4px 7px;"+statuscolor+"'>" + lastConnect.toLocaleString() + "</td>";
                table += "</tr>"
                table += "</table>"

                $("#deviceListIntegra").html(table);
            }


            setTimeout(UpdateDeviceTableIntegra, 5000);
        })
        .fail(function() {
            setTimeout(UpdateDeviceTableIntegra, 10000);
        });               
}

function settingTR() {
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><h2>Ajustes Tarjetas Regalo</h2>'+
                '<div id="tarjeta-regalo-ajuste"></div>'+
            '</div>'+
          '</div>'  ,
        on: {
            open: function (popup) {
                var txt='<div class="list list-outline-ios list-strong-ios list-dividers-ios">'+
                    '<ul>'+
                    '<li class="">'+
                    '<label class="item-content">'+
                      '<div class="item-inner">'+
                        '<div class="item-title">Poner precio</div>'+
                        '<div class="item-after">'+
                        '<div class="toggle toggle-init">'+
                          '<input type="checkbox"  id="precio_en_tarjeta_regalo" onclick="cambiaprevioenTR();">'+
                          '<span class="toggle-icon"></span>'+
                        '</div>'+
                        '</div>'+
                      '</div>'+
                    '</li>'+
                    '</ul>'+
                    '</div>';
                $('#tarjeta-regalo-ajuste').html(txt);
                var server=servidor+'admin/includes/integracion.php';
                $.ajax({
                    type: "POST",
                    data: {id:'foo'},
                    url: server,
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            if (obj.precio_en_tr==1){
                            $('#precio_en_tarjeta_regalo').prop('checked',true);
                            }
                        }
                    }
                });
                
            },
            opened: function (popup) {
            //console.log('Popup opened');
            },
        }
    }); 
    dynamicPopup.open(); 
    
    
    
}
function cambiaprevioenTR(){
    var precio_en_tarjeta_regalo=0;
    if ($('#precio_en_tarjeta_regalo').prop('checked')){
        precio_en_tarjeta_regalo=1;
    }
        
    var server=servidor+'admin/includes/guardapreciotr.php';
    $.ajax({
        type: "POST",
        data: {precio_en_tr:precio_en_tarjeta_regalo},
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                app.popup.close();
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}
