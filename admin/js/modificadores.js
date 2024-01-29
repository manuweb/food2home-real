

function muestraCatmodificadores(){
    //alert('modificadores');
    $('#titulo-modificadores').html('Categoría de modificador');
    var server=servidor+'admin/includes/leecategoriamodificadores.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                var nombre=obj.nombre;
                var imagen=obj.imagen;
                var activo=obj.activo; 
                var opciones=obj.opciones; 
                var forzoso=obj.forzoso; 
                var maximo=obj.maximo; 
                var modificadores=obj.modificadores; 
                var lista=obj.lista;
                var txtcolor1="";
                var txtcolor0="text-color-gray";
                var txtcolor="";
                //var txt='<div class="list" id="lista-modificadores">'+
                    //'<ul>';
                 var txt='';
                
                if (obj.id !=null){
                for (x=0;x<id.length;x++){
                    if (activo[x]=='0'){
                        txtcolor=txtcolor0;
                    }
                    else {
                        txtcolor=txtcolor1;
                    }                   
                     
                    txt=txt+
                    '<div class="grid grid-cols-3 grid-gap">'+
                       // '<a href="#" class="item-content">'+

                    '<div class=" '+txtcolor+'" onclick="javascript:editacategoriamodificador(\''+id[x]+'\',\''+nombre[x]+'\',\''+id[x]+'\');">'+nombre[x]+'</div>';
                    if (txtcolor==""){
                        txt=txt+
                            '<div class="" onclick="javascript:muestramodificadores(\''+id[x]+'\',\''+nombre[x]+'\');"><span class="float-right"><a href="#" class="link">'+lista[x]+'</a></span></div>';
                    }
                    else {
                        txt=txt+
                            '<div class=" '+txtcolor+'"><span class="float-right">'+lista[x]+'</span></div>';
                    }

                    txt=txt+
                        '<div class=""><i class="icon f7-icons" onclick="editacategoriamodificador(\''+id[x]+'\',\''+nombre[x]+'\',\''+id[x]+'\');">pencil</i></div>';
                      //  '</a>'+
                        txt=txt+'</div>';
                    //'</li>';
                }
                }
                $('#modificadores-page').html(txt);
                if (obj.id !=null){
                    $('#titulo-modificadores').html('Categoría de modificador ('+id.length+')');
                }
                else {
                    $('#titulo-modificadores').html('Categoría de modificador (0)');
                }
                
            }
            else{   
                
                $('#modificadores-page').html(txt);
                $('#titulo-modificadores').html('Categoría de modificador');
            }
        }
    });
  

} 

