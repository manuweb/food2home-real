
var $ = Dom7;
var version_backoffice='2.0.0';
var app = new Framework7({
  name: 'Food2Home Admin', // App name
  theme: 'ios', // Automatic theme detection
  el: '#app', // App root element
  language: 'es',    
    dialog: {
    theme: 'ios', // Automatic theme detection
    buttonCancel: 'Cancelar',
    color: '#fa3f00'

  },
colors: {
    // specify primary color theme
    primary: '#fa3f00'
},
smartSelect:{
   popupCloseLinkText: 'Cerrar' ,
    closeOnSelect: true,
}, 
on: {
      init: function () {
          
          window.jQuery = jQuery;
          window.$ = jQuery;
         
      }
},
textEditor: {
        imageUrlText:'URL de la imagen',
        customButtons: {
        // property key is the button id
        hr: {
          // button html content
          content: '&lt;Hr&gt;',
          // button click handler
          onClick(event, buttonEl) {
            document.execCommand('insertHorizontalRule', false);
          }
        }
      },
      // now we use custom button id "hr" to add it to buttons
      buttons: [
          ['bold', 'italic', 'underline', 'strikeThrough'],
          ['orderedList', 'unorderedList'],
          ['paragraph', 'h1', 'h2', 'h3'],
          ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],
          ['subscript', 'superscript'],
          ['indent', 'outdent','hr'],
      ]    
    }
    
});



app.theme='ios';
app.setColorTheme('#00495e')
$('.views').css('margin-right','260px');
$('.views').css('left','260px');
$('#email-user').html(window.localStorage.getItem("email"));
$('#nick-user').html(window.localStorage.getItem("nick"));

function abrepanel(){
    $('.panel-left').show();
    $('.views').css('margin-right','260px');
    $('.views').css('left','260px');
    $('.menu-left').hide();

}

function cierrapanel(){
    $('.panel-left').hide();
    $('.views').css('margin-right','0');
    $('.views').css('left','0');  
    $('.menu-left').show();
}

$('.navbar-inner .title-principal').html('<span style="position: relative;top:-12px;">'+app.name+'</span><img src="img/icono.png" width="auto" height="36px">');

$('.menu-left').hide();
$('#version-backoffice').html('versión:'+version_backoffice);

function navegar(destino) {
    app.panel.close();
    if (destino=='#view-setting-taxes'){
        
        leeimpuestos();      
    }
    if (destino=='#view-setting-alergenos'){
        
        leealergenos();      
    }  
    if (destino=='#view-setting-pagos'){
        
        leemetodospago();     
    }  
    if (destino=='#view-setting-store'){
        
        leeempresa();      
    }
    if (destino=='#view-productos'){
        muestragrupos(); 
            
    } 
    if (destino=='#view-modificadores'){
        muestraCatmodificadores(); 
            
    } 
    if (destino=='#view-modificadores2'){
        destino='#view-modificadores';
        muestraGrumodificadores();      
    }
    if (destino=='#view-setting-home'){
        paginainicio();      
    }   
    
    if ( window.innerWidth <= 600) {
        cierrapanel();
    }
    app.tab.show(destino);
 
}

var tienda=window.localStorage.getItem("tienda");
var nombreTiendas=['Master'];
console.log('tienda:'+tienda);
cambiaNombreTienda();
var tipousu=window.localStorage.getItem("tipousu");               console.log('tipousu:'+tipousu);


$(".tab" ).on('tab:show', function() {
    cambiaNombreTienda();
});

$('#my-login-screen .login-button').on('click', function () {
    var username = $('#my-login-screen [name="username"]').val();
    var password = $('#my-login-screen [name="password"]').val();
    var server=servidor+'admin/includes/login.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{username:username, password: password},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                console.log(obj);
                console.log('Tipo:'+obj.tipo);
                    window.localStorage.setItem("nick",username);
                   window.localStorage.setItem("tienda",obj.tienda); window.localStorage.setItem("email",obj.email);
                    window.localStorage.setItem("tipousu",obj.tipo);
                if(obj.tipo=='3'){
                    location.href='index_motos.php';
                }
                else {
                    
                    
                    if (obj.first==false){
                        $('#my-login-screen').hide();
                        $('#first-screen').show();
                        $('#breadcrumbs').html(progresscheckout(1));
                    }
                    else {
                        
                        location.href='index.php';
                    }
                    
                }
            }
        }
    });
                     

});

