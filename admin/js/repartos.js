function ajustesrepartos(){
    $('#titulo-repartos').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Ajustes de repartos');
    var txt="";
    var server=servidor+'admin/includes/leeajustesreparto.php';
    $.ajax({     
        type: "POST",
        url: server,
        dataType: "json",
        data: {foo:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                txt+=''+
                '<form class="list" id="ajustes-reparto-form" enctype="multipart/form-data">'+
                     '<input type="hidden" name="idportes"  value=""/>'+
                '<ul>'+
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Pedido mínimo envío</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="minimoenvio" placeholder="Pedido mínimo envío" value="'+obj.minimo+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Texto SIN horario</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="norepartomensaje" placeholder="Texto sin horario" value="'+obj.norepartomensaje+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+
                    '<li>'+
                    '<p><label class="toggle toggle-init"><input type="checkbox" name="toggle-envio-gratis" id="toggle-envio-gratis" value="yes" /><i class="toggle-icon chk-envio-gratis"></i></label> Portes gratis</p>'+
                    '</li>'+
                    '<li id="li-portes-gratis" style="display:none;">'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Envío gratis a partir de</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="importeportesgratis" placeholder="importe" value="'+obj.importeportesgratis+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                    '<p><label class="toggle toggle-init"><input type="checkbox" name="toggle-envio-gratis-mensaje" id="toggle-envio-gratis-mensaje" value="yes" /><i class="toggle-icon chk-envio-gratis-mensaje"></i></label> Mostrar mensaje de importe que falta para envío gratis</p>'+
                  '</li>'+
                    
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Tiempo margen para preparar pedidos</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="tiempoenvio" placeholder="Tiempo de margen" value="'+obj.tiempoenvio+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+
                    '<li>'+
                    '<a class="item-link smart-select smart-select-init" data-open-in="popover">'+

                        '<select id="cortesia" name="cortesia">'+
                            '<option value="0">0 (sin tiempo)</option>'+
                            '<option value="5">5 minutos</option>'+
                            '<option value="10">10 minutos</option>'+
                            '<option value="15">15 minutos</option>'+
                            '<option value="20">20 minutos</option>'+
                            '<option value="25">25 minutos</option>'+
                            '<option value="30">30 minutos</option>'+
                        '</select>'+
                        ' <div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="item-title" style="font-size:12px;">Minutos cortesía pedidos</div>'+
                                '<div class="item-after cortesia-after"></div>'+
                            '</div>'+
                        '</div>'+
                    '</a>'+     
                  '</li>'+
                  '<li>'+
                    /*
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Pedidos por tramo para envio</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="pedidosportramoenvio" placeholder="Pedidos por tramo horario" value="'+obj.pedidosportramoenvio+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+  
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Pedidos por tramo para entrega</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="pedidosportramococina" placeholder="Pedidos por tramo horario" value="'+obj.pedidosportramococina+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                  
                  */
                    '<li>'+
                    '<div class="item-content item-input">'+
                        '<div class="item-media busca-producto-portes">'+
                            '<i class="icon f7-icons">search</i>'+
                        '</div>'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Producto Portes</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="productoportes" placeholder="Producto" value="'+obj.idenvio+'-'+obj.portes+'" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                    '<li>'+
                    '<div class="item-content item-input">'+
                        '<div class="item-media busca-iva-portes">'+
                            '<i class="icon f7-icons">search</i>'+
                        '</div>'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Iva Portes</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="iva" placeholder="Iva" value="'+obj.iva+'" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                    
                  '</ul>'+
                    '<p><label class="toggle toggle-init"><input type="checkbox" name="toggle-tarifa" id="toggle-tarifa" value="yes" /><i class="toggle-icon"></i></label> Distinta tarifa para envío</p>'+
                '</form>';
                txt=txt+'<div class="text-align-center"><span id="button-guardar" class="button button-fill" onclick="guardaajustesreparto();" style="width: 50%;margin: auto;">Guardar</span></div>';
              
            }
            $('#repartos-page').html(txt);
            $("#cortesia").val(obj.cortesia);

            
            $(".cortesia-after").html($("#cortesia option:selected").text());
            if (obj.tarifa==1){
                $('input[name=toggle-tarifa]').prop('checked', true);
            }
            else {
                $('input[name=toggle-tarifa]').prop('checked', false);
            }
            if (obj.portesgratis==1){
                $('input[name=toggle-envio-gratis]').prop('checked', true);
                $('#li-portes-gratis').show();
                
            }
            else {
                $('input[name=toggle-envio-gratis]').prop('checked', false);
            }
            if (obj.portesgratismensaje==1){
                $('input[name=toggle-envio-gratis-mensaje]').prop('checked', true);
                $('#li-portes-gratis').show();
                
            }
            else {
                $('input[name=toggle-envio-gratis-mensaje]').prop('checked', false);
            }
            
            $('#ajustes-reparto-form input[name*="idportes"]').val(obj.idenvio);
            
            $('.busca-producto-portes').on('click', function () {
        var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+

            '<form class="searchbar">'+
                '<div class="searchbar-inner">'+
                    '<div class="searchbar-input-wrap">'+
                        '<input type="search" placeholder="Buscar producto">'+
                        '<i class="searchbar-icon"></i>'+
                        '<span class="input-clear-button"></span>'+
                    '</div>'+
                    '<span class="searchbar-disable-button">Cancelar</span>'+
                '</div>'+
            '</form>  '   +  

            '<div class="block">' +  
                '<div class="searchbar-backdrop"></div>'+
                '<div class="list searchbar-found lista-productos" id="lista-productos">'+
                '</div>'+

               ' <div class="block searchbar-not-found">'+
                   ' <div class="block-inner">Producto no encontrado</div>'+
                '</div>'+
             '</div>' + 

            '</div>'+
          '</div>'
         ,
            on: {
          open: function (popup) {

                var server=servidor+'admin/includes/leeproductossearch.php';
                $.ajax({     
                    type: "POST",
                    url: server,
                    dataType: "json",
                    data: {foo:'foo'},
                    success: function(data){
                        var obj=Object(data);
                       var obj=Object(data);
                        if (obj.valid==true){
                            var txt='<ul>';
                            for (x=0;x<obj.id.length;x++){
                                 txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'"data-nombre="'+obj.nombre[x]+'" onclick="muestraprodbuscado(this);">'+
                                    '<div class="item-inner">'+
                                        '<div class="item-title item-buscado"">'+obj.nombre[x]+'</div>'+
                                    '</div>'+
                                    '</li>';
                            }
                            txt+='</ul>';
                            $('#lista-productos').html(txt);  
                        }
                        else{
                            $('#lista-productos').html('');
                        }

                        var searchbar = app.searchbar.create({
                            el: '.searchbar',
                            searchContainer: '#lista-productos',
                            searchIn: '.item-buscado',
                            on: {
                              search(sb, query, previousQuery) {
                                //console.log(query, previousQuery);
                              }
                            }
                        });
                    }
                });
 
          },
                },
            }); 
        dynamicPopup.open();


    });

    $('#toggle-envio-gratis').on('change', function () {
        if($('#toggle-envio-gratis').prop("checked")=='1') {
            $('#li-portes-gratis').show();
           
        }
        else {
            $('#li-portes-gratis').hide();

        }
        //console.log($(this).attr('name'));
    });

            $('.busca-iva-portes').on('click', function () {
    var dynamicPopup = app.popup.create({
    content: ''+
      '<div class="popup">'+
        '<div class="block page-content">'+
          '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+
            '<div class="block">Seleccione impuesto</div>'+
            '<div class="list" id="lista-iva">'+
            '</div>'+


        '</div>'+
      '</div>'
     ,
        on: {
      open: function (popup) {

            var server=servidor+'admin/includes/leeimpuestos.php';
          $.ajax({     
                type: "POST",
                url: server,
                dataType: "json",
                data: {foo:'foo'},
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){
                        var txt='<ul>';
                        for (x=0;x<obj.id.length;x++){
                             txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'"data-nombre="'+obj.nombre[x]+'" data-porcentaje="'+obj.porcentaje[x]+'"data-nombre="'+obj.nombre[x]+'" onclick="muestraivabuscado(this);">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-buscado"">'+obj.nombre[x]+'</div>'+
                                '</div>'+
                                '</li>';
                        }
                        txt+='</ul>';
                        $('#lista-iva').html(txt);  
                    }
                    else{
                        $('#lista-iva').html('');
                    }
                }
          });
              
      },
            },
        }); 
    dynamicPopup.open();


});

        }
        ,
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
}

