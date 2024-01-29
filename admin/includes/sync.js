$(".sync-button").on('click', function () {

    var grupos=document.getElementById('chk-grupos').checked;
    var categorias=document.getElementById('chk-categorias').checked;
    var productos=document.getElementById('chk-productos').checked;
    var modificadores=document.getElementById('chk-modificadores').checked;
    var imagenes=document.getElementById('chk-imagenes').checked;
    $('.sync-button').prop('disabled', true);

    // grupos
    if (grupos==true){
        var syncimagen='false';
        if (imagenes==true) {syncimagen='true';}
        $('#li-grupos-progressbar').show();
        var server=servidor+'admin/includes/syncgrupos.php';    

        $('.sync-button').prop('disabled', true);
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                

                var obj=Object(data);               
                if (obj.valid==true){
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var imagen=obj.imagen;
                    var impuesto=obj.impuesto;
                    var orden=obj.orden;
                    var activo=obj.activo;            

                   // console.log(obj);

                    for(n=0;n<id.length;n++){
                        var server=servidor+'admin/includes/syncguardagrupos.php';

                        app.request.postJSON(server, { id:id[n],nombre:nombre[n],imagen:imagen[n], orden:orden[n],impuesto:impuesto[n],activo:activo[n], syncimagen:syncimagen}, 
                            function (data) {
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                }
                                
                            },
                            function (xhr, status) {

                            //app.dialog.alert('No se pudo sincronizar los grupos');
                        });                         
                        

                        //grupos-progressbar       
                    }
                    $('#li-grupos-progressbar').hide(); 
                    $('.sync-button').prop('disabled', false);
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los grupos');
                    $('.sync-button').prop('disabled', false); 
                }
            },
            function (xhr, status) {
                 $('.sync-button').prop('disabled', false);  
                app.dialog.alert('No se pudo sincronizar los grupos');
        });  
        //$('#li-grupos-progressbar').hide();  
    }
    // categorias
    if (categorias==true){
        var syncimagen='false';
        if (imagenes==true) {syncimagen='true';}
        $('#li-categorias-progressbar').show();
        $('.sync-button').prop('disabled', true);
        var server=servidor+'admin/includes/synccategorias.php';    

        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                
                //app.dialog.close();
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

                    
                    var el=$('#categorias-progressbar');
                    //console.log(obj);

                    for(n=0;n<id.length;n++){
                        var server=servidor+'admin/includes/syncguardacategorias.php';
                        
                        app.request.postJSON(server, { id:id[n], nombre:nombre[n], grupo:grupo[n], imagen:imagen[n], orden:orden[n], impuesto:impuesto[n], activo:activo[n], syncimagen:syncimagen,modifier_category_id:modifier_category_id[n], modifier_group_id:modifier_group_id[n]}, 
                            function (data) {

                                
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                    //console.log('OK: id['+n+']='+id[n])
                                }
                            
                                
                            },
                            function (xhr, status) {
                                //app.dialog.alert('No se pudo sincronizar los grupos');

                        });                         
                        
                        
                        //grupos-progressbar  

                    }
                    $('.sync-button').prop('disabled', false);
                    $('#li-categorias-progressbar').hide(); 
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los grupos');
                    $('.sync-button').prop('disabled', false);
                }
                //app.preloader.hide();
            },
            function (xhr, status) {
                $('.sync-button').prop('disabled', false);
                app.dialog.alert('No se pudo sincronizar los grupos');
        }); 
    }
    // products
    if (productos==true){
        var syncimagen='false';
        if (imagenes==true) {syncimagen='true';}
        $('.sync-button').prop('disabled', true);
        $('#li-productos-progressbar').show();
        var server=servidor+'admin/includes/syncproductos.php';  
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                
                //app.dialog.close();
                var obj=Object(data);       
                //app.preloader.hide();
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
                    var modifier_category_id=obj.modifier_category_id;
                    var modifier_group_id=obj.modifier_group_id;
                    //console.log(obj);
                    for(n=0;n<id.length;n++){
                        if (modifier_category_id[n]==null){
                            modifier_category_id[n]='';
                            
                        }
                        if (modifier_group_id[n]==null){
                            modifier_group_id[n]='';
                            
                        }
                        var server=servidor+'admin/includes/syncguardaproductos.php';
                        app.request.postJSON(server, { id:id[n], nombre:nombre[n], categoria:categoria[n], imagen:imagen[n], orden:orden[n], impuesto:impuesto[n], activo:activo[n], precio:precio[n], info:info[n],alergias:alergias[n], syncimagen:syncimagen, modifier_category_id:modifier_category_id[n], modifier_group_id:modifier_group_id[n]}, 
                            function (data) {
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                }
                                
                            },
                            function (xhr, status) {

                             
                                //app.dialog.alert('No se pudo sincronizar los grupos');
                        });                         
                        
                        
                        //items-progressbar      

                    }
                    $('.sync-button').prop('disabled', false);
                    $('#li-productos-progressbar').hide(); 
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los productos');
                    $('.sync-button').prop('disabled', false);
                }
            },
            function (xhr, status) {
                //app.preloader.hide();  
                app.dialog.alert('No se pudo sincronizar los productos');
                $('.sync-button').prop('disabled', false);
        });   
    }
    if (modificadores==true){
        
        $('.sync-button').prop('disabled', true);
        $('#li-modificadores-progressbar').show();
        var server=servidor+'admin/includes/syncmodificadores.php';  
        //alert('empieza sinc');
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                
                //app.dialog.close();
                var obj=Object(data);       
                //app.preloader.hide();
                if (obj.valid==true){
                    //alert('recibidos ok');
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var activo=obj.activo; 
                    var precio=obj.precio;
                    var category_id=obj.category_id;
                    var autoseleccionado=obj.autoseleccionado;
                
                    //console.log(obj);
                    for(n=0;n<id.length;n++){
                        
                        if (category_id[n]==null){
                            category_id[n]='';
                            
                        }
                        var server=servidor+'admin/includes/syncguardamodificadores.php';
                        app.request.postJSON(server, { id:id[n], nombre:nombre[n], activo:activo[n], precio:precio[n], category_id:category_id[n],autoseleccionado:autoseleccionado[n]}, 
                            function (data) {
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                    //console.log(nombre[n]);
                                }  
                            },
                            function (xhr, status) {
                        });                         
                        
                        
                        //items-progressbar      

                    }
                    $('.sync-button').prop('disabled', false);
                    $('#li-modificadores-progressbar').hide(); 
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los modificadores');
                    $('.sync-button').prop('disabled', false);
                }
            },
            function (xhr, status) {
                //app.preloader.hide();  
                app.dialog.alert('No se pudo sincronizar los modificadores');
                $('.sync-button').prop('disabled', false);
        });   
        var server=servidor+'admin/includes/syncmodifiergroups.php';  
        //alert('empieza sinc');
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                
                //app.dialog.close();
                var obj=Object(data);       
                //app.preloader.hide();
                if (obj.valid==true){
                    //alert('recibidos ok');
                    var id=obj.id;
                    var nombre=obj.nombre;

                
                    //console.log(obj);
                    for(n=0;n<id.length;n++){
                        
                        var server=servidor+'admin/includes/syncguardamodifiergroups.php';
                        app.request.postJSON(server, { id:id[n], nombre:nombre[n]}, 
                            function (data) {
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                    //console.log(nombre[n]);
                                    
        var server=servidor+'admin/includes/syncmodifierPivots.php';  
        //alert('empieza sinc');
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                                       
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
                            j=j+1;
                            grupo[j]=group_id[n];
                            categoria[j]='';
                        }
                        categoria[j]+=category_id[n]+',';
                    }
                    for(n=0;n<grupo.length;n++){ 
                        console.log('grupo:'+grupo[n]+'='+categoria[n]);
                        
                    }
                    
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los  modifierPivots');
                    $('.sync-button').prop('disabled', false);
                }
            },
            function (xhr, status) {
                //app.preloader.hide();  
                app.dialog.alert('No se pudo sincronizar modifierPivots');
                $('.sync-button').prop('disabled', false);
        });   
                                    
                                    
                                    
                                    
                                    
                                    
                                }  
                            },
                            function (xhr, status) {
                        });                         
                        
                        
                        //items-progressbar      

                    }
                    $('.sync-button').prop('disabled', false);
                    $('#li-modificadores-progressbar').hide(); 
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                    $('.sync-button').prop('disabled', false);
                }
            },
            function (xhr, status) {
                //app.preloader.hide();  
                app.dialog.alert('No se pudo sincronizar Grupo de modificador');
                $('.sync-button').prop('disabled', false);
        });   
        
        var server=servidor+'admin/includes/syncmodifiercategories.php';  
        //alert('empieza sinc');
        app.request.postJSON(server, { foo:'foo'}, 
            function (data) {                
                //app.dialog.close();
                var obj=Object(data);       
                //app.preloader.hide();
                if (obj.valid==true){
                    //alert('recibidos ok');
                    var id=obj.id;
                    var nombre=obj.nombre;

                
                    //console.log(obj);
                    for(n=0;n<id.length;n++){
                        
                        var server=servidor+'admin/includes/syncguardamodifiercategories.php';
                        app.request.postJSON(server, { id:id[n], nombre:nombre[n]}, 
                            function (data) {
                                //app.dialog.close();
                                var obj=Object(data);

                                if (obj.valid==true){
                                    //console.log(nombre[n]);
                                }  
                            },
                            function (xhr, status) {
                        });                         
                        
                        
                        //items-progressbar      

                    }
                    $('.sync-button').prop('disabled', false);
                    $('#li-modificadores-progressbar').hide(); 
                }
                else{
                    app.dialog.alert('No se pudo sincronizar los Grupo de modificador');
                    $('.sync-button').prop('disabled', false);
                }
            },
            function (xhr, status) {
                //app.preloader.hide();  
                app.dialog.alert('No se pudo sincronizar Grupo de modificador');
                $('.sync-button').prop('disabled', false);
        }); 
        
        
    }       

    
     
 });
