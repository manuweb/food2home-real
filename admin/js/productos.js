//muestragrupos();  


function muestragrupos(){
     $('#titulo-productos').html('<span>Grupos</span><span id="button-guardar"class="button button-fill float-right" style="display:none;">Guardar</span><span id="boton-add-grupo" class="button button-fill float-right">Nuevo</span>');
    $('#volver-productos').attr("href", "javascript:navegar('#view-home');");
    $('#boton-add-grupo').attr('onclick','editaGrupo(); ');
    var server=servidor+'admin/includes/leegrupos.php';
    
    $.ajax({
        type: "POST",
        url: server,
        data: {tienda:tienda},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                var nombre=obj.nombre;
                var imagen=obj.imagen;
                var total=obj.total;
                var orden=obj.orden;
                var activo=obj.activo; 
                var activo_web=obj.activo_web; 
                var activo_app=obj.activo_app; 
                var img_app=obj.imagen_app; 
                var img=servidor+'admin/img/no-imagen.png';
                var img2='';
                var txtcolor1="";
                var txtcolor0="text-color-gray";
                var txtcolor="";
                //var txt='<div class="row"><div class="col-60"></div><div class="col-20">WEB</div><div class="col-20">APP</div></div>';
                var txt='<div class="grid grid-cols-2"><div class=""></div><div class=""></div></div>';
                 txt+='<div class="list sortable sortable-opposite list-outline-ios list-dividers-ios sortable" id="lista-grupos">'+
                    '<ul id="prod">';
                for (x=0;x<id.length;x++){
                    var chk_web='';
                    var chk_app='';
                    
                    if (activo_web[x]=='1'){chk_web='checked';}
                    if (activo_app[x]=='1'){chk_app='checked';}
                    
                    if(imagen[x]==''){
                        img2='../webapp/img/productos/no-imagen.jpg';
                    }
                    else {
                        // imagen revo img2='img/productos/'+imagen[x];
                        img2='../webapp/img/revo/'+imagen[x];
                        
                    }
                    if(img_app[x]!=''){
                        img2='../webapp/img/productos/'+img_app[x];
                    }
                    if (activo[x]=='0'){
                        txtcolor=txtcolor0;
                    }
                    else {
                        txtcolor=txtcolor1;
                    }                   
                     
                    txt=txt+
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+
                            '<div class="item-media"><img src="'+img2+'" width="28px" height="auto"></div>'+
                            '<div class="item-inner">'+
                        
                                '<div class="item-title '+txtcolor+'" style="width: 100%;">'+
                                    '<div class="grid grid-cols-2">'+
                                        '<div class="" ><label class="checkbox"><input type="checkbox" name="activo_web-'+id[x]+'" '+chk_web+' data-id="'+id[x]+'" data-tipo="web" data-elemento="grupos" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label> <span onclick="javascript:muestracategorias(\''+id[x]+'\',\''+nombre[x]+'\');">'+nombre[x]+' ('+total[x]+')'+

                                        '</span></div>'+
                                        '<div class="">'+

                                        '</div>'+
                                        //'<div class=""><label class="checkbox"><input type="checkbox" name="activo_app-'+id[x]+'" '+chk_app+' data-id="'+id[x]+'" data-tipo="app" data-elemento="grupos" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label></div>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="item-after"><i class="f7-icons" onclick="editaGrupo(\''+id[x]+'\');">pencil</i></div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';
                }
                txt=txt+
                    '</ul>'+
                '</div>';
                var txt_add_grupo='';
                if(integracion=='1'){
                    
                   $('#boton-add-grupo').hide();
                    if (id.length==0){
                        $("#titulo-productos").append('<div id="CopiarMaster" style="width:50%;text-align:center;margin: auto;"><a class="button button-fill" href="#" onclick="CopiarMaster('+tienda+');">Copiar de master</a></div>');
                    }
                }

                if (id.length==0 && integracion=='2'){
                   
                    txt_add_grupo=''+
                        '<br><div class="grid grid-cols-2 grid-gap">'+
                            '<div><a class="button button-fill hace-importacion" href="#" onclick="importaNuevos();">Importar</a></div>'+
                            '<div><a class="button button-fill" href="'+servidor+'admin/descargas/file.php?file=Items_import.csv">Descargar csv</a></div>'+
                        '</div><br><div style="margin: auto;width: 50%; display: none;" id="boton-subir-importacion"><input type="file" id="fileimport" name="fileimport" style="display: none;"><label for="fileimport"><buttom class="button button-fill">Seleccionar archivo</buttom></label><div>';
                }
                
                $('#product-page').html(txt+txt_add_grupo);
                $('.button-add-grupo').on('click', function () {
                    //editaGrupo(0);
                });
                app.sortable.enable("#lista-grupos");
                var aGrupos=new Array();
                $('#button-guardar').on('click', function () {
                    $('#button-guardar').hide();
                    
                    var server=servidor+'admin/includes/ordengrupos.php';              //categorias:aCategorias}
                    
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{grupos:aGrupos, tienda:tienda},
                        success: function(data){
                            var obj=Object(data);

                            if (obj.valid==true){
                                muestragrupos();
                            }
                            else{
                                console.log('ERROR');
                            }
                        }
                    });
                }); 
                
                app.sortable.enable($('#lista-grupos'));

                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar').show();
                    $('#boton-add-grupo').hide();
                   
                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;
                    
                    $("#prod li").each(function(){
                        aGrupos[n]=$(this).attr('data');
                        
                        n++;
                    });
                    
                    
                });

                
            }
            else{
                //console.log('ERROR');}
            }
        },

                error: function (xhr, ajaxOptions, thrownError){
                    console.log(xhr.status);
                    console.log(thrownError);
                  }
    });
    
    
} 

