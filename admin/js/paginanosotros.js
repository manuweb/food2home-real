paginanosotros(); // borrar
//navegar('#view-setting-inicio');
var textEditor;

function paginanosotros() {
$('#titulo-nosotros').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Página Nosotros<span id="button-guardar-nosotros" class="button button-fill float-right" style="display:none;">Guardar</span><span onclick="editanosotros();" id="add-bloque-nosotros" class="button button-fill float-right" >Nuevo</span>');
   

    //var txt='<ul id="noso">';
    var server=servidor+'admin/includes/leenosotros.php';  
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{foo:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var tipo=obj.tipo;
                var texto=obj.texto;
                var nombre=obj.nombre;
                var id=obj.id;
                var activo_web=obj.activo_web;
                var activo_app=obj.activo_app;
                var chk_web="";
                var chk_app="";
                var tip="";
                var txt='<div class="grid grid-cols-5"><div style="width: 40%;"><b>Nombre</b></div><div style="width: 10%;"><b>Web/App</b></div><div style="width: 15%;"><b>Tipo</b></div><div style="width: 10%;"><b>Editar</b></div><div style="width: 10%;"><b>Borrar</b></div></div>';
                 txt+='<div class="list sortable sortable-opposite list-outline-ios list-dividers-ios sortable-enabled" id="lista-nosotros">'+
                    '<ul id="noso">';
                for (x=0;x<id.length;x++){
                    chk_web="";
                    chk_app="";
                    tip="";
                    if (activo_web[x]==1){
                        chk_web='checked';
                    }
                    if (activo_app[x]==1){
                        chk_app='checked';
                    }
                    if (tipo[x]==1){
                        tip='txt';
                    }
                    if (tipo[x]==2){
                        tip='js';
                    }
                    if (tipo[x]==3){
                        tip='css';
                    }
                    if (tipo[x]==4){
                        tip='Imagenes';
                    }
                    txt+=
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="grid grid-cols-6 grid-gap item-title" style="width:100%;">'+
                                    '<div class="col-40">'+nombre[x]+'</div>'+
                                    '<div class="col-10"><label class="checkbox"><input type="checkbox" name="activo_web_'+id[x]+'" data-id="'+id[x]+'" '+chk_web+' onclick="cambiaInicioActivoWeb(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    //'<div class="col-10"><label class="checkbox"><input type="checkbox" name="activo_app_'+id[x]+'" data-id="'+id[x]+'" '+chk_app+' onclick="cambiaInicioActivoApp(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    '<div class="col-15">'+tip+'</div>'+
                                    '<div class="col-10" onclick="editanosotros(\''+id[x]+'\',\''+nombre[x]+'\',\''+tipo[x]+'\');"><i class="f7-icons" >pencil</i></div>'+
                                    '<div class="col-10" onclick="borranosotros(\''+id[x]+'\',\''+nombre[x]+'\',\''+tipo[x]+'\');"><i class="f7-icons" >trash</i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';   
                    
                }    
                txt=txt+
                    '</ul>'+
                '</div>';
                

                $('#nosotros-page').html(txt);  
                
                //document.getElementById("add-bloque-nosotros").setAttribute("data-orden", x);
                
                
                $('#button-guardar-nosotros').on('click', function () {
                    $('#button-guardar-nosotros').hide();
                    $('#add-bloque-nosotros').show();
                  
                    var server=servidor+'admin/includes/ordenpaginanosotros.php';          
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{grupos:aGrupos},
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
                var aGrupos=new Array();
                app.sortable.enable($('#lista-nosotros'));

                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar-nosotros').show();
                   $('#add-bloque-nosotros').hide();
                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;
                    
                    $("#noso li").each(function(){
                        aGrupos[n]=$(this).attr('data');
                        
                        n++;
                    });
                    
                    
                });
                
                

            }
            else{
                app.dialog.alert('No se pudo leer la página nosotros');
            }
        }
    });  

}

