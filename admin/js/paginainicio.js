var textEditor;
var colorprimario='';
var colorsecundario='';
var tot_elem_inicio=0;
var imagensCatProd= new Array();
paginainicio();
function paginainicio() {
$('#titulo-inicio').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> Página de inicio<span id="button-guardar-inicio" class="button button-fill float-right" style="display:none;">Guardar</span><span onclick="editainicio();" id="add-bloque-inicio" class="button button-fill float-right" >Nuevo</span>');

    //var txt='<ul id="inic">';
    var server=servidor+'admin/includes/leeinicio.php';         $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{foo:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                colorprimario=obj.primario.replace("#", "");
                colorsecundario=obj.secundario.replace("#", "");
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
                 txt+='<div class="list sortable sortable-opposite list-outline-ios list-dividers-ios sortable-enabled" id="lista-inicio">'+
                    '<ul id="inic">';
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
                    if (tipo[x]==5){
                        tip='Txt Scroll';
                    }
                    
                    txt+=
                    '<li data="'+id[x]+'">'+
                        '<div class="item-content">'+
                            '<div class="item-inner">'+
                                '<div class="grid grid-cols-5 grid-gap item-title" style="width:100%;">'+
                                    '<div class="col-40">'+nombre[x]+'</div>'+
                                    '<div class="col-10"><label class="checkbox"><input type="checkbox" name="activo_web_'+id[x]+'" data-id="'+id[x]+'" '+chk_web+' onclick="cambiaInicioActivoWeb(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    //'<div class="col-10"><label class="checkbox"><input type="checkbox" name="activo_app_'+id[x]+'" data-id="'+id[x]+'" '+chk_app+' onclick="cambiaInicioActivoApp(this);"/><i class="icon-checkbox"></i></label></div>'+
                                    '<div class="col-15">'+tip+'</div>'+
                                    '<div class="col-10" onclick="editainicio(\''+id[x]+'\',\''+nombre[x]+'\',\''+tipo[x]+'\','+x+');"><i class="f7-icons" >pencil</i></div>'+
                                    '<div class="col-10" onclick="borrainicio(\''+id[x]+'\',\''+nombre[x]+'\',\''+tipo[x]+'\');"><i class="f7-icons" >trash</i></div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                        '<div class="sortable-handler" ></div>'+
                    '</li>';          
                    
                    
                }    
                txt=txt+
                    '</ul>'+
                '</div>';
                

                $('#inicio-page').html(txt);  
               
                document.getElementById("add-bloque-inicio").setAttribute("data-orden", x);
                tot_elem_inicio=x;
                
                
                $('#button-guardar-inicio').on('click', function () {
                    $('#button-guardar-inicio').hide();
                    $('#add-bloque-inicio').show();
                    var server=servidor+'admin/includes/ordenpaginainicio.php';        
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
                        },
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });

                    
                });
                
                var aGrupos=new Array();
                app.sortable.enable($('#lista-inicio'));
                
                app.on('sortableSort', function (listEl, indexes) {
                    $('#button-guardar-inicio').show();
                   $('#add-bloque-inicio').hide();
                    //console.log(indexes['from']+'->'+indexes['to']);
                    var n=0;
                    
                    $("#inic li").each(function(){
                        aGrupos[n]=$(this).attr('data');
                        
                        n++;
                    });
                    
                    
                });
                
                

            }
            else{
                app.dialog.alert('No se pudo leer la página de inicio');
            }
        },

        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });       
    

}

function cambiaInicioActivoWeb(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivowebinicio.php';
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

function borrainicio(id,nombre='',tipo=1){
    app.dialog.confirm('¿Desea Borrar <br><b>'+nombre+'</b>?', function () {
        var server=servidor+'admin/includes/borrardeinicio.php';
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
                    
                    app.preloader.show();
                    var server=servidor+'admin/includes/ordenpaginainicio.php';    
                    $.ajax({
                        type: "POST",
                        url: server,
                        dataType:"json",
                        data:{grupos:aGrupos},
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                document.getElementById('safari_window').contentWindow.location.reload(); 
                            }
                            else{
                                console.log('ERROR');
                            }
                        }
                     });
                    
                    */
                    paginainicio();
                    //document.getElementById('safari_window').contentWindow.location.reload();   
                }
                else{
                    app.dialog.alert('No se pudo Borrar');
                }
            }
            ,
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });
  
    });
}

