//
// Tipo push
//      1 - Normal
//      2 - Producto (2-XXXX)
//      3 - 

//navegar('#view-push');
//
//leepush(0);
function leepush(pagina=0) {
    var txt='';
    var server=servidor+'admin/includes/leepush.php';           $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{pagina:pagina},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var id=obj.id;
                var titulo=obj.titulo;
                var body=obj.body;
                var fecha=obj.fecha;
                var hora=obj.hora;
                var imagen=obj.imagen;
                var tipo=obj.tipo;
                var iok=obj.ios_ok;
                var iko=obj.ios_ko;
                var aok=obj.android_ok;
                var ako=obj.android_ko;
                var total=obj.total;
                var paginas=total/15;
                var resto=total%15;
                var pagina_actual=pagina;
                if (pagina_actual==0){
                    //pagina_actual=1;
                }
                if (resto>0){
                    paginas=Math.trunc(paginas)+1;
                }
                //alert (paginas);
                for (x=0;x<id.length;x++){
                    fecha[x]=fecha[x].substr(8,2)+'/'+fecha[x].substr(5,2)+'/'+fecha[x].substr(0,4);
                    var img=servidor+'img/no-imagen.png';
                    if (imagen[x]!=''){
                        img=servidor+'webapp/img/upload/'+imagen[x];
                    }
                    txt+=
                        '<div class="grid grid-cols-5 grid-gap" style="font-size:12px;">'+
                            '<div class="">'+titulo[x].substr(0,20)+'</div>'+
                            '<div class="">'+fecha[x]+'</div>'+
                            '<div class=""><img src="'+img+'" style="max-height:25px;max-width:25px;height:auto;width:auto;"></div>'+
                            '<div class="text-align-center">'+tipo[x]+'</div>'+
                            '<div class="" onclick="verpush(\''+id[x]+'\',\''+titulo[x]+'\',\''+body[x]+'\',\''+fecha[x]+'\',\''+hora[x]+'\',\''+tipo[x]+'\',\''+imagen[x]+'\',\''+iok[x]+'\',\''+iko[x]+'\',\''+aok[x]+'\',\''+ako[x]+'\');"><i class="f7-icons size-20" >eye</i></div>'+
                        '</div>';  
                    
                    
                } 
                
                var paginado="";
                var anterior="";
                var siguiente="";
                
                    if (paginas>1){
                        if (pagina_actual==0){
                            //pagina_actual=1;
                        }
                        
                        if (pagina_actual<(paginas-1)){
                            siguiente='<span class="link" onclick="leepush('+(pagina_actual+1)+');"> -  siguiente >> </span>';
                        }
                        if (pagina_actual>0){
                            anterior='<span class="link" onclick="leepush('+(pagina_actual-1)+');"> << anterior - </span>';
                        }
                        paginado='<p style="text-align:center;">'+anterior+' Pagina: <b>'+(pagina_actual+1)+'</b> '+siguiente+'</p>';
                    }
                
                txt+=paginado;
                $('#tabla-push').html(txt);
                
            }
            else{
                //app.dialog.alert('No se pudo leer las push');
            }
        }
    });     
    
    
}

function verpush(id, titulo, body, fecha, hora, tipo, imagen, iok, iko, aok, ako, wok, wko){
   
    if (imagen!=""){
        body+='<br><br><div style="text-align:center;"><img src="'+servidor+'webapp/img/upload/'+imagen+'" width="90%" height="auto"></div>';
    }
   var not=app.notification.create({
                   // myApp.alert('<b>'+data.title+'</b>.<br>'+data.message, 'Atención');

            icon: '<img src="'+servidor+'img/icon.png" width="22px" height="auto">',
            title: "TestF7",
            subtitle: titulo,
            titleRightText: fecha+' ('+hora+')',
            //subtitle: 'Notificación con cierre con clic',
            text: body,
            closeOnClick: true,
            //closeTimeout:5000,
            on: {
                close: function () {
                    
                    
                    not.$el.remove();
                    app.dialog.alert('Alcance<hr>'+
                                     '<div class="grid grid-cols-3 grid-gap">'+
                                     '<div class="">iOS</div>'+                                     '<div class="text-color-green">'+iok+'</div>'+
                                     '<div class="text-color-red">'+iko+'</div>'+
                                     '</div>'+
                                     '<div class="grid grid-cols-3 grid-gap">'+
                                     '<div class="">Andoid</div>'+                                 '<div class="text-color-green">'+aok+'</div>'+
                                     '<div class="text-color-red">'+ako+'</div>'+
                                     '</div>'+
                                     '<div class="grid grid-cols-3 grid-gap">'+
                                     '<div class="">Web</div>'+       '<div class="text-color-green">'+wok+'</div>'+
                                     '<div class="text-color-red">'+wko+'</div>'+
                                     '</div>'+
                                     '<div class="grid grid-cols-3 grid-gap">'+
                                     '<div class=""><b>Total</b></div>'+                           '<div class="text-color-green"><b>'+(parseInt(iok)+parseInt(aok)+parseInt(wok))+'</b></div>'+
                                     '<div class="text-color-red"><b>'+(parseInt(iko)+parseInt(ako)+parseInt(wko))+'</b></div>'+
                                     '</div>');
                                     
              //app.dialog.alert('Notificación cerrada');
                },
            },
        });
        not.open(); 
    
}