function CopiarMaster(id){
    var server=servidor+'admin/includes/copiaproductos.php';
    
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data: { id:id} ,
        success: function(data){
            var obj=Object(data);   
            if (obj.valid==true){
                //CopiarMaster
                $("#CopiarMaster").remove();
                muestragrupos();
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function importaNuevos(){
    $('#boton-subir-importacion').show();
    $('#fileimport').on('change', function(event) {
        var f=document.getElementById('fileimport').files[0];
        var FData = new FormData();
        FData.append('archivo',f);

        var server=servidor+'admin/includes/importacion.php'; 
        $.ajax({
            url: server,
            type : 'POST',
            data : FData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    app.dialog.alert('Grupos: '+obj.contadorG+'<br>Categorías: '+obj.contadorC+'<br>Productos: '+obj.contadorP,'Se han creado:');
                    muestragrupos();
                }
                else{
                    app.dialog.alert('No se pudo hacer importación');
                }  
                
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        
      
    });
}

function cambiaActivoGrupo(el){
 
    var id = $(el).attr('data-id');
    var tipo = $(el).attr('data-tipo');
    var elemento = $(el).attr('data-elemento');
    var estado= $(el).prop('checked') ;
    var activo=0;
    if (estado) {activo=1;}
    //console.log('id:'+id+' -'+tipo+' -'+activo);
    var server=servidor+'admin/includes/cambiaactivoelemento.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data: { id:id, tipo:'activo_'+tipo,activo:activo, tabla:elemento,tienda:tienda} ,
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                
            }
            else{
                console.log('ERROR');
            }
            
        }
    });
                
    
    
    
    //var activo_web=$('#chk-activo-web').prop("checked");
    //var activo_app=$('#chk-activo-app').prop("checked");
}

function editaGrupo(id=0) {
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title" id="titulo-edita-grupo">Modificar Grupo</div>'+
            '<form  id="grupo-form" enctype="multipart/form-data">'+
                '<input type="hidden" name="id">'+
                '<div class="list">'+
                '<ul>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Nombre" value="" />'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+      
                   '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Impuesto %</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="impuesto" placeholder="Impuesto" value="" />'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                '</ul>'+   
                '</div>'+
                '<div style="text-align:center;" id="revo-img"><span style="font-size:12px;">Imagen Revo: </span></div>'+
                '<div class="text-align-center" id="imagen-revo">'+
                    '<img src="" >'+
                '</div>'+
                '<div class="simple-list">'+
                '<ul>'+
                  '<li id="revo-act">'+
                      '<span>Activo Revo</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo" type="checkbox" checked disabled/>'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+            
                    '</li>'+
                    '<li>'+
                      '<span>Activo WEB</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo-web" name="chk-activo-web" type="checkbox" checked />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+               
                    '</li>'+
 //                   '<li>'+
//                      '<span>Activo APP</span>'+
//                    '<label class="toggle toggle-init">'+
//                        '<input id="chk-activo-app" name="chk-activo-app" type="checkbox" checked />'+
  //                      '<span class="toggle-icon"></span>'+
    //                  '</label>'+         
      //              '</li>'+                      
                  '</ul>'+ 
                '</div>'+
        
                '<div style="text-align:center;" id="div-img-web">'+
                  '<p><span style="font-size:12px;">Imagen Web: </span></p>'+
                  '<img name="imagen" id="imagen-app" src="" width="60%" height="auto"/>  '+  
                  '<input id="input-imagen" type="file" accept="image/*" onchange="loadFileImg(event,\'#imagen-app\');$(\'#guarda-imagen\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-imagen" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-imagen" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+            
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" href="#" id="guardagrupo-boton" onclick="guardagrupo(0);">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'
         ,
        // Events
        on: {
            open: function (popup) {
            //console.log('Popup open');
                if (id!=0){
                    $('#guardagrupo-boton').attr("onclick","guardagrupo("+id+")");
                    var server=servidor+'admin/includes/leegrupo.php';
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data: { id:id, tienda:tienda} ,
                        success: function(data){
                            var obj=Object(data);   
                            if (obj.valid==true){


                                var nombre=obj.nombre;
                                var imagen=obj.imagen;
                                var imagen_app=obj.imagen_app;

                                var impuesto=obj.impuesto;
                                var activo=obj.activo;
                                var activo_web=obj.activo_web;
                                var activo_app=obj.activo_app;
                                if (imagen==""){
                                    imagen=servidor+'admin/img/no-imagen.png';
                                }

                                $('input[name=nombre]').val(nombre);
                                $('input[name=id]').val(id);
                                $('input[name=impuesto]').val(impuesto);
                                document.getElementById("imagen-revo").src =imagen;
                                if (activo=="0"){
                                    $('#chk-activo').prop("checked", false );
                                }
                                if (activo_web=="0"){
                                    $('#chk-activo-web').prop("checked", false );
                                }
                                if (activo_app=="0"){
                                    $('#chk-activo-app').prop("checked", false );
                                }
                                if (imagen_app==""){
                                    document.getElementById('imagen-app').src=servidor+'admin/img/no-imagen.png'
                                }
                                else {
                                    document.getElementById('imagen-app').src=servidor+'webapp/img/productos/'+imagen_app;
                                }

                            }
                            else{
                                app.dialog.alert('No se pudo leer el grupo');
                            }
                        }
                    });
                }
                else {
                   $('#titulo-edita-grupo').html('NUEVO GRUPO'); document.getElementById('imagen-app').src=servidor+'admin/img/no-imagen.png';
                    if(integracion==2){
                        $('#titulo-edita-grupo').html('NUEVO GRUPO'); 
                        $('#revo-img').hide();
                       $('#cambia-imagen').hide(); 
                       $('#revo-act').hide();
                        $('#div-img-web').hide();
                        //document.getElementById('imagen-app').src=servidor+'admin/img/no-imagen.png';
                    }
                }
                if(integracion==1){
                    //nombre
                    //impuesto
                    //revo-img
                    //revo-act
                    $('input[name=nombre]').attr('disabled',true);
                    $('input[name=impuesto]').attr('disabled',true);
                }
                else {
                    $('#revo-img').hide();
                    $('#revo-act').hide();

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
    $("#cambia-imagen").on('click', function () {
        $('#input-imagen').show(); 
        $("#cambia-imagen").hide();
     
    });
   $("#guarda-imagen").on('click', function () {
        
        var f=document.getElementById('input-imagen').files[0];
       
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        FData.append('id',id); 
        FData.append('tienda',tienda); 
       
        var server=servidor+'admin/includes/guardaimggrupo.php'; 
        $.ajax({
            url: server,
            type : 'POST',
            data : FData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leealergenos();
                }
                else{
                    app.dialog.alert('No se pudo guardar imagen');
                }  
                
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
           
        });

    });    
    

}