function cambiaInicioActivoApp(e) {
    var id=e.getAttribute('data-id');
    var estado=$(e).prop('checked');
    var valor=0;
    if (estado){
        valor=1;
    }
    var server=servidor+'admin/includes/cambiactivoappinicio.php';
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

function editainicio(id=0,nombre='',tipo=1,orden=tot_elem_inicio){
    $('#titulo-inicio').html('<a href="javascript:navegar(\'#view-setting\');" class="link">Ajustes</a> -> <a onclick="paginainicio();">Página de inicio</a>');
    $('#add-bloque-inicio').hide();
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
        imagensCatProd= new Array();
    }
    if (tipo==5){
        tip='Txt Scroll';
    } 
    var txt='';
    txt+=
        '<div class="title">Contenido Página de inicio</div>'+
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
                    '</ul>'+   
                '</div>'+
      '<div>'+
        '<div class="item-title item-label">Contenido</div>'+
        '<div id="editor-contenido">' +               
        '</div>'+
      '</div>'+

    '</form>'+
    '<div class="block block-strong grid grid-cols-2 grid-gap">'+
        '<div class="col"><a class="button button-fill button-cancelar" href="#" onclick="paginainicio();">Cancelar</a></div>'+
        '<div class="col"><a class="button button-fill" data-id="'+id+'" data-orden="'+orden+'" onclick="guardainicio(this);" href="#">Guardar</a</div>'+
    '</div>';
    
    $('#inicio-page').html(txt);
  
    if (id!=0){
        var server=servidor+'admin/includes/leeinicio.php';   
        $.ajax({
            type: "POST",
            url: server,
            dataType:"json",
            data:{ id:id},
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){          

                    var txteditor="";
                    if (tipo=='1' || tipo=='5') {
                        // Texto
                        txteditor=''+
                            '<div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">'+
                                '<div class="text-editor-content" contenteditable></div>'+
                            '</div>'   ;        
                            $('#editor-contenido').html(txteditor);
                             textEditor = app.textEditor.create({
                              el: '#my-text-editor',
                                 customButtons: {
                                    color: {
                                          // button html content
                                          content: '<i class="icon f7-icons">eyedropper</i>',
                                          // button click handler
                                          onClick(event, buttonEl) {
                                              app.dialog.create({
                                                  title: 'Color',
                                                  text: 'Seleccione color',
                                                  buttons: [
                                                    {
                                                      text: 'Primario',
                                                      onClick: function () {
                                                            document.execCommand('foreColor', false, colorprimario);
                                                        }
                                                    },
                                                      {
                                                      text: 'Secundario',
                                                      onClick: function () {
                                                            document.execCommand('foreColor', false, colorsecundario);
                                                        }
                                                    }
                                                  ],
                                                  verticalButtons: true,
                                                }).open();

                                          }
                                        },
                                 },
                                      // now we use custom button id "hr" to add it to buttons
                                      buttons: [
                                          ['bold', 'italic', 'underline', 'strikeThrough'],
                                          ['paragraph', 'h1', 'h2', 'h3'],
                                          ['indent', 'outdent'],
                                          ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],['color']
                                        ]

                             });
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
    var nom= new Array();
    var CatProd= new Array();
    var contenido=texto.split("||");
    for (var x=0;x<(contenido.length-1);x++){
        separados[x]=contenido[x].split("##");

    }
    for (var x=0;x<separados.length;x++){
        if (separados[x][0]==''){
            prod[x]='';
            nom[x]='';
            CatProd[x]='N';
        }
        else {
           var catprodS=separados[x][1].split("===");
            src[x]=separados[x][0];

            prod[x]=catprodS[1];
            nom[x]=catprodS[2];
            CatProd[x]=catprodS[0];
        }
        imagensCatProd.push({imagen:src[x],CatProd:CatProd[x],id:prod[x],nombre:nom[x]});
    }


var txt=''+ 
    '<div style="margin-top: 20px;">'+
        '<img name="imagen" id="visor-image1" src="" width="35%" height="auto"/>  '+
        '<input type="file" name="image[]" id="image-inicio1" onchange="loadFileImg(event,\'#visor-image1\');"/><span class="imageninfo">&nbsp;&nbsp;(JPG o PNG)</span>'+
    '</div>'+
'<div id="cat-page" class="block block-strong" style="margin-top: 0;margin-bottom: 0;"">'+
'<div class="list sortable sortable-opposite sortable-enabled" id="lista-cate">'+
'</div>'+
'</div>';  
$('#editor-contenido').html(txt);
var txt2='<ul id="inicRecGru">';
var nomCatProd='';
for(var x=0;x<imagensCatProd.length;x++){
    nomCatProd='Nada';
    if (imagensCatProd[x]['CatProd']=='P'){
        nomCatProd='producto';
    }
    if (imagensCatProd[x]['CatProd']=='C'){
        nomCatProd='categoría';
    }
    if (nomCatProd=='Nada'){
        imagensCatProd[x]['nombre']='';
    }
    
    
txt2+=
'<li data="'+x+'" id="li-recomienda-'+x+'" data-tipo="'+nomCatProd+'" data-id="'+imagensCatProd[x]['id']+'" data-nombre="'+imagensCatProd[x]['nombre']+'" data-imagen="'+imagensCatProd[x]['imagen']+'">'+
'<div class="item-content" >'+
    '<div class="item-media">'+
    '<img src="../webapp/img/upload/'+imagensCatProd[x]['imagen']+'" width="80px" height="auto">'+
    '</div>'+
    '<div class="item-inner">'+
        '<div class="item-title">&nbsp;'+nomCatProd+': '+imagensCatProd[x]['nombre']+'</div>'+
        '<div class="item-after" onclick="borraRecomienda('+x+');"><i class="f7-icons" >trash</i></div>'+
    '</div>'+
'</div>'+
'<div class="sortable-handler" ></div>'+
'</li>';
}
txt2+='</ul>';
addRecomendado(x);
$("#lista-cate").html(txt2);




                    }

                }
                else{
                    console.log('ERROR');
                }

            }
        });                   


    }
    else {
        // nuevo id="tipo-bloque"

        var tipobloque='<select name="tipo" onchange="cambiatipobloque(this);">'+
                '<option value="1" selected>Texto</option>'+
                '<option value="2">Js</option>'+
                '<option value="3">css</option>'+
                '<option value="4">Imagenes</option>'+
                '<option value="5">Txt Scroll</option>'+
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

}
function borraRecomienda(x){   
    $('#li-recomienda-'+x).remove(); 
}
function cambiatipobloque(e) {
    var tipo=e.value;
    var txteditor='';
    if (tipo=='1' || tipo=='5') {
        txteditor=''+
            '<div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">'+
                '<div class="text-editor-content" contenteditable></div>'+
            '</div>'   ;        
        $('#editor-contenido').html(txteditor);
        textEditor = app.textEditor.create({
          el: '#my-text-editor',
             customButtons: {
                color: {
                      // button html content
                      content: '<i class="icon f7-icons">eyedropper</i>',
                      // button click handler
                      onClick(event, buttonEl) {
                          app.dialog.create({
                              title: 'Color',
                              text: 'Seleccione color',
                              buttons: [
                                {
                                  text: 'Primario',
                                  onClick: function () {
                                        document.execCommand('foreColor', false, colorprimario);
                                    }
                                },
                                  {
                                  text: 'Secundario',
                                  onClick: function () {
                                        document.execCommand('foreColor', false, colorsecundario);
                                    }
                                }
                              ],
                              verticalButtons: true,
                            }).open();
                        
                      }
                    },
             },
                  // now we use custom button id "hr" to add it to buttons
                  buttons: [
                      ['bold', 'italic', 'underline', 'strikeThrough'],
                      
                      ['paragraph', 'h1', 'h2', 'h3'],
                      
                      ['indent', 'outdent'],
                      ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],['color']
                    ]
                
         });
    }

    if ((tipo=='2')||(tipo=='3')) {
        txteditor='<input type="text" name="texto" placeholder="Archivo" value=""/>';
        $('#editor-contenido').html(txteditor);
    }

    if (tipo=='4') {
        var txt=''+   
            '<div style="margin-top: 20px;">'+
        '<img name="imagen" id="visor-image1" src="" width="25%" height="auto"/>  '+
        '<input type="file" name="image[]" id="image-inicio1" onchange="loadFileImg(event,\'#visor-image1\');"/><span class="imageninfo">&nbsp;&nbsp;(JPG o PNG)'+
    '</div>'+
'<div id="cat-page" class="block block-strong" style="margin-top: 0;margin-bottom: 0;"">'+
            '<div class="list sortable sortable-opposite sortable-enabled" id="lista-cate">'+
            '</div>'+
        '</div>';  
        imagensCatProd= new Array();
       


  //'<input type="file" id="upload_file" name="upload_file[]" onchange="preview_image();" multiple/>'+
 

        $('#editor-contenido').html(txt);
        addRecomendado(0);
       
        
        
        //document.getElementById('filesToUpload').addEventListener('change', fileSelect, false);
    }

    
}

