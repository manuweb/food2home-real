


function sincronizacion(){
    
    var txt=''+
        '<div class="list simple-list">'+
            '<ul>'+
'<li>'+
                    '<span>Grupos</span>'+
                    '<label class="toggle toggle-init">'+
                        '<input id="chk-grupos" type="checkbox"  />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                '</li>'+
        
'<li id="li-grupos-progressbar" style="display:none;">'+
    '<div style="width: 100%;">'+
        '<div class="progressbar-infinite color-green" data-progress="0" id="grupos-progressbar" style="height: 10px;"></div>'+

        '<div id="grupos-progressbar-txt"></div>'+
    '</div>'+
'</li>'+
        
                
                '<li>'+
                    '<span>Categorías</span>'+
                    '<label class="toggle toggle-init">'+
                        '<input id="chk-categorias" type="checkbox"  />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                '</li>'+
        
'<li id="li-categorias-progressbar" style="display:none;">'+
    '<div style="width: 100%;">'+
        '<div class="progressbar-infinite color-green" data-progress="0" id="categorias-progressbar" style="height: 10px;"></div>'+

        '<div id="categorias-progressbar-txt"></div>'+
    '</div>'+
'</li>'+
                '<li>'+
                    '<span>Productos</span>'+
                    '<label class="toggle toggle-init">'+
                        '<input id="chk-productos" type="checkbox"  />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                '</li>'+
'<li id="li-productos-progressbar" style="display:none;">'+
    '<div style="width: 100%;">'+
        '<div class="progressbar-infinite color-green" data-progress="0" id="productos-progressbar" style="height: 10px;"></div>'+
        '<div id="productos-progressbar-txt"></div>'+
    '</div>'+
'</li> '+           
                '<li>'+
                    '<span>Modificadores</span>'+
                    '<label class="toggle toggle-init">'+
                        '<input id="chk-modificadores" type="checkbox"  />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                '</li>'+
'<li id="li-modificadores-progressbar" style="display:none;">'+
    '<div style="width: 100%;">'+
        '<div class="progressbar-infinite color-green" data-progress="0" id="modificadores-progressbar" style="height: 10px;"></div>'+
        '<div id="modificadores-progressbar-txt"></div>'+
    '</div>'+
'</li> '+       
                '<li>'+
                    '<span>Menús</span>'+
                    '<label class="toggle toggle-init">'+
                        '<input id="chk-menus" type="checkbox"  />'+
                        '<span class="toggle-icon"></span>'+
                      '</label>'+
                '</li>'+
'<li id="li-menus-progressbar" style="display:none;">'+
    '<div style="width: 100%;">'+
        '<div class="progressbar-infinite color-green" data-progress="0" id="menus-progressbar" style="height: 10px;"></div>'+
        '<div id="menus-progressbar-txt"></div>'+
    '</div>'+
'</li> '+        
        
'<li>'+
    '<label class="item-checkbox item-content">'+
      '<input type="checkbox" id="chk-imagenes" value="chk-imagenes" />'+
      '<i class="icon icon-checkbox"></i>'+
      '<div class="item-inner">'+
        '<div class="item-title">Sincronizar imagenes</div>'+
      '</div>'+
    '</label>'+
  '</li>'+
'<li>'+
    '<label class="item-checkbox item-content" style="margin-left: 30px;">'+
      '<input type="checkbox" id="chk-imagenes-png" value="chk-imagenes-png" />'+
      '<i class="icon icon-checkbox"></i>'+
      '<div class="item-inner">'+
        '<div class="item-title">Convertir a WEBP</div>'+
      '</div>'+
    '</label>'+
'</li> '+
'<li>'+
    '<label class="item-checkbox item-content">'+
      '<input type="checkbox" id="chk-precios" value="chk-precios" />'+
      '<i class="icon icon-checkbox"></i>'+
      '<div class="item-inner">'+
        '<div class="item-title">Sincronizar precios</div>'+
      '</div>'+
    '</label>'+
'</li>'+  
'<li>'+
    '<label class="item-checkbox item-content">'+
      '<input type="checkbox" id="chk-estados" value="chk-estados" />'+
      '<i class="icon icon-checkbox"></i>'+
      '<div class="item-inner">'+
        '<div class="item-title">Sincronizar estados</div>'+
      '</div>'+
    '</label>'+
'</li>'+  
            '</ul>'+
        '</div>'+
        '<div class="text-align-center">'+
            '<button class="button button-fill sync-button" style="width:50%;margin: auto;" onclick="sincronizar();"><i class="icon f7-icons">arrow_2_circlepath_circle</i> Sincronizar</button>'+
        '</div> ';
    
    $('#sync-page').html(txt);
}

