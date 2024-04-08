function hacerPedido(){
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
    
    var estadopanel=$('.panel-left').css('display');
    $('#boton-hacer-pedido').attr('onclick', "$('#view-pedidos').css('position','relative');$('.panel-left').css('display','"+estadopanel+"');$('.navbar').show();leepedidos();");
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
        txt_modo='Llevar';
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
        txt_modo='Llevar';
        icono='delivery';
    }
    //console.log('Modo:'+txt_modo);
    
    
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
                        txt+='<button class="button-hora-reparto button button-large button-raised '+estado_boton+'" data-hora="'+horario[x]['hora']+'" style="line-height: 18px;" onclick="cambiahorapedido(this,'+modo+');" '+    deshabilitado+'><p><b>'+horario[x]['hora']+'</b><br><span style="font-size:14px;">'+horario[x]['cantidad']+'</span></p></button>';
                        
                        
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
    var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Llevar';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedidoCuando('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div class="" style="display: flex;">';
    txt+='<div class="list" style="width: 40%;">'+
        '<ul>'+
          '<li class="item-content item-input">'+
            '<div class="item-inner">'+
              '<div class="item-title item-label">Teléfono</div>'+
              '<div class="item-input-wrap" >'+
                '<input type="text" placeholder="teléfono del cliente" id="autocomplete-telefono-cliente"/>'+
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
                '<input type="text" placeholder="nombre del cliente" id="autocomplete-nombre-cliente"/>'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
    txt+='<div class="list" style="width: 20%;">'+
        '<ul>'+
          '<li class="item-content">'+
            '<div class="item-inner">'+
              '<button class="button button-fill button-round" style="font-size: 18px; float: left;top: 15px;display:none;" onclick="hacerPedidoNuevoCliente('+modo+');" id="hacer-pedido-nuevo-ciente">Nuevo</button>'
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
                    inputEl: '#autocomplete-nombre-cliente',
                    openIn: 'dropdown',
                    dropdownPlaceholderText: 'Use "apellidos,nombre"',
                    typeahead: true,
                    source: function (query, render) {
                      var results = [];
                      if (query.length === 0) {
                        render(results);
                        return;
                      }
                      // Find matched items
                      for (var i = 0; i < datos.length; i++) {
                        if (datos[i].toLowerCase().indexOf(query.toLowerCase()) === 0) {
                            results.push(datos[i]);
                            
                            $('#hacer-pedido-nuevo-ciente').hide();                                            }
                          else {
                              $('#hacer-pedido-nuevo-ciente').show();
                          }
                      }
                      // Render items by passing array with result items
                      render(results);
                    },
                    on:{

                        close: function(ac){
                            //console.log(ac.value[0]);
                            var position = datos.indexOf(ac.value[0]);
                            //console.log(clientes[position]);
                            //console.log(position);
                            if (position>=0){
                                hacerPedido3(modo,clientes[position]['id'],clientes[position]);
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
                      // Find matched items
                      for (var i = 0; i < datos2.length; i++) {
                        if (datos2[i].toLowerCase().indexOf(query.toLowerCase()) === 0) {
                            results.push(datos2[i]);
                            $('#hacer-pedido-nuevo-ciente').hide();                                                          }
                          else {
                              $('#hacer-pedido-nuevo-ciente').show();
                          }
                      }
                      // Render items by passing array with result items
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
        txt_modo='Llevar';
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
                '<input type="text" placeholder="nombre del cliente" id="pedido-nombre-cliente"/>'+
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
                '<input type="text" placeholder="nombre del cliente" id="pedido-apellidos-cliente"/>'+
              '</div>'+
            '</div>'+
          '</li>'+
        '</ul>'+
      '</div>';
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
        if (errores!=''){
            app.dialog.alert('Deble completar:<br>'+errores);
            return;
        }
        else {
            
            cliente['telefono']=$('#pedido-telefono-cliente').val();
            cliente['nombre']=$('#pedido-nombre-cliente').val();
            cliente['apellidos']=$('#pedido-apellidos-cliente').val();
            if (modo==1){
                
                 cliente['direccion']=$('#pedido-domicilio-cliente').val()+'##'+$('#pedido-poblacion-cliente').val()+'##'+$('#pedido-cod_postal-cliente').val()+'##'+$('#pedido-provincia-cliente').val();
                 hacerPedido3(modo,'Nuevo',cliente);
                 
            }
            else {
                hacerPedido3(modo,'Nuevo',cliente);
            }
            
        }
        
    });
    
}