function editacategoriamodificador(id=0, nombre='',nuevo='si') {
   var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title">Categoría Modificador</div>'+
            '<form  id="categoria-form" enctype="multipart/form-data">'+
                '<div class="simple-list">'+
                    '<ul>'+
                        '<li>'+
                          '<span>Activo</span>'+
                          '<label class="toggle toggle-init">'+
                            '<input id="chk-activo" type="checkbox" checked disabled/>'+
                            '<span class="toggle-icon"></span>'+
                          '</label>'+            
                        '</li>'+
                    '</ul>'+   
                '</div>'+
                '<div class="list">'+
                    '<ul>'+
                       '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Id</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="id" placeholder="Id" readonly value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
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
                            '<div class="item-title item-label">Opciones</div>'+
                            '<div class="item-input-wrap">'+
                                '<select name="opciones" id="opciones">'+
                                    '<option value="0" selected="selected">Seleccionar uno</option>'+
                                    '<option value="1" >Varias opciones</option>'+
                                '</select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Forzoso</div>'+
                            '<div class="item-input-wrap">'+
                                '<select name="forzoso" id="forzoso">'+
                                    '<option value="0" selected="selected">Forzoso</option>'+
                                    '<option value="1" >opcional</option>'+
                                '</select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                        '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Nº Máximo</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="maximo" placeholder="máximo" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
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
                if (id!='0'){
                    var server=servidor+'admin/includes/leecategoriamodificador.php';
                    $.ajax({
                        type: "POST",
                        url: server,
                        data:{id:id},
                        dataType:"json",
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){

                                var nombre=obj.nombre;
                                var activo=obj.activo;
                                var opciones=obj.opciones;
                                var forzoso=obj.forzoso;
                                var maximo=obj.maximo;
                                $('input[name=id]').val(id);
                                $('input[name=nombre]').val(nombre);
                                $(`#opciones option[value='${opciones}']`).prop('selected', true);
                                $(`#forzoso option[value='${forzoso}']`).prop('selected', true);
                                $('input[name=maximo]').val(maximo);
                                if (activo=="0"){
                                    $('#chk-activo').prop("checked", false );
                                }

                            }
                            else{
                                app.dialog.alert('No se pudo leer la categoria');
                            }
                        }
                        ,

                error: function (xhr, ajaxOptions, thrownError){
                    console.log(xhr.status);
                    console.log(thrownError);
                  }
                    });

                }
              
          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
    
    dynamicPopup.open();
    
    $('.save-data').on('click', function () {
        var activo=$('#chk-activo').prop("checked");
        var formData = app.form.convertToData('#categoria-form'); 
        var id=$('input[name=id]').val();
        var nombre=$('input[name=nombre]').val();
        var opciones=$('#opciones').val();
        var forzoso=$('#forzoso').val(); 
        var maximo=$('input[name=maximo]').val();

        var server=servidor+'admin/includes/guardacategoriamodificador.php';
        
        $.ajax({
            type: "POST",
            url: server,
            data:{id:id, nombre:nombre, activo:activo, opciones:opciones, forzoso:forzoso, maximo:maximo, nuevo:nuevo},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    //leeempresa();
                }
                else{
                    app.dialog.alert('No se pudo guardar la categoria');
                }
                muestraCatmodificadores();
            }
        });

        dynamicPopup.close(); 
        
    });
  
}

function muestramodificadores(id,nombre,maximo=0) {
    var dynamicPopup = app.popup.create({
        content: ''+ 
        '<div class="popup">'+
            '<div class="block page-content">'+
                '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
                '<div class="title">Categoría Modificador <b>'+nombre+'</b><br><br></div>'+ 
                '<div class="grid grid-cols-4 grid-gap">'+
                    '<div class=""><b>Act.</b></div>'+
                    '<div class=""><b>Nombre</b></div>'+
                    '<div class="text-align-right"><b>Precio</b></div>'+
                    '<div class=""><b>Selec.</b></div>'+
                '</div>'+
                '<div id="lista-modificadores"></div>'+
            '</div>'+
        '</div>'     
        
        ,
        // Events
        on: {
          open: function (popup) {
              
            //console.log('Popup open');
            
            var server=servidor+'admin/includes/leemodificadores.php';
             $.ajax({
                type: "POST",
                url: server,
                data:{id:id},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){
                        var id=obj.id;
                        var nombre=obj.nombre;
                        var activo=obj.activo;
                        var precio=obj.precio;
                        var autoseleccionado=obj.autoseleccionado;
                        var txt="";
                        var chk="";
                        var chk2="";
                        for (x=0;x<id.length;x++){
                            chk="";
                            chk2="";
                            if (activo[x]==1){
                                chk='checked';
                            }
                            if (autoseleccionado[x]==1){
                                chk2='checked';
                            }
                            txt+=''+
                            
                            '<div class="grid grid-cols-4 grid-gap">'+                               
                                '<div class=""><label class="checkbox"><input type="checkbox" name="activo_'+id[x]+'" data-id="'+id[x]+'" '+chk+' onclick="cambiaActivo(this);"/><i class="icon-checkbox"></i></label></div>'+
                                '<div class="" data-id="'+id[x]+'" data-nombre="'+nombre[x]+'" onclick="cambiaNombre(this);"><span id="nombre_'+id[x]+'">'+nombre[x]+'</span></div>'+
                                '<div class="text-align-right" data-id="'+id[x]+'" data-precio="'+precio[x]+'" onclick="cambiaPrecio(this);"><span id="precio_'+id[x]+'">'+precio[x]+'</span></div>'+
                                '<div class=""><label class="checkbox"><input type="checkbox" name="auto_'+id[x]+'" data-id="'+id[x]+'" '+chk2+' onclick="cambiaAuto(this);"/><i class="icon-checkbox"></i></label></div>'+
                            '</div>';
                            //console.log(nombre[x]);
                        }
                        
                        $('#lista-modificadores').html(txt);
    
                    }
                    else{
                        app.dialog.alert('Sin modificadores');
                    }
                }
             });
            

          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
            close:function (popup) {
                
            muestraCatmodificadores();
          },
        }
    });  
    
    dynamicPopup.open();   
    
}
function cambiaActivo(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiamodificador.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{cambia:'activo', id:id,valor:valor},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){       
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
   
}


