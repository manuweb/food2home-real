var carrito = [],carritohtml = "El carrito está vacío",
    carritoSubtotal = 0, portes = 0, totalItemCarrito=0,datos_cliente = [];

var map;
var estadopanel;

function initMap() {
 map = new google.maps.Map(document.getElementById("map"), {
    center: {  lat: 36.60113320, lng: -6.22815830 },
    zoom: 18,
  });
}



function hacerPedido(){
    //app.dialog.alert('delivery:'+delivery);
    var d = new Date();
    //d.setMinutes(d.getMinutes()+minutos);
    //var day = d.getDay();
    //var dia=new Date();
    //var mil=d.getTime();
    //console.log('tiempoenvio:'+tiempoenvio);
    var hoy = diaCastellano(d.getDate()) + "/" + mesCastellano(d.getMonth()) + "/" + d.getFullYear();  
    
    fecha_pedido=hoy;
    $('#pedidos-page').hide();
    $('#pedido-manual').show();
    $('#boton-hacer-pedido').html('cancelar');
    $('#boton-hacer-pedido').css('background-color','#f35605');
    $('#pedidos-titulo').html('Hacer un pedido');
    $('#boton-hacer-pedido').attr('onclick', 'leepedidos()');
    
    estadopanel=$('.panel-left').css('display');
    $('#boton-hacer-pedido').attr('onclick', "borradatospedido();$('#view-pedidos').css('position','relative');$('.panel-left').css('display','"+estadopanel+"');$('.navbar').show();leepedidos();");
    //console.log(estadopanel);
    $('.panel-left').css('display','none');
    // position: fixed; width: 100%;left: 0;
    $('#view-pedidos').css('position','fixed');
    $('#view-pedidos').css('width','100%');
    $('#view-pedidos').css('left','0');
    $('.navbar').hide();
    
    
    var txt='<div class="block block-strong inset" id="pedido-manual-pagina"></div>';
    $('#pedido-manual').html(txt);
    txt='<div class="grid grid-cols-2">'+
        '<div style="margin:10px;"><button class="button button-outline" style="height: auto;font-size: 18px;padding:5px;" onclick="hacerPedidoCuando(2)"><img src="img/commerce.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 40px;">&nbsp;Recoger</button></div>'+
        '<div style="margin:10px;"><button class="button button-outline" style="height: auto;font-size: 18px;padding:5px;" onclick="hacerPedidoCuando(1)"><img src="img/delivery.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 40px;">&nbsp;Llevar</button></div>'+
        '</div>';
    $('#pedido-manual-pagina').html(txt);
        
    
    // filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);
    
    // filter: invert(100%) sepia(0%) saturate(7500%) hue-rotate(113deg) brightness(106%) contrast(105%);
  
}

function hacerPedidoCuando(modo){
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido();"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    //llenafechaspedido(dias_vista);
    
    if (dias_vista>1){
        txt+=llenafechaspedido(dias_vista,modo);
    }
    else {
        txt+='<p style="font-size:18px;">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    }
    txt+='<p class="block-title-medium" id="titulo-la-hora-pedido">Seleccionar hora</p><p class="grid grid-cols-5 grid-gap" id="la-hora-pedido"></p>';
    $('#pedido-manual-pagina').html(txt);
    hacerPedidollenahoras(fecha_pedido,modo);
}

function hacerPedidollenahoras(fecha,modo){
    //fecha_pedido=fecha;

    
    $('#la-fecha-pedido').html(fecha_pedido);
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==2){
        txt_modo='Domicilio';
        icono='delivery';
    }
    //console.log('Modo:'+txt_modo);
    //console.log('fecha:'+fecha);
    
    var server=servidor+'admin/includes/mirapedidoshoy.php';
    //console.log(fecha_pedido);
    
    $.ajax({
        url: server,
        data:{modo:modo,fecha:fecha},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                var horario=obj.horario
                //console.log(horario);
                
                if (horario!=null){
                    //console.log(horario);
                    var disponibles=0;
                    var estado_boton='button-raised';
                    var txt='';
                    var deshabilitado='';
                    for(x=0;x<horario.length;x++){
                        //console.log(horario[x]);
                        if (horario[x]['disponible']==1){
                            disponibles=1;
                            deshabilitado='';
                            estado_boton='';
                        }
                        else {
                            deshabilitado='disabled';
                            estado_boton='button-tonal';
                        }
                        txt+='<button class="button-hora-reparto button button-large button-raised '+estado_boton+'" data-hora="'+horario[x]['hora']+'" style="line-height: 18px;" onclick="cambiahorapedido(this,'+modo+');" '+    deshabilitado+'><p><b>'+horario[x]['hora']+'</b><br><span style="font-size:14px;">'+(obj.maximo-horario[x]['cantidad'])+'</span></p></button>';
                        
                        
                    }
                    //console.log(txt); 
                    $('#la-hora-pedido').removeClass('grid-cols-5');
                    $('#la-hora-pedido').addClass('grid-cols-5');
                    $('#la-hora-pedido').html(txt);
                    $('#titulo-la-hora-pedido').show();
                }
                else {
                    //console.log('No hay horas');grid-cols-2
                    $('#la-hora-pedido').removeClass('grid-cols-5');
                    $('#la-hora-pedido').html('<span style="color:#f35605;font-size:20px;text-align: center;">NO HAY HORAS DISPONIBLE</span>');
                    $('#titulo-la-hora-pedido').hide();
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

function cambiahorapedido(elem,modo){
    var elemento=$(elem);
    var hora=elemento.attr('data-hora');
    hora_pedido=hora;
    var txt='<p style="font-size:18px">Hora: <b><span id="la-hora-pedido" style="color:#00495e;">'+hora+'</span></b></p>';
    $('#sel-la-fecha-pedido').hide();
    $('#la-hora-pedido').html('');
    $('#titulo-la-hora-pedido').html(txt);
    $('#titulo-la-hora-pedido').removeClass('block-title-medium');
    hacerPedido2(modo);
}

function cambiaDiaEnvioCalendar(modo){
    
    $('.button-fecha-reparto').removeClass('button-fill');
    $('.button-fecha-reparto').addClass('button-raised');
    $('#calendario-para-otro-dia').removeClass('button-raised');
    $('#calendario-para-otro-dia').addClass('button-fill');
    var inicio=new Date();
    var fin=new Date();
    fin.setDate(fin.getDate()+parseInt(dias_vista));
    inicio.setDate(inicio.getDate()+5);
    
    var maxD = new Date(8640000000000000);
    var minD = new Date(-8640000000000000);
    inicio.setDate(inicio.getDate() - 1);
    var primi=new Date();
    primi.setDate(primi.getDate() + 5);

    var calendarDateTime = app.calendar.create({
        inputEl: '#fecha_pedido',
        value: [primi ],
        disabled: [
          {
            from: minD,
            to: inicio,
          },
          {
            from: fin   ,
            to: maxD
          }
        ],
        on:{

            close: function(){
                var dia=this.getValue();
                var fecha=diaCastellano(dia[0].getDate()) + "/" + mesCastellano(dia[0].getMonth()) + "/" + dia[0].getFullYear();
                //console.log(fecha);
                cambiaDiaEnviodesdeCalendario(fecha,modo);
                

            }
        },
        openIn: 'customModal',
        //header: true,
        closeOnSelect:true,
        dateFormat: 'dd/mm/yyyy'
        //footer: true,
    });
    
    calendarDateTime.open();
    
}

function hacerPedido2(modo){
    borradatospedido();
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedidoCuando('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div class="" style="display: flex;">';
    
    //txt+='<input id="hideKeyboard" style="position: absolute; left: 0px; top: -20px; z-index: -1;" type="text" name="hideKeyboard" readonly="readonly" />';
    txt+='<div class="list" style="width: 40%;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Teléfono</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="teléfono del cliente" id="autocomplete-telefono-cliente" />'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
    
     
    txt+='<div class="list" style="width: 40%;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Nombre</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="nombre del cliente" id="autocomplete-nombre-cliente" />'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
      
    
    txt+='<div class="list" style="width: 20%;">'+
        '<ul>'+
          '<li class="item-content">'+
            '<div class="item-inner">'+
              '<button class="button button-fill button-round" style="font-size: 18px; float: left;top: 15px;" onclick="hacerPedidoNuevoCliente('+modo+');" id="hacer-pedido-nuevo-ciente-1">Nuevo</button>'
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
    txt+='</div>';  
    $('#pedido-manual-pagina').html(txt);
    var server=servidor+'admin/includes/leeclientes2.php';             $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                var clientes=obj.clientes;
                var datos=new Array();
                var datos2=new Array();
                for(j=0;j<clientes.length;j++){
                    
                    datos.push(clientes[j]['apellidos']+', '+clientes[j]['nombre']);
                    datos2.push(clientes[j]['telefono']);
                }
                
                
                autocompleteDropdownTypeahead = app.autocomplete.create({
                   openIn: 'dropdown',
                    inputEl: '#autocomplete-nombre-cliente',
                    //dropdownPlaceholderText: 'Use "apellidos,nombre"',
                    typeahead: true,
                    closeOnSelect: true, 
                    source: function (query, render) {
                      var results = [];
                      if (query.length === 0) {
                        render(results);
                        return;
                      }
                      // Find matched items
                        //$('#hacer-pedido-nuevo-ciente-1').hide(); 
                      for (var i = 0; i < datos.length; i++) {
                        if (datos[i].toLowerCase().indexOf(query.toLowerCase()) === 0) {
                            results.push(datos[i]);
                            
                             }
                          else {
                             // $('#hacer-pedido-nuevo-ciente-1').show();
                          }
                      }
                      // Render items by passing array with result items
                      render(results);
                        window.scrollTo(0, document.body.scrollHeight);
                    },
                    on:{
        
                        close: function(ac){
                            //console.log(ac.value[0]);
                            var position = datos.indexOf(ac.value[0]);
                            //console.log(clientes[position]);
                            //alert(position);
                            //alert(ac.value[0]);
                            if (position>=0){
                               if ($('#autocomplete-nombre-cliente').val()==ac.value[0]){                         hacerPedido3(modo,clientes[position]['id'],clientes[position]);
                                }
                            }
                            
                        }
                    } 
                  });   
                
                autocompleteDropdownTypeahead = app.autocomplete.create({
                    inputEl: '#autocomplete-telefono-cliente',
                    openIn: 'dropdown',
                    //dropdownPlaceholderText: 'Use "apellidos,nombre"',
                    typeahead: true,
                    source: function (query, render) {
                      var results = [];
                        
                      if (query.length === 0) {
                        render(results);
                        return;
                      }
                        //$('#hacer-pedido-nuevo-ciente-1').hide(); 
                      // Find matched items
                      for (var i = 0; i < datos2.length; i++) {
                        if (datos2[i].toLowerCase().indexOf(query.toLowerCase()) === 0) {
                            results.push(datos2[i]);
                                                                                     }
                          else {
                              //$('#hacer-pedido-nuevo-ciente-1').show();
                          }
                      }
                      // Render items by passing array with result items
                        window.scrollTo(0, document.body.scrollHeight);
                      render(results);
                    },
                    on:{

                        close: function(ac){
                            //console.log(ac.value);
                            var position = datos2.indexOf(ac.value[0]);
                            //console.log(clientes[position]);
                            //console.log(position);
                            if (position>=0){
                                hacerPedido3(modo,clientes[position]['id'],clientes[position]);
                            }
                        }
                    } 
                  });  
                  

            }
        
        },
        error: function(e){
            console.log('error');
        }
    });
    
}

function hacerPedidoNuevoCliente(modo){
    //console.log($('#autocomplete-telefono-cliente').val());
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
    }

    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div class="block-title block-title-medium">Datos del cliente</div>';
    txt+='<div class="list" style="margin: 0;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Teléfono</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="teléfono del cliente" id="pedido-telefono-cliente" value="'+$('#autocomplete-telefono-cliente').val()+'"/>'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
    txt+='<div class="list" style="margin: 0;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Nombre</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="nombre del cliente" id="pedido-nombre-cliente" value="'+$('#autocomplete-nombre-cliente').val()+'"/>'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
    txt+='<div class="list" style="margin: 0;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Apellidos</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="apellidos del cliente" id="pedido-apellidos-cliente"/>'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div><br>';
    if (modo==1){
        //enviar
        //
        txt+='<iframe id="google_place" name="google_place" border="0" scrolling="no" marginwidth="0" marginheight="0" style="height: 250px;border: 0;width: 100%;"></iframe>';
        
        
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Domicilio</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Domicilio" id="pedido-domicilio-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Población</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Población" id="pedido-domicilio-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Cod. Postal</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Código postal" id="pedido-cod_postal-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Provincia</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Provincia" id="pedido-provincia-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<input type="hidden" id="pedido-lat-cliente"/>'+
            '<input type="hidden" id="pedido-lng-cliente"/>';
    }
    
    txt+='<button class="button button-outline button-round" disabled style="margin:auto;width: 25%;" id="hacer-pedido-nuevo-cliente">Continuar</button>';
    
    $('#pedido-manual-pagina').html(txt);
    
    if (modo==1){
        
        document.all.google_place.src = 'includes/google/index.html';
        
        
    }
    else {
        $('#hacer-pedido-nuevo-cliente').addClass('button-fill');
        $('#hacer-pedido-nuevo-cliente').removeAttr('disabled');
    }
    
   
    
    $('#hacer-pedido-nuevo-cliente').on ('click',function(){
        var errores='';
        if ($('#pedido-telefono-cliente').val()==''){
            errores+='Telefono,';
        }
        if ($('#pedido-nombre-cliente').val()==''){
            errores+='Nombre,';
        }
        if ($('#pedido-apellidos-cliente').val()==''){
            errores+='Apellidos,';
        }
        if (modo==1){
            if ($('#pedido-domicilio-cliente').val()==''){
                errores+='Domicilio,';
            }
            if ($('#pedido-poblacion-cliente').val()==''){
                errores+='Población,';
            }
            if ($('#pedido-cod_postal-cliente').val()==''){
                errores+='Cod. Postal,';
            }
            if ($('#pedido-provincia-cliente').val()==''){
                errores+='Provincia,';
            }
        }
        else {
            domicilio_cliente=[];
            datos_cliente = {
                'cliente':cliente,
                'domicilio':domicilio_cliente
            };
        }
        if (errores!=''){
            app.dialog.alert('Deble completar:<br>'+errores);
            return;
        }
        else {
            
            cliente['telefono']=$('#pedido-telefono-cliente').val();
            cliente['nombre']=$('#pedido-nombre-cliente').val();
            cliente['apellidos']=$('#pedido-apellidos-cliente').val();
            cliente['id']='Nuevo';
            
            if (modo==1){
                
                 //cliente['direccion']=$('#pedido-domicilio-cliente').val()+'##'+$('#pedido-poblacion-cliente').val()+'##'+$('#pedido-cod_postal-cliente').val()+'##'+$('#pedido-provincia-cliente').val();
                
                 hacerPedido3(modo,'Nuevo',cliente);
                 
            }
            else {
                
                domicilio_cliente=[];
                datos_cliente = {
                    'cliente':cliente,
                    'domicilio':domicilio_cliente
                };
                hacerPedido3(modo,'Nuevo',cliente);
            }
            
        }
        
    });
    
}