function addpush() {
    var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Crear notificación nueva</div>'+
                '<form>'+
                '<div class="list">'+
                    '<ul>'+
                       '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Título</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="titulo" placeholder="Título" required validate value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Texto</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="body" placeholder="Texto del mensaje" required validate value="" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Tipo</div>'+
                            '<div class="item-input-wrap">'+
                              '<select name="tipo" id="tipo-push" onchange="cambiatipopush(this);">'+
                            '<option value="1" selected>Normal</option>'+
                            '<option value="2">Ver producto</option>'+
                            '</select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li id="producto-push">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Producto</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="producto" required validate />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Imagen</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="file" name="imagen_push" id="imagen_push" onchange="loadFileimagenpush(event,this);"/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+  
                      
                      // desde, hasta tipo
        
                    '</ul>'+   
                '</div>'+
                '<img name="imagen" id="visor-imagen-push" src="" width="80%" height="auto"/>  '+


         
                '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar href="#">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill" onclick="enviapush(this);" href="#">Enviar</a</div>'+
                '</div>'+
         
            '</div>'+
          '</div>'  ,
         open: function (popup) {
                //01/34/6789
                
                
              
                
            }
        
    });  
    
    dynamicPopup.open(); 
    $('#producto-push').hide();
    
}

function cambiatipopush(e){
    var tipo=e.value;
    $('#producto-push').hide();
    /*
'<option value="1">% Dto. Global</option>'+
'<option value="2">% Dto. Producto</option>'+
'<option value="3">Envío Gratis</option>'+
'<option value="4">Importe descuento</option>'+
    */

    if (tipo=='2') {
        $('#producto-push').show();
    }
    
}

