
function promociones() {
    var txt='';
    var server=servidor+'admin/includes/leepromosespeciales.php';     $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo',tienda:tienda},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                var tipo=obj.tipo;
                var nombre=obj.nombre;
                var id=obj.id;
                var usuario=obj.usuario;
                var grupo=obj.grupo;
                var envio_recoger=obj.envio_recoger;
                var activo=obj.activo;
                var dias=obj.dias;
                var desde=obj.desde;
                var hasta=obj.hasta;
                var codigo=obj.codigo;
                var logica=obj.logica;
                var hoy = new Date();
                var min = hoy.getMinutes();
                var HH = hoy.getHours();
                var dd = hoy.getDate();
                var mm = hoy.getMonth()+1;
                var yyyy = hoy.getFullYear();
                var iconTooltip2=new Array();
                if(dd<10) {
                    dd='0'+dd;
                } 
                
                if(mm<10) {
                    mm='0'+mm;
                } 
                var fecha= yyyy+'-'+mm+'-'+dd+' '+HH+':'+min+':00';
                
                
                for (x=0;x<id.length;x++){
                    
                    var color="";  
                    var chk_prom="checked";
                    if (activo[x]==0){
                        color=" text-color-red";
                        chk_prom=''
                    }
                    
                    var colfecha=dias[x]+' dias'
                    if (hasta[x]!=null){
                        var fhasta=new Date();
                
                        fhasta.setFullYear(parseInt(hasta[x].substr(0,4)));
                        fhasta.setMonth( parseInt(hasta[x].substr(5,2))-1);
                        fhasta.setDate(parseInt(hasta[x].substr(8,2)));
                        fhasta.setHours(parseInt(hasta[x].substr(11,2)));
                        fhasta.setMinutes(parseInt(hasta[x].substr(14,2)));

                        if (hoy.getTime()>fhasta.getTime()){
                            color=" text-color-red";
                        }
                       //desde[x]=desde[x].substr(8,2)+'/'+desde[x].substr(5,2)+'/'+desde[x].substr(0,4)+' '+desde[x].substr(11,2)+':'+desde[x].substr(14,2); 
                        colfecha=hasta[x].substr(8,2)+'/'+hasta[x].substr(5,2)+'/'+hasta[x].substr(0,4)+' '+hasta[x].substr(11,2)+':'+hasta[x].substr(14,2);
                    }
                    txt+=
                        '<div class="grid grid-cols-6 grid-gap'+color+'">'+
                            '<div class="" style="font-size:12px;">'+nombre[x]+'</div>'+
                        '<div class="" style="font-size:12px;"><i style="font-size: 20px" class="icon f7-icons icon-tooltip" id="tooltip-esp-'+id[x]+'">info_circle_fill</i></div>'+
                            '<div class="" style="font-size:8px;">'+codigo[x]+'</div>'+
                        '<div class="" style="font-size:8px;"><label class="checkbox"><input type="checkbox" name="activo_prom" '+chk_prom+' data-id="'+id[x]+'" data-tipo="web" data-elemento="promo" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label></div>'+
                            '<div class="" style="font-size:8px;">'+colfecha+'</div>'+
                            '<div class="" onclick="editapromoespecial(\''+id[x]+'\',\''+nombre[x]+'\',\''+tipo[x]+'\',\''+codigo[x]+'\',\''+envio_recoger[x]+'\',\''+usuario[x]+'\',\''+grupo[x]+'\',\''+dias[x]+'\',\''+desde[x]+'\',\''+hasta[x]+'\');"><i class="f7-icons" >pencil</i></div>'+
                        '</div>';  
                    
                    
                }    
                $('#tabla-promos-especiales').html(txt);  
                var tip="";
                for (x=0;x<id.length;x++){
                    //'<option value="1">% Dto. Global</option>'+
                    //<option value="2">% Dto. Producto</option>'+
                    //<option value="3">Envío Gratis</option>'+
                    //<option value="4">Importe descuento</option>'+   
                    if (tipo[x]==1){
                        tip='% Descuento global';       
                    }
                    if (tipo[x]==2){
                        tip='% Descuento en producto';       
                    }
                    if (tipo[x]==7){
                        tip='% Descuento en categoría';       
                    }
                    if (tipo[x]==3){
                        tip='Envío Gratis';       
                    }
                    if (tipo[x]==4){
                        tip='x € de regalo';       
                    }
                    
                    if (tipo[x]==5){
                        tip='H x J en producto';       
                    }
                    if (tipo[x]==6){
                        tip='H x J en categoría';       
                    }
                
                    iconTooltip2[x] = app.tooltip.create({
                        targetEl: '#tooltip-esp-'+id[x],
                        text: tip,
                    });
                    
                }
                
            }
            else{
                app.dialog.alert('No se pudo leer las promociones');
            }
        }
    });


}