function cambiaAuto(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiamodificador.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{cambia:'autoseleccionado', id:id,valor:valor},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){       
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
 
}

function cambiaNombre(e) {
    var id=e.getAttribute('data-id');
    var nombre=e.getAttribute('data-nombre');
    
    //app.dialog.prompt(text, title, callbackOk, callbackCancel, defaultValue)
    app.dialog.prompt('Nombre modificador:', 
        function (name) {
            var server=servidor+'admin/includes/cambiamodificador.php';
        
            $.ajax({
                type: "POST",
                url: server,
                data:{cambia:'nombre', id:id,valor:name},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){   
                        $('#nombre_'+id).html(name);
                    }
                    else{
                        app.dialog.alert('No se pudo cambiar');
                    }
                }
            });
                   
        
        
        },
        function () {},nombre              
                       
    );   
}
function cambiaPrecio(e) {
    var id=e.getAttribute('data-id');
    var precio=e.getAttribute('data-precio');
    
    //app.dialog.prompt(text, title, callbackOk, callbackCancel, defaultValue)
    app.dialog.prompt('Precio modificador:', 
        function (name) {
            var server=servidor+'admin/includes/cambiamodificador.php';
            $.ajax({
                type: "POST",
                url: server,
                data:{cambia:'precio', id:id,valor:name},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){   
                        $('#precio_'+id).html(name);
                    }
                    else{
                        app.dialog.alert('No se pudo cambiar');
                    }     
                }
            });         
        
        
        },
        function () {},precio              
                       
    );   
}

////// ==== Grupo Modificadores =======

function muestraGrumodificadores(){
    $('#titulo-modificadores').html('Grupo de modificadores');
    var server=servidor+'admin/includes/leegrupomodificadores.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{username:'username'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                var nombre=obj.nombre;
                var imagen=obj.imagen;
                var modificadores=obj.modificadores; 
                var lista=obj.lista;
                
                //var txt='<div class="list" id="lista-modificadores">'+
                    //'<ul>';
                 var txt='';
                 if (obj.id !=null){
                for (x=0;x<id.length;x++){
               
                     
                    txt=txt+
                    '<div class="grid grid-cols-3 grid-gap">'+
                       // '<a href="#" class="item-content">'+

                    '<div class="" onclick="javascript:editagrupomodificadores(\''+id[x]+'\',\''+nombre[x]+'\',\''+id[x]+'\');">'+nombre[x]+'</div>';
                    
                    txt=txt+
                            '<div class="" onclick="javascript:muestrgrupoamodificadores(\''+id[x]+'\',\''+nombre[x]+'\');"><span class="float-right"><a href="#" class="link">'+lista[x]+'</a></span></div>';
                    

                    txt=txt+
                        '<div class=""><i class="icon f7-icons" onclick="editagrupomodificadores(\''+id[x]+'\',\''+nombre[x]+'\',\''+id[x]+'\');">pencil</i></div>';
                      //  '</a>'+
                        txt=txt+'</div>';
                    //'</li>';
                }
                 }
                $('#modificadores-page').html(txt);
                 if (obj.id !=null){
                     $('#titulo-modificadores').html('Grupo de modificadores ('+id.length+')');
                 }
                else{
                    $('#titulo-modificadores').html('Grupo de modificadores (0)');
                }
                
            }
            else{   
                 
                $('#modificadores-page').html(txt);
                $('#titulo-modificadores').html('Grupo de modificadores');
            }
        }
    });

}