function guardagrupo(id){
        
    var activo_web=$('#chk-activo-web').prop("checked");
    var activo_app=$('#chk-activo-app').prop("checked");
    var formData = app.form.convertToData('#grupo-form');   var nombre=$('input[name=nombre]').val(); 
    var impuesto=$('input[name=impuesto]').val();  
    console.log('nombre:'+nombre+' impuesto:'+impuesto);
    
    var server=servidor+'admin/includes/guardagrupo.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data: {id:id,nombre:nombre, impuesto:impuesto, activo_web:activo_web, activo_app:activo_app, tienda:tienda,nombre:nombre,impuesto:impuesto} ,
        success: function(data){
            var obj=Object(data);   
            if (obj.valid==true){
                muestragrupos();
            }
            else{
                app.dialog.alert('No se pudo guardar el grupo');
            }   
        }
    });
        
        
                  
       app.popup.close();   

}

function muestracategorias(grupo,nombregrupo){
    //id="titulo-productos
    
    $('#volver-productos').attr("href", "javascript:muestragrupos();");
    $('#titulo-productos').html('<span style="font-size:16px;"><a href="javascript:muestragrupos();" class="item-link">Grupos</a> -> '+nombregrupo+'</span><span id="button-guardar"class="button button-fill float-right" style="display:none;">Guardar</span><span id="boton-add-grupo" class="button button-fill float-right">Nuevo</span>');
    $('#boton-add-grupo').attr('onclick','editaCategoria('+grupo+',\''+nombregrupo+'\',0,\'\'); ');
    var server=servidor+'admin/includes/leecategorias.php';
    
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data: { grupo:grupo,tienda:tienda} ,
        success: function(data){
            var obj=Object(data);   
            if (obj.valid==true){
                var id=obj.id;
                var nombre=obj.nombre;
                var imagen=obj.imagen;
                var img_app=obj.imagen_app;
                var total=obj.total;
                var orden=obj.orden;
                var activo=obj.activo; 
                var activo_web=obj.activo_web; 
                var activo_app=obj.activo_app; 
                var img=servidor+'admin/img/no-imagen.png';
                var img2='';
                var txtcolor1="";
                var txtcolor0="text-color-gray";
                var txtcolor="";
                var txt='<div class="grid grid-cols-2"><div class=""></div><div class=""></div></div>';
                 txt+='<div class="list sortable sortable-opposite list-outline-ios list-dividers-ios sortable" id="lista-categorias">'+
                    '<ul id="prod">';
                if(id!=null){
                for (x=0;x<id.length;x++){
                    var chk_web='';
                    var chk_app='';
                    if (activo_web[x]=='1'){chk_web='checked';}
                    if (activo_app[x]=='1'){chk_app='checked';}
                    if(imagen[x]==''){
                        img2='../webapp/img/productos/no-imagen.jpg';
                    }
                    else {
                        // imagen revo img2='img/productos/'+imagen[x];
                        img2='../webapp/img/revo/'+imagen[x];
                        
                    }
                    if(img_app[x]!=null){
                        img2='../webapp/img/productos/'+img_app[x];
                    }
                    
                    
                    if (activo[x]=='0'){
                        txtcolor=txtcolor0;
                    }
                    else {
                        txtcolor=txtcolor1;
                    }                   

                    txt=txt+
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+

                            '<div class="item-media"><img src="'+img2+'" width="28px" height="auto"></div>'+
                            '<div class="item-inner">'+

                        
                        '<div class="item-title '+txtcolor+'" style="width: 100%;"><div class="grid grid-cols-2">'+
                            '<div class=""><label class="checkbox"><input type="checkbox" name="activo_web" '+chk_web+' data-id="'+id[x]+'" data-tipo="web" data-elemento="categorias" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label> <span onclick="javascript:muestraproductos(\''+grupo+'\',\''+nombregrupo+'\',\''+id[x]+'\',\''+nombre[x]+'\');">'+nombre[x]+' ('+total[x]+')'+
                            '</span></div>'+
                        
                        
                        
                            '<div class="">'+

                            '</div>'+
                               // '<div class=""><label class="checkbox"><input type="checkbox" name="activo_app" '+chk_app+' data-id="'+id[x]+'" data-tipo="app" data-elemento="categorias" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label></div>'+

                        '</div>'+                        
                        '</div>'+  


                                '<div class="item-after"><i class="f7-icons" onclick="editaCategoria(\''+grupo+'\',\''+nombregrupo+'\',\''+id[x]+'\',\''+nombre[x]+'\');">pencil</i></div>'+
                            '</div>'+

                        '</div>'+
                        '<div class="sortable-handler" ></div>'+

                    '</li>';
                }
                }
                txt=txt+
                    '</ul>'+
                '</div>';
                $('#product-page').html(txt);
                app.sortable.enable("#lista-categorias");
                $('#button-guardar').on('click', function () {
                    $('#button-guardar').hide();
                   
                    var server=servidor+'admin/includes/ordencategorias.php';             
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data: { categorias:aCategorias, tienda:tienda},
                        success: function(data){
                            var obj=Object(data);   
                            if (obj.valid==true){
                                muestracategorias(grupo,nombregrupo);
                            }
                            else{
                                console.log('ERROR');
                            }
                        }
                    });


                });
                var aCategorias=new Array();
                app.sortable.enable($('#lista-categorias'));

                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar').show();

                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;

                    $("#prod li").each(function(){
                        aCategorias[n]=$(this).attr('data');

                        n++;
                    });


                });

                    
            }
            else{
                //console.log('ERROR');}
            }  
        }
    });
   

}