window.datosdegoogle= function (datos){
    domicilio_cliente=datos;
    var server=servidor+'admin/includes/leezonasrepartospedidos.php';
    //console.log(fecha_pedido);
    
    $.ajax({
        url: server,
        data:{foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                var precio=obj.precio;
                var minimo=obj.minimo;
                var reparto=obj.reparto;
                var zonas=obj.zonas;
                var esta='no';
                var noreparto='no';
                var buscar=new google.maps.LatLng(parseFloat(datos['lat']), parseFloat(datos['lng']));
                for(x=0;x<zonas.length;x++){
                    for (j=0;j<zonas[x].length;j++){
                        zonas[x][j]={ "lat": parseFloat(zonas[x][j]['lat']), "lng": parseFloat(zonas[x][j]['lng']) };
                    }
                    var polygon = new google.maps.Polygon({paths:zonas[x],map: map});
                    //console.log(buscar);
                    var isWithinPolygon = google.maps.geometry.poly.containsLocation(buscar, polygon);
                    if (isWithinPolygon){
                        esta='si';
                        portes=precio[x];
                        pedidominimo=minimo[x];
                        if (reparto[x]=='0'){
                            // zona no reparto
                            noreparto='si';
                        }       
                    }
                }
                if ((esta=='si')&&(noreparto=='no')) {
                    
                    $('#google_place').hide();
                    $('#hacer-pedido-nuevo-cliente').addClass('button-fill');
                    $('#hacer-pedido-nuevo-cliente').removeAttr('disabled');
                    //console.log(domicilio_cliente);
                    $('#pedido-domicilio-cliente').val(datos['direccion']);
                    $('#pedido-poblacion-cliente').val(datos['poblacion']);
                    $('#pedido-cod_postal-cliente').val(datos['cod_postal']);
                    $('#pedido-provincia-cliente').val(datos['provincia']);
                    $('#pedido-lat-cliente').val(datos['coordenadas']['lat']);
                    $('#pedido-lng-cliente').val(datos['coordenadas']['lng']);
                    $('#domicilio-cliente-pedido').show();
                    $('#boton-buscar-domicilios').hide();  
                    
                }
                else {
                    app.dialog.alert('El domicilio seleccionado NO está en nuestra zona de reparto');

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

function hacerPedidoCliente(modo,tipo,cliente) {
    //console.log(cliente);
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Cliente: <b>'+cliente['apellidos']+', '+cliente['nombre']+'</b></span><br>'+
    '<span>Teléfono:<b>'+cliente['telefono']+'</b></span></button></div>'; 
    txt+='<br><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;text-transform: inherit;width: auto;float: left; height: 35px;" id="boton-buscar-domicilios" onclick="buscaDomiciliosPedido(\''+cliente['id']+'\',\''+cliente['nombre']+'\',\''+cliente['apellidos']+'\');"><i class="f7-icons">house</i> Elegir domicilio</button><br>';
    txt+='<iframe id="google_place" name="google_place" border="0" scrolling="no" marginwidth="0" marginheight="0" style="height: 250px;border: 0;width: 100%;"></iframe>';
        
    txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Domicilio</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Domicilio" id="pedido-domicilio-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Población</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Población" id="pedido-poblacion-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Cod. Postal</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Código postal" id="pedido-cod_postal-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
        txt+='<div class="list" style="margin: 0;display:none;">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Provincia</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Provincia" id="pedido-provincia-cliente"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>';
    txt+='<input type="hidden" id="dato-id-cliente" value="'+cliente['id']+'">'+
        '<input type="hidden" id="dato-nombre-cliente" value="'+cliente['nombre']+'">'+
        '<input type="hidden" id="dato-apellidos-cliente" value="'+cliente['apellidos']+'">'+
        '<input type="hidden" id="dato-telefono-cliente" value="'+cliente['telefono']+'">';
    txt+='<input type="hidden" id="pedido-lat-cliente"/>'+
            '<input type="hidden" id="pedido-lng-cliente"/>';
    txt+='<div id="domicilio-cliente-pedido"></div>';
    
    txt+='<button class="button button-outline button-round" disabled style="margin:auto;width: 25%;" id="hacer-pedido-nuevo-cliente">Continuar</button>';
    
    $('#pedido-manual-pagina').html(txt);
    
    document.all.google_place.src = 'includes/google/index.html';
    
    $('#hacer-pedido-nuevo-cliente').on ('click',function(){
        var errores='';
        
        if ($('#pedido-domicilio-cliente').val()==''){
            errores+='Domicilio,';
        }
        if ($('#pedido-poblacion-cliente').val()==''){
            errores+='Población,';
        }
        if ($('#pedido-cod_postal-cliente').val()==''){
            errores+='Cod. Postal,';
        }
        if ($('#pedido-provincia-cliente').val()==''){
            errores+='Provincia,';
        }
 
        if (errores!=''){
            app.dialog.alert('Deble completar:<br>'+errores);
            return;
        }
        else {
                
             //cliente['direccion']=$('#pedido-domicilio-cliente').val()+'##'+$('#pedido-poblacion-cliente').val()+'##'+$('#pedido-cod_postal-cliente').val()+'##'+$('#pedido-provincia-cliente').val();
             domicilio_cliente={
                 'direccion':$('#pedido-domicilio-cliente').val(),
                 'poblacion':$('#pedido-poblacion-cliente').val(),
                 'cod_postal':$('#pedido-cod_postal-cliente').val(),
                 'provincia':$('#pedido-provincia-cliente').val(),
                 'coordenadas':{'lat':$('#pedido-lat-cliente').val(),'lng':$('#pedido-lng-cliente').val()}
             }
            datos_cliente = {
                'cliente':cliente,
                'domicilio':domicilio_cliente
            };
            
            txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
            txt+='<br><br>';
            txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
            txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
            txt+='<div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Cliente: <b>'+cliente['apellidos']+', '+cliente['nombre']+'</b></span><br>'+
            '<span>Teléfono:<b>'+cliente['telefono']+'</b></span></button></div>';

            txt+='<br><div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Domicilio:</span> <br><span><b>'+domicilio_cliente['direccion']+'</b></span><br>'+
            '<span><b>'+domicilio_cliente['cod_postal']+' - '+domicilio_cliente['poblacion']+' ('+domicilio_cliente['provincia']+')</b></span><br></button></div>';
            txt+='<input type="hidden" id="modo-pedido" value="1">';

            txt+='<br><div>'+
            '<button class="button button-outline button-round text-align-left" style="height:auto;font-size: 18px;text-transform: inherit;width: auto;float: left; height: 45px;" id="boton-pedidos-anteriores" onclick="BuscaPedidosnteriores();"><i class="f7-icons">bag</i> Pedidos anteriores</button>'+
            '<button class="button button-outline button-round" style="height:45px;font-size: 18px;width: auto;float: right;" onclick="muestracarrito();">Importe &nbsp; &nbsp; <span id="importe-en-carrito">0 €</span><i class="f7-icons" style="color:#'+colorprimario+'">cart</i><span class="badge cantidad-cart" style="top: -10px;left: -5px;background-color: #'+colorsecundario+'">0</span></button>'+
            '</div><br><br>';
            
            txt+='<div id="div-tienda-pedido"></div>';

            txt+='<br><button class="button button-outline button-round" disabled style="margin:auto;width: 50%;font-size: 18px;height:45px;" id="hacer-pedido-nuevo-finalizar" onclick="finalizarpedido();">Finalizar Pedido <span id="total-carrito-pedido" style="margin-left: 50px;">0 €</span></button>';

            $('#pedido-manual-pagina').html(txt);
            if (cliente['id']=='Nuevo'){
                $('#boton-pedidos-anteriores').hide();
            }
            muestragrupospedido();
       
        }
    });
}

function hacerPedido3(modo,tipo,cliente){
   // console.log(cliente);
  //alert(importeportesgratis);
    //console.log(tipo);
    //alert(cliente['nombre']);
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Cliente: <b>'+cliente['apellidos']+', '+cliente['nombre']+'</b></span><br>'+
    '<span>Teléfono:<b>'+cliente['telefono']+'</b></span></button></div>';
    
    
    
    if (modo==1){
        if (tipo!='Nuevo') {
            //alert(modo);
            hacerPedidoCliente(modo, tipo, cliente);
            return;
        }
        //console.log(domicilio_cliente);
        txt+='<br><div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Domicilio:</span> <br><span><b>'+domicilio_cliente['direccion']+'</b></span><br>'+
        '<span><b>'+domicilio_cliente['cod_postal']+' - '+domicilio_cliente['poblacion']+' ('+domicilio_cliente['provincia']+')</b></span><br></button></div>';
        

            
    }
    else {
        domicilio_cliente=[];
    }
    
    
    
    txt+='<br><div>'+
            '<button class="button button-outline button-round text-align-left" style="height:auto;font-size: 18px;text-transform: inherit;width: auto;float: left; height: 45px;" id="boton-pedidos-anteriores" onclick="BuscaPedidosnteriores();"><i class="f7-icons">bag</i> Pedidos anteriores</button>'+
            '<button class="button button-outline button-round" style="height:45px;font-size: 18px;width: auto;float: right;" onclick="muestracarrito();">Importe &nbsp; &nbsp; <span id="importe-en-carrito">0 €</span><i class="f7-icons" style="color:#'+colorprimario+'">cart</i><span class="badge cantidad-cart" style="top: -10px;left: -5px;background-color: #'+colorsecundario+'">0</span></button>'+
            '</div><br><br>';
    txt+='<input type="hidden" id="modo-pedido" value="'+modo+'">';
    //txt+='<button class="button button-fill button-round" style="margin:auto;width: 25%;font-size: 18px;" id="add-producto-pedido">+ Añadir Producto</button><br>';
    
    txt+='<div id="div-tienda-pedido"></div>';
    
    txt+='<br><button class="button button-outline button-round" disabled style="margin:auto;width: 50%;font-size: 18px;height:45px;" id="hacer-pedido-nuevo-finalizar" onclick="finalizarpedido();">Finalizar Pedido <span id="total-carrito-pedido" style="margin-left: 50px;">0 €</span></button>';
    $('#pedido-manual-pagina').html(txt);
    
    datos_cliente = {
        'cliente':cliente,
        'domicilio':domicilio_cliente
    };
    
    if (cliente['id']=='Nuevo'){
        $('#boton-pedidos-anteriores').hide();
    }
    //console.log(datos_cliente);

    muestragrupospedido();
    
}

function BuscaPedidosnteriores(){
    var id=datos_cliente['cliente']['id'];
    var nombre=datos_cliente['cliente']['nombre'];
    var apellidos=datos_cliente['cliente']['apellidos'];
    
    //console.log(id);
    var server=servidor+'admin/includes/buscaPedidosPedido.php';
    //console.log(fecha_pedido);
    
    $.ajax({
        url: server,
        data:{id:id, nombre:nombre, apellidos:apellidos},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                var pedidos=obj.pedidos;
                //console.log(pedidos);
                var dynamicPopup = app.popup.create({
                    content: ''+
                      '<div class="popup">'+
                        '<div class="block page-content">'+
                          '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><h2>Pedidos de: '+apellidos+', '+nombre+'</h2>'+
                            '<div id="detalleotrospedidos"></div>'+
                        '</div>'+
                      '</div>'  ,
                    on: {
                        open: function (popup) {
                            var txt='<div class="data-table"><table>'+
                            '<thead>'+
                              '<tr>'+
                                '<th class="label-cell">Número</th>'+
                                '<th class="label-cell">Día</th>'+
                                '<th class="label-cell">Hora</th>'+
                                '<th class="numeric-cell">Total</th>'+
                                '<th class="label-cell">Método</th>'+
                              '</tr>'+
                            '</thead>'+
                            '<tbody>';
                            var fecha='';
                            var dia='';
                            var metodo='';
                            for (x=0;x<pedidos.length;x++){
                               
                                fecha=fecha_php_a_castellano(pedidos[x]['fecha']);
                                dia=fecha_php_a_castellano(pedidos[x]['dia']);;
                                if (dia=='00/00/0000'){
                                    dia=fecha;
                                }
                                metodo=servidor+'admin/img/delivery.svg';
                                if (pedidos[x]['envio']=='2'){
                                    metodo=servidor+'admin/img/commerce.svg';
                                } 
                                txt+=''+
                                    '<tr onclick="verelpedidocliente('+pedidos[x]['id']+',\''+pedidos[x]['numero']+'\');" style="cursor:pointer;">'+
                                        '<th class="label-cell">'+pedidos[x]['numero']+'</th>'+
                                        '<th class="label-cell">'+dia+'</th>'+
                                        '<th class="label-cell">'+pedidos[x]['hora']+'</th>'+
                                        
                                        '<th class="numeric-cell">'+pedidos[x]['total']+'</th>'+
                                        '<th class="label-cell"><img src="'+metodo+'" width="24" height="auto"></th>'+
                                      '</tr>';
                            }
                            txt+='</tbody>'+
                                '</table></div><br><br>';
                            $('#detalleotrospedidos').html(txt);
                        },
                        opened: function (popup) {
                        //console.log('Popup opened');
                        },
                    }
                }); 
                dynamicPopup.open();                

            }else {
                app.dialog.alert('No se encontraron pedidos');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            app.dialog.alert('Error buscando pedidos ('+xhr.status+')');
        }
    });
    
}

function verelpedidocliente(id,numero){
   var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><h2>Detalles del pedido '+numero+'<span class="button button-fill" style="float: right;height: 44px;" onclick="app.popup.close();repetirpedido('+id+');">Repetir</span></h2>'+
                '<div id="detallepedido"></div>'+         
            '</div>'+
          '</div>'  ,
        on: {
         open: function (popup) {
            //$('#detallepedido').html(numero);
             
              var txt='';
            var server=servidor+'admin/includes/leeunpedido.php';     $.ajax({
                type: "POST",
                url: server,
                data: {idpedido:id},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                   //console.log(obj);
                     
                    if (obj.valid==true){
                        //console.log(obj.order);
                        var order=obj.order;
                        var carrito=order['carrito'];
                        
                        var haymenu=0;
                        for(var x=0;x<carrito.length;x++){
                           if (carrito[x]['menu']>0){
                               haymenu=1;
                           } 
                        }
                        
                        
                        if (order['metodo']==1){
                            txt +='<b>ENVIADO A</b>:<br>';
                            txt +='&emsp;'+order['domicilio']['direccion']+'<br>';
                            if (order['domicilio']['complementario']!=""){
                                txt +='&emsp;'+order['domicilio']['complementario']+'<br>';
                            }
                            txt +='&emsp;'+order['domicilio']['cod_postal']+' - '+order['domicilio']['poblacion']+' ('+order['domicilio']['provincia']+')<br>';
                        }
                        else {
                            txt +='<b>RECOGIDA EN LOCAL</b><br>';
                        }
                        if (order['comentario']!=''){
                            txt +='<hr>Notas: '+order['comentario'] ;
                        }
                        if (order['tarjeta']!=idRedsys){
                            txt +='<hr>Pago: <b>EFECTIVO</b><hr>';
                        }
                        else {
                            txt +='<hr>Pago: <b>TARJETA</b><hr>';
                        }
                        txt+='<div class="card data-table">'+
                            '<table>'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th class="numeric-cell">Cantidad</th>'+
                                        '<th class="label-cell">Articulo</th>'+
                                        '<th class="numeric-cell">Precio</th>'+
                                        '<th class="numeric-cell">Total</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
                                    
                        for(var x=0;x<carrito.length;x++){
                            txt+=''+
                                    '<tr>'+
                                        '<th class="numeric-cell">'+carrito[x]['cantidad']+'</th>'+
                                        '<th class="label-cell">'+carrito[x]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['precio_sin']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['cantidad']*carrito[x]['precio']).toFixed(2)+'</th>'+
                                    '</tr>';

                            if (typeof carrito[x]['elmentosMenu']!='undefined'){
                                for(var j=0;j<carrito[x]['elmentosMenu'].length;j++){
                                    txt+=''+
                                    '<tr style="font-size:11px;font-style:italic;">'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell">'+carrito[x]['elmentosMenu'][j]['cantidad']+' x '+carrito[x]['elmentosMenu'][j]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['elmentosMenu'][j]['precio']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell"></th>'+
                                    '</tr>';
                                }
                            }   
                            if (typeof carrito[x]['modificadores']!='undefined'){
                                for(var j=0;j<carrito[x]['modificadores'].length;j++){
                                    txt+=''+
                                    '<tr style="font-size:11px;font-style:italic;">'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell">'+carrito[x]['modificadores'][j]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['modificadores'][j]['precio']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell"></th>'+
                                    '</tr>';
                                }
                            }      
                            if (carrito[x]['comentario']!=''){
                                txt+=''+
                                    '<tr>'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell"><i>'+carrito[x]['comentario']+'</i></th><th class="numeric-cell"></th><th class="numeric-cell"></th>'+
                                    '</tr>';
                            }    
                        }
                        txt+=''+
                            '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Subtotal</th>'+
                                '<th class="numeric-cell">'+(parseFloat(order['subtotal'])).toFixed(2)+'</th>'+
                            '</tr>';
                        if (order['portes']!=0){
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Portes</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['portes']).toFixed(2)+'</th>'+
                            '</tr>';
                        }      
                        if (order['descuento']!=0){
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Descuento</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['descuento']).toFixed(2)+'</th>'+
                            '</tr>';
                        }
                         if (order['monedero']!=0){
                             
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Monedero</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['monedero']).toFixed(2)+'</th>'+
                            '</tr>';
                        }

                        /*
                        txt+='<tr>'+
                                    '<th class="numeric-cell"></th>'+
                                    '<th class="label-cell"></th>'+
                                    '<th class="label-cell">Impuestos</th>'+
                                    '<th class="numeric-cell">'+parseFloat(impuestos).toFixed(2)+'</th>'+
                                '</tr>';
                        */                                  
                        txt+='<tr>'+
                            '<th class="numeric-cell"></th>'+
                            '<th class="label-cell"></th>'+
                            '<th class="label-cell">Total</th>'+
                            '<th class="numeric-cell">'+parseFloat(order['total']).toFixed(2)+'</th>'+
                        '</tr>';  
                            
                        txt+=''+
                                '</tbody>'+
                            '</table><br><br>'+
                        '</div>';
                    }
                    $('#detallepedido').html(txt);
                    
                    
                        
                        
                },
                error: function (e){
                    console.log('error');
                }
            });
            
            $('#detallepedido').html(txt);
         },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
        
 
    
    dynamicPopup.open();  
}