function sincronizar(){

    var grupos=document.getElementById('chk-grupos').checked;

    var categorias=document.getElementById('chk-categorias').checked;
    var productos=document.getElementById('chk-productos').checked;
    var modificadores=document.getElementById('chk-modificadores').checked;
    var menus=document.getElementById('chk-menus').checked;
    var imagenes=document.getElementById('chk-imagenes').checked;
    var imagenes_png=document.getElementById('chk-imagenes-png').checked;
    
    var estados=document.getElementById('chk-estados').checked;
    
    console.log(estados);
    $('.sync-button').prop('disabled', true);
    var precios=document.getElementById('chk-precios').checked;
    

    // grupos
    if (grupos==true){
        var syncimagen='false';
        var syncimagen_png='false';
        if (imagenes==true) {syncimagen='true';}
        if (imagenes_png==true) {syncimagen_png='true';}
        
        $('#li-grupos-progressbar').show();
        $('#li-grupos-progress').show();
        $('#grupos-progressbar-txt').html('Leyendo Grupos de Revo ..');
        $('.sync-button').prop('disabled', true);
        
        var server=servidor+'admin/includes/sync.php';   
        $.ajax({
            url: server,
            data:{ tipo:'grupos'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    
                    var datosRevo=obj.datosRevo; 
                    //console.log(datosRevo);
                    
                    $('#grupos-progressbar').removeClass('progressbar-infinite');
                    
                    $('#grupos-progressbar').addClass('progressbar');
                    
                    var el=$('#grupos-progressbar');
                    var pendientes=datosRevo.length;
                    var hecho=0;
                    var total=pendientes;
                    for(n=0;n<datosRevo.length;n++){

                       $('#grupos-progressbar-txt').html('Grupo:'+datosRevo[n]['nombre']); 
                        var server=servidor+'admin/includes/syncguarda.php';

                    
    $.ajax({
        url: server,
        data:{tipo:'grupos',datosRevo:datosRevo[n], estados:estados},
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
                    $('#grupos-progressbar').hide();
                    $('#grupos-progressbar-txt').html('Resultado Grupos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncgrupos.txt\')">Ver log</a>');
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
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });      

                   
                    
 
        //$('#li-grupos-progressbar').hide();  
    }
    
    if (categorias==true){
        
        $('#li-categorias-progressbar').show();
        $('#li-categorias-progress').show();
        $('#categorias-progressbar-txt').html('Leyendo Categorías de Revo ..');
        $('.sync-button').prop('disabled', true);
        
        var server=servidor+'admin/includes/sync.php';   
        $.ajax({
            url: server,
            data:{ tipo:'categorias'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var datosRevo=obj.datosRevo;
                    $('#categorias-progressbar').removeClass('progressbar-infinite');
                    
                    $('#categorias-progressbar').addClass('progressbar');
                    
                    var el=$('#categorias-progressbar');
                    var pendientes=datosRevo.length;
                    var hecho=0;
                    var total=pendientes;
                    for(n=0;n<datosRevo.length;n++){

                       $('#categorias-progressbar-txt').html('Categoría:'+datosRevo[n]['nombre']); 
                        var server=servidor+'admin/includes/syncguarda.php';
                       
                   
                    
    $.ajax({
        url: server,
        data:{tipo:'categorias',datosRevo:datosRevo[n], syncimagen:imagenes,syncimagen_png:imagenes_png, estados:estados},
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
    
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
    
    if (productos==true){
        
        $('#li-productos-progressbar').show();
        $('#li-productos-progress').show();
        $('#productos-progressbar-txt').html('Leyendo Productos de Revo ..');
        $('.sync-button').prop('disabled', true);
        
        var server=servidor+'admin/includes/sync.php';   
        $.ajax({
            url: server,
            data:{ tipo:'productos'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var datosRevo=obj.datosRevo;
                    $('#productos-progressbar').removeClass('progressbar-infinite');
                    
                    $('#productos-progressbar').addClass('progressbar');
                    
                    var el=$('#productos-progressbar');
                    var pendientes=datosRevo.length;
                    var hecho=0;
                    var total=pendientes;
                    for(n=0;n<datosRevo.length;n++){
                    
                       $('#productos-progressbar-txt').html('Producto:'+datosRevo[n]['nombre']); 
                        var server=servidor+'admin/includes/syncguarda.php';
                       
                   
                    
    $.ajax({
        url: server,
        data:{tipo:'productos',datosRevo:datosRevo[n],syncimagen:imagenes,syncimagen_png:imagenes_png, estados:estados,precios:precios},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                pendientes--;
                hecho++;
                app.progressbar.set('#productos-progressbar', (hecho*100/total), 5);
                if (pendientes==0){
                    $('.sync-button').prop('disabled', false);
                    $('#productos-progressbar').hide();
                    $('#productos-progressbar-txt').html('Resultado Productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
                }
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
            if (xhr.status='200'){
                pendientes--;
                hecho++;
                app.progressbar.set('#productos-progressbar', (hecho*100/total), 5);
                if (pendientes==0){
                    $('.sync-button').prop('disabled', false);
                    $('#productos-progressbar').hide();
                    $('#productos-progressbar-txt').html('Resultado Productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
                }
            }
            
        }
    });
    
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
    
    if (menus==true){
        syncMenus();
    }
    
    if (modificadores==true){
        
        $('#li-modificadores-progressbar').show();
        $('#li-modificadores-progress').show();
        $('#modificadores-progressbar-txt').html('Leyendo Modificadores de Revo ..');
        $('.sync-button').prop('disabled', true);
        //console.log('Modificadores');

        var server=servidor+'admin/includes/sync.php';   
        $.ajax({
            url: server,
            data:{ tipo:'modificadores'},
            method: "post",
            dataType:"json",
            success: function(data){    
                var obj=Object(data);
                if (obj.valid==true){
                    var datosRevo=obj.datosRevo;
                    $('#modificadores-progressbar').removeClass('progressbar-infinite');
                    
                    $('#modificadores-progressbar').addClass('progressbar');
                    
                    var el=$('#modificadores-progressbar');
                    var pendientes=datosRevo.length;
                    var hecho=0;
                    var total=pendientes;
                    
                    
                    for(n=0;n<datosRevo.length;n++){

                       $('#modificadores-progressbar-txt').html('Modificador:'+datosRevo[n]['nombre']); 
                        var server=servidor+'admin/includes/syncguarda.php';
                       
                   
                    
    $.ajax({
        url: server,
        data:{tipo:'modificadores',datosRevo:datosRevo[n]},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                pendientes--;
                hecho++;
                app.progressbar.set('#modificadores-progressbar', (hecho*100/total), 5);
                if (pendientes==0){
                    syncCatMod();
                    //syncPivMod();
                    //$('.sync-button').prop('disabled', false);
                    //$('#modificadores-progressbar').hide();
                    //$('#modificadores-progressbar-txt').html('Resultado Productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
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
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
    }
}

function syncCatMod(){
    $('#modificadores-progressbar-txt').html('Leyendo Categorías de Modificadores de Revo ..');
    $('#modificadores-progressbar').removeClass('progressbar');
    $('#modificadores-progressbar').addClass('progressbar-infinite');
    var server=servidor+'admin/includes/sync.php';   
    //console.log('Cat Modificadores');
    $.ajax({
        url: server,
        data:{ tipo:'catmod'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                var datosRevo=obj.datosRevo;
                $('#modificadores-progressbar').removeClass('progressbar-infinite');

                $('#modificadores-progressbar').addClass('progressbar');

                var el=$('#modificadores-progressbar');
                var pendientes=datosRevo.length;
                var hecho=0;
                var total=pendientes;
                for(n=0;n<datosRevo.length;n++){

                   $('#modificadores-progressbar-txt').html('Categoria Modificador:'+datosRevo[n]['nombre']); 
                    var server=servidor+'admin/includes/syncguarda.php';



$.ajax({
    url: server,
    data:{tipo:'catmod',datosRevo:datosRevo[n]},
    method: "post",
    dataType:"json",
    success: function(data){    
        var obj=Object(data);
        if (obj.valid==true){
            pendientes--;
            hecho++;
            app.progressbar.set('#modificadores-progressbar', (hecho*100/total), 5);
            if (pendientes==0){
                syncGruMod();
                //$('.sync-button').prop('disabled', false);
                //$('#modificadores-progressbar').hide();
                //$('#modificadores-progressbar-txt').html('Resultado Productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
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
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });                
    
}

function syncGruMod(){
    $('#modificadores-progressbar-txt').html('Leyendo Grupos de Modificadores de Revo ..');
    $('#modificadores-progressbar').removeClass('progressbar');
    $('#modificadores-progressbar').addClass('progressbar-infinite');
    //console.log('Gru Modificadores');
    var server=servidor+'admin/includes/sync.php';   
    $.ajax({
        url: server,
        data:{ tipo:'grumod'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                var datosRevo=obj.datosRevo;
                $('#modificadores-progressbar').removeClass('progressbar-infinite');

                $('#modificadores-progressbar').addClass('progressbar');

                var el=$('#modificadores-progressbar');
                var pendientes=datosRevo.length;
                var hecho=0;
                var total=pendientes;
                for(n=0;n<datosRevo.length;n++){

                   $('#modificadores-progressbar-txt').html('Grupo Modificador:'+datosRevo[n]['nombre']); 
                    var server=servidor+'admin/includes/syncguarda.php';



$.ajax({
    url: server,
    data:{tipo:'grumod',datosRevo:datosRevo[n]},
    method: "post",
    dataType:"json",
    success: function(data){    
        var obj=Object(data);
        if (obj.valid==true){
            pendientes--;
            hecho++;
            app.progressbar.set('#modificadores-progressbar', (hecho*100/total), 5);
            if (pendientes==0){
                syncPivMod();
                //$('.sync-button').prop('disabled', false);
                //$('#modificadores-progressbar').hide();
                //$('#modificadores-progressbar-txt').html('Resultado Productos: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncproductos.txt\')">Ver log</a>');
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
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });                
    
}

function syncPivMod(){
    $('#modificadores-progressbar-txt').html('Leyendo Pivots de Modificadores de Revo ..');
    $('#modificadores-progressbar').removeClass('progressbar');
    $('#modificadores-progressbar').addClass('progressbar-infinite');
    //console.log('Pivot Modificadores');
    var server=servidor+'admin/includes/sync.php';   
    $.ajax({
        url: server,
        data:{ tipo:'pivmod'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                var datosRevo=obj.datosRevo;
                $('#modificadores-progressbar').removeClass('progressbar-infinite');

                $('#modificadores-progressbar').addClass('progressbar');

                var el=$('#modificadores-progressbar');
                var pendientes=datosRevo.length;
                var hecho=0;
                var total=pendientes;

                var grupo=new Array();
                var categoria=new Array();
                var gru=0;
                var j=-1;

                //console.log(obj);
                for(n=0;n<datosRevo.length;n++){ 
                    if (gru!=datosRevo[n]['group_id']){
                        gru=datosRevo[n]['group_id'];
                        j=j+1;
                        grupo[j]=datosRevo[n]['group_id'];
                        categoria[j]='';
                    }
                    categoria[j]+=datosRevo[n]['category_id']+',';
                }
                for(n=0;n<grupo.length;n++){ 
                    categoria[n] = categoria[n].substring(0, categoria[n].length - 1);
                }
                
                var pendientes=grupo.length;
                var hecho=0;
                var total=pendientes;
                
                for(n=0;n<grupo.length;n++){

                   $('#modificadores-progressbar-txt').html('Pivot:'+grupo[n]); 
                    var server=servidor+'admin/includes/syncguarda.php';


$.ajax({
    url: server,
    data:{tipo:'pivmod',id:grupo[n],categoria:categoria[n]},
    method: "post",
    dataType:"json",
    success: function(data){    
        var obj=Object(data);
        if (obj.valid==true){
            pendientes--;
            hecho++;
            
            app.progressbar.set('#modificadores-progressbar', (hecho*100/total), 5);
            if (pendientes==0){
                
                //syncGruMod();
                $('.sync-button').prop('disabled', false);
                $('#modificadores-progressbar').hide();
                $('#modificadores-progressbar-txt').html('Resultado Modificadores: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncmodificadores.txt\')">Ver log</a>');
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
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });                
    
}

function syncMenus() {
    $('#li-menus-progressbar').show();
    $('#li-menus-progress').show();
    $('#menus-progressbar-txt').html('Leyendo Categoria Menú de Revo ..');
    $('.sync-button').prop('disabled', true);
    //console.log('Modificadores');

    var server=servidor+'admin/includes/sync.php';
    $.ajax({
        url: server,
        data:{ tipo:'menuMenuCategories'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                var datosRevo=obj.datosRevo;
                $('#menus-progressbar').removeClass('progressbar-infinite');

                $('#menus-progressbar').addClass('progressbar');

                var el=$('#menus-progressbar');
                var pendientes=datosRevo.length;
                var hecho=0;
                var total=pendientes;


                for(n=0;n<datosRevo.length;n++){

                   $('#menus-progressbar-txt').html('Categoria Menú:'+datosRevo[n]['nombre']); 
                    var server=servidor+'admin/includes/syncguarda.php';

$.ajax({
    url: server,
    data:{tipo:'menuMenuCategories',datosRevo:datosRevo[n]},
    method: "post",
    dataType:"json",
    success: function(data){    
        var obj=Object(data);
        if (obj.valid==true){
            pendientes--;
            hecho++;
            app.progressbar.set('#menus-progressbar', (hecho*100/total), 5);
            if (pendientes==0){
                syncMenuItems();
               
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
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
     
}

function syncMenuItems(){
    $('#menus-progressbar-txt').html('Leyendo Menu Item de Revo ..');
    $('.sync-button').prop('disabled', true);
    //console.log('Modificadores');

    $('#menus-progressbar').addClass('progressbar-infinite');

    $('#menus-progressbar').removeClass('progressbar');
    var server=servidor+'admin/includes/sync.php';
    $.ajax({
        url: server,
        data:{ tipo:'menuMenuItemCategoryPivots'},
        method: "post",
        dataType:"json",
        success: function(data){    
            var obj=Object(data);
            if (obj.valid==true){
                var datosRevo=obj.datosRevo;
                $('#menus-progressbar').removeClass('progressbar-infinite');

                $('#menus-progressbar').addClass('progressbar');

                var el=$('#menus-progressbar');
                var pendientes=datosRevo.length;
                var hecho=0;
                var total=pendientes;


                for(n=0;n<datosRevo.length;n++){

                   $('#menus-progressbar-txt').html('Menú Item:'+datosRevo[n]['id']); 
                    var server=servidor+'admin/includes/syncguarda.php';

$.ajax({
    url: server,
    data:{tipo:'menuMenuItemCategoryPivots',datosRevo:datosRevo[n]},
    method: "post",
    dataType:"json",
    success: function(data){    
        var obj=Object(data);
        if (obj.valid==true){
            pendientes--;
            hecho++;
            app.progressbar.set('#menus-progressbar', (hecho*100/total), 5);
            if (pendientes==0){
                //syncMenuItems();
                $('.sync-button').prop('disabled', false);
                $('#menus-progressbar').hide();
                $('#menus-progressbar-txt').html('Resultado Menús: <a href="#" onclick="window.open(\''+servidor+'admin/includes/syncmenuMenuCategories.txt\')">Ver log</a>');
               
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
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });
    
}