function cierra_sesion() {
    var server=servidor+'admin/includes/logout.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{username:'username'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                location.href='index.php'
            }
            else{
                console.log('ERROR');
            }
        }
    });
    
    
}

$('#cerrar-sesion').on('click', function () {
    cierra_sesion();      

});


var integracion=1;
var dosTarifas=0;
var idRedsys=0;
var in_push=0;
var in_mail=0;
var in_prom=0;
var in_multi=0;
leeEstaoWeb();

function leeEstaoWeb() {
    var server=servidor+'admin/includes/leeestadoweb.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                idRedsys=obj.idRedsys;
                in_mail=obj.mail;
                in_prom=obj.promos;
                in_push=obj.push;
                dosTarifas=obj.tarifa;
                in_multi=obj.multi;
                
                var nombre_comercial=obj.nombre_comercial;
                app.name=nombre_comercial;
                app.dialog.title=nombre_comercial+' BackOffice';
                document.title = nombre_comercial+' BackOffice';
                //nombreTiendas
                $('.navbar-inner .title-principal').html('<span style="position: relative;top:-12px;">'+nombre_comercial+'</span><img src="img/icono.png" width="auto" height="36px">');

                for(x=0;x<obj.alias.length;x++){
                    nombreTiendas[obj.id[x]]=obj.alias[x];
                }
                cambiaNombreTienda();
                integracion=obj.integracion;
                console.log('integracion:'+integracion);
                
                if (obj.on==0){
                    $('#web-app-off').prop("checked", false);
                }
                else {
                    $('#web-app-off').prop("checked", true);
                }
               
                if (obj.integracion==2){
                    $('#li-sincronizacion').hide();
                    $('#logo-integracion').attr("src",'img/logo-star.png');
                    UpdateDeviceTableIntegra();
                    $('#status-imprespra').show();
                }
                else {
                    $('#logo-integracion').attr("src",'img/logo-revo.png');
                    $('#grid-app-en-mantenimiento').removeClass('medium-grid-cols-2');
                    $('#grid-app-en-mantenimiento').addClass('medium-grid-cols-1');
                    
                }
                /*
                if (in_mail==0){
                    $('#in_mail_campain').hide();
                }
                if (in_prom==0){
                    $('#in_prom').hide();
                }
                if (in_push==0){
                    $('#in_push').hide();
                }
                if (in_multi==1){
                    for(x=0;x<obj.alias.length;x++){
                        nombreTiendas[obj.id[x]]=obj.alias[x];
                    }
                    cambiaNombreTienda();
                }
                */
            }
            else{
                console.log('ERROR Estado Web');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    }); 
}


function cambiaEstadoWeb(estado){
    //console.log(estado.checked);
    var server=servidor+'admin/includes/cambiaestadoweb.php';
    
    $.ajax({
        type: "POST",
        url: server,
        data:{ estado:estado.checked},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){}
            else{}
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    //document.getElementById('safari_window').contentWindow.location.reload();   

}

function cambiaNombreTienda (){
    var ant='<ul><li><a href="#" class="item-link smart-select smart-select-init selector-tienda" data-open-in="popup">';
    var select='<select class="seleccionTienda">';
    var pos='<div class="item-content"><div class="item-inner"><div class="item-title text-color-primary">Tienda:</div><div class="item-after nombretienda" style="color:#f35605"></div></div></div></a></li></ul>';
    var seleccionado='';
    var nombretienda=''
    for (x=0;x<nombreTiendas.length;x++){
        seleccionado='';
        if (parseInt(tienda)==x){
            seleccionado='selected';
            
            nombretienda=nombreTiendas[x];
        }
        
        select+='<option value="'+x+'" '+seleccionado+' >'+nombreTiendas[x]+'</option>';
    }

    select+='</select>';
   
    //$('.navbar-inner .select-tienda').css('color', 'white');
    $('.navbar-inner .select-tienda').html(ant+select+pos);
    $('.nombretienda').html(nombretienda);
    
    $('.seleccionTienda').on('change', function() {
        tienda= this.value ;
        var elemVisible="";
        $('.view-init').each(function(){
           if( $(this).hasClass('tab-active') ){
            elemVisible=this.id;
           }
        });
        window.localStorage.setItem("tienda",tienda);
        navegar('#'+elemVisible);
    });
}

if (autentificado!='1'){
    cierrapanel();
    $('.left').hide();
    $('.right').hide();
    $('.title-principal').css('width','100%');
    /*
    $('.views').css('margin-right','0');
    $('.views').css('left','0');  
    $('.panel-left').hide();
    $('.navbar').hide(); 
    */
}
else {
    abrepanel();
}





function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
    
   return result;
}