///// OJO no existe guardagrupomodificador.php
/// ?????
///
////

function editagrupomodificadores(id=0, nombre='',nuevo='si') {
   var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Grupo Modificador</div>'+
                '<form>'+
                '<div class="list">'+
                    '<ul>'+
                       '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Id</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="id" placeholder="Id" readonly value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
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
                if (id!='0'){
                    var server=servidor+'admin/includes/leegrupomodificador.php';

                    $.ajax({
                        type: "POST",
                        url: server,
                        data:{id:id},
                        dataType:"json",
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                
                                var nombre=obj.nombre;

                                $('input[name=id]').val(id);
                                $('input[name=nombre]').val(nombre);



                            }
                            else{
                                app.dialog.alert('No se pudo leer el grupo');
                            }
                        },
                        error:function(e){
                            app.dialog.alert('ERROR');
                        }
                    });

                }

          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
    
    dynamicPopup.open();
    
    $('.save-data').on('click', function () {
        
        var formData = app.form.convertToData('#categoria-form'); 
        var id=$('input[name=id]').val();
        var nombre=$('input[name=nombre]').val();

        var server=servidor+'admin/includes/guardagrupomodificador.php';
        app.preloader.show();
        $.ajax({
            type: "POST",
            url: server,
            data:{id:id, nombre:nombre, nuevo:nuevo},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    //leeempresa();
                }
                else{
                    app.dialog.alert('No se pudo guardar el grupo');
                }
                muestraGrumodificadores();
            }
        });

        dynamicPopup.close(); 
        
    });
  
}