function cambiaNosotrosActivoWeb(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivowebnosotros.php';
    app.request.postJSON(server, { id:id, valor:valor}, 
        function (data) {
            //app.dialog.close();
            var obj=Object(data);
            if (obj.valid==true){  
                //document.getElementById('safari_window').contentWindow.location.reload();   
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        },
        function (xhr, status) {
    });         
}

function borranosotros(id,nombre='',tipo=1){
    app.dialog.confirm('¿Desea Borrar <br><b>'+nombre+'</b>?', function () {
        var server=servidor+'admin/includes/borrardenosotros.php';
        
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{id:id},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){  
                    /*
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
                    var server=servidor+'admin/includes/ordenpaginanosotros.php';  
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{grupos:aGrupos},
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                //document.getElementById('safari_window').contentWindow.location.reload(); 
                            }
                            else{
                                console.log('ERROR');
                            }
                        }
                     });
                    
                    */
                    paginanosotros();
                    //document.getElementById('safari_window').contentWindow.location.reload();   
                }
                else{
                    app.dialog.alert('No se pudo Borrar');
                }
            }
        });
            
    });
}

function cambiaNosotrosActivoApp(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivoappnosotros.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{ id:id, valor:valor},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){  
                document.getElementById('safari_window').contentWindow.location.reload();   
            }
            else{
                app.dialog.alert('No se pudo cambiar');
            }
        }
    });
    
          
}

function editanosotros(id=0,nombre='',tipo=1){
    var tip="";
    if (tipo==1){
        tip='txt';
    }
    if (tipo==2){
        tip='js';
    }
    if (tipo==3){
        tip='css';
    } 
    if (tipo==4){
        tip='Imagenes';
    } 
    
  var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Contenido Página Nosotros</div>'+
                '<form>'+
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
                            '<div class="item-title item-label">Tipo</div>'+
                            '<div class="item-input-wrap" id="tipo-bloque">'+
                              '<input type="text" name="tipo" placeholder="Tipo" disabled value="'+tip+'"/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Contenido</div>'+
                            '<div class="item-input-wrap" id="editor-contenido">'+
                              
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                    '</ul>'+   
                '</div>'+


         
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class="col"><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                    '<div class="col"><a class="button button-fill" data-id="'+id+'" onclick="guardanosotros(this);" href="#">Guardar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'  ,
       on: {
            open: function (popup) {
              //<textarea placeholder="Bio"></textarea>  id="editor-contenido"
                if (id!=0){
                    var server=servidor+'admin/includes/leenosotros.php';   
                    
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{ id:id},
                        success: function(data){
                            var obj=Object(data);
                            
                            if (obj.valid==true){          

                                var txteditor="";
                                if (tipo=='1') {
                                    // Texto
                                    txteditor=''+
                                        '<div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">'+
                                            '<div class="text-editor-content" contenteditable></div>'+
                                        '</div>'   ;        
                                    $('#editor-contenido').html(txteditor);
                                     textEditor = app.textEditor.create({
                                      el: '#my-text-editor',
                                    })
                                    textEditor.setValue(obj.texto[0]);
                                }
                                
                                if (tipo=='2') {
                                    // JS
                                    txteditor='<input type="text" name="texto" placeholder="Archivo" value="'+obj.texto[0]+'"/>';
                                    $('#editor-contenido').html(txteditor);
                                }
                                if (tipo=='3') {
                                    // CSS
                                    txteditor='<input type="text" name="texto" placeholder="Archivo" value="'+obj.texto[0]+'"/>';
                                    $('#editor-contenido').html(txteditor);
                                }
                                if (tipo=='4') {
                                    // imagenes
                                    var texto=obj.texto[0];
                                    
                                    var separados= new Array();
                                    var src= new Array();
                                    var prod= new Array();
                                    var contenido=texto.split("||");
                                    for (x=0;x<contenido.length;x++){
                                        separados[x]=contenido[x].split("##");
 
                                    }
                                    for (x=0;x<separados.length;x++){
    
                                        src[x]=separados[x][0];
                                        prod[x]=separados[x][1];
                                    }
                                    
                                    
                                    var txt=''+   

                                     '<div class="row">'+
                                         '<div class="col-50">'+
                                            '<img name="imagen" id="visor-image1" src="../webapp/img/upload/'+src[0]+'" width="50%" height="auto"/>  '+

                                            '<input type="text" name="articulo1" id="art1" placeholder="'+prod[0]+'" disabled/>'+
                                         '</div>'+
                                         '<div class="col-50">'+
                                            '<img name="imagen" id="visor-image2" src="../webapp/img/upload/'+src[1]+'" width="50%" height="auto"/>  '+

                                            '<input type="text" name="articulo2" id="art2" placeholder="'+prod[1]+'" disabled/>'+ 
                                         '</div>'+
                                                 '<div class="row">'+
                                         '<div class="col-50">'+
                                            '<img name="imagen" id="visor-image3" src="../webapp/img/upload/'+src[2]+'"width="50%" height="auto"/>  '+

                                            '<input type="text" name="articulo3" id="art3" placeholder="'+prod[2]+'" disabled/>'+
                                         '</div>'+
                                         '<div class="col-50">'+
                                            '<img name="imagen" id="visor-image4" src="../webapp/img/upload/'+src[3]+'" width="50%" height="auto"/>  '+

                                            '<input type="text" name="articulo4" id="art4" placeholder="'+prod[4]+'" disabled/>'+   
                                         '</div>'+
                                    ' </div> ' ;  
                                     $('#editor-contenido').html(txt);
                                    
                                }

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
                    
                    
                }
                else {
                    // nuevo id="tipo-bloque"
                    
                    var tipobloque='<select name="tipo" onchange="cambiatipobloquenosotros(this);">'+
                            '<option value="1" selected>Texto</option>'+
                            '<option value="2">Js</option>'+
                            '<option value="3">css</option>'+
                            '<option value="4">Imagenes</option>'+
                            '</select>';
                    $('#tipo-bloque').html(tipobloque);
                    txteditor=''+
                        '<div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">'+
                            '<div class="text-editor-content" contenteditable></div>'+
                        '</div>'   ;        
                    $('#editor-contenido').html(txteditor);
                     textEditor = app.textEditor.create({
                      el: '#my-text-editor',
                    })
                    textEditor.setValue("");
                    
                    
                    
                }

          },
        }
    });  
    
    dynamicPopup.open(); 
}