function editaCategoria(idGrupo,nombregrupo,id,nombre) {
   var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title titulo-categoria">Modificar Categoría</div>'+
            '<form  id="grupo-form" enctype="multipart/form-data">'+
                '<div class="list">'+
                '<ul>'+
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Nombre" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+      
                   '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Impuesto %</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="impuesto" placeholder="Impuesto" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                '</ul>'+   
                '</div>'+
                '<div style="text-align:center;" id="revo-img"> <span style="font-size:12px;">Imagen Revo: </span><br>'+
                '<div class="text-align-center">'+
                    '<img src="" id="imagen-revo">'+
                '</div></div>'+
                '<div class="simple-list">'+
                '<ul>'+
                  '<li id="revo-act">'+
                      '<span>Activo Revo</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo" type="checkbox" checked disabled/>'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+            
                    '</li>'+
                    '<li>'+
                      '<span>Activo WEB</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo-web" name="chk-activo-web" type="checkbox" checked />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+               
                    '</li>'+
                                         
                  '</ul>'+ 
                '</div>'+
        
                '<div style="text-align:center;" class="producto-nuevo">'+
                  '<p><span style="font-size:12px;">Imagen Web/App: </span></p>'+
                  '<img name="imagen" id="imagen-app" src="" width="80%" height="auto"/>  '+  
                  '<input id="input-imagen" type="file" accept="image/*" onchange="loadFileImg(event,\'#imagen-app\');$(\'#guarda-imagen\').show();" style="display:none;">'+
                  '<a class="button button-fill" href="#" id="cambia-imagen" style="width:50%;margin:auto;">Cambiar</a>'+
                    '<br><br><a class="button button-fill" href="#" id="guarda-imagen" style="width:50%;margin:auto;display:none;">Guardar</a>'+
                  '</div>'+            
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#">Guardar</a></div>'+
                '</div>'+
         
            '</div>'+
          '</div>'
         ,
        // Events
        on: {
          open: function (popup) {
            //console.log('Popup open');
                if (id!=0){
            
                var server=servidor+'admin/includes/leecategoria.php';
                $.ajax({
                    type: "POST",
                    url: server,
                    dataType:"json",
                    data:{id:id, tienda:tienda},
                    success: function(data){
                        var obj=Object(data);
                        
                        if (obj.valid==true){

                            var nombre=obj.nombre;
                            var imagen=obj.imagen;
                            var imagen_app=obj.imagen_app;
                            
                            var impuesto=obj.impuesto;
                            var activo=obj.activo;
                            var activo_web=obj.activo_web;
                            var activo_app=obj.activo_app;
                            if (imagen==""){
                                imagen=servidor+'admin/img/no-imagen.png';
                            }
                            else {
                                imagen=servidor+'webapp/img/revo/'+imagen;
                            }
                            
                            $('input[name=nombre]').val(nombre);
                            $('input[name=impuesto]').val(impuesto);
                            document.getElementById("imagen-revo").src =imagen;
                            if (activo=="0"){
                                $('#chk-activo').prop("checked", false );
                            }
                            if (activo_web=="0"){
                                $('#chk-activo-web').prop("checked", false );
                            }
                            if (activo_app=="0"){
                                $('#chk-activo-app').prop("checked", false );
                            }
                            if (imagen_app=="" || imagen_app==null ){
                                document.getElementById('imagen-app').src=servidor+'admin/img/no-imagen.png'
                            }
                            else {
                                document.getElementById('imagen-app').src=servidor+'webapp/img/productos/'+imagen_app;
                            }
                        }
                        else{
                            app.dialog.alert('No se pudo leer la categoria');
                        }
                    }
                });
                }
              else {
                  $('.producto-nuevo').hide();
                  $('.titulo-categoria').html('Nueva categoría');
                  $('input[name=nombre]').attr('disabled',false);
                    $('input[name=impuesto]').attr('disabled',false);
              }
              if(integracion==2){
                  $('input[name=nombre]').attr('disabled',false);
                    $('input[name=impuesto]').attr('disabled',false);
              }
              if(integracion==1){
                    //nombre
                    //impuesto
                    //revo-img
                    //revo-act
                    $('input[name=nombre]').attr('disabled',true);
                    $('input[name=impuesto]').attr('disabled',true);
                }
                else {
                    $('#revo-img').hide();
                    $('#revo-act').hide();
                    //$('#titulo-edita-grupo').html('NUEVO GRUPO');
                }
                    
          },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
    dynamicPopup.on('close', function (popup) {
        
      });

    dynamicPopup.open();
    $("#cambia-imagen").on('click', function () {
        $('#input-imagen').show(); 
        $("#cambia-imagen").hide();
     
    });
   $("#guarda-imagen").on('click', function () {
        
        var f=document.getElementById('input-imagen').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        FData.append('id',id); 
       FData.append('tienda',tienda); 
        var server=servidor+'admin/includes/guardaimgcategoria.php';
        $.ajax({
            url: server,
            type : 'POST',
            data : FData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leecategorias();
                }
                else{
                    app.dialog.alert('No se pudo guardar imagen');
                }  
                
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        
    });    
    
    $('.save-data').on('click', function () {
        var activo_web=$('#chk-activo-web').prop("checked");
        var activo_app=$('#chk-activo-app').prop("checked");
        var formData = app.form.convertToData('#grupo-form');   var impuesto=$('input[name=impuesto]').val(); 
        var nombre=$('input[name=nombre]').val();    
        var server=servidor+'admin/includes/guardacategoria.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data: {id:id,grupo: idGrupo,nombre:nombre,impuesto:impuesto,activo_web:activo_web, activo_app:activo_app, tienda:tienda} ,
            success: function(data){
                var obj=Object(data);   
                if (obj.valid==true){
                    muestracategorias(idGrupo,nombregrupo);
                }
                else{
                    app.dialog.alert('No se pudo guardar la categoria');
                }   
            }
        });
                       
        dynamicPopup.close();   
    });

    
    
}