function guardainicio(e) {
    var id=e.getAttribute('data-id');
    var nombre=$('input[name=nombre]').val();
    console.log('nombre:'+nombre);
    var texto;
    var tipo=$('input[name=tipo]').val();
   
    var orden= e.getAttribute('data-orden');
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
            var Catprod='N';
            $("#inicRecGru li").each(function(){
                Catprod='N';
                tipo=$(this).attr('data-tipo');
                id=$(this).attr('data-id');
                imagen=$(this).attr('data-imagen');
                nombre=$(this).attr('data-nombre');
                if (tipo=='Nada'){
                    nombre='';
                }
                if (tipo=='categoría'){
                    Catprod='C'
                }
                if (tipo=='producto'){
                    Catprod='P'
                }
                texto+=imagen+'##';
                if (Catprod!='N'){
                    texto+=Catprod+'==='+id+'==='+nombre;
                }
                texto+='||';
            });
            if (texto!=''){
                //texto=texto.substr(0,-2);
                
            }
            console.log(texto);
            return;
            
            
            
            /*
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
            */
            
            FData.append("texto", texto);
             
        }
    }
    else {

        if (tipo=='txt' || tipo=='Txt Scroll'){
            texto=textEditor.getValue();
            FData.append("texto", texto);
        }
        if ((tipo=='Js')||(tipo=='css')){
            texto=$('input[name=texto]').val();
            FData.append("texto", texto);
        }
        if (tipo=='Imagenes'){
            texto="";
            var Catprod='N';
            $("#inicRecGru li").each(function(){
                Catprod='N';
                tipo=$(this).attr('data-tipo');
                id=$(this).attr('data-id');
                imagen=$(this).attr('data-imagen');
                nombre=$(this).attr('data-nombre');
                if (tipo=='Nada'){
                    nombre='';
                }
                if (tipo=='categoría'){
                    Catprod='C'
                }
                if (tipo=='producto'){
                    Catprod='P'
                }
                texto+=imagen+'##';
                if (Catprod!='N'){
                    texto+=Catprod+'==='+id+'==='+nombre;
                }
                texto+='||';
            });
            
            if (texto!=''){
                //texto=texto.substr(0,texto.length - 2);
                
            }
            FData.append("texto", texto);
            
            
        }
    }
    console.log(texto);
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
        var server=servidor+'admin/includes/guardainicio.php'; 
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
                    muestraMensaje('Página inicio guardada correctamente','Datos Guardados');
                   paginainicio(); 
                }
                else{
                    muestraMensaje('No se pudo guardar Página inicio','Error');


                }
                
                //paginainicio();
            },
            error: function (xhr, ajaxOptions, thrownError){
                
                if (xhr.status=='200'){
                    paginainicio();
                }
                else {
                    console.log(xhr.status);
                    console.log(thrownError);
                }
                
                
            }
        });
        
        app.popup.close(); 
        //paginainicio();
    }

    
}