window.datosdegoogle= function (datos){
        domicilio_cliente=datos;
        $('#google_place').hide();
        $('#hacer-pedido-nuevo-cliente').addClass('button-fill');
        $('#hacer-pedido-nuevo-cliente').removeAttr('disabled');
        //console.log(domicilio_cliente);
        $('#pedido-domicilio-cliente').val(datos['direccion']);
        $('#pedido-poblacion-cliente').val(datos['poblacion']);
        $('#pedido-cod_postal-cliente').val(datos['cod_postal']);
        $('#pedido-provincia-cliente').val(datos['provincia']);
    }

function hacerPedido3(modo,tipo,cliente){
    //console.log(tipo);
    //alert(cliente['nombre']);
        var txt_modo='Recoger';
    var icono='commerce';
    if (modo==1){
        txt_modo='Llevar';
        icono='delivery';
    }
    var txt='<div><button class="button button-fill button-round" style="width: 25%;font-size: 18px; float: left;" onclick="hacerPedido2('+modo+');"><i class="icon icon-back"></i>&nbsp;&nbsp;Volver</button><button class="button button-outline" style="width: 25%;font-size: 18px;float: right;padding: 15px"><img src="img/'+icono+'.svg" style="filter: invert(21%) sepia(97%) saturate(540%) hue-rotate(149deg) brightness(91%) contrast(102%);width: 24px;">&nbsp;'+txt_modo+'</buton></div>';
    txt+='<br><br>';
    txt+='<p style="font-size:18px">Fecha: <b><span id="la-fecha-pedido" style="color:#00495e;">'+fecha_pedido+'</span></b></p>';
    txt+='<p style="font-size:18px">Hora: <b><span id="la-fecha-pedido" style="color:#00495e;">'+hora_pedido+'</span></b></p>';
    txt+='<div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Cliente: <b>'+cliente['apellidos']+', '+cliente['nombre']+'</b></span><br>'+
    '<span>Teléfono:<b>'+cliente['telefono']+'</b></span></button></div>';
    if (modo==1){
        
        console.log(domicilio_cliente);
        txt+='<br><div><button class="button button-outline text-align-left" style="height:auto;font-size: 18px;display: block;text-transform: capitalize"><span>Domicilio:</span> <br><span><b>'+domicilio_cliente['direccion']+'</b></span><br>'+
        '<span><b>'+domicilio_cliente['cod_postal']+' - '+domicilio_cliente['poblacion']+' ('+domicilio_cliente['provincia']+')</b></span><br></button></div>';
    }
    
    txt+='<br><div>'+
        '<button class="button button-outline text-align-left" style="height:auto;font-size: 18px;text-transform: inherit;width: auto;float: left; height: 35px;"><i class="f7-icons">bag</i> Pedidos anteriores</button>'+
        '<span style="height:auto;font-size: 18px;width: auto;float: right;"><i class="f7-icons" style="color:#'+colorprimario+'">cart</i><span class="badge cantidad-cart" style="top: -15px;left: -5px;background-color: #'+colorsecundario+'">0</span></span>'+
        '</div><br><br>';
    
    //txt+='<button class="button button-fill button-round" style="margin:auto;width: 25%;font-size: 18px;" id="add-producto-pedido">+ Añadir Producto</button><br>';
    
    txt+='<div id="div-tienda-pedido"></div>';
    
    txt+='<br><button class="button button-outline button-round" disabled style="margin:auto;width: 25%;font-size: 18px;" id="hacer-pedido-nuevo-finalizar">Finalizar</button>';
    $('#pedido-manual-pagina').html(txt);
    
    muestragrupospedido();
    
    
    
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
                                muestraproductopedido(productos[position]['id']);
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

function muestraproductopedido(id){
    
}

function muestragrupospedido(){
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
                var txt='<div style="display:flex;">';
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
                    
                    txt+='<div style="margin: 10px;text-align: center;" onclick="muestracategoriaspedido('+grupos[x]['id']+')">'+
                        '<img src="'+imagen+'" style="border-radius:50%;width: 125px;height: auto;"><br>'+
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

function muestracategoriaspedido(id){
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
     var server=servidor+'admin/includes/leecategoriaspedido.php';
    $.ajax({
        url: server,
        data:{id:id},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                console.log(obj.grupos);
                var grupos=obj.grupos;
                var txt='<div style="display:flex;">';
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
                    
                    txt+='<div style="margin: 10px;text-align: center;" onclick="muestraproductospedido('+grupos[x]['id']+')">'+
                        '<img src="'+imagen+'" style="border-radius:50%;width: 125px;height: auto;"><br>'+
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
    if (devuelve<9){
        devuelve='0'+devuelve;
    }
    return devuelve
}

function diaCastellano(dia){
    var devuelve=dia;
    if (devuelve<9){
        devuelve='0'+devuelve;
    }
    return devuelve
}