function muestraproductos(grupo,nombregrupo,categoria,nombrecategoria){
    //id="titulo-productos"
    
    $('#titulo-productos').html('<span style="font-size:16px;"><a href="javascript:muestragrupos();" class="item-link">Grupos</a> -><a href="javascript:muestracategorias(\''+grupo+'\',\''+nombregrupo+'\');" class="item-link"> '+nombregrupo+'</a>->'+nombrecategoria+'</span><span id="button-guardar"class="button button-fill float-right" style="display:none;">Guardar</span><span id="boton-add-grupo" class="button button-fill float-right">Nuevo</span>');
    $('#volver-productos').attr("href", "javascript:muestracategorias('"+grupo+"','"+nombregrupo+"');");
$('#boton-add-grupo').attr('onclick','editaProducto(0,'+grupo+',\''+nombregrupo+'\','+categoria+',\''+nombrecategoria+'\');');
    var server=servidor+'admin/includes/leeproductos.php';
    
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{categoria:categoria,tienda:tienda},
        success: function(data){
            var obj=Object(data);

            if (obj.valid==true){

                var id=obj.id;
                var nombre=obj.nombre;
                var imagen=obj.imagen;
                var img_app=obj.imagen_app;
                var orden=obj.orden;
                var activo=obj.activo; 
                var activo_web=obj.activo_web; 
                var activo_app=obj.activo_app; 
                var esMenu=obj.esMenu; 
                var img=servidor+'admin/img/no-imagen.png';
                var img2='';
                var txtcolor1="";
                var txtcolor0="text-color-gray";
                var txtcolor="";
                var txt='<div class="grid grid-cols-2"><div class=""></div><div class=""></div></div>';
                 txt+='<div class="list sortable sortable-opposite list-outline-ios list-dividers-ios" id="lista-productos">'+
                    '<ul id="prod">';
                if(id!=null){
                for (x=0;x<id.length;x++){
                    var chk_web='';
                    var chk_app='';
                    if (activo_web[x]=='1'){chk_web='checked';}
                    if (activo_app[x]=='1'){chk_app='checked';}
                    //
                    var textoesMenu=nombre[x];

                    //console.log(nombre[x]+' Tipo'+esMenu[x]);
                    if (esMenu[x]=='1'){
                        textoesMenu='<i class="icon material-icons size-14">restaurant</i> '+nombre[x];
                    }
                    if (esMenu[x]=='2'){
                        textoesMenu='<i class="icon f7-icons size-14">layers_alt_fil</i> '+nombre[x];
                    }

                    if(imagen[x]==''){
                        img2='../webapp/img/productos/no-imagen.jpg';
                    }
                    else {
                        // imagen revo img2='img/productos/'+imagen[x];
                        img2='../webapp/img/revo/'+imagen[x];

                    }
                    if(img_app[x]!=''){
                        img2='../webapp/img/productos/'+img_app[x];
                    }

                    if (activo[x]=='0'){
                        txtcolor=txtcolor0;
                    }
                    else {
                        txtcolor=txtcolor1;
                    }                   
                    txt=txt+
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+
                            '<div class="item-media"><img src="'+img2+'" width="28px" height="auto"></div>'+
                            '<div class="item-inner">'+
                        
                                '<div class="item-title '+txtcolor+'" style="width: 100%;">'+
                                    '<div class="grid grid-cols-2">'+
                                        '<div class=""><label class="checkbox"><input type="checkbox" name="activo_app" '+chk_web+' data-id="'+id[x]+'" data-tipo="web" data-elemento="productos" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label> <span>'+textoesMenu+
                                        '</span></div>'+
                                        '<div class="">'+

                                        '</div>'+
                                        //'<div class=""><label class="checkbox"><input type="checkbox" name="activo_app" '+chk_app+' data-id="'+id[x]+'" data-tipo="app" data-elemento="productos" onclick="cambiaActivoGrupo(this);"><i class="icon-checkbox"></i></label>'+
                                        //'</div>'+
                                    '</div>'+
                            '</div>'+
                            '<div class="item-after"><i class="f7-icons" onclick="editaProducto(\''+id[x]+'\',\''+grupo+'\',\''+nombregrupo+'\',\''+categoria+'\',\''+nombrecategoria+'\');">pencil</i></div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';        
                    
                    
                }
                }
                txt=txt+
                    '</ul>'+
                '</div>';               
                
                $('#product-page').html(txt);
                


                var aProductos=new Array();
                app.sortable.enable('#lista-productos');
                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar').show();

                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;

                    $("#prod li").each(function(){
                        aProductos[n]=$(this).attr('data');

                        n++;
                    });


                });
                $('#button-guardar').on('click', function () {
                    $('#button-guardar').hide();
                    
                    var server=servidor+'admin/includes/ordenproductos.php';              //categorias:aCategorias}
                    
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{productos:aProductos, tienda:tienda}, 
                        success: function(data){
                            var obj=Object(data);

                            if (obj.valid==true){
                                muestraproductos(grupo,nombregrupo,categoria,nombrecategoria);
                            }
                            else{
                                console.log('ERROR');
                            }
                        }
                    });
                });
                
            }
            else{
                //console.log('ERROR');}
            } 
        }
    });
        
                

    
} 