function addRecomendado(x){

    txt='<from id="NUEVO-cateprod"><input type="hidden" name="categoriaproductoNUEVOid" id="categoriaproductoNuevoid" value="0">'+
        '<div class="list" id="NUEVO-cateprod">'+
        '<ul>'+
            '<li>'+
            '<a class="item-link smart-select smart-select-init">'+
                
              '<select name="tipo" id="linkdestacadoNuevo" >'+
            '<option value="N" selected>Nada</option>'+
                '<option value="C" >Categoría</option>'+
                '<option value="P">Producto</option>'+
                '</select>'+
                '<div class="item-content">'+
                    '<div class="item-inner">'+
                    '<div class="item-title">Categoría/Producto</div>'+
                    '<div class="item-after" id="cat-prod-sel-nuevo">Nada</div>'+
              '</div>'+
            '</div>'+
            '</a>'+
          '</li>'+ 
        '<li id="buscador-de-productos" style="display:none;">'+
            '<div class="item-content item-input">'+
                        '<div class="item-media busca-producto-categoria-nuevo">'+
                            '<i class="icon f7-icons">search</i>'+
                        '</div>'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Categoría/Producto</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="categoriaproductoNuevo" id="categoriaproductoNuevo" placeholder="Producto" value="" disabled/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
          '</li>'+
        '</ul>'+
        '</div></from><br>'+
        '<a href="#" onclick="addRecomendado2('+x+');" class="button button-fill" style="margin:auto;width:50%;">+ Añadir</a>';
    $("#cat-page").prepend(txt);
    $('.busca-producto-categoria-nuevo').on('click',function(){
        console.log('Buscar:'+$("#linkdestacadoNuevo").val());
        buscaproductoocategoria('categoriaproductoNuevo','linkdestacadoNuevo');
        
    });
    $("#linkdestacadoNuevo").change(function(){
    $('#categoriaproductoNuevoid').val($(this).val());
        $('#categoriaproductoNuevo').val("");
        if ($(this).val()=='N'){
            $('#buscador-de-productos').hide();
        }
        else {
            $('#buscador-de-productos').show();
        }
        
    });
}

