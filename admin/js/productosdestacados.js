//destacados(); // borrar
//navegar('#view-setting-inicio');

function destacados() {
$('#titulo-destacados').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Productos destacados<span id="button-guardar-destacados" class="button button-fill float-right" style="display:none;">Guardar</span><span onclick="editadestacado();" id="add-bloque-destacado" class="button button-fill float-right">Nuevo</span></div>');
    

    var txt='<ul id="desta">';
    var server=servidor+'admin/includes/leedestacados.php';     $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                
               
                var nombre=obj.nombre;
                var id=obj.id;
                var producto=obj.producto;
                var cat=obj.cat;
                var gru=obj.gru;
                var activo_ini=obj.activo_inicio;
                var activo_cat=obj.activo_catalogo;
                var chk_ini="";
                var chk_cat="";
                var tip="";
                var x=0;
                var txt='<ul id="desta">';
                if (id!=null){
                for (x=0;x<id.length;x++){
                    chk_ini="";
                    chk_cat="";
                    
                    if (activo_ini[x]==1){
                        chk_ini='checked';
                    }
                    if (activo_cat[x]==1){
                        chk_cat='checked';
                    }
                    
                    txt+=
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content >'+
                            '<div class="item-inner">'+
                                '<div class="item-title" style="width:100%;display:flex;">'+
                                    '<div class="col-70" style="width:70%;">'+nombre[x]+'->'+cat[x]+'->'+gru[x]+'</div>'+
                                    '<div class="col-10"style="width:10%;"><label class="checkbox"><input type="checkbox" name="activo_ini_'+id[x]+'" data-id="'+id[x]+'" '+chk_ini+' onclick="cambiaDestacadoActivoIni(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    '<div class="col-10" style="width:10%;"><label class="checkbox"><input type="checkbox" name="activo_cat_'+id[x]+'" data-id="'+id[x]+'" '+chk_cat+' onclick="cambiaDestacadoActivoCat(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    
                                    
                                    '<div class="col-10" style="width:10%;" onclick="borradestacado(\''+id[x]+'\',\''+nombre[x]+'\');"><i class="f7-icons" >trash</i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';          
                    
                    
                }    
                }
                txt=txt+
                    '</ul>'+
                '</div>';
                document.getElementById("add-bloque-destacado").setAttribute("data-orden", x);

                $('#lista-destacados').html(txt);  
               
          
                
                
                $('#button-guardar-destacados').on('click', function () {
                    $('#button-guardar-destacados').hide();
                    $('#add-bloque-destacado').show();
                   
                    //app.preloader.show();
                    var server=servidor+'admin/includes/ordendestacado.php';         
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{grupos:aGrupos},
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                document.getElementById('safari_window').contentWindow.location.reload(); $('#button-guardar-destacado').hide();  
                                destacados(); 
                            }
                            else{
                                console.log('ERROR');
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });
                    
                    
                });
                var aGrupos=new Array();
                app.sortable.enable($('#lista-destacados'));
                
                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar-destacados').show();
                    $('#add-bloque-destacado').hide();
                   
                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;
                    
                    $("#desta li").each(function(){
                        aGrupos[n]=$(this).attr('data');
                        
                        n++;
                    });
                    
                    
                });
                
                
                
                

            }
            else{
                document.getElementById("add-bloque-destacado").setAttribute('data-orden','0');
                //app.dialog.alert('No se pudo leer los productos destacados');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            }
    });
    

}

function cambiaDestacadoActivoIni(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivoinidestacado.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{ id:id, valor:valor},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){  
                //document.getElementById('safari_window').contentWindow.location.reload();   
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        }
    });
        
}