function cambiatipobloquenosotros(e) {
    var tipo=e.value;
    var txteditor='';
    if (tipo=='1') {
        txteditor=''+
            '<div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">'+
                '<div class="text-editor-content" contenteditable></div>'+
            '</div>'   ;        
        $('#editor-contenido').html(txteditor);
         textEditor = app.textEditor.create({
          el: '#my-text-editor',
        })
    }

    if ((tipo=='2')||(tipo=='3')) {
        txteditor='<input type="text" name="texto" placeholder="Archivo" value=""/>';
        $('#editor-contenido').html(txteditor);
    }
    if (tipo=='4') {
        var txt=''+   
'<div class="row">'+
         '<div class="col-50">'+
            '<img name="imagen" id="visor-image1" src="" width="50%" height="auto"/>  '+
            '<input type="file" name="image[]" id="image1" onchange="loadFileImg(event,\'#visor-image1\');"/><br>'+
            '<input type="text" name="articulo1" id="art1" placeholder="Producto" />'+
         '</div>'+
         '<div class="col-50">'+
            '<img name="imagen" id="visor-image2" src="" width="50%" height="auto"/>  '+
            '<input type="file" name="image[]" id="image2" onchange="loadFileImg(event,\'#visor-image2\');"/><br>'+
            '<input type="text" name="articulo2" id="art2" placeholder="Producto" />'+       
         '</div>'+
                 '<div class="row">'+
         '<div class="col-50">'+
            '<img name="imagen" id="visor-image3" src="" width="50%" height="auto"/>  '+
            '<input type="file" name="image[]" id="image3" onchange="loadFileImg(event,\'#visor-image3\');"/><br>'+
            '<input type="text" name="articulo3" id="art3" placeholder="Producto" />'+
         '</div>'+
         '<div class="col-50">'+
            '<img name="imagen" id="visor-image4" src="" width="50%" height="auto"/>  '+
            '<input type="file" name="image[]" id="image4" onchange="loadFileImg(event,\'#visor-image4\');"/><br>'+
            '<input type="text" name="articulo4" id="art4" placeholder="Producto" />'+       
         '</div>'+
    ' </div> ' ;   


  //'<input type="file" id="upload_file" name="upload_file[]" onchange="preview_image();" multiple/>'+
 

        $('#editor-contenido').html(txt);
        
       
        
        
        //document.getElementById('filesToUpload').addEventListener('change', fileSelect, false);
    }

    
}