$('.primera-confirmar-button').on('click',function(){
    var id=$('.primera-confirmar-button').attr('data-id');

    var nombre_empresa=$('input:text[name=nombre_empresa]').val();
    var nombre_comercial=$('input:text[name=nombre_comercial]').val();
    var cif_empresa=$('input:text[name=cif_empresa]').val();
    var domicilio_empresa=$('input:text[name=domicilio_empresa]').val();
    var cp_empresa=$('input:text[name=cp_empresa]').val();
    var provincia_empresa=$('input:text[name=provincia_empresa]').val();
    var telefono_empresa=$('input:text[name=telefono_empresa]').val();


    var tipo_integracion=$('input:radio[name=tipo_integracion]:checked').val();

    var usuario_revo=$('input:text[name=usuario_revo]').val();
    var token_revo=$('input:text[name=token_revo]').val();

    if (id=='1'){
        var errores='';
        if (nombre_empresa==""){
            errores+="Nombre empresa, ";
        }
        if (nombre_comercial==""){
            errores+="Nombre comercial, ";
        }
        if (cif_empresa==""){
            errores+="CIF/NIF ";
        }
        if (errores!==''){
            app.dialog.alert('Deber completar:<br>'+errores);
            $('.primera-configuracion').show();
            return;
        }
        $('.primera-configuracion').hide();
        $('.segunda-configuracion').show();
        $('.primera-confirmar-button').attr('data-id',2);
        $('#breadcrumbs').html(progresscheckout(2));
    }
    if (id=='2'){
        $('.primera-configuracion').hide();
        $('.segunda-configuracion').hide();
        $('.tercera-configuracion').show();
        $('.primera-confirmar-button').attr('data-id',3);
        $('#breadcrumbs').html(progresscheckout(3));
        
        //console.log(tipo_integracion);
        
        if (tipo_integracion==1){
            $('.revo-configuracion').show();  
            $('.star-configuracion').hide();
        }
        else {
            $('.revo-configuracion').hide(); 
            $('.star-configuracion').show();
            //$("#cpurl").html(qualifyURL("cloudprnt.php"));
           
           
               $("#cpurl").html(window.location.href.replace("admin/index.php", "webapp/printer/cloudprnt.php"));
            UpdateDeviceTable();
        }
        
        
    }
    if (id=='3'){
        if (tipo_integracion==1){
            var errores='';
            if(usuario_revo==''){
                errores+='Usuario Revo, '    
            } 
            if(token_revo==''){
                errores+='Token Revo'    
            }
            if (errores!==''){
                app.dialog.alert('Deber completar:<br>'+errores);
                
                return;
            }
            
        } 
        
        var server=servidor+'admin/includes/guardaconfig.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{tipo_integracion:tipo_integracion,telefono_empresa:telefono_empresa,provincia_empresa:provincia_empresa,cp_empresa:cp_empresa,domicilio_empresa:domicilio_empresa,cif_empresa:cif_empresa,nombre_comercial:nombre_comercial,nombre_empresa:nombre_empresa,usuario_revo:usuario_revo,token_revo:token_revo},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    location.href='index.php';
                }
                else{
                    console.log('ERROR');
                }
            }
        });
        

    }

});

function checkout_1(){
    $('.primera-configuracion').show();
    $('.segunda-configuracion').hide();
    $('.tercera-configuracion').hide();
    $('.primera-confirmar-button').attr('data-id',1);
       
    $('#breadcrumbs').html(progresscheckout(1));
}

function checkout_2(){
    $('.primera-configuracion').hide();
    $('.segunda-configuracion').show();
    $('.tercera-configuracion').hide();
    $('.primera-confirmar-button').attr('data-id',2);
    $('#breadcrumbs').html(progresscheckout(2));
}