function guardaajustesreparto() {
    var portesgratis=0;
    var portesgratismensaje=0;
    var norepartomensaje=$('#ajustes-reparto-form input[name=norepartomensaje]').val();
    
    if ($('#toggle-envio-gratis-mensaje').prop("checked")){
        portesgratismensaje=1
    }
    if ($('#toggle-envio-gratis').prop("checked")){
        portesgratis=1
    }
    var importeportesgratis=$('#ajustes-reparto-form input[name=importeportesgratis]').val();
    
    var minimoenvio=$('#ajustes-reparto-form input[name=minimoenvio]').val();
    var tiempoenvio=$('#ajustes-reparto-form input[name=tiempoenvio]').val();
    var cortesia=$("#cortesia").val();
    /*
    var pedidosportramoenvio=$('#ajustes-reparto-form input[name=pedidosportramoenvio]').val();
    var pedidosportramococina=$('#ajustes-reparto-form input[name=pedidosportramococina]').val();
    */
    var portes=$('#ajustes-reparto-form input[name=idportes]').val();
    var iva=$('#ajustes-reparto-form input[name=iva]').val();
    
    var tarifa=0;
    if ($('input[name=toggle-tarifa]').is(':checked')){
        tarifa=1;
        
    }
    //console.log('portesgratis:'+portesgratis);
    //console.log('importeportesgratis:'+importeportesgratis);
    var server=servidor+'admin/includes/guardaajustesreparto.php';
    $.ajax({     
        type: "POST",
        url: server,
        dataType: "json",
        data: //{minimo:minimoenvio,tiempoenvio:tiempoenvio,pedidosportramoenvio:pedidosportramoenvio,pedidosportramococina:pedidosportramococina,portes:portes,iva:iva,tarifa:tarifa,portesgratis:portesgratis,importeportesgratis:importeportesgratis  },
        {minimo:minimoenvio,tiempoenvio:tiempoenvio,pedidosportramoenvio:0,pedidosportramococina:0,portes:portes,iva:iva,tarifa:tarifa,portesgratis:portesgratis,importeportesgratis:importeportesgratis, portesgratismensaje:portesgratismensaje,norepartomensaje:norepartomensaje,cortesia:cortesia  },
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                app.dialog.alert('Datos guardados');
            }
            else {
                app.dialog.alert('Error guardando datos');
            }
        }
    }); 
}
    
function muestraprodbuscado(e) {
    var elem=e;
    $('#ajustes-reparto-form input[name*="productoportes"]').val(elem.dataset.id+'-'+elem.dataset.nombre);
    $('#ajustes-reparto-form input[name*="idportes"]').val(elem.dataset.id);
    app.popup.close();

    
}  

function muestraivabuscado(e) {
    var elem=e;
    $('#ajustes-reparto-form input[name*="iva"]').val(elem.dataset.porcentaje);
        
    app.popup.close();

    
}  

function muestrazonasrepartos(){
    $('#titulo-repartos').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Zonas de repartos');
    var txt="";
    var server=servidor+'admin/includes/leezonasrepartos.php';
    $.ajax({     
        type: "POST",
        url: server,
        dataType: "json",
        data: {foo:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                var nombre=obj.nombre;
                var reparto=obj.reparto;
                var txtcolor1="text-color-green";
                var txtcolor0="text-color-red";
                var txtcolor="";
                txt=txt+'<div class="list sortable sortable-opposite" id="lista-zonas">'+
                    '<ul id="zonas">';
                for (x=0;x<id.length;x++){
                    if (reparto[x]==0){
                        txtcolor=txtcolor0;
                    }
                    else{
                        txtcolor=txtcolor1;
                    }
                    txt=txt+
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="item-title '+txtcolor+'">'+nombre[x]+'</div>'+
                                '<div class="item-after"><i class="f7-icons" onclick="editazona(\''+id[x]+'\');">pencil</i></div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';
                }
                txt=txt+
                    '</ul>'+
                '</div>';
                
                txt=txt+'<div class="text-align-center"><span id="button-guardar" class="button button-fill" onclick="editazona(0);" style="width: 50%;margin: auto;">Añadir zona</span></div>';
                $('#repartos-page').html(txt);
                
            }
            else{
                console.log('ERROR');
                txt=txt+'<div class="justify-content-center"><span id="button-guardar" class="button button-fill" onclick="editazona(0);">Añadir zona</span></div>';
                $('#repartos-page').html(txt);
            }
        },
        error: function (e){
            console.log('error');
        }
    });

}

