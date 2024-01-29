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
                if (obj.integracion==2){
                    //star
                    txt=''+
                    '<div class="block-title block-title-medium">Integración Star<span id="button-cambia-Integración" class="button button-fill float-right" onclick="cambiaaRevo();">Cambiar a Revo</span> </div>'+
                    '<div class="block" style="font-size: 1.3em;"><h4>Configuración impresora</h4>'+
                    '<div id="deviceListIntegra">Buscando . . . </div>'+
                    '<p>Para conectar un nuevo dispositivo, configure su URL de CloudPRNT en:</p><br/>'+
                    '<div style="background-color: whitesmoke; font-weight: bold;border-color: black;border-style: solid;border-width: 1px;border-radius: 4px;padding: 4px;display: inline-block;"><span id="cpurl-integra">...</span></div></div>';
                    $('#integra-page').html(txt);
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
                                    '<div class="item-title item-label">Usuario Revo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="usuario_revo_integra" value="'+obj.usuario+'" placeholder="Usuario Revo"/>'+
                                    '</div>'+
                                 ' </div>'+
                                '</li>'+
                                '<li class="item-content item-input">'+
                                  '<div class="item-inner">'+
                                    '<div class="item-title item-label">Token Revo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="token_revo_integra" value="'+obj.token+'" placeholder="Token Revo"/>'+
                                   ' </div>'+
                                  '</div>'+
                                '</li>'+
                           ' </ul>'+
                        '</div>'+
                        '<div class="row"><button onclick="guardaRevo();" class="button button-fill" style="margin:auto;width: 60%;">Guardar</button></div>'
                    
                    $('#integra-page').html(txt);
                    

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

function guardaRevo(){
    usuario=$('input:text[name=usuario_revo_integra]').val();
    token=$('input:text[name=token_revo_integra]').val();
    if (usuario=='' || token==''){
        app.dialog.alert('Usuario o Token erroneo');
        return;
    }
    var server=servidor+'admin/includes/integracion.php';
    $.ajax({
        type: "POST",
        data: {id:1,usuario:usuario,token:token},
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

