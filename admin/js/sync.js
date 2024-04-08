$(".sync-button").on('click', function () {

    var grupos=document.getElementById('chk-grupos').checked;
    var categorias=document.getElementById('chk-categorias').checked;
    var productos=document.getElementById('chk-productos').checked;
    var modificadores=document.getElementById('chk-modificadores').checked;
    var imagenes=document.getElementById('chk-imagenes').checked;
    var imagenes_png=document.getElementById('chk-imagenes-png').checked;
    $('.sync-button').prop('disabled', true);
    var precios=document.getElementById('chk-precios').checked;
    

    // grupos
    if (grupos==true){
        var syncimagen='false';
        var syncimagen_png='false';
        if (imagenes==true) {syncimagen='true';}
        if (imagenes_png==true) {syncimagen_png='true';}
        $('#li-grupos-progressbar').show();
        $('#li-grupos-progres').show();
        $('#grupos-progressbar-txt').html('Leyendo Grupos de Revo ..');
        var server=servidor+'admin/includes/syncgrupos.php';    

        $('.sync-button').prop('disabled', true);
        $.ajax({
            url: server,
            data:{ foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var imagen=obj.imagen;
                    var impuesto=obj.impuesto;
                    var orden=obj.orden;
                    var activo=obj.activo;            

                   // console.log(obj);
                    var pendientes=id.length;
                    var hecho=0;
                    var total=pendientes;
                    $('#grupos-progressbar').removeClass('progressbar-infinite');
                    
                    $('#grupos-progressbar').addClass('progressbar');
                    
                    for(n=0;n<id.length;n++){
                       $('#grupos-progressbar-txt').html('Grupo:'+nombre[n]); //console.log('sinc:'+nombre[n]+'('+activo[n]+')');
                        
                        var server=servidor+'admin/includes/syncguardagrupos.php';
                        
                        
                        $.ajax({
                            url: server,
                            data:{id:id[n],nombre:nombre[n],imagen:imagen[n], orden:orden[n],impuesto:impuesto[n],activo:activo[n], syncimagen:syncimagen,syncimagen_png:syncimagen_png},
                            method: "post",
                            dataType:"json",
                            success: function(data){    
                                var obj=Object(data);
                                if (obj.valid==true){
                                    pendientes--;
                                    hecho++;
                                    app.progressbar.set('#grupos-progressbar', (hecho*100/total), 5);
                                    if (pendientes==0){
                                        
                                        $('.sync-button').prop('disabled', false);
                                        //$('#li-grupos-progressbar').hide();
                                        //$('#li-grupos-progress').hide();
                                        
                                        $('#grupos-progressbar').hide();
                                        $('#grupos-progressbar-txt').html('Resultado grupos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncgrupos.txt\')">Ver log</a>');
                                        
                                    }
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });
                                               
                        

                        //grupos-progressbar       
                    }
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los grupos');
                    $('.sync-button').prop('disabled', false); 
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
                    
 
        //$('#li-grupos-progressbar').hide();  
    }
    // categorias
    if (categorias==true){
        var syncimagen='false';
        if (imagenes==true) {syncimagen='true';}
        $('#li-categorias-progressbar').show();
        $('#li-categorias-progress').show();
        $('#categorias-progressbar-txt').html('Leyendo Categorías de Revo ..');
        $('.sync-button').prop('disabled', true);
        var server=servidor+'admin/includes/synccategorias.php';   $.ajax({
            url: server,
            data:{ foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var grupo=obj.grupo;
                    var imagen=obj.imagen;
                    var impuesto=obj.impuesto;
                    var orden=obj.orden;
                    var activo=obj.activo; 
                    var modifier_category_id=obj.modifier_category_id;
                    var modifier_group_id=obj.modifier_group_id;

                    $('#categorias-progressbar').removeClass('progressbar-infinite');
                    
                    $('#categorias-progressbar').addClass('progressbar');
                    
                    var el=$('#categorias-progressbar');
                    var pendientes=id.length;
                    var hecho=0;
                    var total=pendientes;
                    
                    for(n=0;n<id.length;n++){
                        
                       $('#categorias-progressbar-txt').html('Categoría:'+nombre[n]); //console.log('sinc:'+nombre[n]+'('+activo[n]+')');

                        
                        var server=servidor+'admin/includes/syncguardacategorias.php';
                        
                        $.ajax({
                            url: server,
                            data:{id:id[n], nombre:nombre[n], grupo:grupo[n], imagen:imagen[n], orden:orden[n], impuesto:impuesto[n], activo:activo[n], syncimagen:syncimagen, syncimagen_png:syncimagen_png, modifier_category_id:modifier_category_id[n], modifier_group_id:modifier_group_id[n]},
                            method: "post",
                            dataType:"json",
                            success: function(data){    
                                var obj=Object(data);
                                if (obj.valid==true){
                                    pendientes--;
                                    hecho++;
                                    app.progressbar.set('#categorias-progressbar', (hecho*100/total), 5);
                                    if (pendientes==0){
                                        $('.sync-button').prop('disabled', false);
                                        //$('#li-categorias-progressbar').hide();
                                        //$('#li-categorias-progress').hide();
                                        $('#categorias-progressbar').hide();
                                        $('#categorias-progressbar-txt').html('Resultado categorías: <a href="#" onclick="window.open(\''+servidor+'admin/includes/synccategorias.txt\')">Ver log</a>');
                                    }
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });

                        //grupos-progressbar  

                    }
                    
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los grupos');
                    $('.sync-button').prop('disabled', false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
    
    // products
    if (productos==true){
        var syncimagen='false';
        var syncprecio='false';
        //app.dialog.alert('empieza la lectura de productos');
        if (imagenes==true) {syncimagen='true';}
        if (precios==true) {syncprecio='true';}
        $('.sync-button').prop('disabled', true);
        $('#li-productos-progressbar').show();
        $('#li-productos-progress').show();
        $('#productos-progressbar-txt').html('Leyendo Productos de Revo ..');
       
        var server=servidor+'admin/includes/syncproductos.php';  
        $.ajax({
            url: server,
            data:{ foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var categoria=obj.categoria;
                    var imagen=obj.imagen;
                    var impuesto=obj.impuesto;
                    var orden=obj.orden;
                    var activo=obj.activo; 
                    var precio=obj.precio;
                    var info=obj.info;
                    var alergias=obj.alergias;
                    var esMenu=obj.esMenu;
                    var modifier_category_id=obj.modifier_category_id;
                    var modifier_group_id=obj.modifier_group_id;
                    
                    //console.log('syncimagen:'+syncimagen);
                    //console.log('syncprecio:'+syncprecio);
                    
                    
                    $('#productos-progressbar').removeClass('progressbar-infinite');
                    
                    $('#productos-progressbar').addClass('progressbar');
                    //console.log(obj);
                    //for(n=0;n<1;n++){
                    var pendientes=id.length;
                    var hecho=0;
                    var total=pendientes;
                    console.log('Total:'+total);
                    for(n=0;n<id.length;n++){
                        
                        if (modifier_category_id[n]==null){
                            modifier_category_id[n]='';
                            
                        }
                        if (modifier_group_id[n]==null){
                            modifier_group_id[n]='';
                            
                        }
                        if (alergias[n]==null){
                            alergias[n]='';
                            
                        }
                        if (imagen[n]==null){
                            imagen[n]='';
                            
                        }
                        $('#productos-progressbar-txt').html('Producto:'+nombre[n]); //console.log('sinc:'+nombre[n]+'('+activo[n]+')');
                        
                        var server=servidor+'admin/includes/syncguardaproductos.php';
                        $.ajax({
                            url: server,
                            data:{ id:id[n], nombre:nombre[n], categoria:categoria[n], imagen:imagen[n], orden:orden[n], impuesto:impuesto[n], activo:activo[n], precio:precio[n], info:info[n],alergias:alergias[n], syncimagen:syncimagen, syncimagen_png:syncimagen_png, syncprecio:syncprecio, modifier_category_id:modifier_category_id[n], modifier_group_id:modifier_group_id[n], esMenu:esMenu[n]},
                            method: "post",
                            dataType:"json",
                            success: function(data){    
                                var obj=Object(data);
                                if (obj.valid==true){
                                    pendientes--;
                                    hecho++;
                                    console.log('Pendientes:'+pendientes);
                                    app.progressbar.set('#productos-progressbar', (hecho*100/total), 5);
                                    if (pendientes==0){
                                        $('.sync-button').prop('disabled', false);
                                        //$('#li-productos-progressbar').hide();
                                        //$('#li-productos-progres').hide();
                                        $('#productos-progressbar').hide();
                                        $('#productos-progressbar-txt').html('Resultado productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
                                    }
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });

                        //items-progressbar      

                    }

                }
                else{
                    app.dialog.alert('No se pudo sincronizar los productos');
                    $('.sync-button').prop('disabled', false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
  
    }
    
    if (modificadores==true){
        
        $('.sync-button').prop('disabled', true);
        $('#li-modificadores-progressbar').show();
        $('#li-modificadores-progress').show();
        $('#li-modificadores-progress').html('Leyendo Modificadores de Revo ..');
        var server=servidor+'admin/includes/syncmodificadores.php';  
        //alert('empieza sinc');
        $.ajax({
            url: server,
            data:{ foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    //alert('recibidos ok');
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var activo=obj.activo; 
                    var precio=obj.precio;
                    var category_id=obj.category_id;
                    var autoseleccionado=obj.autoseleccionado;
                
                    //console.log(obj);
                    
                    var pendientes=id.length;
                    console.log('Sync Modificadores:'+id.length);
                    for(n=0;n<id.length;n++){
                        
                        if (category_id[n]==null){
                            category_id[n]='';
                            
                        }
                        $('#li-modificadores-progress').html('Modificador:'+nombre[n]);
                        var server=servidor+'admin/includes/syncguardamodificadores.php';
                        
                        $.ajax({
                            url: server,
                            data:{ id:id[n], nombre:nombre[n], activo:activo[n], precio:precio[n], category_id:category_id[n],autoseleccionado:autoseleccionado[n]},
                            method: "post",
                            dataType:"json",
                            success: function(data){    
                                var obj=Object(data);
                                if (obj.valid==true){
                                    pendientes--;
                                    
                                    if (pendientes==0){
                                        syncmodifiergroups();
                                    }
                                    
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });

                    }
                    
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los modificadores');
                    $('.sync-button').prop('disabled', false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
 
    }       

 });

function syncmodifiergroups(){   
    $('#li-modificadores-progress').html('Leyendo Grupo Modificadores de Revo ..');
    var server=servidor+'admin/includes/syncmodifiergroups.php';  
        //alert('empieza sinc');
    $.ajax({
        url: server,
        data:{ foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                //alert('recibidos ok');
                var id=obj.id;
                var nombre=obj.nombre;

                var pendientes=id.length;
                console.log('Sync Grupo Modificadores:'+id.length);
                //console.log(obj);
                for(n=0;n<id.length;n++){
                    $('#li-modificadores-progress').html('Grupo modificador:'+nombre[n]);
                    var server=servidor+'admin/includes/syncguardamodifiergroups.php';
                    $.ajax({
                        url: server,
                        data:{ id:id[n], nombre:nombre[n]},
                        method: "post",
                        dataType:"json",
                        success: function(data){    
                            var obj=Object(data);
                            if (obj.valid==true){
                                pendientes--;
                                if (pendientes==0){
                                    syncmodifiercategories(); 
                                }
                                
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    }); 
                    

                }
                

            }
            else{
                app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                $('.sync-button').prop('disabled', false);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function syncmodifiercategories(){
    $('#li-modificadores-progress').html('Leyendo Categorías Modificadores de Revo ..');
    var server=servidor+'admin/includes/syncmodifiercategories.php';  
        //alert('empieza sinc');
    $.ajax({
        url: server,
        data:{ foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                //alert('recibidos ok');
                var id=obj.id;
                var nombre=obj.nombre;
                var activo=obj.activo;
                var opciones=obj.opciones;
                var forzoso=obj.forzoso;

                var pendientes=id.length;
                console.log('Sync Categoria Modificadores:'+id.length);
                //console.log(obj);
                for(n=0;n<id.length;n++){
                    //console.log('Nombre:'+nombre[n])
                    $('#li-modificadores-progress').html('Categoría modificador:'+nombre[n]);
                    var server=servidor+'admin/includes/syncguardamodifiercategories.php';
                    $.ajax({
                        url: server,
                        data:{ id:id[n], nombre:nombre[n],activo:activo[n],opciones:opciones[n],forzoso:forzoso[n]},
                        method: "post",
                        dataType:"json",
                        success: function(data){    
                            var obj=Object(data);
                            if (obj.valid==true){
                                pendientes--;
                                if (pendientes==0){
                                    //syncmenucategories(); 
                                    syncpivots();
                                }
                            }
                        }
                        ,
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });



                    //items-progressbar      

                }

                
                //syncpivots();
            }
            else{
                app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                $('.sync-button').prop('disabled', false);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function syncmenucategories(){
    $('#li-modificadores-progress').html('Leyendo Categorías Menús de Revo ..');
    server=servidor+'admin/includes/syncmenucategories.php';  
    //alert('empieza sinc');
    $.ajax({
        url: server,
        data:{ foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                //$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"orden"=>$orden,"eleMulti"=>$eleMulti,"min"=>$min,"max"=>$max,"producto"=>$producto);
                //alert('recibidos ok');
                var id=obj.id;
                var nombre=obj.nombre;
                var orden=obj.orden;
                var eleMulti=obj.eleMulti;
                var min=obj.min;
                var max=obj.max;
                var producto=obj.producto;
                var pendientes=id.length;
                console.log('Sync Categoria menu:'+id.length);

                //console.log(obj);
                for(n=0;n<id.length;n++){
                    $('#li-modificadores-progress').html('Categoría menú:'+nombre[n]);
                    var server=servidor+'admin/includes/syncguardamenucategories.php';
                    $.ajax({
                        url: server,
                        data:{ id:id[n], nombre:nombre[n], orden:orden[n], eleMulti:eleMulti[n], min:min[n], max:max[n], producto:producto[n]},
                        method: "post",
                        dataType:"json",
                        success: function(data){    
                            var obj=Object(data);
                            if (obj.valid==true){
                                pendientes--;
                                if (pendientes==0){
                                    syncmenumenuitem(); 
                                }
                                
                            }
                        }
                        ,
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });                         


                    //items-progressbar      

                }
                
            }
            else{
                app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                $('.sync-button').prop('disabled', false);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}

function syncmenumenuitem  (){
    $('#li-modificadores-progress').html('Leyendo Items Menús de Revo ..');
    server=servidor+'admin/includes/syncmenumenuitem.php';  
    //alert('empieza sinc');
    $.ajax({
        url: server,
        data:{ foo:'foo'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                //$json=array("valid"=>$checking,"id"=>$id,"activo"=>$activo,"orden"=>$orden,"precio"=>$precio,"producto"=>$producto,"category_id"=>$category_id,"modifier_group_id"=>$modifier_group_id,"addPrecioMod"=>$addPrecioMod);
                //alert('recibidos ok');
                var id=obj.id;
                var activo=obj.activo;
                var orden=obj.orden;
                var precio=obj.precio;
                var producto=obj.producto;
                var category_id=obj.category_id;
                var modifier_group_id=obj.modifier_group_id;
                var addPrecioMod=obj.addPrecioMod;

                var pendientes=id.length;
                console.log('Sync Item menu:'+id.length);
                //console.log(obj);
                for(n=0;n<id.length;n++){
                    $('#li-modificadores-progress').html('Item menú:'+id[n]);
                    var server=servidor+'admin/includes/syncguardamenumenuitem.php';
                    $.ajax({
                        url: server,
                        data:{ id:id[n], activo:activo[n], orden:orden[n], precio:precio[n], producto:producto[n], category_id:category_id[n], modifier_group_id:modifier_group_id[n], addPrecioMod:addPrecioMod[n]},
                        method: "post",
                        dataType:"json",
                        success: function(data){    
                            var obj=Object(data);
                            if (obj.valid==true){
                                pendientes--;
                                if (pendientes==0){
                                    syncpivots(); 
                                }
                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });                     


                   

                }
                      
            }
            else{
                app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                $('.sync-button').prop('disabled', false);
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
}

function syncpivots() {
    $('#li-modificadores-progress').html('Leyendo Pivots de Revo ..');
    var server=servidor+'admin/includes/syncmodifierPivots.php';  
        $.ajax({
            url: server,
            data:{ foo:'foo'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    //alert('recibidos ok');
                    var id=obj.id;
                    var group_id=obj.group_id;
                    var category_id=obj.category_id;
                    
                    var grupo=new Array();
                    var categoria=new Array();
                    var gru=0;
                    var j=-1;
                    
                    //console.log(obj);
                    for(n=0;n<id.length;n++){ 
                        if (gru!=group_id[n]){
                            gru=group_id[n];
                            j=j+1;
                            grupo[j]=group_id[n];
                            categoria[j]='';
                        }
                        categoria[j]+=category_id[n]+',';
                    }
                    var pendientes=grupo.length;
                    console.log('Sync pivot:'+id.length);
                    for(n=0;n<grupo.length;n++){ 
                        categoria[n] = categoria[n].substring(0, categoria[n].length - 1);
                        //console.log('grupo:'+grupo[n]+'='+categoria[n]);
                        var server=servidor+'admin/includes/syncUPDATEmodifiergroups.php';
                        $.ajax({
                            url: server,
                            data:{  id:grupo[n], categoria:categoria[n]},
                            method: "post",
                            dataType:"json",
                            success: function(data){    
                                var obj=Object(data);
                                if (obj.valid==true){
                                    pendientes--;
                                    if (pendientes==0){
                                       terminaSyncMod(); 
                                    }
                                    
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });
                        
                    }
                    
                    
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los  modifierPivots');
                    $('.sync-button').prop('disabled', false);
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
        
}

function terminaSyncMod(){
    $('.sync-button').prop('disabled', false);
    $('#li-modificadores-progressbar').hide(); 
    $('#li-modificadores-progress').hide(); 
}