function editazona(id) {
   var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title">Modificar Zona de Reparto</div>'+
            '<form  id="grupo-form" enctype="multipart/form-data">'+
                '<div class="list">'+
                '<ul>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Nombre" value=""/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+      
                   '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Precio</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="precio" placeholder="Precio" value=""/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                    '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Mínimo</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="minimo" placeholder="Mínimo" value=""/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
            
                  '<li>'+
       
                        '<div class="item-content">'+
                             ' <div class="item-inner">'+
                                '<div class="item-title">SI/NO Reparto</div>'+
                                '<div class="item-after">'+
                                  '<label class="toggle toggle-init">'+
                                    '<input type="checkbox" id="chk-reparto" checked /><i class="toggle-icon"></i>'+
                                  '</label>'+
                                '</div>'+
                              '</div>'+
                        '</div>'+
                    '</li>'+
                    '<li id="archivo-reparto">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Archivo KML</div>'+
                            '<div class="item-input-wrap">'+
                              '<input id="input-file" name="file_zona" type="file">'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                    '<li id="ver-zona-reparto" style="display:none;">'+
                        
                      '</li>'+  
                  '</ul>'+ 
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
              if (id==0){
                  $('#archivo-reparto').show();
                  $('.title').html('Añadir Zona');
              }
              else {
                var server=servidor+'admin/includes/leezonareparto.php';
                $.ajax({     
                    type: "POST",
                    url: server,
                    dataType: "json",
                    data: {id:id},
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){

                            var nombre=obj.nombre;
                            var reparto=obj.reparto;
                            var precio=obj.precio;
                            var minimo=obj.minimo;
                            var zona=obj.zona;
                            //console.log(zona);
                            $('input[name=nombre]').val(nombre);
                            $('input[name=precio]').val(precio);
                            $('input[name=minimo]').val(minimo);
                            
                            if (reparto=="0"){
                                $('#chk-reparto').prop("checked", false );
                            }
                            
                            var txt_visor=''+
                                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                                    '<div class="" id="ver-archivo-reparto"><a class="button button-fill" href="#">Nuevo archivo</a></div>'+
                                    '<div class=""><a class="button button-outline" href="#" onclick="abreVisorMapa();");">Ver la zona</a</div>'+
                                '</div>';
                            $('#archivo-reparto').hide();
                           
                            
                            window.localStorage.setItem("mapaVisor",JSON.stringify(zona));
                            $('#ver-zona-reparto').html(txt_visor);
                             $('#ver-zona-reparto').show();
                            
                            $('#ver-archivo-reparto').on('click', function () {
                                $('#ver-zona-reparto').hide();  
                                $('#archivo-reparto').show();   
                             });
                            //document.getElementById('abre-visor').setAttribute=( "onclick","abreVisorMapa("+zona+");");
                            //window.open("visormapa.php?zona="+JSON.stringify(zona)); 
                        }
                        else{
                            app.dialog.alert('No se pudo leer la zona');
                        }
                    }
                });
                     
              }
              
              
          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
    dynamicPopup.on('close', function (popup) {
        //console.log('Popup close');
        muestragrupos();
      });
    dynamicPopup.open();
    
     
    
    $('.save-data').on('click', function () {
        var precio= $('input[name=precio]').val();
        var minimo= $('input[name=minimo]').val();
        var nombre=$('input[name=nombre]').val();
        var reparto=$('#chk-reparto').prop("checked");
        
        var f=document.getElementById('input-file').files[0];
        var FData = new FormData();
        FData.append('archivo',f);    // this is main row
        FData.append('id',id); 
        FData.append('precio',precio); 
        FData.append('minimo',minimo);
        FData.append('nombre',nombre); 
        FData.append('reparto',reparto); 
    
        var server=servidor+'admin/includes/guardazona.php';
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
                    //muestrazonasrepartos();
                    app.dialog.alert('Datos guardados');
                }
                else{
                    app.dialog.alert('No se pudo guardar la zona');
                }       
                muestrazonasrepartos();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        /*
        app.request({
            url: server, 
            method: 'POST', 
            data: FData,
            cache: false, 
            dataType: 'application/json',
            crossDomain: true, 
            contentType: 'multipart/form-data',
            processData: true, 
            success: function (data){
                app.preloader.hide(); 
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leealergenos();
                    
                }
                else{
                    app.dialog.alert('No se pudo guardar la zona');
                }       
                muestrazonasrepartos();
              //console.log(data);
              }
        });   
        */
       dynamicPopup.close();   

    });  

}

function abreVisorMapa(){
    var zona=window.localStorage.getItem("mapaVisor");
    window.open("visormapa.php?zona="+zona);  
}