function enviapush(e){
    var errores="";
    var titulo=$('input[name=titulo]').val();
    var body=$('input[name=body]').val();
     var tipo=$('#tipo-push').val();
    if ($('input[name=titulo]').val()==""){
        errores+='título, ';
    }
    if ($('input[name=body]').val()==""){
        errores+='texto del mensaje, ';
    }
    
    if ((tipo=='2')&&($('input[name=producto]').val()=="")){
        errores+='producto, ';
    }
    if (errores!='') {
            app.dialog.alert('Errores: '+errores);
    }
    else {
        var FData = new FormData();
        FData.append("titulo", titulo);
        FData.append("body", body);
        var tipo=$("#tipo-push").val();
        if (tipo=='1') {

            logica='1';
        }
        if (tipo=='2') {

            logica='2-'+$('input[name=producto]').val();
        }
        
        FData.append("tipo", logica);
        if (document.getElementById('imagen_push').files[0]!=null){
            FData.append("files", document.getElementById('imagen_push').files[0]);
        }
        var server=servidor+'admin/includes/enviapush.php';  
        //console.log(FData);
        $.ajax({
            type: "POST",
            url: server,
            data: FData,
            cache: false, 
            //dataType: 'application/json',
            crossDomain: true,      
            processData: true, 
            contentType: false,
            processData: false,
            success: function(data){
                var obj= JSON.parse(data);
                //leepush();
                if (obj.valid==true){
                    var idtel=obj.idtel;
                    var plataforma=obj.plataforma;
                    var imagen=obj.imagen;
                    //console.log(idtel);
                     //console.log(plataforma);
                    $('#push-page').hide();
                    $('#push-send').show();
                    var ok_ios=0;
                    var ko_ios=0;
                    var ok_android=0;
                    var ko_android=0;
                    var ok_web=0;
                    var ko_web=0;
                    var progress=100/idtel.length;
                    for (var j=0;j<idtel.length;j++){
                        var resultado='ok';
                       sendthepush(plataforma[j], idtel[j], titulo, body,imagen,logica, resultado,function () {
                          //console.log('resultado recibdo:'+resultado);
                        });
                        
                        
                       //console.log('resultado recibdo:'+resultado);
                        //console.log(plataforma[j]);
                       if (resultado=='ok'){
                            if(plataforma[j]=='iOS'){
                                ok_ios++;
                            }else{
                                if(plataforma[j]=='web'){
                                    ok_web++;
                                }
                                else {
                                    ok_android++;
                                }
                            }
                        }
                        else {
                            if(plataforma[j]=='iOS'){
                                ko_ios++;
                            }else{
                                if(plataforma[j]=='web'){
                                    ko_web++;
                                }
                                else {
                                    ko_android++;
                                }
                            }
                        }
                        
                        $('#send-ios-ok').html(ok_ios);
                        $('#send-ios-ko').html(ko_ios);
                        $('#send-android-ok').html(ok_android);
                        $('#send-android-ko').html(ko_android);
                        $('#send-web-ok').html(ok_web);
                        $('#send-web-ko').html(ko_web);

                        var progreso=(progress)*(j+1);

                        app.progressbar.set('#progressbar-send', progreso);
                        

                    
                    }
                    $('#cerrar-progreso-push').show();
                    var server=servidor+'admin/includes/actualizapush.php';      
                    $.ajax({
                        type: "POST",
                        url: server,
                        data: {iok:ok_ios, iko:ko_ios,aok:ok_android,ako:ko_android,wok:ok_web,wko:ko_web},
                        dataType:"json",
                        success: function(data){
                            var obj=Object(data);
                            if (obj.valid==true){
                                
                            }
                            else {

                            }
                            $('#cerrar-progreso-push').show();
                            
                        }
                    });
                    
                    /*
                    var servidor_envio=servidor+'admin/includes/msg/';
                    var server='';
                    var ok_ios=0;
                    var ko_ios=0;
                    var ok_android=0;
                    var ko_android=0;
                    var progress=100/idtel.length;
                    //var FData=new Array();
                    var x=0;
                    window.localStorage.setItem("x",x);
                    for (var j=0;j<idtel.length;j++){

                        var FData = new FormData();
                        FData.append("titulo", $('input[name=titulo]').val());
                        FData.append("body", $('input[name=body]').val());
                        FData.append("key", logica);
                        FData.append("token", idtel[j]);
                        FData.append("imagen", imagen);
                        //console.log('token-> '+FData[j]['token']);
                        if(plataforma[j]=='iOS'){
                            server=servidor_envio+'push_ios.php';
                        }
                        else {
                            server=servidor_envio+'push_android.php';
                        }
                        
                        app.request({
                            url: server, 
                            method: 'POST', 
                            data: FData,
                            cache: false, 
                            dataType: 'application/json',
                            crossDomain: true, 
                            contentType: 'multipart/form-data',
                            processData: true, 
                            success: function (data){
                                var j=parseInt(window.localStorage.getItem("x"));

                                //var obj= JSON.parse(data);
                                //console.log(Object(data));
                                console.log(JSON.parse(data));
                                
                                var obj=JSON.parse(data);
                                console.log('Valid:'+obj['valid']);
                                console.log('J='+j);
                                 console.log(plataforma[j]);
                                console.log(idtel[j]);

                                if (obj['valid']==true){
                                    if(plataforma[j]=='iOS'){
                                        ok_ios++;
                                    }else{
                                        ok_android++;
                                    }
                                }
                                else {
                                    if(plataforma[j]=='iOS'){
                                        ko_ios++;
                                    }else{
                                        ko_android++;
                                    }
                                }
                                window.localStorage.setItem("x",j+1);
                                $('#send-ios-ok').html(ok_ios);
                                $('#send-ios-ko').html(ko_ios);
                                $('#send-android-ok').html(ok_android);
                                $('#send-android-ko').html(ko_android);
                          
                                var progreso=(progress)*(j+1);
                                
                                app.progressbar.set('#progressbar-send', progreso);
                        
                            }
                        });
                       
                        
                        
                        
                    }
                    */
                    
                    
                    //push-send
                    //app.progressbar.set('progressbar-send', progress, duration) 
                   
                    //leepush();
                    
                }
                else{
                    app.dialog.alert('No se pudo enviar');
                }

            }
        });  
        
        app.popup.close(); 
        
        
        
    }

}

function sendthepush(plataforma, idtel, titulo,body,imagen,logica, resultado,callback){
    var servidor_envio=servidor+'admin/includes/msg/';
    var server=''; 
    var FData = new FormData();
    FData.append("titulo", titulo);
    FData.append("body", body);
    FData.append("key", logica);
    FData.append("token", idtel);
    FData.append("imagen", imagen);
    
    //console.log('token-> '+FData[j]['token']);

    if(plataforma=='iOS'){
        server=servidor_envio+'push_ios.php';
    }
    else {
        if(plataforma=='web'){
            server=servidor_envio+'push_web.php';
        }
        else {
            server=servidor_envio+'push_android.php';
        }
    }

    $.ajax({
        type: "POST",
        url: server,
        data: FData,
        cache: false, 
        //dataType: 'application/json',
        crossDomain: true,      
        processData: true, 
        contentType: false,
        processData: false,
        success: function(data){
            var obj= JSON.parse(data);
            if (obj.valid==true){
                callback(null,resultado='ok');
                //return 'ok';
            }
            else {
                callback(null,resultado='ko');
                //return 'ko'; 
            }

        },
        error:function(e){
            console.log('Error enviando push');
        }
    }); 
    
    
}



function cerrarprogresopush(){
    $('#push-page').show();
    $('#cerrar-progreso-push').hide();
    $('#push-send').hide();   
    leepush();
    
}

var loadFileimagenpush = function(event,e) {
    var img="";
    //alert(e.id);
    var reader = new FileReader();

    reader.onload = function(){
        
      var output = document.getElementById('visor-imagen-push');
      output.src = reader.result;
        $("#"+e.id).show();   
    };
    reader.readAsDataURL(event.target.files[0]);
    img=event.target.files[0];
    
    
};