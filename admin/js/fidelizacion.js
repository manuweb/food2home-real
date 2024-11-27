

function cambiaEstadoFidelidad(estado){
    var estadochk=0;
    if (estado.checked){
        $('#id-fidelidad-por').show();
        $('#fidelidad-page').show();
        estadochk=1;
        
    }
    else{
        $('#id-fidelidad-por').hide();
        $('#fidelidad-page').hide();
    }
    var server=servidor+'admin/includes/cambiaestadofidelidad.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {estado:estadochk},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){          
                
            }
            else{
                
            }
        }
    });   
}

function cambiaporcentajefidelidad(e){
    //console.log($('#fidelidad-por').val());
    var server=servidor+'admin/includes/cambiaporcentajefidelidad.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {porcentaje:$('#fidelidad-por').val()},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){          
                
            }
            else{
                
            }
            //  leeempresa();
        }
    });
   
}

function leegruposfidelizacion(){
    var server=servidor+'admin/includes/leefidelizacion.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){ 
                $('#fidelidad-por').val(obj.porcentaje);
                if (obj.fidelizacion==0){
                    $('#fidelidad-off').prop('checked',false);
                    $('#id-fidelidad-por').hide();
                    $('#fidelidad-page').hide();
                }
            }
        }
    });
    var server=servidor+'admin/includes/leegruposfidelizacion.php';
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
                var porcentaje=obj.porcentaje;
                var cantidad=obj.cantidad; 
                var activo=obj.activo; 
                var usuarios=obj.usuarios;
                var txt="";
                //console.log(usuarios)
                for (var x=0;x<id.length;x++){
                    var color="";
                    var disabledmas='';
                    var disabledmenos='';
                    if (activo[x]==0){
                        color="text-color-red";
                    }

                    
                    
                    var minus='<div class=""><i class="f7-icons text-color-gray">folder_fill_badge_minus</i></div>';
                    var plus='<div class="" onclick="addgrupofidelidad('+id[x]+',\''+nombre[x]+'\');"><i class="f7-icons '+color+'">folder_fill_badge_plus</i></div>';
                    if (cantidad[x]>0){
                        minus='<div class="" onclick="restagrupofidelidad('+id[x]+',\''+nombre[x]+'\');"><i class="f7-icons '+color+'">folder_fill_badge_minus</i></div>';
                        if (activo[x]==0){
                            minus='<div class="text-color-orange"><i class="f7-icons ">folder_fill_badge_minus</i></div>';
                        }
                        
                    }
                    else{
                        if (activo[x]==0){
                            minus='<div class="text-color-orange"><i class="f7-icons ">folder_fill_badge_minus</i></div>';
                        }
                    }
                    if (usuarios[x]==cantidad[x]){
                        plus='<div class=""><i class="f7-icons text-color-gray">folder_fill_badge_plus</i></div>';
                        if (activo[x]==0){
                            plus='<div class="col-15"><i class="f7-icons text-color-orange">folder_fill_badge_plus</i></div>';
                        }
                    }
                    else{
                        if (activo[x]==0){
                            plus='<div class=""><i class="f7-icons text-color-orange">folder_fill_badge_plus</i></div>';
                        }
                    }
                    txt+=''+
                        '<div class="grid grid-cols-5 grid-gap  '+color+'">'+
                                '<div class="">'+nombre[x].substr(0,15)+' ('+cantidad[x]+')</div>'+
                                '<div class="">'+porcentaje[x]+'</div>'+
                                '<div class="" onclick="editgrupofidelidad('+id[x]+');"><i class="f7-icons">pencil</i></div>'+plus+
                                minus+
                            '</div>';
                }
                $('#fidelidad-tabla').html(txt);
                
            }
            else{
                
            }
        }
    });
    
}