function repetirpedido(id){
    app.dialog.confirm('Añadir el pedido significa borrar el carrito<br><br>¿Desea continuar?', function () {
    
        app.popup.close();
        var txt='';
        var server=servidor+'admin/includes/leeunpedidodatos.php';     $.ajax({
            type: "POST",
            url: server,
            data: {idpedido:id},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
               //console.log(obj);

                if (obj.valid==true){

                    var carrito2=obj.carrito;
                    carrito=[];
                    calcularTotalCarrito();

                    for(var x=0;x<carrito2.length;x++){
                        if (typeof carrito2[x]['modificadores'] !== "undefined") {
                            carrito.push({id:carrito2[x]['id'],nombre:carrito2[x]['nombre'],precio:parseFloat(carrito2[x]['precio']), precio_sin:parseFloat(carrito2[x]['precio_sin']),cantidad:parseFloat(carrito2[x]['cantidad']),img:carrito2[x]['img'],menu:0,elmentMenu:0,comentario:carrito2[x]['comentario'],modificadores:carrito2[x]['modificadores']});
                        }
                        else {
                            carrito.push({id:carrito2[x]['id'],nombre:carrito2[x]['nombre'],precio:parseFloat(carrito2[x]['precio']), precio_sin:parseFloat(carrito2[x]['precio_sin']),cantidad:parseFloat(carrito2[x]['cantidad']),img:carrito2[x]['img'],menu:0,elmentMenu:0,comentario:carrito2[x]['comentario']});
                        }


                    }

                    calcularTotalCarrito();

                    renderizarCarrito();
                    app.dialog.alert('Pedido añadido al carrito');

                }
                else{
                    app.dialog.alert('error al añadir el pedido al carrito');
                }
            },
            error: function (e){
                console.log('error');
            }
        });
    });
}