/*
var loadFileimagen = function(event,e) {
    var img="";
    //alert(e.id);
    var reader = new FileReader();

    reader.onload = function(){
        
      var output = document.getElementById('visor-'+e.id);
      output.src = reader.result;
        $("#"+e.id).show();   
    };
    reader.readAsDataURL(event.target.files[0]);
    img=event.target.files[0];
    
    
};

*/
    
function guardanosotros(e) {
    var id=e.getAttribute('data-id');
    var nombre=$('input[name=nombre]').val();
    var texto;
    var tipo=$('input[name=tipo]').val();
   
    var orden= document.getElementById("add-bloque-inicio").getAttribute('data-orden');
    var FData = new FormData();
    FData.append("id", id);
    FData.append("nombre", nombre);
   
    FData.append("orden", orden);
    
    if (id==0){
        tipo=$('select[name=tipo]').val();
        FData.append("tipo", tipo);
         
        if (tipo==1 || tipo==5){
            texto=textEditor.getValue();
            FData.append("texto", texto);
        }
        if ((tipo==2)||(tipo==3)){
            texto=$('input[name=texto]').val();
            FData.append("texto", texto);
        }
        if (tipo==4){
            texto="";
            if (document.getElementById('image1').files[0]!=null){
                 FData.append("files[]", document.getElementById('image1').files[0]);

                texto+=$('#art1').val()+'||';
                
            }
            if (document.getElementById('image2').files[0]!=null){
                 FData.append("files[]", document.getElementById('image2').files[0]);

                texto+=$('#art2').val()+'||';
            }
            if (document.getElementById('image3').files[0]!=null){
                 FData.append("files[]", document.getElementById('image3').files[0]);

                texto+=$('#art3').val()+'||';
            }           
            if (document.getElementById('image4').files[0]!=null){
                 FData.append("files[]", document.getElementById('image4').files[0]);

                texto+=$('#art4').val()+'||';
            }              
            FData.append("texto", texto);
             
        }
    }
    else {

        if (tipo=='txt'){
            texto=textEditor.getValue();
            FData.append("texto", texto);
        }
        if ((tipo=='Js')||(tipo=='css')){
            texto=$('input[name=texto]').val();
            FData.append("texto", texto);
        }
    }
    var errores="";
    if (nombre==""){
        errores+=' Nombre,';
    }
    if (texto==""){
        if (tipo==4){
            errores+=" alguna imagen."
        }else{
            errores+=" algún texto."
        }
        
        errores+=" algún texto."
    }
    if (errores!="") {
        app.dialog.alert('Debe completar'+errores);
    }
    else {
       // alert(texto);
        var server=servidor+'admin/includes/guardanosotros.php'; 
        $.ajax({
            type: "POST",
            url: server,
            data: FData,
            cache: false, 
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            success: function(data){
                var obj= JSON.parse(data);
                if (obj.valid==true){
                    muestraMensaje('Página nosotros guardada correctamente','Datos Guardados');
                   paginanosotros(); 
                }
                else{
                    muestraMensaje('No se pudo guardar Página nosostros','Error');


                }
                
            },
            error: function (xhr, ajaxOptions, thrownError){
                
                if (xhr.status=='200'){
                    paginanosotros();
                }
                else {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
                
                
            }
        
        });  
        
        
        app.popup.close(); 
        
    }

    
}