function addgrupofidelidad(id, grupo){
    var server=servidor+'admin/includes/leeclientesgrupo.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {id:id, tipo:'add'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){         
                var idcliente=obj.id;
                var nombre=obj.nombre;
                var apellidos=obj.apellidos;
                var username=obj.username;
                var dynamicPopup = app.popup.create({
                    content: ''+
                    '<div class="popup view">'+
                        '<div class="page page-with-subnavbar">'+
                            '<div class="navbar">'+
                                '<div class="navbar-bg"></div>'+
                                '<div class="navbar-inner">'+
                                    '<div class="title">Añadir a grupo '+grupo+'</div>'+
                                    '<div class="right">'+
                                        '<a href="#" class="link "><i class="icon f7-icons popup-close">xmark</i></a>'+
                                    '</div>'+        
                                    '<div class="subnavbar">'+
                                        '<form class="searchbar ">'+
                                            '<div class="searchbar-inner">'+
                                                '<div class="searchbar-input-wrap">'+
                                                    '<input type="search" placeholder="Buscar cliente" />'+
                                                    '<i class="searchbar-icon"></i>'+
                                                    '<span class="input-clear-button"></span>'+
                                                '</div>'+
                                                '<span class="searchbar-disable-button if-not-aurora">Cancelar</span>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+         
                            '<div class="block page-content" style="overflow: scroll;"><br>'+
                                ' <div id="search">'+
                                    '<div class="searchbar-backdrop"></div><br><br>'+
                                    '<div class="list searchbar-found" id="lista-clientes"></div>'+
                                    '<div class="block searchbar-not-found">'+
                                        '<div class="block-inner">Cliente no encontrado.</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+   
                        '</div>'+
                    '</div>'  ,
                

                 on: {
                        open: function (popup) {                
                
                         }
                    }
                });  
                
                dynamicPopup.open();
                var txt='<ul>';
                for(x=0;x<nombre.length;x++){
                    var txt_1='<li class="item-content">'+
                        '<div class="item-inner">'+
                          '<div class="item-title" onclick="addclientepregunta(\''+id+'\',\''+idcliente[x]+'\',\''+nombre[x]+'\',\''+apellidos[x]+'\',\''+username[x]+'\');">'+apellidos[x]+' '+nombre[x]+'</div>'+
                        '<div class="item-subtitle" style="font-size:12px;">'+username[x]+'</div>'+
                        '</div>'+
                      '</li>';
                    txt+=txt_1;
                }
                txt+='</ul>';
                $('#lista-clientes').html(txt);
                var searchbar = app.searchbar.create({
                    el: '.searchbar',
                    searchContainer: '#lista-clientes',
                    searchIn: '.item-title',
                    on: {
                      search(sb, query, previousQuery) {
                        //console.log(query, previousQuery);
                      },
                      
                    },

                  });

            }
            else{
                
            }
        }
    });

}

function restagrupofidelidad(id, grupo){
    var server=servidor+'admin/includes/leeclientesgrupo.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {id:id, tipo:'delete'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){         
                var idcliente=obj.id;
                var nombre=obj.nombre;
                var apellidos=obj.apellidos;
                var username=obj.username;
                var dynamicPopup = app.popup.create({
                    content: ''+
                    '<div class="popup view">'+
                        '<div class="page page-with-subnavbar">'+
                            '<div class="navbar">'+
                                '<div class="navbar-bg"></div>'+
                                '<div class="navbar-inner">'+
                                    '<div class="title">Quitar del grupo '+grupo+'</div>'+
                                    '<div class="right">'+
                                        '<a href="#" class="link "><i class="icon f7-icons popup-close">xmark</i></a>'+
                                    '</div>'+        
                                    '<div class="subnavbar">'+
                                        '<form class="searchbar ">'+
                                            '<div class="searchbar-inner">'+
                                                '<div class="searchbar-input-wrap">'+
                                                    '<input type="search" placeholder="Buscar cliente" />'+
                                                    '<i class="searchbar-icon"></i>'+
                                                    '<span class="input-clear-button"></span>'+
                                                '</div>'+
                                                '<span class="searchbar-disable-button if-not-aurora">Cancelar</span>'+
                                            '</div>'+
                                        '</form>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+         
                             '<div class="block page-content" style="overflow: scroll;"><br>'+
                                ' <div id="search">'+
                                    '<div class="searchbar-backdrop"></div><br><br>'+
                                    '<div class="list searchbar-found" id="lista-clientes"></div>'+
                                    '<div class="block searchbar-not-found">'+
                                        '<div class="block-inner">Cliente no encontrado.</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+   
                        '</div>'+
                    '</div>'  ,
                

                 on: {
                        open: function (popup) {                
                
                         }
                    }
                });  
                
                dynamicPopup.open();
                var txt='<ul>';
                for(x=0;x<nombre.length;x++){
                    var txt_1='<li class="item-content">'+
                        '<div class="item-inner">'+
                          '<div class="item-title" onclick="borraclientepregunta(\''+id+'\',\''+idcliente[x]+'\',\''+nombre[x]+'\',\''+apellidos[x]+'\',\''+username[x]+'\');">'+apellidos[x]+' '+nombre[x]+'</div>'+
                        '<div class="item-subtitle" style="font-size:12px;">'+username[x]+'</div>'+
                        '</div>'+
                      '</li>';
                    txt+=txt_1;
                }
                txt+='</ul>';
                $('#lista-clientes').html(txt);
                var searchbar = app.searchbar.create({
                    el: '.searchbar',
                    searchContainer: '#lista-clientes',
                    searchIn: '.item-title',
                    on: {
                      search(sb, query, previousQuery) {
                        //console.log(query, previousQuery);
                      },
                      
                    },

                  });

            }
            else{
                
            }
        }
    });
    
}