function editapromoespecial(id=0, nombre='',tipo=1, codigo='', envio_recoger=0,usuario='', grupo='',dias='',desde='',hasta=''){
 
    //console.log(desde+'-'+hasta);

    var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Promociones</div>'+
                '<form id="promos-form" >'+
            '<input type="hidden" name="idpromo"  value=""/>'+
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
                            '<div class="item-title item-label">Código<span style="float:right;">de 8 a 12 caracteres MAYUS. y/o NUM.</span></div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="codigo" placeholder="Código"  value="'+codigo+'" validate pattern="[A-Z0-9]{8,12}$"/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                      '<li id="dias">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Días para utilizar</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="dias" value="'+dias+'" placeholder="Días" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                        '<li id="desde">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Desde</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="desde" value="'+desde+'" placeholder="Seleccione fecha" id="calendar-desde" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li id="hasta">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Hasta</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="hasta" value="'+hasta+'" placeholder="Seleccione fecha" id="calendar-hasta" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                     '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Recoger/Enviar</div>'+
                            '<div class="item-input-wrap input-dropdown-wrap" >'+
                                '<select placeholder="Escoja Recoger/Enviar" id="envio-recoger" >'+
                                    '<option value="0">Ambos</option>'+
                                    '<option value="2">Sólo recoger</option>'+
                                    '<option value="1">Sólo Enviar</option>'+
                                   
                                ' </select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                    '<li id="destino">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Beneficiarios</div>'+
                            '<div class="item-input-wrap input-dropdown-wrap" >'+
                                '<select placeholder="Escoja alcance del cupón" id="destino-cupon" onchange="cambiatipodestino(this);">'+
                                    '<option value="1">Todos los usuarios</option>'+
                                    '<option value="2">Solos los usuarios registrados</option>'+
                                    '<option value="3">Grupo fidelización</option>'+
                                   
                                ' </select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                    '<li id="grupos">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Grupos</div>'+
                            '<div id="contenido-grupos"></div>'+
                              
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                    '<li id="maximo">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Máximo (0 para sin limite)</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="maximo" value="0" placeholder="Maximo" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Tipo</div>'+
                            '<div class="item-input-wrap input-dropdown-wrap" >'+
                                '<select placeholder="Escoja un tipo ..." id="tipopromo" onchange="cambiatipopromo(this);">'+
                                    '<option value="1">% Dto. Global</option>'+
                                    '<option value="2">% Dto. Producto</option>'+
        '<option value="7">% Dto. Categoria</option>'+
                                    '<option value="3">Envío Gratis</option>'+
                                    '<option value="4">Importe descuento</option>'+
        '<option value="5">H x J en productos</option>'+
        '<option value="6">H x J en categoría</option>'+
                                ' </select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li id="porcentaje">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Porcentaje</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="porcentaje" validate  />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 

                    '<li id="producto-array">'+
                    '<a class="item-link item-content" onclick="addproductotoarray();" id="addproductotoarray">'+
                      '<div class="item-inner">'+
                        '<div class="item-title">Lista de productos </div>'+
                        '<div class="item-after" id="div-producto-array">Sin</div>'+
                        
                      '</div>'+
                    '</a>'+
                  '</li>'+
                      '<li id="producto">'+
                        '<div class="item-content item-input">'+
                            '<div class="item-media busca-producto-promo">'+
                                '<i class="icon f7-icons">search</i>'+
                            '</div>'+
                          '<div class="item-inner">'+

                            '<div class="item-title item-label">Producto</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="productopromo" disabled/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                        '<li id="categoria">'+
                        '<div class="item-content item-input">'+
                            '<div class="item-media busca-categoria-promo">'+
                                '<i class="icon f7-icons">search</i>'+
                            '</div>'+
                          '<div class="item-inner">'+

                            '<div class="item-title item-label">Categoría</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="categoriapromo" disabled/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      '<li id="importe">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Importe</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="importe" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      '<li id="minimo">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Mínimo</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="minimo" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                    '<li id="H-promo">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">H x J</div>'+
                            '<div class="item-input-wrap" style="display: flex;" >'+
                              '<input type="text" name="h_promo" maxlength="2" style="width: 30%;"/> &nbsp; <i class="icons f7-icons size-16" style="margin-top: 10px;">xmark</i> &nbsp; '+
        '<input type="text" name="j_promo" maxlength="2" style="width: 30%;"/>&nbsp;&nbsp;<label class="checkbox" style="display: flex;margin-top: 7px;"><input type="checkbox" name="precio_promo" checked/><i class="icon-checkbox"></i>&nbsp; Precio más bajo</label>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      
                      // desde, hasta tipo
                    '</ul>'+   
                '</div>'+
                '<input type="hidden" name="lista_poroductos_descuento" />'+

         
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" data-id="'+id+'" onclick="guardapromoespecial(this);" href="#">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'  ,
     on: {
            open: function (popup) {
                
                //01/34/6789
                var fhasta;

                
                if ((id==1)||(id==2)){
                    
                    $('#promos-form input[name*="hasta"]').val('');
                    $('#hasta').hide();
                    $('#desde').hide();
                    $('#maximo').hide();
                    $('#destino').hide();
                    $('#grupos').hide();
                    $('#promos-form input[name*="nombre"]').prop('disabled',true);
                }
                else {
                    
                    $('#promos-form input[name*="dias"]').prop('disabled',true);
                    $('#dias').hide();
                    if (hasta==''){
                        fhasta=new Date();
                    }
                    else {

                         fhasta=new Date(hasta.substr(0,4), parseInt(hasta.substr(5,2))-1, hasta.substr(8,2),hasta.substr(11,2), hasta.substr(14,2));
                        
                    }
                    if (desde==''){
                        fdesde=new Date();
                    }
                    else {

                         fdesde=new Date(desde.substr(0,4), parseInt(desde.substr(5,2))-1, desde.substr(8,2),desde.substr(11,2), desde.substr(14,2));
                        
                    }
                    
                    calendarHasta = app.calendar.create({
                        inputEl: '#calendar-hasta',
                        value: [fhasta],
                        openIn: 'customModal',
                        timePicker: true,
                        toolbarCloseText: 'Hecho',
                        //header: true,
                        closeOnSelect:true,
                        dateFormat: 'dd/mm/yyyy HH::mm'
                    });
                    calendarDesde = app.calendar.create({
                        inputEl: '#calendar-desde',
                        value: [fdesde],
                        openIn: 'customModal',
                        timePicker: true,
                        toolbarCloseText: 'Hecho',
                        //header: true,
                        closeOnSelect:true,
                        dateFormat: 'dd/mm/yyyy HH::mm'
                    });
                    if (id==5){
                        $('#producto-array').show();
                        
                        
                        $('#categoria').hide();
                    }
                    if (id==6){
                        $('#producto').hide();
                        $('#categoria').show();
                    }
                }

                if (id!=0){
                    var server=servidor+'admin/includes/leeunapromoespecial.php';     
                    $.ajax({
                        type: "POST",
                        url: server,
                        data: {id:id,tienda:tienda},
                        dataType:"json",
                        success: function(data){
                            var obj=Object(data);
                            //console.log(obj);
                            if (obj.valid==true){
                                var tipo=obj.tipo;
                                var nombre=obj.nombre;
                                var codigo=obj.codigo;
                                var envio_recoger=obj.envio_recoger;
                                var id=obj.id;
                                var dias=obj.desde;
                                var hasta=obj.hasta;
                                var logica=obj.logica;
                                var producto=obj.producto;
                                var categoria=obj.categoria;
                                var usuario=obj.usuario;
                                var usuario=obj.usuario;
                                var grupo=obj.grupo;
                                var maximo=obj.maximo;
//console.log(producto);
                                $('#promos-form input[name*="maximo"]').val(maximo);
                                
                                if (usuario<3){
                                    $('#grupos').hide();
                                }
                                else {
                                    rellenagruposbeneficiarios(grupo);
                                }
                                $('#destino-cupon option[value="'+ usuario +'"]').attr("selected",true);
                                $('#envio-recoger option[value="'+ envio_recoger+'"]').attr("selected",true);
                                if (tipo==2){
                                if (producto!=''){
                                    $('#promos-form input[name*="productopromo"]').val(producto);
                                    porciones=producto.split("-");
                                    $('#promos-form input[name*="idpromo"]').val(porciones[0]);
                                }
                                }
                                if (categoria!=''){
                                    $('#promos-form input[name*="categoriapromo"]').val(categoria);
                                    porciones=producto.split("-");
                                    $('#promos-form input[name*="idpromo"]').val(porciones[0]);
                                }
                                $("#tipopromo").val(tipo);
                                $('#porcentaje').hide();
                                $('#producto').hide();
                                $('#categoria').hide();
                                $('#importe').hide();
                                $('#minimo').hide();
                                $('#H-promo').hide();
                                $('#producto-array').hide();
                                
                                /*
                                '<option value="1">% Dto. Global</option>'+
                                '<option value="2">% Dto. Producto</option>'+
                                '<option value="3">Envío Gratis</option>'+
                                '<option value="4">Importe descuento</option>'+
                                */
                                var partes;
                                if (tipo=='1') {
                                    $('#porcentaje').show();
                                    $('#minimo').show();
                                    partes=logica.split('##')
                                    $('input[name=porcentaje]').val(partes[0]);
                                    $('input[name=minimo]').val(partes[1]);
                                    $('#producto').hide();
                                }
                                if (tipo=='2') {
                                    partes=logica.split('##')
                                    $('input[name=porcentaje]').val(partes[0]);
                                    $('input[name=producto]').val(partes[1]);
                                    $('#porcentaje').show();
                                    $('#producto').show();
                                }
                                if (tipo=='7') {
                                    partes=logica.split('##')
                                    $('input[name=porcentaje]').val(partes[0]);
                                    $('input[name=categoria]').val(partes[1]);
                                    $('#porcentaje').show();
                                    $('#categoria').show();
                                }
                                if (tipo=='3') {
                                    $('#minimo').show();
                                    $('input[name=minimo]').val(logica);
                                }   
                                if (tipo=='4') {
                                    partes=logica.split('##')
                                    $('#importe').show();
                                    $('input[name=importe]').val(partes[0]);
                                    $('#minimo').show();
                                    $('input[name=minimo]').val(partes[1]);
                                }
                                if (tipo=='5') {
                                   $('#H-promo').show();
                                    partes=logica.split('##')
                                    $('#producto-array').show();
                                   $('#div-producto-array').html('Editar');
                                    $('#categoria').hide(); 
                                    $('input[name=h_promo]').val(partes[0]);
                                    $('input[name=j_promo]').val(partes[1]);
                                    if (partes[2]==0){
                                      $('input[name=precio_promo]').prop('checked',false); 
                                    }
                                    
                                   
                                   $('input[name=lista_poroductos_descuento]').val(JSON.stringify(producto)); 
                                    //console.log(producto);
                                    
                                }
                                if (tipo=='6') {
                                   $('#H-promo').show(); partes=logica.split('##')
                                    $('#producto').hide();
                                    $('#producto-array').hide();
                                   $('#categoria').show(); $('input[name=h_promo]').val(partes[1]);
                                    $('input[name=j_promo]').val(partes[2]);
                                    if (partes[3]==0){
                                      $('input[name=precio_promo]').prop('checked',false); 
                                    }
                                }

                            }
                            else{
                                app.dialog.alert('No se pudo leer la promoción');
                            }
                        },
                        error: function(e){
                            console.log('error');
                        }
                    });
                                
                }
                else {
                    $('input[name=codigo]').val(makeid(12));
                    $('#porcentaje').show();
                    $('#categoria').hide();
                    $('#categoria').hide();
                    $('#H-promo').hide();
                    $('#producto').hide();
                    $('#importe').hide();
                    $('#minimo').show();
                    $('#grupos').hide();
                    $('#producto-array').hide();
                }
            }
        }
    });  
    
    dynamicPopup.open(); 
    
    $('.busca-producto-promo').on('click', function () {
    
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
                    data: {id:id,tienda:tienda},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            var txt='<ul>';
                            for (x=0;x<obj.id.length;x++){
                                 txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'" data-nombre="'+obj.nombre[x]+'" onclick="muestraprodbuscadopromo(this);">'+
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
    
    $('.busca-categoria-promo').on('click', function () {
    
        var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+
        
            '<form class="searchbar">'+
                '<div class="searchbar-inner">'+
                    '<div class="searchbar-input-wrap">'+
                        '<input type="search" placeholder="Buscar categoria">'+
                        '<i class="searchbar-icon"></i>'+
                        '<span class="input-clear-button"></span>'+
                    '</div>'+
                    '<span class="searchbar-disable-button">Cancelar</span>'+
                '</div>'+
            '</form>  '   +  

            '<div class="block">' +  
                '<div class="searchbar-backdrop"></div>'+
                '<div class="list searchbar-found lista-productos" id="lista-categoria">'+
                '</div>'+

               ' <div class="block searchbar-not-found">'+
                   ' <div class="block-inner">Categoría no encontrada</div>'+
                '</div>'+
             '</div>' + 

            '</div>'+
          '</div>'
         ,
            on: {
          open: function (popup) {

                var server=servidor+'admin/includes/leecategoriassearch.php';
                $.ajax({
                    type: "POST",
                    url: server,
                    data: {id:id,tienda:tienda},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            var txt='<ul>';
                            for (x=0;x<obj.id.length;x++){
                                 txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'" data-nombre="'+obj.nombre[x]+'" onclick="muestracatbuscadopromo(this);">'+
                                    '<div class="item-inner">'+
                                        '<div class="item-title item-buscado"">'+obj.nombre[x]+'</div>'+
                                    '</div>'+
                                    '</li>';
                            }
                            txt+='</ul>';
                            $('#lista-categoria').html(txt);  
                        }
                        else{
                            $('#lista-categoria').html('');
                        }
                    
                        var searchbar = app.searchbar.create({
                            el: '.searchbar',
                            searchContainer: '#lista-categoria',
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
}


function addproductotoarray(){
    var temp= $('input[name=lista_poroductos_descuento]').val();
    
    
    var lista=new Array();
    if (temp!=''){
        lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
    }
    //console.log(lista);
    var dynamicPopup = app.popup.create({
        content: ''+
        '<div class="popup">'+
            '<div class="block page-content">'+
                '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+
                '<div class="block-title">Productos</div>'+
                '<div class="card data-table" id="div-lista-productos"></div>'+
                
            '</div>'+
        '</div>'
         ,
        // Events
        on: {
          open: function (popup) {
            //console.log('Popup open');
            var txt='<table>'+
              '<thead>'+
                '<tr>'+
                '<th class="label-cell">Producto</th>'+
                '<th class="label-cell">Borrar</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody>';

              for (x=0;x<lista.length;x++){
                  
                  var arr=lista[x].split('||');
                  txt+='<tr>'+
                    '<th class="label-cell">'+arr[1]+'</th>'+
                    '<th onclick="BorrEsteelemento('+x+');$(this).closest(\'tr\').remove();" class="label-cell"><i class="icon f7-icons color-red size-26">trash</i></th>'+
                    '</tr>';
              }

              txt+='</tbody>'+
                  '</table>'
              txt+='<div class="data-table-footer ">'+
                  '<button class="button button-fill" onclick="button_add_prod();" style="width: 25%;margin: auto;" ><i class="icon f7-icons size-26">plus_circle</i></button>'
                  '</div>';
              
              $('#div-lista-productos').html(txt);
              
          },
          opened: function (popup) {
            //console.log('Popup opened');
              
          },
        }
    });  
    dynamicPopup.on('close', function (popup) {
        //console.log('Popup close');
        var lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
        if (lista.length==0){
            $('#div-producto-array').html('Sin');
        }
        
      });
    dynamicPopup.open();

    

    
}

function button_add_prod() {
    
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
                    data: {tienda:tienda},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            var txt='<ul>';
                            for (x=0;x<obj.id.length;x++){
                                 txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'" data-nombre="'+obj.nombre[x]+'" onclick="muestraprodbuscadopromoAdd(this);">'+
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
        
        
        
        
}

function BorrEsteelemento(x){
    var lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
    lista.splice(x, 1);
   //redibujar
   var txt='<table>'+
      '<thead>'+
        '<tr>'+
        '<th class="label-cell">Producto</th>'+
        '<th class="label-cell">Borrar</th>'+
        '</tr>'+
        '</thead>'+
        '<tbody>';

  for (x=0;x<lista.length;x++){

      var arr=lista[x].split('||');
      txt+='<tr>'+
        '<th class="label-cell">'+arr[1]+'</th>'+
        '<th onclick="BorrEsteelemento('+x+');$(this).closest(\'tr\').remove();" class="label-cell"><i class="icon f7-icons color-red size-26">trash</i></th>'+
        '</tr>';
  }

  txt+='</tbody>'+
      '</table>'
  txt+='<div class="data-table-footer ">'+
                  '<button class="button button-fill" onclick="button_add_prod();" style="width: 25%;margin: auto;" ><i class="icon f7-icons size-26">plus_circle</i></button>'
                  '</div>';

  $('#div-lista-productos').html(txt); $('input[name=lista_poroductos_descuento]').val(JSON.stringify(lista));

}

function muestraprodbuscadopromoAdd(e){

    var temp= $('input[name=lista_poroductos_descuento]').val();
    
    
    var lista=new Array();
    if (temp!=''){
        lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
    }
    lista.splice(0,0, e.dataset.id+'||'+e.dataset.nombre);
    //elem.dataset.id+'||'+elem.dataset.nombre
    $('input[name=lista_poroductos_descuento]').val(JSON.stringify(lista));
    app.popup.close();
    var txt='<table>'+
      '<thead>'+
        '<tr>'+
        '<th class="label-cell">Producto</th>'+
        '<th class="label-cell">Borrar</th>'+
        '</tr>'+
        '</thead>'+
        '<tbody>';

  for (x=0;x<lista.length;x++){

      var arr=lista[x].split('||');
      txt+='<tr>'+
        '<th class="label-cell">'+arr[1]+'</th>'+
        '<th onclick="BorrEsteelemento('+x+');$(this).closest(\'tr\').remove();" class="label-cell"><i class="icon f7-icons color-red size-26">trash</i></th>'+
        '</tr>';
  }

  txt+='</tbody>'+
      '</table>'
  txt+='<div class="data-table-footer ">'+
                  '<button class="button button-fill" onclick="button_add_prod();" style="width: 25%;margin: auto;" ><i class="icon f7-icons size-26">plus_circle</i></button>'
                  '</div>';

  $('#div-lista-productos').html(txt); 
    
}
    
function rellenagruposbeneficiarios(grupo){
    //var grupos=grupo.split('-');
    var server=servidor+'admin/includes/leegruposfidelizacion.php';
    var grupos=grupo.split(',');
    $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){          
                var id=obj.id;
                var nombre=obj.nombre;
                var txt='';
                var selected='';
                for (var x=0;x<id.length;x++){
                     var selected='';
                    if (grupos.includes(id[x])){
                        selected='checked';
                    }
                    txt+='<div class="grid grid-cols-2 grid-gap">'+
                        '<div class=""><label class="checkbox"><input type="checkbox" name="grupo_promo" data-id="'+id[x]+'" '+selected+' /><i class="icon-checkbox"></i></label></div>'+
                        '<div class="" data-id="'+id[x]+'" data-nombre="'+nombre[x]+'" >'+nombre[x]+'</div>'+
                        '</div>';
                }   
                $('#contenido-grupos').html(txt);
                //console.log(txt);
            }
        }
        ,
        error:function(e){
            console.log('error leyendo grupos');
        }
    });

}

function cambiatipodestino(e){
    $('#grupos').hide();
    var tipo=e.value;
    // 1 - todos
    // 2 - registrados
    // 3 - grupo
    if (tipo=='3') {
        $('#grupos').show();
        rellenagruposbeneficiarios('grupo');
    }
}

function cambiatipopromo(e){
    var tipo=e.value;
   $('#porcentaje').hide();
    $('#producto').hide();
    $('#categoria').hide();
    $('#importe').hide();
    $('#minimo').hide();
    $('#H-promo').hide();
    /*
'<option value="1">% Dto. Global</option>'+
'<option value="2">% Dto. Producto</option>'+
'<option value="3">Envío Gratis</option>'+
'<option value="4">Importe descuento</option>'+
    */
    if (tipo=='1') {
        $('#porcentaje').show();
        $('#minimo').show();
    }
    if (tipo=='2') {
        $('#porcentaje').show();
        $('#producto').show();
    }
    if (tipo=='7') {
        $('#porcentaje').show();
        $('#categoria').show();
    }
    if (tipo=='3') {
        $('#minimo').show();
    }   
    if (tipo=='4') {
        $('#importe').show();
        $('#minimo').show();
    }
    if (tipo=='5') {
        //$('#producto').show();
        $('#producto-array').show();
        
        $('#H-promo').show();
    }
    if (tipo=='6') {
        $('#categoria').show();
        $('#H-promo').show();
        $('#producto-array').hide();
    }
}

function guardapromoespecial(e){
    
    var errores="";
    var envio_recoger=$('#envio-recoger').val();
    var usuario=$('#destino-cupon').val();
    var dias=0;
    var grupo='';
    var h=0;
    var j=0;
    if (usuario==3){
    // si grupo == 3 ver si hay alguno vacío
        var grupos = new Array();
        $('input[name=grupo_promo]:checked').each(function() {
            
            grupo+=$(this).attr('data-id')+',';
        });
        grupo=grupo.slice(0, grupo.length - 1);
    }

   var id=e.getAttribute('data-id');
    if ($('input[name=nombre]').val()==""){
        errores+='nombre, ';
    }
    if ($('input[name=codigo]').val()==""){
        errores+='código, ';
    }
    if ((id==1)||(id==2)){
        dias=$('input[name=dias]').val();
    }
    else {
        if ($('input[name=hasta]').val()==""){
            errores+='Fecha hasta, ';
        }
        if ($('input[name=desde]').val()==""){
            errores+='Fecha desde, ';
        }
    }
    

     /*
'<option value="1">% Dto. Global</option>'+
'<option value="2">% Dto. Producto</option>'+
'<option value="3">Envío Gratis</option>'+
'<option value="4">Importe descuento</option>'+
    */   

    var logica="";
    var tipo=$("#tipopromo").val();
    if (tipo=='1') {
        if ($('input[name=porcentaje]').val()==""){
            errores+='porcentaje, ';
        }
        logica=$('input[name=porcentaje]').val()+'##'+$('input[name=minimo]').val();
    }
    if (tipo=='2') {
        var porciones=$('input[name=productopromo]').val().split('-');
        if ($('input[name=porcentaje]').val()==""){
            errores+='porcentaje, ';
        }
        if ($('input[name=producto]').val()=="" || porciones[0]==''){
            errores+='producto, ';
        }
        logica=$('input[name=porcentaje]').val()+'##'+porciones[0];
    }
    if (tipo=='7') {
        var porciones=$('input[name=categoriapromo]').val().split('-');
        
        if ($('input[name=porcentaje]').val()=="" ){
            errores+='porcentaje, ';
        }
        if ($('input[name=categoria]').val()=="" || porciones[0]==''){
            errores+='categoria, ';
        }
         
        
        logica=$('input[name=porcentaje]').val()+'##'+porciones[0];
    }
    if (tipo=='3') {
        logica=$('input[name=minimo]').val();
    }   
    if (tipo=='4') {
        if ($('input[name=importe]').val()==""){
            errores+='importe, ';
        }
        logica=$('input[name=importe]').val()+'##'+$('input[name=minimo]').val();
    }
    
    // si grupo == 3 ver si hay alguno vacío
    //var lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
    if (tipo==5){
        var precio=0;
        if ($('input[name=precio_promo]').prop('checked')){
            precio=1;
        } 
        h=$('input[name=h_promo]').val();
        j=$('input[name=j_promo]').val();

        if (h=='' || j=='' || j>h){
            errores+='H y J, ';
        }
        var lista=JSON.parse($('input[name=lista_poroductos_descuento]').val());
        if (lista.length==0){
            errores+='Productos, ';
        }
        var prod=new Array();
        for(x=0;x<lista.length;x++){
            var ids=lista[x].split('||');
            prod.push(ids[0]);
        }
        logica=h+'##'+j+'##'+precio+'##'+prod;
        console.log(logica);
        
    }
    if (tipo==6){
        var precio=0;
        if ($('input[name=precio_promo]').prop('checked')){
            precio=1;
        } 
        h=$('input[name=h_promo]').val();
        j=$('input[name=j_promo]').val();

        if (h=='' || j=='' || j>h){
            errores+='H y J, ';
        }
        
        
        
        if ($('input[name=categoria]').val()==""){
            errores+='categoria, ';
        }
        var porciones=$('input[name=categoriapromo]').val().split('-');
        
        logica=porciones[0]+'##'+h+'##'+j+'##'+precio;
    }
    


    if (errores!='') {
        app.dialog.alert('Errores: '+errores);
    }
    else {
        //app.dialog.alert('logica: '+logica);
        app.popup.close(); 
        var server=servidor+'admin/includes/guardapromo.php';

        var desde=$('input[name=desde]').val();
        desde=desde.substr(6,4)+'-'+desde.substr(3,2)+'-'+desde.substr(0,2)+' '+desde.substr(11,5)+':00';;
        var hasta=$('input[name=hasta]').val();
        // 01/02/2022 00:00
        // 0113456789012345
        hasta=hasta.substr(6,4)+'-'+hasta.substr(3,2)+'-'+hasta.substr(0,2)+' '+hasta.substr(11,5)+':00';
        $.ajax({
            type: "POST",
            url: server,
            data: {id:id, nombre:$('input[name=nombre]').val(), codigo:$('input[name=codigo]').val(), usuario:usuario,grupo:grupo,dias:dias,desde:desde, hasta:hasta, tipo:tipo, maximo:$('input[name=maximo]').val(),logica:logica, envio_recoger:envio_recoger,tienda:tienda},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    muestraMensaje('Promoción guardada correctamente','Datos Guardados');
                    promociones();
                }
                else{
                    muestraMensaje('No se pudo guardar la Promoción','Error');                    
                }
            }
        });
                   
        
    }
    
}

function muestraprodbuscadopromo(e) {
    var elem=e;
    $('#promos-form input[name*="productopromo"]').val(elem.dataset.id+'-'+elem.dataset.nombre);
    $('#promos-form input[name*="idpromo"]').val(elem.dataset.id);
    app.popup.close();

    
}

function muestracatbuscadopromo(e) {
    var elem=e;
    $('#promos-form input[name*="categoriapromo"]').val(elem.dataset.id+'-'+elem.dataset.nombre);
    $('#promos-form input[name*="idpromo"]').val(elem.dataset.id);
    app.popup.close();

    
}