function muestrgrupoamodificadores(id,nombre) {
   var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="page popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                
    
                '<div class="title">Grupo <b>'+nombre+'<b></div>'+    
                '<div class="block-title">Categorías:</div>'+
                '<div id="cat-page" class="block block-strong">'+
                    '<div class="list sortable sortable-opposite" id="lista-cate">'+
                    '</div>'+
                '</div>'+
       
                '<div class="row">'+
                    '<div class="col-100 medium-50">'+
                        '<div class="list" style="margin:0;">'+
                            '<ul>'+
                                '<li>'+
                                    '<a class="item-link smart-select smart-select-init" data-open-in="popup" data-searchbar="true"  close-on-select="true"  data-searchbar-placeholder="Buscar  categoríar">'+
                                        '<select name="categorias" id="select-categoria">'+
                                            '<option value="0"></option>'+
                                        '</select>'+
                                        '<div class="item-content">'+
                                          '<div class="item-inner">'+
                                            '<div class="item-title">Categoría</div>'+
                                            '<div class="item-after" id="cat-selected"></div>'+
                                          '</div>'+
                                        '</div>'+
                                    '</a>'+
                                '</li>'+
                            '</ul>'+
                        '</div>'+

                    '</div>'+ 
                    '<div class="col-100 medium-50" style="align-items:center;justify-content:center;height:44px;display:flex;">'+
                           '<button onclick="app.popup.close();addcategoria(\''+id+'\',\''+nombre+'\');" class="col-60 button button-fill" style="margin:auto;">+ Añadir categoría</button></div>'+  
                    '</div>'+ 

                '</div>'+ 
            '</div>'+
          '</div>'
         ,
        // Events
   
          
        on: {
            close: function (popup) {
              muestraGrumodificadores();
          },
          open: function (popup) {
              
            //console.log('Popup open');
                var server=servidor+'admin/includes/leegrupomodificador.php';
                $.ajax({
                    type: "POST",
                    url: server,
                    data:{id:id},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            var idg=obj.id;
                            var nombreg=obj.nombre; 
                            var cate=obj.modificadores[0].split(",");
                            var txt2='<ul>';

                            var server=servidor+'admin/includes/leecategoriamodificadores.php';
                            $.ajax({
                                type: "POST",
                                url: server,
                                data:{id:id, order:obj.modificadores[0]},
                                dataType:"json",
                                success: function(data){
                                    var obj=Object(data);
                                    if (obj.valid==true){
                                        var idg=obj.id;
                                        var nombreg=obj.nombre; 

                                        var num='';
                                        var txt='<option value="0" disabled ></option>';
                                        for(x=0;x<idg.length;x++){
                                            num=idg[x].toString();
                                            if (cate.indexOf( num )>=0 ){

                                                txt2+=
                                                  '<li>'+
                                                    '<div class="item-content >'+
                                                        '<div class="item-inner">'+
                                                            '<div class="item-title">'+nombreg[x]+'</div>'+
                                                            '<div class="item-after" onclick="borraCategoria(\''+id+'\',\''+nombre+'\',\''+idg[x]+'\',\''+cate+'\');"><i class="f7-icons" >trash</i></div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="sortable-handler" ></div>'+
                                                '</li>';
                                            }
                                            else {
                                                txt+='<option value="'+idg[x]+'">'+nombreg[x]+'</option>';
                                            }
                                        }
                                        txt2=txt2+'</ul>';
                                        $("#lista-cate").html(txt2);

                                        app.sortable.enable($('#lista-cate'));
                                        app.on('sortableSort', function (listEl, indexes) {
                                            cate=ordenaArray(cate,indexes['from'],indexes['to']);
                                            //alert(id+":"+cate);

                                            var server=servidor+'admin/includes/ordengrupomodificador.php';
                                            
                                            $.ajax({
                                                type: "POST",
                                                url: server,
                                                data:{iid:id, cate:cate},
                                                dataType:"json",
                                                success: function(data){
                                                    var obj=Object(data);
                                                    if (obj.valid==true){
                                                    }
                                                    else{
                                                        console.log('ERROR');
                                                    } 
                                                }
                                            });

                                        });

                                        $("#select-categoria").html(txt);
                                    }
                                    else{
                                        app.dialog.alert('No se pudo leer las categorias');
                                    }
                                }
                                
                            });

                        }
                        else{
                            app.dialog.alert('No se pudo leer el grupo');
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

function addcategoria(id, nombre) {
    app.popup.close();
    var valor=$("select[id=select-categoria]" ).val();
    if ($('#cat-selected').html()!=""){
        var server=servidor+'admin/includes/addcategoriagrupo.php';
        $.ajax({
            type: "POST",
            url: server,
            data:{id:id,idcat:valor},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    //app.popup.close();
                    //muestraGrumodificadores();
                    muestraGrumodificadores();
                    
                    //muestrgrupoamodificadores(id,nombre);
                }
                else{
                    app.dialog.alert('No se añadir la categorias');
                }
            }
        });
        
    }
}                       
                        
function borraCategoria(id, nombre, idg, cate){
    app.popup.close();
    var server=servidor+'admin/includes/borracategoriadegrupo.php';
    $.ajax({
        type: "POST",
        url: server,
        data:{id:id, idg:idg,cate:cate},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                muestraGrumodificadores();
                //muestrgrupoamodificadores(id,nombre);
            }
            else{
                console.log('ERROR');
            }
        }
    });
    
 
    //console.log('id:'+id+' nombre:'+nombre);

}


function ordenaArray(array,de,a){
    var nuevo=[];
    var j=0;
    for (var x=0;x<array.length;x++){
        if (x!=de) {
            nuevo[j]=array[x];
            j++;
        }   
    }
    nuevo.splice(a, 0, array[de]);
    
    return nuevo;
    
}