function addclientepregunta(id,idcliente,nombre,apellido,username) {
    app.dialog.confirm('Añadir cliente<br>'+apellido+', '+nombre,
        function () {
            var server=servidor+'admin/includes/editaclientesgrupo.php';
            $.ajax({
                type: "POST",
                url: server,
                data: {id:id, tipo:'add', cliente:idcliente},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){    
                        app.dialog.alert('¡'+apellido+', '+nombre+' añadido!');
                    }
                    else {
                        app.dialog.alert('No se pudo añadir');
                    }
                    leegruposfidelizacion();
                }
            });
            app.popup.close();
        },
        function () {
            
            app.popup.close();
        }
    );
    
}

function borraclientepregunta(id,idcliente,nombre,apellido,username) {
    app.dialog.confirm('Eliminar cliente<br>'+apellido+', '+nombre,
        function () {
            var server=servidor+'admin/includes/editaclientesgrupo.php';
            $.ajax({
                type: "POST",
                url: server,
                data: {id:id, tipo:'delete', cliente:idcliente},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){    
                        app.dialog.alert('¡'+apellido+', '+nombre+' eliminado!');
                    }
                    else {
                        app.dialog.alert('No se pudo eliminar');
                    }
                    leegruposfidelizacion();
                }
            });
            app.popup.close();
        },
        function () {
            
            app.popup.close();
        }
    );
    
}



function editgrupofidelidad(id=0) {
    var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Grupo clientes</div>'+
                '<form>'+
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
                            '<div class="item-title item-label">Porcentaje</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="porcentaje" value="" placeholder="Porcentaje"  />'+
                            '</div>'+
                          '</div>'+
                        '</div><br>'+
                     '<li>'+
                   '</ul>'+   
                 '</div>'+

        
        
                 '<div class="list simple-list" style="margin-top: -35px;">'+
                      '<ul>'+
                        '<li>'+
                            '<span>Activo</span>'+
                            '<label class="toggle toggle-init">'+
                                '<input type="checkbox" id="grupo-activo" checked/>'+
                                '<span class="toggle-icon"></span>'+
                            '</label>'+
                      '</li>'+ 
                    '</ul>'+ 
                '</div>'+
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" data-id="'+id+'" onclick="guardagrupoclientes(this);" href="#">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'  ,
     on: {
            open: function (popup) {
                if (id!=0){

                    var server=servidor+'admin/includes/leegrupofidelizacion.php';     
                    $.ajax({
                        type: "POST",
                        url: server,
                        data: {id:id},
                        dataType:"json",
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                var id=obj.tipo;
                                var nombre=obj.nombre;
                                var porcentaje=obj.porcentaje;
                                var cantidad=obj.cantidad;
                                var activo=obj.activo;
                                if (activo==0){
                                    $('#grupo-activo').prop('checked', false);
                                }
                                else {
                                    $('#grupo-activo').prop('checked', true);
                                }
                                $('input[name=nombre]').val(nombre);
                                $('input[name=porcentaje]').val(porcentaje);
                                if (cantidad>0){
                                    $('.title').html('Grupo clientes (en el grupo: <b>'+cantidad+'</b>)');
                                }  
                            }
                            else{
                                app.dialog.alert('No se pudo leer el grupp');
                            }
                        }
                    });
                }
                else {
                    
                }
            }
        }
    });  
    
    dynamicPopup.open(); 
    
}

function guardagrupoclientes(e){
    var id=e.getAttribute('data-id');
    var errores="";
    var activo=$('#grupo-activo').prop('checked');
    
    if ($('input[name=nombre]').val()==""){
        errores+='nombre, ';
    }
    if ($('input[name=porcentaje]').val()==""){
        errores+='porcentaje, ';
    }
    if (errores!='') {
        app.dialog.alert('Errores: '+errores);
    }
    else {
        app.dialog.alert('nombre: '+$('input[name=nombre]').val());
        app.popup.close(); 
        var server=servidor+'admin/includes/guardagrupoclientes.php';
        $.ajax({
            type: "POST",
            url: server,
            data: {id:id, nombre:$('input[name=nombre]').val(), porcentaje:$('input[name=porcentaje]').val(), activo:activo},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    leegruposfidelizacion();
                }
                else{
                    app.dialog.alert('No se pudo guardar el grupo');
                }
            },
            error: function(e){
                console.log('error');
            }
        });                   
        
    }
}