function muestrahorasrepartos() {
    var intervalo='15';
    $('#titulo-repartos').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Horas de repartos'); 
    var txt=''+
    '<form id="my-form">'+
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<a class="item-link smart-select smart-select-init" data-open-in="popover">'+

                        '<select id="intervalo" name="intervalo">'+
                            '<option value="10">10</option>'+
                            '<option value="15">15</option>'+
                            '<option value="20">20</option>'+
                            '<option value="30">30</option>'+
                            '<option value="45">45</option>'+
                            '<option value="60">60</option>'+
                        '</select>'+
                        ' <div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="item-title" style="font-size:20px;"><b>Intervalo</b></div>'+
                                '<div class="item-after intervalo-after"></div>'+
                            '</div>'+
                        '</div>'+
                    '</a>'+                  
                '</li>'+
            '</ul>'+
        '</div>'+
        
        // LUNES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>Lunes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-lunes" name="lunes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hlunes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="lpp" placeholder="máximo lunes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-lp" name="lunes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="ld1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="lh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="lp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="ld2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="lh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // MARTRES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>Martes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-martes" name="martes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hmartes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="mpp" placeholder="máximo martes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-mp" name="martes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="md1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="mh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="mp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="md2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="mh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // MIERCOLES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>miercoles</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-miercoles" name="miercoles" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hmiercoles" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="xpp" placeholder="máximo miércoles" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-xp" name="miercoles" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="xp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // JUEVES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>jueves</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-jueves" name="jueves" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hjueves" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="jpp" placeholder="máximo jueves" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-jp" name="jueves" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="jp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // VIERNES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>viernes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-viernes" name="viernes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hviernes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="vpp" placeholder="máximo viernes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-vp" name="viernes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="vp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // SABADO
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>sabado</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-sabado" name="sabado" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hsabado" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="spp" placeholder="máximo sábado" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-sp" name="sabado" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="sp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // DOMINGO
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>domingo</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-domingo" name="domingo" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hdomingo" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="dpp" placeholder="máximo domingo" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-dp" name="domingo" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="dp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
                
        
        
        '</form>'+
        '<div class="block block-strong row">'+
                    '<div class="col-33"><a class="button button-fill save-data" href="#" style="margin: auto;width: 50%;">Guardar</a</div>'+
                '</div>';
    $('#repartos-page').html(txt);
    
    var server=servidor+'admin/includes/leehorasreparto.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);       
            if (obj.valid==true){       
                intervalo=obj.intervalo;
                var lunes=obj.lunes;
                var martes=obj.martes;
                var miercoles=obj.miercoles;
                var jueves=obj.jueves;
                var viernes=obj.viernes;
                var sabado=obj.sabado;
                var domingo=obj.domingo;
                var ld1=obj.ld1;
                var lh1=obj.lh1;
                var ld2=obj.ld2;
                var lh2=obj.lh2;
                var md1=obj.md1;
                var mh1=obj.mh1;
                var md2=obj.md2;
                var mh2=obj.mh2;
                var xd1=obj.xd1;
                var xh1=obj.xh1;
                var xd2=obj.xd2;
                var xh2=obj.xh2; 
                var jd1=obj.jd1;
                var jh1=obj.jh1;
                var jd2=obj.jd2;
                var jh2=obj.jh2;
                var vd1=obj.vd1;
                var vh1=obj.vh1;
                var vd2=obj.vd2;
                var vh2=obj.vh2;
                var sd1=obj.sd1;
                var sh1=obj.sh1;
                var sd2=obj.sd2;
                var sh2=obj.sh2;
                var dd1=obj.dd1;
                var dh1=obj.dh1;
                var dd2=obj.dd2;
                var dh2=obj.dh2;
                
                var lp=obj.lp;  
                var mp=obj.mp;
                var xp=obj.xp;  
                var jp=obj.jp; 
                var vp=obj.vp;  
                var sp=obj.sp;
                var dp=obj.dp;  
                var lpp=obj.lpp;  
                var mpp=obj.mpp;  
                var xpp=obj.xpp;  
                var jpp=obj.jpp;  
                var vpp=obj.vpp;  
                var spp=obj.spp;  
                var dpp=obj.dpp;  
                if (lunes=="0"){
                    $('#chk-lunes').prop("checked", false );
                }
                else {
                    $('#chk-lunes').prop("checked", true );
                    $('#hlunes').show();
                    $('#hlunespp').show();
                }
                if (lp=="0"){
                    $('#chk-lp').prop("checked", false );
                }
                else {
                    $('#chk-lp').prop("checked", true );
                    $('#lp').show();
                }
                if (martes=="0"){
                    $('#chk-martes').prop("checked", false );
                }
                else {
                    $('#chk-martes').prop("checked", true );
                    $('#hmartes').show();
                }
                if (mp=="0"){
                    $('#chk-mp').prop("checked", false );
                }
                else {
                    $('#chk-mp').prop("checked", true );
                    $('#mp').show();
                }
                if (miercoles=="0"){
                    $('#chk-miercoles').prop("checked", false );
                }
                else {
                    $('#chk-miercoles').prop("checked", true );
                    $('#hmiercoles').show();
                }
                if (xp=="0"){
                    $('#chk-xp').prop("checked", false );
                }
                else {
                    $('#chk-xp').prop("checked", true );
                    $('#xp').show();
                }
                if (jueves=="0"){
                    $('#chk-jueves').prop("checked", false );
                }
                else {
                    $('#chk-jueves').prop("checked", true );
                    $('#hjueves').show();
                }
                if (jp=="0"){
                    $('#chk-jp').prop("checked", false );
                }
                else {
                    $('#chk-jp').prop("checked", true );
                    $('#jp').show();
                }
                if (viernes=="0"){
                    $('#chk-viernes').prop("checked", false );
                }
                else {
                    $('#chk-viernes').prop("checked", true );
                    $('#hviernes').show();
                }
                if (vp=="0"){
                    $('#chk-vp').prop("checked", false );
                }
                else {
                    $('#chk-vp').prop("checked", true );
                    $('#vp').show();
                }
                if (sabado=="0"){
                    $('#chk-sabado').prop("checked", false );
                }
                else {
                    $('#chk-sabado').prop("checked", true );
                    $('#hsabado').show();
                }
                if (sp=="0"){
                    $('#chk-sp').prop("checked", false );
                }
                else {
                    $('#chk-sp').prop("checked", true );
                    $('#sp').show();
                }
                if (domingo=="0"){
                    $('#chk-domingo').prop("checked", false );
                }
                else {
                    $('#chk-domingo').prop("checked", true );
                    $('#hdomingo').show();
                }
                if (dp=="0"){
                    $('#chk-dp').prop("checked", false );
                }
                else {
                    $('#chk-dp').prop("checked", true );
                    $('#dp').show();
                }
                
                //alert(intervalo);
                $("#intervalo").val(intervalo);
                $(".intervalo-after").html(intervalo);
                $('input[name=ld1]').val(ld1);
                $('input[name=lh1]').val(lh1);
                $('input[name=ld2]').val(ld2);
                $('input[name=lh2]').val(lh2);
                $('input[name=md1]').val(md1);
                $('input[name=mh1]').val(mh1);
                $('input[name=md2]').val(md2);
                $('input[name=mh2]').val(mh2);
                $('input[name=xd1]').val(xd1);
                $('input[name=xh1]').val(xh1);
                $('input[name=xd2]').val(xd2);
                $('input[name=xh2]').val(xh2);
                $('input[name=jd1]').val(jd1);
                $('input[name=jh1]').val(jh1);
                $('input[name=jd2]').val(jd2);
                $('input[name=jh2]').val(jh2);
                $('input[name=vd1]').val(vd1);
                $('input[name=vh1]').val(vh1);
                $('input[name=vd2]').val(vd2);
                $('input[name=vh2]').val(vh2);
                $('input[name=sd1]').val(sd1);
                $('input[name=sh1]').val(sh1);
                $('input[name=sd2]').val(sd2);
                $('input[name=sh2]').val(sh2);
                $('input[name=dd1]').val(dd1);
                $('input[name=dh1]').val(dh1);
                $('input[name=dd2]').val(dd2);
                $('input[name=dh2]').val(dh2);
                $('input[name=lpp]').val(lpp);
                $('input[name=mpp]').val(mpp);
                $('input[name=xpp]').val(xpp);
                $('input[name=jpp]').val(jpp);
                $('input[name=vpp]').val(vpp);
                $('input[name=spp]').val(spp);
                $('input[name=dpp]').val(dpp);
                //$('input').css('width','100px');
                $('.grid-cols-2').css('margin-top','-40px');
                $('.grid-cols-2').css('margin-bottom','-40px');
            }
            else{
                app.dialog.alert('No se pudo leer las horas');
            }
        }
    });
                
     
    
    $('.dia-semana').on('click', function () {
        var nombre=$(this).attr('name');
        if($('#chk-'+nombre).prop("checked")=='1') {
            $('#h'+nombre).show();
            $('#h'+nombre+'pp').show();
        }
        else {
            $('#h'+nombre).hide();
            $('#h'+nombre+'pp').hide();
        }
        //console.log($(this).attr('name'));
    });
    $('.dia-partido').on('click', function () {
        //console.log($(this).attr('name'));
        var nombre=$(this).attr('name');
        var nom="";
        if (nombre=='lunes'){nom='lp';}
        if (nombre=='martes'){nom='mp';}
        if (nombre=='miercoles'){nom='xp';}
        if (nombre=='jueves'){nom='jp';}
        if (nombre=='viernes'){nom='vp';}
        if (nombre=='sabado'){nom='sp';}
        if (nombre=='domingo'){nom='dp';}
        
        if($('#chk-'+nom).prop("checked")=='1') {
            $('#'+nom).show();
        }
        else {
            $('#'+nom).hide();
        }
    
    });

    $('#intervalo').change(function() {
        intervalo=$('#intervalo').val();
        $(".hora" ).each(function() {
            var time=($(this).val()).split(':');
            var hora=time[0];
            var minutos=time[1];
            //minutos=buscaminutosmascercano(minutos,parseInt(intervalo));
            $(this).val(hora+':'+minutos);
        });
    });
    
    $(".hora" ).blur(function() {
        var time=($(this).val()).split(':');
        var hora=time[0];
        var minutos=time[1];
        //minutos=buscaminutosmascercano(minutos,parseInt(intervalo));
        $(this).val(hora+':'+minutos);
    });
    
    $('.save-data').on('click', function () {
        var intervalo=$('#intervalo').val();
        var ld1= $('input[name=ld1]').val();
        var lh1= $('input[name=lh1]').val();
        var ld2= $('input[name=ld2]').val();
        var lh2= $('input[name=lh2]').val();
        var chklunes=$('#chk-lunes').prop("checked");
        var chklp=$('#chk-lp').prop("checked");
        var md1= $('input[name=md1]').val();
        var mh1= $('input[name=mh1]').val();
        var md2= $('input[name=md2]').val();
        var mh2= $('input[name=mh2]').val();
        var chkmartes=$('#chk-martes').prop("checked");
        var chkmp=$('#chk-mp').prop("checked");
        var xd1= $('input[name=xd1]').val();
        var xh1= $('input[name=xh1]').val();
        var xd2= $('input[name=xd2]').val();
        var xh2= $('input[name=xh2]').val();
        var chkmiercoles=$('#chk-miercoles').prop("checked");
        var chkxp=$('#chk-xp').prop("checked");
        var jd1= $('input[name=jd1]').val();
        var jh1= $('input[name=jh1]').val();
        var jd2= $('input[name=jd2]').val();
        var jh2= $('input[name=jh2]').val();
        var chkjueves=$('#chk-jueves').prop("checked");
        var chkjp=$('#chk-jp').prop("checked");
        var vd1= $('input[name=vd1]').val();
        var vh1= $('input[name=vh1]').val();
        var vd2= $('input[name=vd2]').val();
        var vh2= $('input[name=vh2]').val();
        var chkviernes=$('#chk-viernes').prop("checked");
        var chkvp=$('#chk-vp').prop("checked");
        var sd1= $('input[name=sd1]').val();
        var sh1= $('input[name=sh1]').val();
        var sd2= $('input[name=sd2]').val();
        var sh2= $('input[name=sh2]').val();
        var chksabado=$('#chk-sabado').prop("checked");
        var chksp=$('#chk-sp').prop("checked");
        var dd1= $('input[name=dd1]').val();
        var dh1= $('input[name=dh1]').val();
        var dd2= $('input[name=dd2]').val();
        var dh2= $('input[name=dh2]').val();
        var lpp= $('input[name=lpp]').val();
        var mpp= $('input[name=mpp]').val();
        var xpp= $('input[name=xpp]').val();
        var jpp= $('input[name=jpp]').val();
        var vpp= $('input[name=vpp]').val();
        var spp= $('input[name=spp]').val();
        var dpp= $('input[name=dpp]').val();
        var chkdomingo=$('#chk-domingo').prop("checked");
        var chkdp=$('#chk-dp').prop("checked");
        var error="";
        if (chklunes) {
            if (toDate(lh1,"h:m")<=toDate(ld1,"h:m")){
                error+="lunes, ";
            }
            if (chklp) {
                if ((toDate(ld2,"h:m")<=toDate(lh1,"h:m")) || (toDate(lh2,"h:m")<=toDate(ld2,"h:m"))){
                    error+="2ª hora lunes, ";
                }
            }
        }
        if (chkmartes) {
            if (toDate(mh1,"h:m")<=toDate(md1,"h:m")){
                error+="martes, ";
            }
            if (chkmp) {
                if ((toDate(md2,"h:m")<=toDate(mh1,"h:m")) || (toDate(mh2,"h:m")<=toDate(md2,"h:m"))){
                    error+="2ª hora martes, ";
                }
            }
        }
        if (chkmiercoles) {
            if (toDate(xh1,"h:m")<=toDate(xd1,"h:m")){
                error+="miércoles, ";
            }
            if (chkxp) {
                if ((toDate(xd2,"h:m")<=toDate(xh1,"h:m")) || (toDate(xh2,"h:m")<=toDate(xd2,"h:m"))){
                    error+="2ª hora miércoles, ";
                }
            }
        }
        if (chkjueves) {
            if (toDate(jh1,"h:m")<=toDate(jd1,"h:m")){
                error+="jueves, ";
            }
            if (chkjp) {
                if ((toDate(jd2,"h:m")<=toDate(jh1,"h:m")) || (toDate(jh2,"h:m")<=toDate(jd2,"h:m"))){
                    error+="2ª hora jueves, ";
                }
            }
        }        
        if (chkviernes) {
            if (toDate(vh1,"h:m")<=toDate(vd1,"h:m")){
                error+="viernes, ";
            }
            if (chkvp) {
                if ((toDate(vd2,"h:m")<=toDate(vh1,"h:m")) || (toDate(vh2,"h:m")<=toDate(vd2,"h:m"))){
                    error+="2ª hora viernes, ";
                }
            }
        }        
        if (chksabado) {
            if (toDate(sh1,"h:m")<=toDate(sd1,"h:m")){
                error+="sábado, ";
            }
            if (chksp) {
                if ((toDate(sd2,"h:m")<=toDate(sh1,"h:m")) || (toDate(sh2,"h:m")<=toDate(sd2,"h:m"))){
                    error+="2ª hora sábado, ";
                }
            }
        }        
        if (chkdomingo) {
            if (toDate(dh1,"h:m")<=toDate(dd1,"h:m")){
                error+="domingo, ";
            }
            if (chkdp) {
                if ((toDate(dd2,"h:m")<=toDate(dh1,"h:m")) || (toDate(dh2,"h:m")<=toDate(dd2,"h:m"))){
                    error+="2ª hora domingo, ";
                }
            }
        }         
        if (error!=""){
            error=error.substring(0, error.length - 2);
            app.dialog.alert("Revise los errores: "+error);
        }
        else {
            //console.log("Sin errores");
            
            var server=servidor+'admin/includes/guardahorasreparto.php';
            $.ajax({
                type: "POST",
                url: server,
                data: {intervalo:intervalo, chklunes:chklunes, chklp:chklp, ld1:ld1, lh1:lh1, ld2:ld2, lh2:lh2, chkmartes:chkmartes, chkmp:chkmp, md1:md1, mh1:mh1, md2:md2, mh2:mh2, chkmiercoles:chkmiercoles, chkxp:chkxp, xd1:xd1, xh1:xh1, xd2:ld2, xh2:xh2, chkjueves:chkjueves, chkjp:chkjp, jd1:jd1, jh1:jh1, jd2:jd2, jh2:jh2, chkviernes:chkviernes, chkvp:chkvp, vd1:vd1, vh1:vh1, vd2:vd2, vh2:vh2, chksabado:chksabado, chksp:chksp, sd1:sd1, sh1:sh1, sd2:sd2, sh2:sh2, chkdomingo:chkdomingo, chkdp:chkdp, dd1:dd1, dh1:dh1, dd2:dd2, dh2:dh2,lpp:lpp, mpp:mpp, xpp:xpp, jpp:jpp, vpp:vpp, spp:spp, dpp:dpp},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){
                        muestrahorasrepartos();
                        app.dialog.alert('Datos guardados');
                    }
                    else{
                        app.dialog.alert('No se pudo guardar los horarios');
                    }
                }
            });

        }

    });
    
}