function borradestacado(id,nombre='',tipo=1){
    app.dialog.confirm('¿Desea Borrar <br><b>'+nombre+'</b>?', function () {
        var server=servidor+'admin/includes/borrardedestacado.php';
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{ id:id},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){  
                    var aGrupos=new Array();
                    var n=0;

                    $("#inic li").each(function(){
                        if (id!=$(this).attr('data')){
                            aGrupos[n]=$(this).attr('data');
                        }

                        n++;
                    });
                    //console.log(aGrupos);
                    //app.preloader.show();
                    var server=servidor+'admin/includes/ordendestacado.php';  
                     $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{ grupos:aGrupos},
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                document.getElementById('safari_window').contentWindow.location.reload(); 
                                destacados();
                            }
                            else{
                                console.log('ERROR');
                            } 
                        }
                     });
                    
                    
                    
                    //document.getElementById('safari_window').contentWindow.location.reload();   
                }
                else{
                    app.dialog.alert('No se pudo Borrar');
                }
            }
        });       
            
    });
}

function cambiaDestacadoActivoCat(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivocatdestacado.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{ id:id, valor:valor},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){  
                //document.getElementById('safari_window').contentWindow.location.reload();   
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        }
    });
             
}

function editadestacado(){
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Añadir producto destacado</div>'+

               '<div class="list">'+
                  '<ul>'+
                        '<li>'+
                          '<a class="item-link smart-select" data-searchbar="true" data-searchbar-placeholder="Buscar producto" data-open-in="popup">'+
                            '<select name="producto" id="lista-productos">'+

                            '</select>'+
                            '<div class="item-content">'+
                              '<div class="item-inner">'+
                                '<div class="item-title">Producto</div>'+
                                '<div class="item-after" id="producto-seleccionado"></div>'+
                              '</div>'+
                            '</div>'+
                          '</a>'+
                        '</li> '   + 
        '<li>'+
                '<div class="item-content">'+
                  
                  '<div class="item-inner">'+
                   ' <div class="item-title">Visible Inicio</div>'+
                    '<div class="item-after">'+
                      '<label class="toggle toggle-init">'+
                        '<input type="checkbox" id="check-ini"/>'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</li>'+
                '<li>'+
                '<div class="item-content">'+
                  
                  '<div class="item-inner">'+
                   ' <div class="item-title">Visible Catálogo</div>'+
                    '<div class="item-after">'+
                      '<label class="toggle toggle-init">'+
                        '<input type="checkbox" id="check-cat"/>'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
              '</li>'+
                    '</ul>'+
                '</div>'+

                    '<div class="grid grid-cols-2 grid-gap">'+
                        '<div class="col"><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                        '<div class="col"><a class="button button-fill"  onclick="guardadestacado();" href="#">Guardar</a</div>'+
                    '</div>'+
         
            '</div>'+
          '</div>'  ,
       on: {
            open: function (popup) {
                    var server=servidor+'admin/includes/leeproductossearch.php';
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{foo:'foo'},
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                var id=obj.id;
                                var nombre=obj.nombre;


                                txt='';
                                for(x=0;x<nombre.length;x++){
                                    
                                    txt+='<option value="'+id[x]+'">'+nombre[x]+'</option>';
                                }
                                
                                $('#lista-productos').html(txt);
                                
                            }
                            else{
                                //muestraMensaje('No se pudo leer los productos',titulo='',subtitulo='Error');
                            }
                        }
                    });              
                
                
            },
        }
    });  
    
    dynamicPopup.open();              
                
    
}


function guardadestacado() {
    if ($('#producto-seleccionado').html()==""){
        return;
    }
    var orden= document.getElementById("add-bloque-destacado").getAttribute('data-orden');
    var inicio=0;
    var catalogo=0;
    if($('#check-ini').prop('checked')){
        inicio=1;
    }
    if($('#check-cat').prop('checked')){
        catalogo=1;
    }
    
    
    server=servidor+'admin/includes/guardadestacado.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{id:$('#lista-productos').val(),inicio:inicio,catalogo:catalogo,orden:orden},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                muestraMensaje('Producto destacado guardado correctamente','Datos Guardados');
                destacados();
            }
            else{
                muestraMensaje('No se pudo guardar Producto destacado','Error');                    
            }
            
        }
    });    
    
                  
    app.popup.close(); 
    
}