function editaProducto(id,grupo,nombregrupo,categoria,nombrecategoria) {
  var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
            
            '<div class="title titulo-edita-producto">Modificar Producto</div>'+
            '<form  id="producto-form" enctype="multipart/form-data">'+
                '<div class="list">'+
                '<ul>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Id</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="id" placeholder="id" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+    
                  '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombre" placeholder="Nombre" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+      
                   '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Impuesto %</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="impuesto" placeholder="Impuesto" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</li>'+ 
                '</ul>'+   
                '</div>'+
                '<div style="text-align:center;" id="revo-img"><span style="font-size:12px;">Imagen Revo: </span><br>'+
                '<div class="text-align-center">'+
                    '<img src="" id="imagen-revo" width="80%" height="auto">'+
                '</div></div>'+
                '<div class="simple-list">'+
                '<ul>'+
                  '<li id="revo-act">'+
                      '<span>Activo Revo</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo" type="checkbox" checked disabled/>'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+            
                    '</li>'+
                    '<li>'+
                      '<span>Activo WEB</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo-web" name="chk-activo-web" type="checkbox" checked />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+               
                    '</li>'+

                    '<li>'+
                      '<span>Quitar modificadores</span>'+
                      '<label class="toggle toggle-init">'+
                        '<input id="chk-activo-modificadores" name="chk-activo-modificadores" type="checkbox" />'+
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
                            '<div class="item-title item-label">Descripción</div>'+
                            '<div class="item-input-wrap">'+
                              '<textarea name="info" id="info" placeholder="Descripción"></textarea>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                    '<li id="precio-revo">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Precio Revo</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="precio" placeholder="Precio" value="" disabled/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Precio Web</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="precio_web" id="precio_web" placeholder="Precio Web" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+

      
                        '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Cat. Modificador</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="modifier_category_id" id="modifier_category_id" placeholder="" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                        '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Grupo Modificador</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="modifier_group_id" id="modifier_group_id" placeholder="" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                    '</ul>'+ 
                '</div>'+
                '<div class="block" style="display: inline-block;">'+
                    '<p><span style="font-size:12px;">Alergenos: </span></p>'+
                    '<div id="alergias"></div>'+
                '</div>'+
                '<div class="block text-align:center">'+
                    '<p class="producto-nuevo"><span style="font-size:12px;">Imagen Web/App: </span></p>'+
                    '<div class="row producto-nuevo">'+
                        '<div class="col-100 medium-50 large-33 text-align-center">'+
                            '<img name="imagen1" id="imagen-app1" src="" width="80%" height="auto"/>  '+  
                            '<input id="input-imagen1" type="file" accept="image/*" onchange="loadFileImg(event,\'#imagen-app1\');$(\'#guarda-imagen1\').show();" style="display:none;">'+
                            '<a class="button button-fill" href="#" id="cambia-imagen1" style="width:50%;margin:auto;">Cambiar</a>'+
                            '<br><br><a class="button button-fill" href="#" id="guarda-imagen1" style="width:50%;margin:auto;display:none;">Guardar</a>'+      
                        '</div>'+
                        
                    '</div>'+    
      
                  '</div>'+    
      
      
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap ">'+
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
              if (id>0){
                var server=servidor+'admin/includes/leeproducto.php';
                $.ajax({
                    type: "POST",
                    url: server,
                    dataType:"json",
                    data:{id:id, tienda:tienda},
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){

                            var nombre=obj.nombre;
                            var imagen=obj.imagen;
                            var precio=obj.precio;
                            var precio_web=obj.precio_web;
                            var precio_app=obj.precio_app;
                            var imagen_app1=obj.imagen_app1;
                            
                            var alergenos=obj.alergenos;
                            //console.log(alergenos);
                            var impuesto=obj.impuesto;
                            var info=obj.info;
                            var activo=obj.activo;
                            var activo_web=obj.activo_web;
                            var activo_app=obj.activo_app;
                            var modifi=obj.modifcadores;
                            var modifier_category_id=obj.modifier_category_id;
                            var modifier_group_id=obj.modifier_group_id;

                            if (imagen==""){
                                imagen=servidor+'admin/img/no-imagen.png';
                            }
                            else {
                                imagen=servidor+'webapp/img/revo/'+imagen;
                            }

                            var txt_alergias="";
                            
                            for (x=0;x<alergenos.length;x++){
                                
                                txt_alergias+='<div style="text-align:center;float:left;margin:10px;"><img src="'+servidor+'webapp/img/alergenos/'+alergenos[x]['imagen']+'" width="64px" height="auto"><br>'+alergenos[x]['nombre']+'</div>';
                                
                            }
                            $('input[name=id]').val(id);
                            $('input[name=nombre]').val(nombre);
                            $('input[name=precio]').val(precio);
                            $('input[name=precio_web]').val(precio_web);
                            $('input[name=precio_app]').val(precio_app);
                            $('input[name=modifier_category_id]').val(modifier_category_id);
                            $('input[name=modifier_group_id]').val(modifier_group_id);
                            $('#info').val(info);
                            $('input[name=impuesto]').val(impuesto);
                            document.getElementById("imagen-revo").src =imagen;
                            if (activo=="0"){
                                $('#chk-activo').prop("checked", false );
                            }
                            if (activo_web=="0"){
                                $('#chk-activo-web').prop("checked", false );
                            }
                            /*
                            if (activo_app=="0"){
                                $('#chk-activo-app').prop("checked", false );
                            }
                            */
                            if (modifi=="0"){
                                $('#chk-activo-modificadores').prop("checked", false );
                                
                            }
                            if (imagen_app1==""){
                                document.getElementById('imagen-app1').src=servidor+'admin/img/no-imagen.png'
                            }
                            else {
                                document.getElementById('imagen-app1').src=servidor+'webapp/img/productos/'+imagen_app1;
                            }
                            /*
                            if (imagen_app2==""){
                                document.getElementById('imagen-app2').src=servidor+'img/no-imagen.png'
                            }
                            else {
                                document.getElementById('imagen-app2').src=servidor+'webapp/img/productos/'+imagen_app2;
                            }
                            if (imagen_app3==""){
                                document.getElementById('imagen-app3').src=servidor+'img/no-imagen.png'
                            }
                            else {
                                document.getElementById('imagen-app3').src=servidor+'webapp/img/productos/'+imagen_app3;
                            }
                            */
                            
                             if (dosTarifas==0){
                                 $('#precio-reparto').hide();
                             }
                            server=servidor+'admin/includes/leealergenos.php';   
           
                            $.ajax({
                                type: "POST",
                                url: server,
                                dataType:"json",
                                data:{id:'foo'},
                                success: function(data){
                                    
                                    var obj2=Object(data); 
                                    //console.log(obj2)
                                    var idalergeno=obj2.id;
                                    var nombrealergeno=obj2.nombre;
                                    var imagenalergeno=obj2.imagen;
                                    var txt_form_ale="";
                                    
                                    for (h=0;h<idalergeno.length;h++){   
                                        var checked="";
                                        for (k=0;k<alergenos.length;k++){
                                            if (alergenos[k]['id']==idalergeno[h].toString()){
                                                checked="checked";
                                            }
                                        }
                                        
                                    txt_form_ale+='<div style="text-align:center;float:left;margin:10px;"><img src="'+servidor+'webapp/img/alergenos/'+imagenalergeno[h]+'" width="64px" height="auto"><br>'+nombrealergeno[h]+'<br><label class="checkbox"><input type="checkbox" name="alerChk" '+checked+' value="'+idalergeno[h]+'" /><i class="icon-checkbox"></i></label></div>';
                                    //console.log(obj2);
                                    }
                                    $('#alergias').html(txt_form_ale);
                                }
                            });
                            
                        }
                        else{
                            app.dialog.alert('No se pudo leer el producto');
                        }
                    }
                });
              }
              else {
                server=servidor+'admin/includes/leealergenos.php';   
           
                $.ajax({
                    type: "POST",
                    url: server,
                    dataType:"json",
                    data:{id:'foo'},
                    success: function(data){

                        var obj2=Object(data); 
                        //console.log(obj2)
                        var idalergeno=obj2.id;
                        var nombrealergeno=obj2.nombre;
                        var imagenalergeno=obj2.imagen;
                        var txt_form_ale="";

                        for (h=0;h<idalergeno.length;h++){   
                            var checked="";


                        txt_form_ale+='<div style="text-align:center;float:left;margin:10px;"><img src="'+servidor+'webapp/img/alergenos/'+imagenalergeno[h]+'" width="64px" height="auto"><br>'+nombrealergeno[h]+'<br><label class="checkbox"><input type="checkbox" name="alerChk" '+checked+' value="'+idalergeno[h]+'" /><i class="icon-checkbox"></i></label></div>';
                        //console.log(obj2);
                        }
                        $('#alergias').html(txt_form_ale);
                    }
                });
                  $('.producto-nuevo').hide();
                  $('.titulo-edita-producto').html('Nuevo producto');
              }
              if(integracion==1){
                    //nombre
                    //impuesto
                    //revo-img
                    //revo-act
                    $('input[name=nombre]').attr('disabled',true);
                    $('input[name=impuesto]').attr('disabled',true);
                }
                else {
                    $('#revo-img').hide();
                    $('#revo-act').hide();
                    
                    $('#precio-revo').hide();
                    $('input[name=nombre]').attr('disabled',false);
                    $('input[name=impuesto]').attr('disabled',false);
                    //$('#titulo-edita-grupo').html('NUEVO PRODUCTO');
                }

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
         
      });
    dynamicPopup.open();
    $("#cambia-imagen1").on('click', function () {
        $('#input-imagen1').show(); 
        $("#cambia-imagen1").hide();
     
    });
    /*
    $("#cambia-imagen2").on('click', function () {
        $('#input-imagen2').show(); 
        $("#cambia-imagen2").hide();
     
    });
    $("#cambia-imagen3").on('click', function () {
        $('#input-imagen3').show(); 
        $("#cambia-imagen3").hide();
     
    });
    */
    $("#guarda-imagen1").on('click', function () {
       
        var f=document.getElementById('input-imagen1').files[0];
        var FData = new FormData();
        FData.append('imagen',f);    // this is main row
        FData.append('id',id); 
        FData.append('tienda',tienda); 
        var server=servidor+'admin/includes/guardaimgproducto1.php';
        $.ajax({
            url: server,
            type : 'POST',
            data : FData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    //leecategorias();
                }
                else{
                    app.dialog.alert('No se pudo guardar imagen');
                }  
                
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });  
    });    
    
    
    $('.save-data').on('click', function () {
        var activo_web=$('#chk-activo-web').prop("checked");
        var activo_app=$('#chk-activo-app').prop("checked");
        var modifi=$('#chk-activo-modificadores').prop("checked");
        
       
        var formData = app.form.convertToData('#grupo-form');   var impuesto=$('input[name=impuesto]').val(); 
        var nombre=$('input[name=nombre]').val(); 
        var server=servidor+'admin/includes/guardaproducto.php';
        var precio_web=$('#precio_web').val();
        var precio_app=$('#precio_app').val();
        var modifier_category_id=$('#modifier_category_id').val();
        var modifier_group_id=$('#modifier_group_id').val();
        var info=$('#info').val();
        var alergenos='';
        $('input[name=alerChk]:checked').each(function() {
            alergenos+=$(this).val()+',';     
        });
        alergenos=alergenos.substring(0, alergenos.length - 1);
        //$('input[name=alergenos]').val(val_alergenos);

        var server=servidor+'admin/includes/guardaproducto.php';
        
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data: {id:id,categoria:categoria,nombre:nombre, impuesto:impuesto,activo_web:activo_web, activo_app:activo_app,precio_web:precio_web, precio_app:precio_app,info:info, modifier_category_id:modifier_category_id, modifier_group_id:modifier_group_id,alergias:alergenos,modifi:modifi, tienda:tienda} ,
            success: function(data){
                var obj=Object(data);   
                if (obj.valid==true){
                    //leeempresa();
                   
                    muestraproductos(grupo,nombregrupo,categoria,nombrecategoria);
                   
                }
                else{
                    app.dialog.alert('No se pudo guardar el producto');
                }
            }
        });
                   
        dynamicPopup.close();   
    });

    
    

}