function progresscheckout(steep){
    var colorPrimario=app.colors['primary']
    var txt='<div class="breadcrumbs checkout" style="font-size: calc(.5em + 1vw);">';
    
    var separador='<div class="breadcrumbs-separator"></div>';
    
    var txt1='<div class="breadcrumbs-item">'+
            '<i class="icon material-icons">home</i>'+
            '<span>Inicio</span>'+
        '</div>';
    
    var txt2='<div class="breadcrumbs-item">'+
            '<i class="icon f7-icons">gear</i>'+
            '<span>Integración</span>'+
        '</div>';
    
    var txt3='<div class="breadcrumbs-item">'+
            '<i class="icon f7-icons">floppy_disk</i>'+
            '<span>Guardar</span>'+
        '</div>';  
    
    
   
    if (steep==1) {
        txt1='<div class="breadcrumbs-item" style="color:'+colorPrimario+';">'+
            '<i class="icon material-icons">home</i>'+
            '<span>Inicio</span>'+
        '</div>';
    }    
    if (steep==2) {
        txt1='<div class="breadcrumbs-item" style="color:'+colorPrimario+';"><a href="javascript:checkout_1();" class="link">'+
            '<i class="icon material-icons">home</i>'+
            '<span>Inicio</span>'+
        '</a></div>';
        txt2='<div class="breadcrumbs-item" style="color:'+colorPrimario+';">'+
            '<i class="icon f7-icons">gear</i>'+
            '<span>Integración</span>'+
        '</div>';
    }    
    if (steep==3) {
        txt1='<div class="breadcrumbs-item" style="color:'+colorPrimario+';"><a href="javascript:checkout_1();" class="link">'+
            '<i class="icon material-icons">home</i>'+
            '<span>Inicio</span>'+
        '</a></div>';
        txt2='<div class="breadcrumbs-item" style="color:'+colorPrimario+';"><a href="javascript:checkout_2();" class="link">'+
            '<i class="icon f7-icons">gear</i>'+
            '<span>Integración</span>'+
        '</a></div>';
        txt3='<div class="breadcrumbs-item" style="color:'+colorPrimario+';">'+
            '<i class="icon f7-icons">floppy_disk</i>'+
            '<span>Guardar</span>'+
        '</div>'; 
    }  
     
    txt=txt+txt1+separador+txt2+separador+txt3+'</div>';
    return(txt);
}


//var destino='#view-setting-home';
//app.tab.show(destino);

function qualifyURL(url) {
    
    var a = document.createElement('a');
    a.href = url.replace("admin/index.php", "webapp/printer");
    return a.href;
}

function UpdateDeviceTable() {
    $.get("devices.php" )
        .done(function(data) {
            var obj=JSON.parse(Object(data));
            if (obj.mac!='') {

                var table = "<table style='border-collapse: collapse; text-align: left;display: inline-block; background: #fff; overflow: hidden; border: 1px solid #000000; border-radius: 8px; font-size:.8em;'>"
                table += "<thead style='padding: 4px 7px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #fa3f00), color-stop(1, #fa3f00) );'><tr><th>Impresora</th><th>Status</th><th>Client Type (v)</th><th>Last Connection</th><th></th></thead>";
                table += '';

                //var device = data;
                //var lastConnect = new Date(device.lastConnection);
                var lastConnect = new Date(1970, 0, 1);
                lastConnect.setSeconds(obj.lastConnection);
                lastConnect.setHours(lastConnect.getHours()+2);
                table += "<tr>";
                table += "<td style='padding: 4px 7px;'>" + obj.mac + "</td>";
                table += "<td style='padding: 4px 7px;'>" + obj.status + "</td>";
                table += "<td style='padding: 4px 7px;'>" + obj.clientType + " (" + obj.clientVersion + ")</td>";
                table += "<td style='padding: 4px 7px;'>" + lastConnect.toLocaleString() + "</td>";
                table += "</tr>"
                table += "</table>"

                $("#deviceList").html(table);
            }


            setTimeout(UpdateDeviceTable, 5000);
        })
        .fail(function() {
            setTimeout(UpdateDeviceTable, 10000);
        });               
}

var loadFileImg = function(event,elem) {
    var img="";
    var reader = new FileReader();
    reader.onload = function(){
        
      var output = $(elem);
        var contents = $(elem)[0];
        
      output.src = reader.result;
        contents.src=output.src;
        $(elem).show();
        
    };
    reader.readAsDataURL(event.target.files[0]);
    img=event.target.files[0];

}

if ( window.innerWidth <= 600) {
    cierrapanel();
}