function buscaDomiciliosPedido(id,nombre,apellidos){
    //alert(id);
    var server=servidor+'admin/includes/buscaDomiciliosPedido.php';
    //console.log(fecha_pedido);
    
    $.ajax({
        url: server,
        data:{id:id, nombre:nombre, apellidos:apellidos},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                //console.log(obj.domicilios);
                var domicilios=obj.domicilios;
                var txt='<h3>Seleccione domicilio</h3><div class="list list-dividers-ios media-list"><ul>';
                for (x=0;x<domicilios.length;x++){
                    txt+='<li>'+
                        '<div class="item-content" style="cursor:pointer;padding: 0;" data-domicilio="'+domicilios[x]['direccion']+'" data-domicilio="'+domicilios[x]['direccion']+'" data-cod_postal="'+domicilios[x]['cod_postal']+'" data-poblacion="'+domicilios[x]['poblacion']+'"  data-provincia="'+domicilios[x]['provincia']+'" onclick="seleccionadomicilio(this);app.dialog.close();">'+
                            '<div class="item-media"><i class="f7-icons">house</i>'+
                            '</div>'+
                            '<div class="item-inner">'+
                                '<div class="item-title-row">'+
                                    '<div class="item-title" style="color:'+colorsecundario+';">'+domicilios[x]['direccion']+'</div>'+
                                '</div>'+
                                '<div class="item-subtitle">'+domicilios[x]['poblacion']+' ('+domicilios[x]['provincia']+')</div>'+
                            '</div>'+
                        '</div>'+
                    '</li>';

                }
                txt+='</ul></div>';
                app.dialog.alert(txt);  
                
            }else {
                app.dialog.alert('No se encontraron domicilios');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}

function seleccionadomicilio(eldomi){
    
    var domicilio=$(eldomi).attr('data-domicilio');
    var cod_postal=$(eldomi).attr('data-cod_postal');
    var poblacion=$(eldomi).attr('data-poblacion');
    var provincia=$(eldomi).attr('data-provincia');
        
    var local=domicilio+' '+cod_postal+' '+poblacion;
    
    
    $.get('https://maps.googleapis.com/maps/api/geocode/json?address='+local+'&key=AIzaSyDsv3MEv58JZ42mQIqNyE_Zwpyo361dzhs', function(data) {
        var lat=data.results[0].geometry.location.lat;
        var lng=data.results[0].geometry.location.lng;

        var server=servidor+'admin/includes/leezonasrepartospedidos.php';
//console.log(fecha_pedido);

        $.ajax({
            url: server,
            data:{foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){ 
                var obj=Object(data);
                if (obj.valid==true){
                    var precio=obj.precio;
                    var minimo=obj.minimo;
                    var reparto=obj.reparto;
                    var zonas=obj.zonas;
                    var esta='no';
                    var noreparto='no';
                    var buscar=new google.maps.LatLng(parseFloat(lat), parseFloat(lng));
                    for(x=0;x<zonas.length;x++){
                        for (j=0;j<zonas[x].length;j++){
                            zonas[x][j]={ "lat": parseFloat(zonas[x][j]['lat']), "lng": parseFloat(zonas[x][j]['lng']) };
                        }
                        var polygon = new google.maps.Polygon({paths:zonas[x],map: map});
                        //console.log(buscar);
                        var isWithinPolygon = google.maps.geometry.poly.containsLocation(buscar, polygon);
                        if (isWithinPolygon){
                            esta='si';
                            portes=precio[x];
                            pedidominimo=minimo[x];
                            if (reparto[x]=='0'){
                                // zona no reparto
                                noreparto='si';
                            }   

                        }

                    }
                    if ((esta=='si')&&(noreparto=='no')) {

                        $('#google_place').hide();
                        $('#hacer-pedido-nuevo-cliente').addClass('button-fill');
                        $('#hacer-pedido-nuevo-cliente').removeAttr('disabled');
                        //console.log(domicilio_cliente);
                        $('#pedido-domicilio-cliente').val(domicilio);
                        $('#pedido-poblacion-cliente').val(poblacion);
                        $('#pedido-cod_postal-cliente').val(cod_postal);
                        $('#pedido-provincia-cliente').val(provincia);
                        $('#domicilio-cliente-pedido').show();
                        $('#boton-buscar-domicilios').hide();  
                        $('#pedido-lat-cliente').val(lat);
                        $('#pedido-lng-cliente').val(lng);
                        var txt_modo='Domicilio';
                        var icono='delivery';
                      
                        
                        
                        
                        var cliente={
                            'id':$('#dato-id-cliente').val(),
                            'nombre':$('#dato-nombre-cliente').val(),
                            'apellidos':$('#dato-apellidos-cliente').val(),
                            'telefono':$('#dato-telefono-cliente').val()
                            
                        }
                        domicilio_cliente={
                             'direccion':$('#pedido-domicilio-cliente').val(),
                             'poblacion':$('#pedido-poblacion-cliente').val(),
                             'cod_postal':$('#pedido-cod_postal-cliente').val(),
                             'provincia':$('#pedido-provincia-cliente').val(),
                            'coordenadas':{'lat':lat,'lng':lng}
                         }
                        //console.log(domicilio_cliente['coordenadas']);
                        datos_cliente = {
                            'cliente':cliente,
                            'domicilio':domicilio_cliente
                        };
                        var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2(1);"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
                        txt+='<br><br>';
                        txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
                        txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
                        txt+='<div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Cliente: <b>'+$('#dato-apellidos-cliente').val()+', '+$('#dato-nombre-cliente').val()+'</b></span><br>'+
                        '<span>Teléfono:<b>'+$('#dato-telefono-cliente').val()+'</b></span></button></div>';
                        txt+='<br><div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Domicilio:</span> <br><span><b>'+domicilio_cliente['direccion']+'</b></span><br>'+
                        '<span><b>'+domicilio_cliente['cod_postal']+' - '+domicilio_cliente['poblacion']+' ('+domicilio_cliente['provincia']+')</b></span><br></button></div>';
                        txt+='<br><div>'+
                        '<button class="button button-outline button-round text-align-left" style="height:auto;font-size: 18px;text-transform: inherit;width: auto;float: left; height: 45px;" onclick="BuscaPedidosnteriores();"><i class="f7-icons">bag</i> Pedidos anteriores</button>'+
                        '<button class="button button-outline button-round" style="height:45px;font-size: 18px;width: auto;float: right;" onclick="muestracarrito();">Importe &nbsp; &nbsp; <span id="importe-en-carrito">0 €</span><i class="f7-icons" style="color:#'+colorprimario+'">cart</i><span class="badge cantidad-cart" style="top: -10px;left: -5px;background-color: #'+colorsecundario+'">0</span></button>'+
                        '</div><br><br>';

                        txt+='<input type="hidden" id="modo-pedido" value="1">';
    
    
                        txt+='<div id="div-tienda-pedido"></div>';

                        $('#pedido-manual-pagina').html(txt)
                        muestragrupospedido();
                    }
                    else {
                        app.dialog.alert('El domicilio seleccionado NO está en nuestra zona de reparto');

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
        
    });
}

function buscaproductospedido(){
    
    var server=servidor+'admin/includes/buscaproductos.php';
    $.ajax({
        url: server,
        data:{foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                //console.log(obj.productos);
                var productos=obj.productos;
                var nombres=new Array();
                for (x=0;x<productos.length;x++){
                    nombres[x]=productos[x]['nombre'];
                }
                //console.log(nombres);
                autocompleteSearchbar = app.autocomplete.create({
                    openIn: 'dropdown',
                    inputEl: '#searchbar-autocomplete input[type="search"]',
                    //dropdownPlaceholderText: 'Escriba el nombre del producto',
                    source: function (query, render) {
                      var results = [];
                      if (query.length === 0) {
                        render(results);
                        return;
                      }
                      // Find matched items
                      for (var i = 0; i < nombres.length; i++) {
                        if (nombres[i].toLowerCase().indexOf(query.toLowerCase()) >= 0) results.push(nombres[i]);
                      }
                      // Render items by passing array with result items
                      render(results);
                    },
                    on:{

                        close: function(ac){
                            //console.log(ac.value[0]);
                            var position = nombres.indexOf(ac.value[0]);
                            //console.log(nombres[position]);
                            //console.log(position);
                            if (position>=0){
                               //console.log(nombres[position]); 
                               //console.log(productos[position]['id']); 
                                muestraelproductopedido(productos[position]['id']);
                            }
                            
                        }
                    } 
                  })
                
                
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    
}

function muestragrupospedido(){
    //console.log(datos_cliente);
    //div-tienda-pedido
    var txt='<br><div class="searchbar-backdrop"></div>'+
        '<form class="searchbar no-outline" id="searchbar-autocomplete">'+
        '<div class="searchbar-inner">'+
        '<div class="searchbar-input-wrap">'+
        '<input type="search" placeholder="Buscar producto" />'+
        '<i class="searchbar-icon"></i>'+
        '<span class="input-clear-button"></span>'+
        '</div>'+
        '<span class="searchbar-disable-button">Cancel</span>'+
        '</div>'+
        '</form><br>';
    txt+='<div id="grupo-productos-pedido">Hello</div>'
    
    

    
    $('#div-tienda-pedido').html(txt);
    $('#searchbar-autocomplete input[type="search"]').css({'border-radius':'10px'});
    $('.searchbar-inner').css('background-color', 'white');
    buscaproductospedido();
     var server=servidor+'admin/includes/leegrupospedido.php';
    $.ajax({
        url: server,
        data:{foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                //console.log(obj.grupos);
                var grupos=obj.grupos;
                var txt='<div class="grid medium-grid-cols-5 small-grid-cols-2">';
                var imagen=''
                for (x=0;x<grupos.length;x++){
                    if (grupos[x]['imagen_app']!=''){
                        imagen=servidor+'webapp/img/productos/'+grupos[x]['imagen_app'];
                    }
                    else {
                        if (grupos[x]['imagen']!=''){
                            imagen=servidor+'webapp/img/revo/'+grupos[x]['imagen'];
                        }
                        else {
                            imagen=servidor+'webapp/img/no-imagen.png';
                        }
                    }
                    
                    txt+='<div style="margin: 10px;text-align: center;" onclick="muestracategoriaspedido('+grupos[x]['id']+',\''+grupos[x]['nombre']+'\')">'+
                        '<img src="'+imagen+'" style="border-radius:50%;width: 125px;height: 125px;"><br>'+
                        '<p style="color:#'+colorprimario+';font-size: 16px;font-weight: bold;">'+grupos[x]['nombre']+'</p>';
                    txt+='</div>';
                }
                txt+='</div>';
                $('#grupo-productos-pedido').html(txt);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    
}

function muestracategoriaspedido(id,nombre){
    var txt='<br><div class="searchbar-backdrop"></div>'+
        '<form class="searchbar no-outline" id="searchbar-autocomplete">'+
        '<div class="searchbar-inner">'+
        '<div class="searchbar-input-wrap">'+
        '<input type="search" placeholder="Buscar producto" />'+
        '<i class="searchbar-icon"></i>'+
        '<span class="input-clear-button"></span>'+
        '</div>'+
        '<span class="searchbar-disable-button">Cancel</span>'+
        '</div>'+
        '</form><br>';
    txt+='<div style="font-size:18px;display: flex;line-height: 40px;"><a href="#" class="button button-fill button-round" style="font-size: 20px;height: 40px;" onclick="muestragrupospedido();">Inicio</a> &nbsp; &nbsp; -> &nbsp; &nbsp; '+nombre+'</div>'
    txt+='<div id="grupo-productos-pedido">categorias</div>';
    
    

    
    $('#div-tienda-pedido').html(txt);
    $('#searchbar-autocomplete input[type="search"]').css({'border-radius':'10px'});
    $('.searchbar-inner').css('background-color', 'white');
    buscaproductospedido();
     var server=servidor+'admin/includes/leecategoriaspedido.php';
    $.ajax({
        url: server,
        data:{id:id},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                
                var grupos=obj.grupos;
                var txt='<div class="grid medium-grid-cols-5 small-grid-cols-2">';
                var imagen=''
                for (x=0;x<grupos.length;x++){
                    if (grupos[x]['imagen_app']!=''){
                        imagen=servidor+'webapp/img/productos/'+grupos[x]['imagen_app'];
                    }
                    else {
                        if (grupos[x]['imagen']!=''){
                            imagen=servidor+'webapp/img/revo/'+grupos[x]['imagen'];
                        }
                        else {
                            imagen=servidor+'webapp/img/no-imagen.png';
                        }
                    }
                    
                    txt+='<div style="margin: 10px;text-align: center;" onclick="muestraproductospedido('+grupos[x]['id']+',\''+grupos[x]['nombre']+'\','+id+',\''+nombre+'\')">'+
                        '<img src="'+imagen+'" style="border-radius:50%;width: 125px;height: 125px;"><br>'+
                        '<p style="color:#'+colorprimario+';font-size: 16px;font-weight: bold;">'+grupos[x]['nombre']+'</p>';
                    txt+='</div>';
                }
                txt+='</div>';
                $('#grupo-productos-pedido').html(txt);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
}

function muestraproductospedido(id,nombre,idgru,nomgru){
    var txt='<br><div class="searchbar-backdrop"></div>'+
        '<form class="searchbar no-outline" id="searchbar-autocomplete">'+
        '<div class="searchbar-inner">'+
        '<div class="searchbar-input-wrap">'+
        '<input type="search" placeholder="Buscar producto" />'+
        '<i class="searchbar-icon"></i>'+
        '<span class="input-clear-button"></span>'+
        '</div>'+
        '<span class="searchbar-disable-button">Cancel</span>'+
        '</div>'+
        '</form><br>';
    txt+='<div style="font-size:18px;display: flex;line-height: 40px;"><a href="#" class="button button-fill button-round" style="font-size: 20px;height: 40px;" onclick="muestragrupospedido();">Inicio</a> &nbsp;&nbsp;->&nbsp;&nbsp;<a href="#" class="button button-fill button-round" style="font-size: 20px;height: 40px;"  onclick="muestracategoriaspedido('+idgru+',\''+nomgru+'\');">'+nomgru+'</a>&nbsp;&nbsp;->&nbsp;&nbsp;'+nombre+'</div>'
    txt+='<div id="grupo-productos-pedido">categorias</div>';

    $('#div-tienda-pedido').html(txt);
    $('#searchbar-autocomplete input[type="search"]').css({'border-radius':'10px'});
    $('.searchbar-inner').css('background-color', 'white');
    buscaproductospedido();
    var server=servidor+'admin/includes/leeproductospedido.php';
    $.ajax({
        url: server,
        data:{id:id},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                
                var grupos=obj.grupos;
                var txt='<div class="grid medium-grid-cols-5 small-grid-cols-2">';
                var imagen=''
                for (x=0;x<grupos.length;x++){
                    if (grupos[x]['imagen_app']!=''){
                        imagen=servidor+'webapp/img/productos/'+grupos[x]['imagen_app'];
                    }
                    else {
                        if (grupos[x]['imagen']!=''){
                            imagen=servidor+'webapp/img/revo/'+grupos[x]['imagen'];
                        }
                        else {
                            imagen=servidor+'webapp/img/no-imagen.png';
                        }
                    }
                    
                    txt+='<div style="margin: 10px;text-align: center;" onclick="muestraelproductopedido('+grupos[x]['id']+')">'+
                        '<img src="'+imagen+'" style="border-radius:50%;width: 125px;height: 125px;"><br>'+
                        '<p style="color:#'+colorprimario+';font-size: 16px;font-weight: bold;">'+grupos[x]['nombre']+'</p>';
                    txt+='</div>';
                }
                txt+='</div>';
                $('#grupo-productos-pedido').html(txt);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    
}

function muestraelproductopedido(id){
    var server=servidor+'admin/includes/leeelproductopedido.php';
    $.ajax({
        url: server,
        data:{id:id},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                
                var producto=obj.producto;
                var txt='';
                var imagen='';
                //console.log(producto);
                
                if (producto['imagen_app']!=''){
                    imagen=servidor+'webapp/img/productos/'+producto['imagen_app'];
                }
                else {
                    if (producto['imagen']!=''){
                        imagen=servidor+'webapp/img/revo/'+producto['imagen'];
                    }
                    else {
                        imagen=servidor+'webapp/img/no-imagen.png';
                    }
                }
                txt+='<div style="display:flex;">'+
                    '<div><img src="'+imagen+'" style="border-radius:50%;width: 125px;height: 125px;padding-right: 10px;"></div>'+
                    '<div style="width: 100%;"><span style="font-size:20px;">'+producto['nombre']+'</span><br><span>'+producto['info']+'</span><br><br><span style="font-size: 28px;float: right;color:#'+colorprimario+'">'+producto['precio']+' €</span></div>'+
                    '</div><br>'+
                    
                    '<div id="modificadores-producto-pedido" style="margin: 10px;">'+
                     '</div><br><br>'+
                    '<div class="item-input-wrap"><input type="text" placeholder="Comentario" id="comentario-prod-pedido" style="font-size: 18px;width: 95%;"></div><br>'+
                    '<div style="border-top: 1px solid;">'+
                    '<div class="block" style="margin-top:5px;margin-bottom:-8px;" id="stepper-producto-pedido">'+
                        '<div class="" style="display: flex;margin-top: 10px;">'+
                            '<div class="" style="font-size:16px;font-weight: bold;width: 60%;">Número de unidades</div>'+
                            '<div class="" style="width: 40%;text-align: right;">'+
                                '<div class="stepper stepper-fill stepper-round stepper-init">'+
                                    '<div class="stepper-button-minus" onclick="RestaUnoPedido();"></div>'+
                                    '<div class="stepper-input-wrap"><input type="text" value="1" id="cantidadProductoPedido" readonly=""></div>'+
                                    '<div class="stepper-button-plus" onclick="SumaUnoPedido();"></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="button button-outline button-round button-fill" id="add-producto-pedido" data-id="'+producto['id']+'" data-nombre="'+producto['nombre']+'" data-precio="'+producto['precio']+'" data-sin="'+producto['precio']+'"  data-img="'+imagen+'" onclick="addCarritoPedido(this);" style="width: 95%; margin: auto;display: block;height: 40px;line-height: 35px;margin-top: 12px;" data-modificadores="{&quot;forzoso&quot;:&quot;0&quot;}"><span style="float:left;">Añadir al pedido</span> <span id="precio-producto" style="font-size:22px;font-weight: bold;float:right;">'+producto['precio']+'€</span><span></span></div>'+
                    '</div><br><br><br>';
                    txt+='<input type="hidden" value="0" id="modificadores-obligatorios">';
                var dynamicPopup = app.popup.create({
                content: ''+
                  '<div class="popup">'+
                    '<div class="block page-content">'+
                      '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br>'+
                        '<div id="detalleproducto"></div>'+         
                    '</div>'+
                  '</div>'  ,
                on: {
                    open: function (popup) {
                        $('#detalleproducto').html(txt);
                        if (producto['esmenu']==1){
                            poneMenuCategoriespedido(producto['MenuCategories']);
                        }
                        else {
                            if (typeof producto['modifierCategories']){
                                ponemodificadorespedido(producto['modifierCategories']);
                            }
                        }

                    },
                    opened: function (popup) {
                    //console.log('Popup opened');
                        },
                    }
                });  
        
 
    
                dynamicPopup.open(); 
                        
                        
                txt+='';
                //$('#grupo-productos-pedido').html(txt);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    
}

function SumaUnoPedido(){
    var cantidad=parseInt($('#cantidadProductoPedido').val());
    var partes=$('#precio-producto').html().split("€");
    var precio=parseFloat(partes)/cantidad;
    var nuevo=(cantidad+1)*precio;
    if (cantidad<98) {
        $('#cantidadProductoPedido').val(cantidad+1);
        $('#precio-producto').html(nuevo.toFixed(2)+'€');      
    }
    
}

function RestaUnoPedido(){
    var cantidad=parseInt($('#cantidadProductoPedido').val());  
    var partes=$('#precio-producto').html().split("€");
    var precio=parseFloat(partes)/cantidad;
    var nuevo=(cantidad-1)*precio;
    if (cantidad>1) {
        $('#cantidadProductoPedido').val(cantidad-1);
        $('#precio-producto').html(nuevo.toFixed(2)+'€');
    }
}

function ponemodificadorespedido(modificadores){
    //console.log(modificadores);
    if (modificadores==undefined){
        return;
    }
    if (modificadores.length>0){
        var obligatorio=0
        var txt='';
        var txt_forzoso='';
        for (x=0;x<modificadores.length;x++){
            txt_forzoso='Opcional';
            if (modificadores[x]['forzoso']==0){
                obligatorio=1;
                txt_forzoso='Obligatorio';
            }
            txt+='<div class="grid grid-cols-2 grid-gap" style="margin:-15px;padding: 5px;border: white 2px solid;border-radius: 5px;">'+
                '<div class="" style="font-size:18px;font-weight: bold;">'+modificadores[x]['nombre']+'</div>'+
                '<div class="" style="float: right;"><span id="but-mod-'+modificadores[x]['id']+'" data-forzoso="'+modificadores[x]['forzoso']+'"><button class="col button button-small button-fill" style="font-size: 14px;height: 20px;text-transform: initial;width: auto;float: right;margin-top: 3px;">'+txt_forzoso+'</button></span></div>'+
            '</div>';
            txt+='<div class="list">'+
                '<ul>';
            var tipo='checkbox';
            //cambiamodificador('+modificadores[x]['modificaores'][j]['precio']+','+modificadores.length+',\''+0+'\');muestramodificador(this,'+modificadores[x]['id']+');
            for (j=0;j<modificadores[x]['modificaores'].length;j++){
                if (modificadores[x]['opciones']==0){
                   tipo='radio'; 
                }
                txt+='<li>'+
                    '<label class="'+tipo+' item-content">'+
                          '<input class="modificador-producto" type="'+tipo+'" name="mod_tipo_'+x+'" id="mod_tipo_'+x+'_'+j+'" value="'+modificadores[x]['modificaores'][j]['id']+'#'+modificadores[x]['modificaores'][j]['precio']+'#'+modificadores[x]['modificaores'][j]['nombre']+'" data-precio="0.50" onclick="miraMaximoElegible('+x+','+modificadores[x]['maximo']+','+modificadores[x]['opciones']+','+j+','+modificadores[x]['forzoso']+');">'+
                          '<i class="icon icon-'+tipo+'"></i>'+
                          '<div class="item-inner">'+
                            '<div class="item-title">&nbsp; &nbsp; '+modificadores[x]['modificaores'][j]['nombre']+' ('+modificadores[x]['modificaores'][j]['precio']+' €)</div>'+
                          '</div>'+
                        '</label>'+
                    '</li>';
            }
            txt+='</ul>'+
                '</div>';
            
        }
        if (obligatorio>0){
            $('#modificadores-obligatorios').val(obligatorio);
            $('#add-producto-pedido').attr('disable',true);
            $('#add-producto-pedido').removeClass('button-fill');
            $('#stepper-producto-pedido').hide();
        }
        $('#modificadores-producto-pedido').html(txt);
    }
}

function poneMenuCategoriespedido(MenuCategories){
    //console.log(MenuCategories);
    // 0  Seleccionar 1 no obl
    // 1  varias opciones
    // 2  seleccion 1 obligatorio
    // 3  seleccionar por defecto (min - max)
    // 4  personalizado (min - max) oblig
    var obligatorio=0
    var txt='';
    var txt_forzoso='';
    for (x=0;x<MenuCategories.length;x++){
        txt_forzoso='Opcional';
        if (MenuCategories[x]['eleMulti']==1){
            obligatorio++;
            txt_forzoso='Obligatorio';
        }
        if (MenuCategories[x]['eleMulti']==2){
            obligatorio++;
            txt_forzoso='Obligatorio';
        }
        if (MenuCategories[x]['eleMulti']==3){
            if (MenuCategories[x]['min']>0){
                obligatorio++;
                txt_forzoso='Obligatorio';
            }
        }
        if (MenuCategories[x]['eleMulti']==4){
            if (MenuCategories[x]['min']>0){
                obligatorio++;
                txt_forzoso='Obligatorio';
            }
        }
        txt+='<div class="grid grid-cols-2 grid-gap" style="margin:-15px;padding: 5px;border: white 2px solid;border-radius: 5px;">'+
            '<div class="" style="font-size:18px;font-weight: bold;">'+MenuCategories[x]['nombre']+'</div>'+
            '<div class="" style="float: right;"><span id="but-mod-'+MenuCategories[x]['id']+'" data-forzoso="'+MenuCategories[x]['eleMulti']+'"><button class="col button button-small button-fill" style="font-size: 14px;height: 20px;text-transform: initial;width: auto;float: right;margin-top: 3px;">'+txt_forzoso+'</button></span></div>'+
        '</div>';
        txt+='<div class="list media-list">'+
            '<ul>';
        var tipo='checkbox';

        for (j=0;j<MenuCategories[x]['opcionesMenu'].length;j++){
            // 0  Seleccionar 1 no obl
            // 1  varias opciones
            // 2  seleccion 1 obligatorio
            // 3  seleccionar por defecto (min - max)
            // 4  personalizado (min - max) oblig
            /*
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'orden'=>$grupo->orden,
                    'eleMulti'=>$grupo->eleMulti,
                    'min'=>$grupo->minimo,
                    'max'=>$grupo->maximo,
                    'opcionesMenu'=>$this->leeMenuItems($grupo->id,$tienda)
                ];
                
                opcionesMenu
            'id'=>$grupo->id,
                    'orden'=>$grupo->orden,
                    'nombre'=>$grupo->nombre,
                    'producto'=>$grupo->producto,
                    'precio'=>$grupo->precio,
                    'modifier_group_id'=>$grupo->modifier_group_id,
                    'addPrecioMod'=>$grupo->addPrecioMod,
                    'imagen'=>$grupo->imagen,
                    'imagen_app'=>$grupo->imagen_app1,
                    'alergias'=>$grupo->alergias,
                    'info'=>$grupo->alergias,
                    'alergias'=>$grupo->alergias     
            */
            if (MenuCategories[x]['eleMulti']==1){
               tipo='radio'; 
            }
            var imagen=servidor+'webapp/img/no-imagen.png';
            if (MenuCategories[x]['opcionesMenu'][j]['imagen_app']!=''){
                    imagen=servidor+'webapp/img/productos/'+MenuCategories[x]['opcionesMenu'][j]['imagen_app'];
            }
            else {
                if (MenuCategories[x]['opcionesMenu'][j]['imagen']!=''){
                     imagen=servidor+'webapp/img/revo/'+MenuCategories[x]['opcionesMenu'][j]['imagen'];
                }
                    
            }
            
            txt+='<li>'+
                '<div class="item-content" style="padding: 0">'+
                    '<div class="item-media">'+
                        '<img style="border-radius: 15px" src="'+imagen+'" width="50" />'+
                    '</div>'+
                        
                    '<div class="item-inner">' +
                
                        '<div class="item-title-row">'+
                
                            '<div class="item-title">'+MenuCategories[x]['opcionesMenu'][j]['nombre']+'</div>'+
                
                        '</div>'+
                
                        '<div class="item-title" style="float: right;margin-right: -20px;">'+poneOpcionSelMenu(MenuCategories[x]['id'],MenuCategories[x]['eleMulti'],MenuCategories[x]['min'],MenuCategories[x]['max'],MenuCategories[x]['opcionesMenu'][j]['precio'],MenuCategories[x]['opcionesMenu'][j]['producto'],MenuCategories[x]['opcionesMenu'][j]['nombre'],imagen)+
                        ' </div>'+
                
                        '<div class="item-subtitle">(+'+MenuCategories[x]['opcionesMenu'][j]['precio']+' €)</div>'+

                        '<div class="item-text">'+MenuCategories[x]['opcionesMenu'][j]['info']+'</div>'+
            
                    '</div>'+
                '</div> '+
            '</li>';
        }
        txt+='</ul>'+
            '</div>';
    }
    txt+='</ul>'+
        '</div>';
    if (obligatorio>0){
        $('#modificadores-obligatorios').val(obligatorio);
        $('#add-producto-pedido').attr('disable',true);
        $('#add-producto-pedido').removeClass('button-fill');
        $('#stepper-producto-pedido').hide();
    }
    $('#modificadores-producto-pedido').html(txt);
    
}

function poneOpcionSelMenu(id,tipo, min, max, precio,producto,nombre,img){
    var pasa=id+'#'+tipo+'#'+min+'#'+max+'#'+precio+'#'+producto+'#'+nombre+'#'+img;
    var txt='';
    if (tipo==1 || tipo==3){
        pasa==id+'#'+tipo+'#0#9999#'+precio+'#'+producto+'#'+nombre+'#'+img;
    }
    if (tipo==0){
        //seleccionar 1
        txt='<label class="checkbox"><input type="checkbox" name="chk-ele-multi-'+id+'" value="'+pasa+'" class="elem-menu-opc elem-menu-opc-'+id+' elem-menu-opc-'+id+'-'+producto+'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-checkbox"></i></label>';
        
    }
    if (tipo==2){
        //seleccionar 1
        txt='<label class="radio"><input type="radio" name="chk-ele-multi-'+id+'" value="'+pasa+'" class="elem-menu-opc elem-menu-opc-'+id+' elem-menu-opc-'+id+'-'+producto+'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-radio icon-radio-menus"></i></label>';
        
    }
    if (tipo==1 || tipo==3){
        $txt='<label class="checkbox"><input type="checkbox" name="chk-ele-multi-'+id+'" value="'+pasa+'" class="elem-menu-opc elem-menu-opc-'+id+' elem-menu-opc-'+id+'-'+producto+'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-checkbox"></i></label>';
        
    }
    if (tipo==4){
        txt='<div class="stepper stepper-raised stepper-small stepper-round stepper-fill stepper-init" data-min="'+min+'" data-max="'+max+'" >'+
            '<div class="stepper-button-minus" onclick="resta1Menu(\''+pasa+'\')"></div>'+
            '<div class="stepper-input-wrap">'+
                '<input type="text" name="chk-ele-multi-'+id+'" value="0" class="elem-menu-opc menu-stepper-'+id+' elem-menu-opc-'+id+' elem-menu-opc-'+id+'-'+producto+'" step="1" data-contenido="'+pasa+'" readonly />'+
            '</div>'+
            '<div class="stepper-button-plus" onclick="suma1Menu(\''+pasa+'\')"></div>'+
        '</div>';
    }
    return txt;
    
}

function cambiaSeleccionOpcionMenu(elem){
    var forz=0;
    var datos=elem.value.split("#");
    //pasa=id+'#'+tipo+'#'+min+'#'+max+'#'+precio;
    
    var su_precio=parseFloat($('#add-producto-pedido').attr('data-sin'));
    var hay=0;
    
    //console.log(datos);
    //console.log('su_precio:'+su_precio);
    
    var hay=0;
    var elemForzoso=$('#but-mod-'+datos[0]);
    var forzoso=parseInt(elemForzoso.attr('data-forzoso'));
    // 0  Seleccionar 1 no obl
    // 1  varias opciones
    // 2  seleccion 1 obligatorio
    // 3  seleccionar por defecto (min - max)
    // 4  personalizado (min - max) oblig
   var suma=0;
    $('.elem-menu-opc-'+datos[0]).each(function(){
        if ($(this).prop('checked')){
            hay++;
            
        }
    });
     $('.elem-menu-opc').each(function(){
         if ($(this).prop('checked')){
            var arr=$(this).val().split("#");
            suma+=parseFloat(arr[4]);
         }
     });
    
    
    //console.log('A sumar:'+suma);
    //console.log('forzoso:'+forzoso);
    //console.log('hay:'+hay);
    var elem=$('.elem-menu-opc-'+datos[0]+'-'+datos[5]);
    
    var chekeado=elem.prop('checked');
   
    var eleMulti=parseInt(datos[1]);
    
    var precio=parseFloat(datos[4]);
    
    //console.log('precio:'+datos[4]);
    
    var min=parseInt(datos[2]);
    var max=parseInt(datos[3]);
    //if (chekeado){
        $('#add-producto-pedido').attr('data-precio',(su_precio+suma).toFixed(2));
        $('#precio-producto').html($('#add-producto-pedido').attr('data-precio')+' €');
    //}
    /*
    else {
        $('#add-producto-pedido').attr('data-precio',(su_precio-precio).toFixed(2));
        $('#precio-producto').html($('#add-producto-pedido').attr('data-precio')+' €');
    }
    */
    if (hay>max){
        elem.prop('checked',false);
        return;
    }
    var forzosos =parseInt($('input:hidden[name=forzoso]').val());
    if (hay>=min) {
        elemForzoso.html('<i class="icon f7-icons text-color-green icon_menu" style="float: right">checkmark_circle_fill</i>');
        
        
    }
    else {
        
        elemForzoso.html('<i class="icon f7-icons text-color-red icon_menu" style="float: right">xmark</i>');
       
    }

    verificaCuantosPuestos();
}

function verificaCuantosPuestos() {
    var suma=0;
    var chk=0;
    $('.icon_menu').each(function(){
        suma++;
        if ($(this).hasClass('text-color-green')){
            chk++;
        }
            
    });

    if(suma==chk){

        $('#add-producto-pedido').removeClass('disabled');
        $('#add-producto-pedido').addClass('button-fill');
        //addCarritoMenu(this);"
        document.getElementById('add-producto-pedido').setAttribute('onclick','addCarritoMenu(this)');
    }
    else {
        $('#add-producto-pedido').addClass('disabled');
        $('#add-producto-pedido').removeClass('button-fill');
    }
}

function suma1Menu(elem){
    var forz=0;
    var datos=elem.split("#");
    //console.log(elem);
    
    var su_precio=parseFloat($('#add-producto-pedido').attr('data-sin'));
    // but-mod-153
    var elemForzoso=$('#but-mod-'+datos[0]);
    var forzoso=elemForzoso.attr('data-forzoso');
    
    // $x.'#'.$id.'#'.$nombre.'#'.$eleMulti.'#'.$min.'#'.$max.'#'.$precio.'#'.$imagen.'#'.$impuesto;
    
    var elem=$('.elem-menu-opc-'+datos[0]+'-'+datos[5]);
    //console.log('.elem-menu-opc-'+datos[0]+'-'+datos[5])
    var hay=parseInt(elem.val());
    var precio=parseFloat(datos[4]);
    var suma=0;
    var min=parseInt(datos[2]);
    var max=parseInt(datos[3]);
    $('.menu-stepper-'+datos[0]).each(function(){
        suma+=parseInt($(this).val());   
    });
    //console.log('hay:'+elem.val());
    if (suma<max){
        elem.val(hay+1);
        //hay++;
        suma++;
        $('#add-producto-pedido').attr('data-precio',(su_precio+precio).toFixed(2));
        $('#precio-producto').html($('#add-producto-pedido').attr('data-precio')+' €');
        
    }
    
    if (suma>=min) {
        elemForzoso.html('<i class="icon f7-icons text-color-green icon_menu" style="float: right">checkmark_circle_fill</i>');
        
    }
    else {
        elemForzoso.html('<i class="icon f7-icons text-color-red icon_menu" style="float: right">xmark</i>');
    }
    
    verificaCuantosPuestos();
    //console.log(datos[2]);
    
}

function resta1Menu(elem){
    var forz=0;
    var datos=elem.split("#");
    console.log(elem);
    
    var su_precio=parseFloat($('#add-producto-pedido').attr('data-sin'));
    // but-mod-153
    var elemForzoso=$('#but-mod-'+datos[0]);
    var forzoso=elemForzoso.attr('data-forzoso');
    
    // $x.'#'.$id.'#'.$nombre.'#'.$eleMulti.'#'.$min.'#'.$max.'#'.$precio.'#'.$imagen.'#'.$impuesto;
    
    var elem=$('.elem-menu-opc-'+datos[0]+'-'+datos[5]);
    console.log('.elem-menu-opc-'+datos[0]+'-'+datos[5])
    var hay=parseInt(elem.val());
    var precio=parseFloat(datos[4]);
    var suma=0;
    var min=parseInt(datos[2]);
    var max=parseInt(datos[3]);
    $('.menu-stepper-'+datos[0]).each(function(){
        suma+=parseInt($(this).val());   
    });
    console.log('hay:'+elem.val());
    if (hay>0){
        elem.val(hay-1);
        //hay--;
        suma--;
        $('##add-producto-pedido').attr('data-precio',(su_precio-precio).toFixed(2));
        $('#precio-producto').html($('#add-producto-pedido').attr('data-precio')+' €');
    }

    if (suma>=min) {
        elemForzoso.html('<i class="icon f7-icons text-color-green icon_menu" style="float: right">checkmark_circle_fill</i>')
    }
    else {
        elemForzoso.html('<i class="icon f7-icons text-color-red icon_menu" style="float: right">xmark</i>');
       
    }
    
    verificaCuantosPuestos();
    //console.log(datos[2]);
    
}

function addCarritoMenu(e){
    var id=e.getAttribute('data-id');
    var nombre=e.getAttribute('data-nombre');
    var precio=e.getAttribute('data-precio');
    var precio_sin=e.getAttribute('data-sin');
    
    var img=e.getAttribute('data-img');
    var mod=e.getAttribute('data-modificadores');
    var comentario=$('#comentario-prod-pedido').val();
    var contenido='';
    var elmentosMenu=Array();
    $('.elem-menu-opc').each(function(){
        if ($(this).attr('type')=='text'){
            if (parseInt($(this).val())>0){
                contenido=$(this).attr('data-contenido');
                //console.log(contenido);
                var datos=contenido.split("#");
               //141#2#0#1#0.00#3510 
               //alert(datos[6]+'-'+$(this).val()) ;
                elmentosMenu.push({id:datos[5],nombre:datos[6],precio:datos[4] ,cantidad:parseInt($(this).val()) ,img:datos[7] ,mod:null});

            }
            
        }
        else {
            if ($(this).prop('checked')){
                contenido=$(this).val();
                //console.log(contenido);
                var datos=contenido.split("#"); elmentosMenu.push({id:datos[5],nombre:datos[6],precio:datos[4] ,cantidad:1 ,img:datos[7] ,mod:null});
                
            }
        }

    });
    //console.log(elmentosMenu);
    carrito.push({id:$('#add-producto-pedido').attr('data-id'),nombre:nombre ,precio:precio,  precio_sin:precio_sin, cantidad:1,img:img,mod:null, comentario:comentario,menu:1,elmentosMenu:elmentosMenu});
    //console.log(elmentosMenu) ;   
   app.popup.close();
        
    calcularTotalCarrito();
    renderizarCarrito();
            
    
    
}

function addCarritoPedido(e){  
    var precio=parseFloat($('#add-producto-pedido').attr('data-precio'));
    var sumar=0;
    var comentario=$('#comentario-prod-pedido').val();
    var cantidad=parseInt($('#cantidadProductoPedido').val()); 
    //console.log(e)
    var modificadores=$('#add-producto-pedido').attr('data-modificadores');
    
    var arr;
    var newm=new Array();
    $("input:checkbox[class=modificador-producto]:checked").each(function(){
            arr=$(this).val().split('#');
            sumar+=parseFloat(arr[1]);
        newm.push({id:arr[0],nombre:arr[2], precio:arr[1]});
        //console.log('Mod:'+arr[0]+' - '+arr[2]);

    });
    $("input:radio[class=modificador-producto]:checked").each(function(){
            arr=$(this).val().split('#');
            sumar+=parseFloat(arr[1]);
        newm.push({id:arr[0],nombre:arr[2], precio:arr[1]});
        //console.log('Mod:'+arr[0]+' - '+arr[2]);

    });
    
    modificadores=JSON.stringify(newm);
    $('#add-producto-pedido').attr('data-modificadores',modificadores)

    addCarritodesdePedido(e,cantidad,comentario);
    app.popup.close();
    
}

function addCarritodesdePedido(e,cantidad,comentario){

    var elementos=0;
    for (var x=0;x<carrito.length;x++) {
        elementos+=carrito[x]['cantidad'];
    }
    //elementos+=cantidad;
    
    //console.log(carritoEdad);
    var id=e.getAttribute('data-id');
    var nombre=e.getAttribute('data-nombre');
    var precio=e.getAttribute('data-precio');
    var precio_sin=e.getAttribute('data-sin');
    var img=e.getAttribute('data-img');
    var mod=e.getAttribute('data-modificadores');
    

    
    
    
    var existe='no';
    for (var x=0;x<carrito.length;x++) {
        
        if ((carrito[x]['id']==id)&&(carrito[x]['menu']==0)){
            if (comentario!='') {
                console.log('hay comentario, nuevo');
                existe='no';
                break;

            }

            if ((carrito[x]['id']==id)&&(carrito[x]['menu']==0)){
            //existe sumamos
                console.log('existe');
                if (carrito[x]['modificadores'].length>0) {
                    existe='no';
                    break;
                    
                }
                if (carrito[x]['comentario']!=''){
                    console.log('Tenia comentario, nuevo');
                    existe='no';
                    break;
                }
                else {
                    console.log('sin comentario, añadir');
                    existe='si';
                    carrito[x]['cantidad']+=cantidad;
                    break;
                }
                
            }
        }

    }
    
    if (existe=='no'){
        carrito.push({id:id,nombre:nombre,precio:precio, precio_sin:precio_sin,cantidad:cantidad,img:img,menu:0,elmentMenu:0,comentario:comentario,modificadores:JSON.parse(mod)});
    }
    
    calcularTotalCarrito();
    renderizarCarrito();
    //console.log(carrito);
}

function calcularTotalCarrito() {
    //console.log('portes_'+portes);
    var envio=0;
    
    for (var r = 0, a = 0, o = 0; o < carrito.length; o++) {
        var c = parseFloat(carrito[o].precio) * carrito[o].cantidad
            ;
         (a += c), (r += carrito[o].cantidad);
    }
    if ($('#modo-pedido').val()==1){
        if (a<importeportesgratis){
            envio=portes;
        }
        
    }
    
    return (
        (totalItemCarrito = r),
        (carritoSubtotal = a),$('#total-carrito-pedido').html((parseFloat(envio)+carritoSubtotal).toFixed(2)+' €'), $('#importe-en-carrito').html((parseFloat(envio)+carritoSubtotal).toFixed(2)+' €'),
        $(".cantidad-cart").html(totalItemCarrito),
        totalItemCarrito
    );
}

function vaciarcarrito(){
    app.dialog.confirm('¿Desea vacia el carrito?', function () {
        carrito=[];
        calcularTotalCarrito();
        renderizarCarrito();
        app.popup.close();
        });
}

function addCarrito2 (e, cantidad=1) {

    for (var x=0;x<carrito.length;x++) {
        if (x==e){
            
            //existe sumamos
            existe='si';
            hay=carrito[x]['cantidad'];
            //padre=carrito[x]['elmentMenu'];
            carrito[x]['cantidad']+=cantidad;
        }
        
    }
      
    //carrito.push({id:id,nombre:nombre,precio:precio,cantidad:1});
    calcularTotalCarrito();
    renderizarCarrito();
    $('#detallcarrito').html(carritohtml);

}

function restarCarrito (e, cantidad=1) {

    for (var x=0;x<carrito.length;x++) {
        
        if (x==e){
            //existe sumamos
            existe='si';
            hay=carrito[x]['cantidad'];
            carrito[x]['cantidad']-=cantidad;
            if (carrito[x]['cantidad']==0){
                borrarItemCarrito(e);
                
            }
            
        }
        
    }
    
    //carrito.push({id:id,nombre:nombre,precio:precio,cantidad:1});
    calcularTotalCarrito();
    renderizarCarrito();
     
    $('#detallcarrito').html(carritohtml);
     
}

function borrarItemCarrito(e) {
    var idaborrar=e;
    var temp=[];

    
    for (var x=0;x<carrito.length;x++) {
        if (x!=e){ 
                temp.push(carrito[x]);
        }
    }
    carrito=temp;
    calcularTotalCarrito();
    renderizarCarrito();
    if (carritoSubtotal==0){
        app.popup.close();
    }
    else {
        $('#detallcarrito').html(carritohtml);
    }
}

function renderizarCarrito() {
    var modo=$('#modo-pedido').val();
    var envio=0;
    // carrito.push({id:id,nombre:nombre,precio:precio,cantidad:1,iva:iva,img:img});
    if (carritoSubtotal<importeportesgratis){
            envio=portes;
        }
    var txt='';
    //console.log(carrito);
    var txt_menu='';
    var ver_menu='';
    for (var x=0;x<carrito.length;x++) {
        
            if (carrito[x]['menu']<2) {
                //if (x>0){
                    txt+='<hr>';
                //}
            }

        var extras="";
        if (carrito[x]['menu']==0) {
            if (typeof carrito[x]['modificadores'] !== "undefined") {
                var modis=carrito[x]['modificadores'];     
                if (modis.length>0){   
                    for (y=0;y<modis.length;y++){
                        extras+=modis[y]['nombre']+', ';
                    }    
                    extras=extras.slice(0, -2);
                }
            }
            
        }
        if (extras!=""){
            extras='<br><span style="font-size:.9em;">'+
                extras+'</span>';
        }


    

        var sumar='<div class="text-align-center" onclick="addCarrito2('+x+');" data-id="'+carrito[x]['id']+'" style="width:10%;"><i class="icon f7-icons size-26 color-green">plus_circle</i></div>';
        if (carrito[x]['menu']==1){
            sumar='<div class="text-align-center" onclick="addCarritoMenu2('+x+',1);" data-id="'+carrito[x]['id']+'" style="width:10%;"><i class="icon f7-icons size-26 color-green">plus_circle</i></div>';
        }
        var restar='<div class="text-align-center"  style="width:10%;"></div>';;
        var borrar='<div class="text-align-center" onclick="borrarItemCarrito('+x+');" data-id="'+carrito[x]['id']+'" style="width:10%;"><i class="icon f7-icons color-red size-26">trash</i></div>';
        if (carrito[x]['menu']!=1) {
            txt_menu='';
            ver_menu='';
        }
        else {
            txt_menu='<i class="icons material-icons size-16">restaurant</i> ';
            //ver_menu=' <i class="icons f7-icons size-16 '+carrito[x]['elmentMenu']+'" onclick="verItemsMenu(\''+carrito[x]['elmentMenu']+'\');">chevron_down</i>';
        }
        if (carrito[x]['menu']==0) { //no son elementos del menu
            if (carrito[x]['cantidad']>1){

                restar='<div class="text-align-center" onclick="restarCarrito('+x+');" data-id="'+carrito[x]['id']+'" style="width:10%;"><i class="icon f7-icons size-26 color-red">minus_circle</i></div>';
            }
            
        }
        if (carrito[x]['menu']==1) { //no son elementos del menu
            if (carrito[x]['cantidad']>1){

                restar='<div class="text-align-center" onclick="addCarritoMenu2('+x+',-1);" data-id="'+carrito[x]['id']+'" style="width:10%;"><i class="icon f7-icons size-26 color-red">minus_circle</i></div>';
            }
            
        }

        txt+='<div style="display: flex;">'+
                '<div class="" style="width:10%;><span style="font-size:1.3em;">'+
                carrito[x]['cantidad']+'</span></div>'+
                '<div class="" style="width:60%;"><span style="font-size: 1.2em;font-weight: 600;">'+txt_menu+carrito[x]['nombre']+ver_menu+'</span>'+
                extras+'<br><span class="text-color-primary" style="font-size:1.2em;">'+parseFloat(carrito[x]['precio']).toFixed(2)+'€</span><br><span class="text-color-primary" style="font-size:.9em;font-style: italic;">'+carrito[x]['comentario']+'</span></div>'+ 
            
                restar+ 
                sumar+ 
                borrar+'</div>';
        //(carrito[x]['cantidad']*parseFloat(carrito[x]['precio'])).toFixed(2) Total=carritoSubtotal
        if (carrito[x]['menu']==1) {
            var elem=carrito[x]['elmentosMenu'];  
            
            for (j=0;j<elem.length;j++){
                txt+='<div style="display: flex;">'+
                '<div class="text-align-center" style="width:10%;"></div><div style="width:10%;><span style="">'+
                elem[j]['cantidad']+'</span></div>'+
                '<div class="" style="width:60%;"><span style="font-size: 1.1em;font-weight: 400;">'+elem[j]['nombre']+'</span></div>'+ 
                '<div class="text-align-center" style="width:10%;"></div>'+ 
                '<div class="text-align-center" style="width:10%;"></div>'+'</div>';                      
            }
            
        }
 
    }
    txt+='<hr><div style="display: flex;">'+
                '<div class="" style="width:10%;"></div>'+
                '<div class="" style="width:90%;"><span style="font-size: 1.3em;font-weight: 600;">Subtotal: '+parseFloat(carritoSubtotal).toFixed(2)+'€</span></div></div>'; 
    if (modo==1){
        txt+='<div style="display: flex;">'+
                '<div class="" style="width:10%;"></div>'+
                '<div class="" style="width:90%;"><span style="font-size: 1.3em;font-weight: 600;">Gastos envío: '+parseFloat(envio).toFixed(2)+'€</span></div></div>'; 
        
    }

    txt+='<hr><br>'+
        '<button class="button button-outline" style="margin: auto;width: 60%;font-size: 1.2em!important;">TOTAL&nbsp; &nbsp; <span style="font-weight: 800;">'+(carritoSubtotal+parseFloat(envio)).toFixed(2)+'€</span></button>';
    



    txt_finalizar='<br><div class="text-align-center" style="margin:auto;width:60%;"><button class="button button-fill button-round"  onclick="finalizarpedido();" style="font-size:.9em;height: 45px;">Finalizar</button></div>';

    txt_vaciar='<br><div class=""><div class="text-align-center" style="margin:auto;width:60%;"><button class="button button-fill button-round text-align-center" style="font-size:.9em;background-color:#'+colorsecundario+';height: 45px;" onclick="vaciarcarrito();"><i class="icon f7-icons if-not-md size-20">trash</i> ¿Vaciar carrito?</button></div></div><br><br>';
    //carritohtml=txt+txt_seguir+txt_finalizar+txt_vaciar;
    carritohtml=txt+txt_finalizar+txt_vaciar;

    if (carrito.length==0){
        //carritohtml='<p>Carrito vacío</p>'+txt_seguir;
        carritohtml='<p>Carrito vacío</p>';
        $('#hacer-pedido-nuevo-finalizar').prop('disabled',true);
        $('#hacer-pedido-nuevo-finalizar').removeClass('button-fill');
        //$('#total-carrito-pedido').html('0 €');
    }
    else {
        if (!$('#hacer-pedido-nuevo-finalizar').hasClass('button-fill')){
            $('#hacer-pedido-nuevo-finalizar').addClass('button-fill');
            $('#hacer-pedido-nuevo-finalizar').prop('disabled',false);
            
        }
    }
     //$("#carrito-page").html('<div class="block-title block-title-medium"> Tu pedido</div>'+carritohtml);
    //console.log(carrito);
}

function muestracarrito(){
    var dynamicPopup = app.popup.create({
    content: ''+
      '<div class="popup">'+
        '<div class="block page-content">'+
          '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><h2>Carrito</h2>'+
            '<div id="detallcarrito"></div>'+         
        '</div>'+
      '</div>'  ,
    on: {
            open: function (popup) {
                $('#detallcarrito').html(carritohtml);



            },
            opened: function (popup) {
            //console.log('Popup opened');
                },
        }
    }); 
    dynamicPopup.open(); 
}

function finalizarpedido() {
    app.popup.close();
    var envio=0;
    
    var modo=$('#modo-pedido').val();
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Domicilio';
        icono='delivery';
        if (carritoSubtotal<importeportesgratis){
            envio=portes;
        }
    }
    var txt_carrito='<div>';
    var txt_menu='';
    var ver_menu='';
    for (var x=0;x<carrito.length;x++) {
        
            if (carrito[x]['menu']<2) {
                //if (x>0){
                    txt_carrito+='<hr>';
                //}
            }
        var extras="";
        if (carrito[x]['menu']==0) {
            if (typeof carrito[x]['modificadores'] !== "undefined") {
                var modis=carrito[x]['modificadores'];     
                if (modis.length>0){   
                    for (y=0;y<modis.length;y++){
                        extras+=modis[y]['nombre']+', ';
                    }    
                    extras=extras.slice(0, -2);
                }
            }
            
        }
        if (extras!=""){
            extras='<br><span style="font-size:.9em;">'+
                extras+'</span>';
        }


        txt_carrito+='<div style="display: flex;">'+
                '<div class="" style="width:10%;"><span style="font-size:1.3em;font-weight: 600;">'+
                carrito[x]['cantidad']+'</span></div>'+
                '<div class="" style="width:80%;"><span style="font-size: 1.2em;font-weight: 600;">'+txt_menu+carrito[x]['nombre']+ver_menu+'</span>'+
                extras+'<br><span class="text-color-primary" style="font-size:1.2em;">'+parseFloat(carrito[x]['precio']).toFixed(2)+'€</span><br><span class="text-color-primary" style="font-size:.9em;font-style: italic;">'+carrito[x]['comentario']+'</span></div>'+ 
                '</div>';
    }
    txt_carrito+='</div>';
    
    var detallepedido= ''+
        '<div class="button button-outline" style="width: 25%;font-size: 18px;float : right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</div>'+
        '<h2 style="margin-top: 0;margin-bottom: 0;">Fecha: <span style="color:#'+colorprimario+';">'+fecha_pedido+'</span> &nbsp;&nbsp;Hora: <span style="color:#'+colorprimario+';">'+hora_pedido+'</span></h2>'+
        '<h2 style="margin-top: 0;margin-bottom: 0;">Cliente: <span style="color:#'+colorprimario+';">'+datos_cliente['cliente']['apellidos']+', '+datos_cliente['cliente']['nombre']+'</span></h2>'+
        '<h2 style="margin-top: 0;margin-bottom: 0;">Teléfono: <span style="color:#'+colorprimario+';">'+datos_cliente['cliente']['telefono']+'</span></h2>';
        
        if (modo==1){
            detallepedido+='<h3 style="margin-top: 0;margin-bottom: 0;">Domicilio:</h3>'+
                '<h3 style="margin-top: 0;margin-bottom: 0;"><span style="color:#'+colorprimario+';">'+datos_cliente['domicilio']['direccion']+'</h3>'+
                '<h3 style="margin-top: 0;margin-bottom: 0;"><span style="color:#'+colorprimario+';">'+datos_cliente['domicilio']['cod_postal']+' - '+datos_cliente['domicilio']['poblacion']+'( '+datos_cliente['domicilio']['provincia']+')</h3>';
        }
        detallepedido+=txt_carrito;
        if (modo==1){
            detallepedido+=
        '<hr><div style="font-size: 20px;float: right;">Subtotal &nbsp;<span style="color:#'+colorprimario+';">'+
            carritoSubtotal.toFixed(2)+'€</span></div>'+
            '<br><br>'+
            '<div style="font-size: 20px;float: right;">Gastos envío &nbsp;<span style="color:#'+colorprimario+';">'+
            parseFloat(envio).toFixed(2)+'€</span></div>'+
            '<br><br>';
                
        }
        detallepedido+=
        '<div style="font-size: 20px;float : right;">TOTAL PEDIDO &nbsp;<span style="color:#'+colorprimario+';">'+
        (carritoSubtotal+parseFloat(envio)).toFixed(2)+'€</span></div>'+
        '<div class="list">'+
            '<ul>'+
              '<li class="item-content item-input">'+
                '<div class="item-inner">'+
                  '<div class="item-title item-label">Comentario</div>'+
                  '<div class="item-input-wrap" >'+
                    '<input type="text" placeholder="Comentario" id="comentario-para-elpedido"/>'+
                  '</div>'+
                '</div>'+
              '</li>'+
            '</ul>'+
          '</div>'+          
        '<br><div style="margin-top: 20px;"><div class="text-align-center" style="margin:auto;width:60%;"><button class="button button-fill button-round text-align-center" style="font-size:1.2em;background-color:#'+colorprimario+';height: 45px;" onclick="hacerelpedido();">Hacer pedido</button></div></div><br><br><br>';
    //app.dialog.alert(txt);
    

    //console.log(datos_cliente);
    var dynamicPopup = app.popup.create({
    content: ''+
      '<div class="popup">'+
        '<div class="block page-content">'+
          '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><h2>Detalles del pedido</h2>'+
            '<div id="detallepedido"></div>'+         
        '</div>'+
      '</div>'  ,
    on: {
            open: function (popup) {
                $('#detallepedido').html(detallepedido);



            },
            opened: function (popup) {
            //console.log('Popup opened');
                },
        }
    }); 
    dynamicPopup.open(); 
    //console.log(JSON.stringify(carrito));
} 

function hacerelpedido() {
    var comentario=$('#comentario-para-elpedido').val();

    var modo=$('#modo-pedido').val();
    var envio=0;
    
    if (modo==1){
        if (carritoSubtotal<importeportesgratis){
            envio=portes;
        }
        if (carritoSubtotal<pedidominimo) {
            app.dialog.alert('El pedido mínimo a domicilio es <b>'+parseFloat(pedidominimo).toFixed(2)+'</b>€');
            return;
        }
            
    }
    var datoscliente=new Array();
    if (datos_cliente['cliente']['id']=='Nuevo') {
        datoscliente={
            cliente:datos_cliente['cliente'],
            domicilio:datos_cliente['domicilio']
        };
    }
    else {
        datoscliente=datos_cliente;
    }
    var nombre=datoscliente['cliente']['nombre'];
    var apellidos=datoscliente['cliente']['apellidos'];
    var telefono=datoscliente['cliente']['telefono'];
    var email='';
    var idcliente=datoscliente['cliente']['id'];
    var domicilio=datoscliente['domicilio'];
    if (typeof datoscliente['cliente']['email'] == undefined){
        
    }
    else {
        email=datoscliente['cliente']['email'];
    }
    var direccion='';
    var cod_postal='';
    var poblacion='';
    var provincia='';
    var lat=0;
    var lng=0
    if (modo==1){
        direccion=datoscliente['domicilio']['direccion'];
        cod_postal=datoscliente['domicilio']['cod_postal'];
        poblacion=datoscliente['domicilio']['poblacion'];
        provincia=datoscliente['domicilio']['provincia'];
        lat=datoscliente['domicilio']['coordenadas']['lat'];
        lng=datoscliente['domicilio']['coordenadas']['lng'];
    }
    //app.dialog.alert(datoscliente['cliente']['nombre']+' '+datoscliente['cliente']['apellidos']+' ('+datoscliente['cliente']['telefono']+')');
    //console.log(datos_cliente);
    //console.log(carrito);
    //console.log(modo);
//console.log('id: '+datoscliente['cliente']['id']);
    
    //console.log(typeof datoscliente);
    
    var server=servidor+'admin/includes/addpedidodesdepedidos.php';
    //console.log(fecha_pedido);
    
    $.ajax({
        url: server,
        data:{idcliente:idcliente, nombre:nombre, apellidos:apellidos, telefono:telefono , email:email, direccion:direccion, cod_postal:cod_postal, poblacion:poblacion, lat:lat,lng:lng, provincia:provincia, modo:modo, carrito:carrito, portes:envio, fecha_pedido:fecha_pedido, hora_pedido:hora_pedido, subtotal:carritoSubtotal.toFixed(2),comentario:comentario},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                idPedido=obj.idPedido;
                //console.log(idPedido);
                var server=servidor+'admin/includes/addpedidoaRevo.php';

                $.ajax({
                    url: server,
                    data:{idPedido:idPedido},
                    method: "post",
                    dataType:"json",
                    success: function(data){ 
                        var obj=Object(data);
                        if (obj.valid==true){
                            app.popup.close();
                            borradatospedido();
                            $('#view-pedidos').css('position','relative');
                            //$('.panel-left').css('display','block');
                            $('.panel-left').css('display',estadopanel);
                            $('.navbar').show();
                            leepedidos();
                        }else {
                            app.dialog.alert("Error enviando a Revo");
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError){
                        app.dialog.alert("Error enviando a Revo ("+xhr.status+")");
                        console.log(xhr.status);
                        console.log(thrownError);
                    }
                });
                
                
            }else {
                app.dialog.alert("Error generando pedido");
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            app.dialog.alert("Error generando pedido ("+xhr.status+")");
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function borradatospedido() {
    carrito=[];
    carritohtml="El carrito está vacío";
    carritoSubtotal=0;
    totalItemCarrito=0;
    datos_cliente=[];
}

function miraMaximoElegible(x, maximo, opciones,j,esforzoso) {
    var preciosin=parseFloat($('#add-producto-pedido').attr('data-sin'));
    var cantidad=parseInt($('#cantidadProductoPedido').val());  
    var partes=$('#precio-producto').html().split("€");
    var precio=parseFloat(partes)/cantidad;
    
    var elegidos =0;
    var sumar=0;
    var arr;
    var obligatorios=$('#modificadores-obligatorios').val();
    if (esforzoso==0){
        //console.log('era forzoso');
        if (obligatorios>0) {
            obligatorios--;
        }
    }
    $('#modificadores-obligatorios').val(obligatorios);
    $('[name="mod_tipo_'+x+'"]:checked').each(function() {
      elegidos++
    });

    

    if (maximo > 0 && elegidos > maximo) {
       app.dialog.alert("Elija máximo " + maximo + " opciones");
        //elem.attr('checked',false)
        $('#mod_tipo_'+x+'_'+j).prop('checked', false);
            return;
    }
    
    $("input:checkbox[class=modificador-producto]:checked").each(function(){
        arr=$(this).val().split('#');
        sumar+=parseFloat(arr[1]);
    });
    
    $("input:radio[class=modificador-producto]").each(function(){
        arr=$(this).val().split('#');
        sumar+=parseFloat(arr[1]);
    });
    //console.log(sumar);
    var preciocon=preciosin+sumar;
    //console.log(preciocon);
    $('#precio-producto').html(preciocon.toFixed(2)+'€');
    $('#add-producto-pedido').attr('data-precio',preciocon.toFixed(2));
    if (obligatorios==0) {
        $('#add-producto-pedido').attr('disable',false);
        $('#add-producto-pedido').addClass('button-fill');
        $('#stepper-producto-pedido').show();
    }
    
}

function llenafechaspedido(dias,modo){
    var diasSemana = [
      'domingo',
      'lunes',
      'martes',
      'miércoles',
      'jueves',
      'viernes',
      'sábado'
    ];
    var seleccionado=fecha_pedido;
    var f = new Date();
    var txt='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#'+colorprimario+';">'+fecha_pedido+'</span></b></p><p class="grid grid-cols-3 grid-gap" id="sel-la-fecha-pedido">';
    losdias=new Array();
    var primero="button-fill";
    var txt_prim='HOY';
    for (j=0;j<dias;j++){
        
        
        losdias[j]=diaCastellano(f.getDate()) + "/" + mesCastellano(f.getMonth()) + "/" + f.getFullYear();
        txt_prim=losdias[j];
        if (j==0){
            txt_prim='HOY';
        }
        if (j==1){
            txt_prim='MAÑANA';
        }
        if (j>1){
            txt_prim=diasSemana[f.getDay()];
        }
        if (losdias[j]!=fecha_pedido){
            primero="button-raised";
        }
        else {
            primero="button-fill";
        }
        txt+='<button class="button-fecha-reparto button button-large '+primero+'" data-fecha="'+losdias[j]+'" style="line-height: 18px;" onclick="cambiaDiaEnvio(this,'+modo+');"><p><b>'+txt_prim+'</b><br><span style="font-size:14px;">'+losdias[j]+'</span></p></button>';
        f.setDate(f.getDate()+1);
        primero="button-raised";
        if (j>3){
            txt+='<button class="button-fecha-reparto button button-large button-raised" data-fecha="" id="calendario-para-otro-dia" onclick="cambiaDiaEnvioCalendar('+modo+');"><i class="f7-icons">calendar</i>&nbsp; otro día</button>'
            break;
        }
    }
    txt+='</p><input type="hidden" id="fecha_pedido" value="'+fecha_pedido+'">';
    //$('#la-fecha-pedido').html(fecha_pedido);
    return txt;
    
    
}

function cambiaDiaEnvio(elm,modo){
    //cambiametodoenvio()
    var elemento=$(elm);
    var fecha=elemento.attr('data-fecha');
    fecha_pedido=fecha;
    $('.button-fecha-reparto').removeClass('button-fill');
    $('.button-fecha-reparto').removeClass('button-raised');
    $('.button-fecha-reparto').addClass('button-raised');
    elemento.removeClass('button-raised');
    elemento.addClass('button-fill');
    var d=new Date();  
    //console.log(fecha_pedido);
    var h=d.getHours();
    var m=d.getMinutes();
    var s=d.getSeconds();
    if (h<=9){
        h='0'+h;
    }
    if (m<=9){
        m='0'+m;
    }
    if (s<=9){
        s='0'+s;
    }
    // 01/34/6789
    var dString=fecha_pedido.substr(6,4)+'-'+fecha_pedido.substr(3,2)+'-'+fecha_pedido.substr(0,2)+' '+h+':'+m+':'+s;
    //console.log(dString);
    
    hacerPedidollenahoras(fecha_pedido,modo);
    
}

function cambiaDiaEnviodesdeCalendario(fecha,modo){
    fecha_pedido=fecha;
    var d=new Date();  
    //console.log(fecha_pedido);
    var h=d.getHours();
    var m=d.getMinutes();
    var s=d.getSeconds();
    if (h<=9){
        h='0'+h;
    }
    if (m<=9){
        m='0'+m;
    }
    if (s<=9){
        s='0'+s;
    }
    // 01/34/6789
    var dString=fecha_pedido.substr(6,4)+'-'+fecha_pedido.substr(3,2)+'-'+fecha_pedido.substr(0,2)+' '+h+':'+m+':'+s;
    //console.log(dString);
    hacerPedidollenahoras(fecha_pedido,modo);
    
    
}

function mesCastellano(mes){
    var devuelve=mes;
    devuelve++;
    if (devuelve<=9){
        devuelve='0'+devuelve;
    }
    return devuelve
}

function diaCastellano(dia){
    var devuelve=dia;
    if (devuelve<=9){
        devuelve='0'+devuelve;
    }
    return devuelve
}