function addRecomendado2(){
    var filename = $("#image-inicio1").val();

    var j=0;
    var tipo='';
    var id='';
    var imagen='';
    var nombre='';
    //si es null muestra un mensaje de error
    if(filename == ''){
        return;
    }
    var existe='no';
    //if ($('#categoriaproductoNuevo').val()!=''){
   var tip='Nada';
   if ($('#linkdestacadoNuevo').val()=='P'){
       tip='producto';
   } 
    if ($('#linkdestacadoNuevo').val()=='C'){
       tip='categoría';
   }


    var txt2='<ul id="inicRecGru">';


    
    
    
    $("#inicRecGru li").each(function(){
        tipo=$(this).attr('data-tipo');
        id=$(this).attr('data-id');
        imagen=$(this).attr('data-imagen');
        nombre=$(this).attr('data-nombre');
        if (tipo=='Nada'){
            nombre='';
        }
  
        txt2+=''+
        '<li data="'+j+'" id="li-recomienda-'+j+'" data-tipo="'+tipo+'" data-id="'+id+'" data-nombre="'+nombre+'" data-imagen="'+imagen+'">'+
            '<div class="item-content" >'+
        '<div class="item-media">'+
            '<img src="../webapp/img/upload/'+imagen+'" width="80px" height="auto">'+
            '</div>'+
                '<div class="item-inner">'+
                    '<div class="item-title">'+tipo+': '+nombre+'</div>'+
                    '<div class="item-after" onclick="borraRecomienda('+j+');"><i class="f7-icons" >trash</i></div>'+
                '</div>'+
            '</div>'+
            '<div class="sortable-handler" ></div>'+
        '</li>';
        j++;
    }); 
    var elnombre=$('#categoriaproductoNuevo').val();
    console.log(nombre);
    var FData = new FormData();

    FData.append('imagen',document.getElementById('image-inicio1').files[0]);  
    var server=servidor+'admin/includes/guardainicio.php'; 
    $.ajax({
        type: "POST",
        url: server,
        data: FData,
        cache: false, 
        //dataType: 'application/json',
        enctype: 'multipart/form-data',
        contentType: false,
        processData: false,
        success: function (data){
            var obj= JSON.parse(data);
            if (obj.valid==true && obj.msg=='ok'){
                
                muestraMensaje('Imagen subida correctamente','Datos Guardados');
               txt2+=''+
                '<li data="'+j+'" id="li-recomienda-'+j+'" data-tipo="'+tip+'" data-id="'+$('#categoriaproductoNuevoid').val()+'" data-nombre="'+elnombre+'" data-imagen="'+obj.nombre+'">'+
                    '<div class="item-content" >'+
                        '<div class="item-media">'+
                        '<img src="../webapp/img/upload/'+obj.nombre+'" width="80px" height="auto">'+
                        '</div>'+
                        '<div class="item-inner">'+
                            '<div class="item-title">'+tip+': '+elnombre+'</div>'+
                            '<div class="item-after" onclick="borraRecomienda('+j+');"><i class="f7-icons" >trash</i></div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="sortable-handler" ></div>'+
                '</li>';
                 txt2+='</ul>';
                $("#lista-cate").html(txt2);
                //imagensCatProd.push({imagen:src[x],CatProd:CatProd[x],id:prod[x],nombre:nom[x]});
            }
            else{
                muestraMensaje('No se pudo subir imagen','Error');
                txt2+='</ul>';
                $("#lista-cate").html(txt2);

            }

            //paginainicio();
        },
        error: function (xhr, ajaxOptions, thrownError){
             txt2+='</ul>';
                $("#lista-cate").html(txt2);
            if (xhr.status=='200'){
               
                
                
            }
            else {
                console.log(xhr.status);
                console.log(thrownError);
            }


        }
    });
            // subir imagen
            
            
    
        
            
   
    $('#visor-image1').attr('src','');
    $('#visor-image1').hide();
    $("#image-inicio1").val('');
    $('#categoriaproductoNuevo').val('');
        
    
}

function buscaproductoocategoria(elemento='categoriaproducto',valor='linkdestacado',cat=0){
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
                var catprod=$('#'+valor).val(); 
              
                var server=servidor+'admin/includes/leeproductossearch.php';
                $.ajax({     
                    type: "POST",
                    url: server,
                    dataType: "json",
                    data: {catprod:catprod,cat:cat},
                    success: function(data){
                        var obj=Object(data);
                       var obj=Object(data);
                        if (obj.valid==true){
                            var txt='<ul>';
                            for (x=0;x<obj.id.length;x++){
                                 txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'" data-tipo="'+catprod+'" data-nombre="'+obj.nombre[x]+'" onclick="muestraproddestacado(this);" data-elemento="'+elemento+'">'+
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

function muestraproddestacado(e){
    var elem=e;
    $('#'+elem.dataset.elemento).val(elem.dataset.nombre);
    $('#'+elem.dataset.elemento+'id').val(elem.dataset.id);
    app.popup.close();
    
}
/*


                    <div class="text-editor text-editor-init" id="my-text-editor" data-placeholder="Escriba algo ...">
                        <div class="text-editor-content" contenteditable></div>
                    </div>               
                    <a href="#" onclick="alert(textEditor.getValue());">Mira</a>
*/