function muestrahorascocina() {
    var intervalo='15';
    $('#titulo-repartos').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Horas de entregas'); 
    var txt=''+
    '<form id="my-form">'+
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<a class="item-link smart-select smart-select-init" data-open-in="popover">'+

                        '<select id="intervalo" name="intervalo">'+
                            '<option value="10">10</option>'+
                            '<option value="15">15</option>'+
                            '<option value="20">20</option>'+
                            '<option value="30">30</option>'+
                            '<option value="45">45</option>'+
                            '<option value="60">60</option>'+
                        '</select>'+
                        ' <div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="item-title" style="font-size:20px;"><b>Intervalo</b></div>'+
                                '<div class="item-after intervalo-after"></div>'+
                            '</div>'+
                        '</div>'+
                    '</a>'+                  
                '</li>'+
            '</ul>'+
        '</div>'+
        
        // LUNES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>Lunes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-lunes" name="lunes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hlunes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="lpp" placeholder="máximo lunes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-lp" name="lunes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="ld1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="lh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="lp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="ld2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="lh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // MARTRES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>Martes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-martes" name="martes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hmartes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="mpp" placeholder="máximo martes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-mp" name="martes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="md1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="mh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="mp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="md2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="mh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // MIERCOLES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>miercoles</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-miercoles" name="miercoles" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hmiercoles" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="xpp" placeholder="máximo miércoles" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-xp" name="miercoles" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="xp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="xh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // JUEVES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>jueves</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-jueves" name="jueves" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hjueves" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="jpp" placeholder="máximo jueves" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-jp" name="jueves" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="jp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="jh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // VIERNES
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>viernes</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-viernes" name="viernes" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hviernes" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="vpp" placeholder="máximo viernes" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-vp" name="viernes" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="vp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="vh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // SABADO
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>sabado</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-sabado" name="sabado" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hsabado" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="spp" placeholder="máximo sábado" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-sp" name="sabado" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="sp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="sh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
        
        // DOMINGO
        '<div class="list">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content">'+
                        '<div class="item-inner">'+
                            '<div class="item-title text-color-primary" style="font-size:22px;"><b>domingo</b></div>'+
                            '<div class="item-after">'+
                              '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="chk-domingo" name="domingo" value="yes" class="dia-semana" /><i class="toggle-icon"></i>'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
        '</div>'+
        '<div id="hdomingo" style="display:none">'+
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Nº pedidos máximo</div>'+
                                    '<div class="item-input-wrap">'+
                                      '<input type="text" name="dpp" placeholder="máximo domingo" value="" />'+
                                    '</div>'+
                                '</div>'+
                             '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                        '<li>'+
                            '<div class="item-content">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title"><b>Horario partido</b></div>'+
                                    '<div class="item-after">'+
                                      '<label class="toggle toggle-init">'+
                                        '<input type="checkbox" id="chk-dp" name="domingo" value="yes" class="dia-partido" /><i class="toggle-icon"></i>'+
                                      '</label>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        
            '<div class="grid grid-cols-2 grid-gap" >'+
                '<div class="list">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 1</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dd1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dh1" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+

                    '</ul>'+
                '</div>'+
                '<div class="list" id="dp" style="display:none">'+
                    '<ul>'+
                        '<li class="item-content item-input"><b>Tramo 2</b></li>'+
                        '<li class="item-content item-input">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Desde:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dd2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li class="item-content item-input ">'+
                            '<div class="item-inner">'+
                                '<div class="item-title item-label">Hasta:</div>'+
                                '<div class="item-input-wrap">'+
                                    '<input type="time" name="dh2" class="hora" value="00:00" max="23:59" min="00:00">'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                    '</ul>'+
                '</div>'+
            '</div>'+
        '</div>'+
                
        
        
        '</form>'+
        '<div class="block block-strong row">'+
                    '<div class="col-33"><a class="button button-fill save-data" href="#" style="margin: auto;width: 50%;">Guardar</a</div>'+
                '</div>';
    $('#repartos-page').html(txt);
    var server=servidor+'admin/includes/leehorascocina.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);       
            if (obj.valid==true){
                
                intervalo=obj.intervalo;
                var lunes=obj.lunes;
                var martes=obj.martes;
                var miercoles=obj.miercoles;
                var jueves=obj.jueves;
                var viernes=obj.viernes;
                var sabado=obj.sabado;
                var domingo=obj.domingo;
                var ld1=obj.ld1;
                var lh1=obj.lh1;
                var ld2=obj.ld2;
                var lh2=obj.lh2;
                var md1=obj.md1;
                var mh1=obj.mh1;
                var md2=obj.md2;
                var mh2=obj.mh2;
                var xd1=obj.xd1;
                var xh1=obj.xh1;
                var xd2=obj.xd2;
                var xh2=obj.xh2; 
                var jd1=obj.jd1;
                var jh1=obj.jh1;
                var jd2=obj.jd2;
                var jh2=obj.jh2;
                var vd1=obj.vd1;
                var vh1=obj.vh1;
                var vd2=obj.vd2;
                var vh2=obj.vh2;
                var sd1=obj.sd1;
                var sh1=obj.sh1;
                var sd2=obj.sd2;
                var sh2=obj.sh2;
                var dd1=obj.dd1;
                var dh1=obj.dh1;
                var dd2=obj.dd2;
                var dh2=obj.dh2;
                
                var lp=obj.lp;  
                var mp=obj.mp;
                var xp=obj.xp;  
                var jp=obj.jp; 
                var vp=obj.vp;  
                var sp=obj.sp;
                var dp=obj.dp;  
                var lpp=obj.lpp;  
                var mpp=obj.mpp;  
                var xpp=obj.xpp;  
                var jpp=obj.jpp;  
                var vpp=obj.vpp;  
                var spp=obj.spp;  
                var dpp=obj.dpp;  
                if (lunes=="0"){
                    $('#chk-lunes').prop("checked", false );
                }
                else {
                    $('#chk-lunes').prop("checked", true );
                    $('#hlunes').show();
                    $('#hlunespp').show();
                }
                if (lp=="0"){
                    $('#chk-lp').prop("checked", false );
                }
                else {
                    $('#chk-lp').prop("checked", true );
                    $('#lp').show();
                }
                if (martes=="0"){
                    $('#chk-martes').prop("checked", false );
                }
                else {
                    $('#chk-martes').prop("checked", true );
                    $('#hmartes').show();
                }
                if (mp=="0"){
                    $('#chk-mp').prop("checked", false );
                }
                else {
                    $('#chk-mp').prop("checked", true );
                    $('#mp').show();
                }
                if (miercoles=="0"){
                    $('#chk-miercoles').prop("checked", false );
                }
                else {
                    $('#chk-miercoles').prop("checked", true );
                    $('#hmiercoles').show();
                }
                if (xp=="0"){
                    $('#chk-xp').prop("checked", false );
                }
                else {
                    $('#chk-xp').prop("checked", true );
                    $('#xp').show();
                }
                if (jueves=="0"){
                    $('#chk-jueves').prop("checked", false );
                }
                else {
                    $('#chk-jueves').prop("checked", true );
                    $('#hjueves').show();
                }
                if (jp=="0"){
                    $('#chk-jp').prop("checked", false );
                }
                else {
                    $('#chk-jp').prop("checked", true );
                    $('#jp').show();
                }
                if (viernes=="0"){
                    $('#chk-viernes').prop("checked", false );
                }
                else {
                    $('#chk-viernes').prop("checked", true );
                    $('#hviernes').show();
                }
                if (vp=="0"){
                    $('#chk-vp').prop("checked", false );
                }
                else {
                    $('#chk-vp').prop("checked", true );
                    $('#vp').show();
                }
                if (sabado=="0"){
                    $('#chk-sabado').prop("checked", false );
                }
                else {
                    $('#chk-sabado').prop("checked", true );
                    $('#hsabado').show();
                }
                if (sp=="0"){
                    $('#chk-sp').prop("checked", false );
                }
                else {
                    $('#chk-sp').prop("checked", true );
                    $('#sp').show();
                }
                if (domingo=="0"){
                    $('#chk-domingo').prop("checked", false );
                }
                else {
                    $('#chk-domingo').prop("checked", true );
                    $('#hdomingo').show();
                }
                if (dp=="0"){
                    $('#chk-dp').prop("checked", false );
                }
                else {
                    $('#chk-dp').prop("checked", true );
                    $('#dp').show();
                }
                
                //alert(intervalo);
                $("#intervalo").val(intervalo);
                $(".intervalo-after").html(intervalo);
                $('input[name=ld1]').val(ld1);
                $('input[name=lh1]').val(lh1);
                $('input[name=ld2]').val(ld2);
                $('input[name=lh2]').val(lh2);
                $('input[name=md1]').val(md1);
                $('input[name=mh1]').val(mh1);
                $('input[name=md2]').val(md2);
                $('input[name=mh2]').val(mh2);
                $('input[name=xd1]').val(xd1);
                $('input[name=xh1]').val(xh1);
                $('input[name=xd2]').val(xd2);
                $('input[name=xh2]').val(xh2);
                $('input[name=jd1]').val(jd1);
                $('input[name=jh1]').val(jh1);
                $('input[name=jd2]').val(jd2);
                $('input[name=jh2]').val(jh2);
                $('input[name=vd1]').val(vd1);
                $('input[name=vh1]').val(vh1);
                $('input[name=vd2]').val(vd2);
                $('input[name=vh2]').val(vh2);
                $('input[name=sd1]').val(sd1);
                $('input[name=sh1]').val(sh1);
                $('input[name=sd2]').val(sd2);
                $('input[name=sh2]').val(sh2);
                $('input[name=dd1]').val(dd1);
                $('input[name=dh1]').val(dh1);
                $('input[name=dd2]').val(dd2);
                $('input[name=dh2]').val(dh2);
                $('input[name=lpp]').val(lpp);
                $('input[name=mpp]').val(mpp);
                $('input[name=xpp]').val(xpp);
                $('input[name=jpp]').val(jpp);
                $('input[name=vpp]').val(vpp);
                $('input[name=spp]').val(spp);
                $('input[name=dpp]').val(dpp);
                //$('input').css('width','100px');
                $('.grid-cols-2').css('margin-top','-40px');
                $('.grid-cols-2').css('margin-bottom','-40px');
            }
            else{
                app.dialog.alert('No se pudo leer las horas');
            }
        }
    });     
    
    $('.dia-semana').on('click', function () {
        var nombre=$(this).attr('name');
        if($('#chk-'+nombre).prop("checked")=='1') {
            $('#h'+nombre).show();
            $('#h'+nombre+'pp').show();
        }
        else {
            $('#h'+nombre).hide();
            $('#h'+nombre+'pp').hide();
        }
        //console.log($(this).attr('name'));
    });
    $('.dia-partido').on('click', function () {
        //console.log($(this).attr('name'));
        var nombre=$(this).attr('name');
        var nom="";
        if (nombre=='lunes'){nom='lp';}
        if (nombre=='martes'){nom='mp';}
        if (nombre=='miercoles'){nom='xp';}
        if (nombre=='jueves'){nom='jp';}
        if (nombre=='viernes'){nom='vp';}
        if (nombre=='sabado'){nom='sp';}
        if (nombre=='domingo'){nom='dp';}
        
        if($('#chk-'+nom).prop("checked")=='1') {
            $('#'+nom).show();
        }
        else {
            $('#'+nom).hide();
        }
    
    });

    $('#intervalo').change(function() {
        intervalo=$('#intervalo').val();
        $(".hora" ).each(function() {
            var time=($(this).val()).split(':');
            var hora=time[0];
            var minutos=time[1];
            //minutos=buscaminutosmascercano(minutos,parseInt(intervalo));
            $(this).val(hora+':'+minutos);
        });
    });
    
    $(".hora" ).blur(function() {
        var time=($(this).val()).split(':');
        var hora=time[0];
        var minutos=time[1];
        //minutos=buscaminutosmascercano(minutos,parseInt(intervalo));
        $(this).val(hora+':'+minutos);
    });
    
    $('.save-data').on('click', function () {
        var intervalo=$('#intervalo').val();
        var ld1= $('input[name=ld1]').val();
        var lh1= $('input[name=lh1]').val();
        var ld2= $('input[name=ld2]').val();
        var lh2= $('input[name=lh2]').val();
        var chklunes=$('#chk-lunes').prop("checked");
        var chklp=$('#chk-lp').prop("checked");
        var md1= $('input[name=md1]').val();
        var mh1= $('input[name=mh1]').val();
        var md2= $('input[name=md2]').val();
        var mh2= $('input[name=mh2]').val();
        var chkmartes=$('#chk-martes').prop("checked");
        var chkmp=$('#chk-mp').prop("checked");
        var xd1= $('input[name=xd1]').val();
        var xh1= $('input[name=xh1]').val();
        var xd2= $('input[name=xd2]').val();
        var xh2= $('input[name=xh2]').val();
        var chkmiercoles=$('#chk-miercoles').prop("checked");
        var chkxp=$('#chk-xp').prop("checked");
        var jd1= $('input[name=jd1]').val();
        var jh1= $('input[name=jh1]').val();
        var jd2= $('input[name=jd2]').val();
        var jh2= $('input[name=jh2]').val();
        var chkjueves=$('#chk-jueves').prop("checked");
        var chkjp=$('#chk-jp').prop("checked");
        var vd1= $('input[name=vd1]').val();
        var vh1= $('input[name=vh1]').val();
        var vd2= $('input[name=vd2]').val();
        var vh2= $('input[name=vh2]').val();
        var chkviernes=$('#chk-viernes').prop("checked");
        var chkvp=$('#chk-vp').prop("checked");
        var sd1= $('input[name=sd1]').val();
        var sh1= $('input[name=sh1]').val();
        var sd2= $('input[name=sd2]').val();
        var sh2= $('input[name=sh2]').val();
        var chksabado=$('#chk-sabado').prop("checked");
        var chksp=$('#chk-sp').prop("checked");
        var dd1= $('input[name=dd1]').val();
        var dh1= $('input[name=dh1]').val();
        var dd2= $('input[name=dd2]').val();
        var dh2= $('input[name=dh2]').val();
        var lpp= $('input[name=lpp]').val();
        var mpp= $('input[name=mpp]').val();
        var xpp= $('input[name=xpp]').val();
        var jpp= $('input[name=jpp]').val();
        var vpp= $('input[name=vpp]').val();
        var spp= $('input[name=spp]').val();
        var dpp= $('input[name=dpp]').val();
        var chkdomingo=$('#chk-domingo').prop("checked");
        var chkdp=$('#chk-dp').prop("checked");
        var error="";
        if (chklunes) {
            if (toDate(lh1,"h:m")<=toDate(ld1,"h:m")){
                error+="lunes, ";
            }
            if (chklp) {
                if ((toDate(ld2,"h:m")<=toDate(lh1,"h:m")) || (toDate(lh2,"h:m")<=toDate(ld2,"h:m"))){
                    error+="2ª hora lunes, ";
                }
            }
        }
        if (chkmartes) {
            if (toDate(mh1,"h:m")<=toDate(md1,"h:m")){
                error+="martes, ";
            }
            if (chkmp) {
                if ((toDate(md2,"h:m")<=toDate(mh1,"h:m")) || (toDate(mh2,"h:m")<=toDate(md2,"h:m"))){
                    error+="2ª hora martes, ";
                }
            }
        }
        if (chkmiercoles) {
            if (toDate(xh1,"h:m")<=toDate(xd1,"h:m")){
                error+="miércoles, ";
            }
            if (chkxp) {
                if ((toDate(xd2,"h:m")<=toDate(xh1,"h:m")) || (toDate(xh2,"h:m")<=toDate(xd2,"h:m"))){
                    error+="2ª hora miércoles, ";
                }
            }
        }
        if (chkjueves) {
            if (toDate(jh1,"h:m")<=toDate(jd1,"h:m")){
                error+="jueves, ";
            }
            if (chkjp) {
                if ((toDate(jd2,"h:m")<=toDate(jh1,"h:m")) || (toDate(jh2,"h:m")<=toDate(jd2,"h:m"))){
                    error+="2ª hora jueves, ";
                }
            }
        }        
        if (chkviernes) {
            if (toDate(vh1,"h:m")<=toDate(vd1,"h:m")){
                error+="viernes, ";
            }
            if (chkvp) {
                if ((toDate(vd2,"h:m")<=toDate(vh1,"h:m")) || (toDate(vh2,"h:m")<=toDate(vd2,"h:m"))){
                    error+="2ª hora viernes, ";
                }
            }
        }        
        if (chksabado) {
            if (toDate(sh1,"h:m")<=toDate(sd1,"h:m")){
                error+="sábado, ";
            }
            if (chksp) {
                if ((toDate(sd2,"h:m")<=toDate(sh1,"h:m")) || (toDate(sh2,"h:m")<=toDate(sd2,"h:m"))){
                    error+="2ª hora sábado, ";
                }
            }
        }        
        if (chkdomingo) {
            if (toDate(dh1,"h:m")<=toDate(dd1,"h:m")){
                error+="domingo, ";
            }
            if (chkdp) {
                if ((toDate(dd2,"h:m")<=toDate(dh1,"h:m")) || (toDate(dh2,"h:m")<=toDate(dd2,"h:m"))){
                    error+="2ª hora domingo, ";
                }
            }
        }         
        if (error!=""){
            error=error.substring(0, error.length - 2);
            app.dialog.alert("Revise los errores: "+error);
        }
        else {
            //console.log("Sin errores");
            
            var server=servidor+'admin/includes/guardahorascocina.php';
            $.ajax({
                type: "POST",
                url: server,
                data: {intervalo:intervalo, chklunes:chklunes, chklp:chklp, ld1:ld1, lh1:lh1, ld2:ld2, lh2:lh2, chkmartes:chkmartes, chkmp:chkmp, md1:md1, mh1:mh1, md2:md2, mh2:mh2, chkmiercoles:chkmiercoles, chkxp:chkxp, xd1:xd1, xh1:xh1, xd2:ld2, xh2:xh2, chkjueves:chkjueves, chkjp:chkjp, jd1:jd1, jh1:jh1, jd2:jd2, jh2:jh2, chkviernes:chkviernes, chkvp:chkvp, vd1:vd1, vh1:vh1, vd2:vd2, vh2:vh2, chksabado:chksabado, chksp:chksp, sd1:sd1, sh1:sh1, sd2:sd2, sh2:sh2, chkdomingo:chkdomingo, chkdp:chkdp, dd1:dd1, dh1:dh1, dd2:dd2, dh2:dh2,lpp:lpp, mpp:mpp, xpp:xpp, jpp:jpp, vpp:vpp, spp:spp, dpp:dpp},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){
                        
                        muestrahorascocina();
                        app.dialog.alert('Datos guardados');
                    }
                    else{
                        app.dialog.alert('No se pudo guardar los horarios');
                    }
                }
            });
 
            
        }

    
    });
    
}

function buscaminutosmascercano(min,intervalo){
    var buscar=parseInt(min);
    var arr=new Array();
    var x=0;
    var i=0;
    
    do {
        arr[i]=x;
        i++;
        x=x+intervalo;
      
    }
    while (x < 60); 
    
    //console.log(arr);
    var cercano=0;
    for (x=0;x<arr.length;x++){
        var dif=Math.abs(arr[x]-min);
        //console.log('Min:'+min+' dif:'+dif);
        if (dif<=(intervalo/2)){
            cercano=arr[x];
            //console.log('Cercano:'+cercano);
        }
    }
    if (cercano<=9){
        cercano='0'+cercano;
    }
    return(cercano);
}

function toDate(dStr,format) {
	var now = new Date();
	if (format == "h:m") {
 		now.setHours(dStr.substr(0,dStr.indexOf(":")));
 		now.setMinutes(dStr.substr(dStr.indexOf(":")+1));
 		now.setSeconds(0);
 		return now;
	}else 
		return "Invalid Format";
}