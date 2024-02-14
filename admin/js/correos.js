function correoAjustes() {
    var txt_old=$('#emails-page').html();
    localStorage.setItem("contenidopagina correo", txt_old);
    var txt='<div class="block-title block-title-medium"><a href="" onclick="restaurapaginacorreos();">Emails</a> -> Ajustes de correo</div>';
    $('#emails-page').html(txt);
    var server=servidor+'admin/includes/leedatoscorreo.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {id:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var nombreremitente=obj.nombreremitente;
                var mail=obj.mail;
                var usuariomail=obj.usuariomail;
                var clavemail=obj.clavemail;
                var host=obj.host;
                var puerto=obj.puerto;
                var SMTPSecure=obj.SMTPSecure;
                var sender=obj.sender;
                var pie=obj.pie;
                txt+='<form class="list" id="correo-form" enctype="multipart/form-data">'+
            '<ul>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Nombre Remitente</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="nombreremitente" placeholder="Nombre" value="'+nombreremitente+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Email</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="mail" placeholder="Email" value="'+mail+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Usuario</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="usuariomail" placeholder="Usuario" value="'+usuariomail+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Clave</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="clavemail" placeholder="Clave" value="'+clavemail+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Host</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="host" placeholder="Host" value="'+host+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Puerto</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="puerto" placeholder="Puerto" value="'+puerto+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">SMTPSecure (ssl o tls)</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="SMTPSecure" placeholder="SMTPSecure" value="'+SMTPSecure+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">MAILsender</div>'+
                        '<div class="item-input-wrap">'+
                          '<input type="text" name="sender" placeholder="Sender" value="'+sender+'"/>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
                '<li>'+
                    '<div class="item-content item-input">'+
                      '<div class="item-inner">'+
                        '<div class="item-title item-label">Pie correos</div>'+
                        '<div class="text-editor text-editor-init" style="width: 100%;">'+
                            '<div class="text-editor-content" contenteditable style="border: solid 1px lightgrey;padding-left: 10px;padding-right: 10px;">'+pie+'</div>'+
                            '</div>'+
                      '</div>'+
                    '</div>'+
                '</li>'+
            '</ul>'+
            '</form>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#" onclick="restaurapaginacorreos();">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#" onclick="guardadatoscorreo();">Guardar</a</div>'+
                '</div>';

                $('#emails-page').html(txt);
                var textEditor = app.textEditor.create({
                  el: '.text-editor',
                  customButtons: {
                      hr: {
                        content: '<i class="icon f7-icons hr-icon"></i>',
                        onClick(editor, buttonEl) {
                          document.execCommand('insertHorizontalRule', false);
                        },
                      },
                    // property key is the button id
                    logo: {
                      // button html content
                      content: '<i class="icon f7-icons logo-boton-icon"></i>',
                      // button click handler
                      onClick(event, buttonEl) {
                        document.execCommand('insertText', false,'[logo]');
                      }
                    }
                  },
                  // now we use custom button id "hr" to add it to buttons
                  buttons: [
                      ['bold', 'italic', 'underline', 'strikeThrough'],
                      ['orderedList', 'unorderedList'],
                      ['link', 'image'],
                      ['paragraph', 'h1', 'h2', 'h3'],
                      ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],
                      ['subscript', 'superscript'],
                      ['indent', 'outdent'],['hr','logo']
                    ]
                });

            }
            else{
                app.dialog.alert('No se pudo recuperar los datos de correo');
            }
        }
    });
                
   
    
    
}

function guardadatoscorreo(){   
    var textEditor = app.textEditor.get('.text-editor');
    var pie=textEditor.value;
    var formData = app.form.convertToData('#correo-form'); 
    var server=servidor+'admin/includes/guardadatoscorreo.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {nombreremitente:formData['nombreremitente'], mail:formData['mail'], usuariomail:formData['usuariomail'], clavemail:formData['clavemail'],host:formData['host'], puerto:formData['puerto'], SMTPSecure:formData['SMTPSecure'], sender:formData['sender'],pie:pie},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                restaurapaginacorreos();
                //correoAjustes();
            }
            else{
                app.dialog.alert('No se pudo guardar los datos de correo');
            }
        }
    });       
}

function restaurapaginacorreos(){
    $('#emails-page').html(localStorage.getItem("contenidopagina correo"));
    localStorage.removeItem("contenidopagina");
}

function correoBienvenida() {
    var txt_old=$('#emails-page').html();
    localStorage.setItem("contenidopagina correo", txt_old);
    var txt='<div class="block-title block-title-medium"><a href="" onclick="restaurapaginacorreos();">Emails</a> -> Correo de Bienvenida</div>';
    $('#emails-page').html(txt);
    var server=servidor+'admin/includes/tiposcorreos.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {nombre:'bienvenida',accion:'leer'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var textomail=obj.textomail;
                
                txt+='<p>Texto correo:</p>'+
                    '<div class="text-editor text-editor-init" style="width: 100%;">'+
                        '<div class="text-editor-content" contenteditable style="border: solid 1px lightgrey;padding-left: 10px;padding-right: 10px;">'+textomail+'</div>'+
                    '</div>'+
                      
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#" onclick="restaurapaginacorreos();">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#" onclick="guardatextocorreo(\'bienvenida\',\'correoBienvenida\');">Guardar</a</div>'+
                '</div>';

                $('#emails-page').html(txt);
                var textEditor = app.textEditor.create({
                  el: '.text-editor',
                  customButtons: {
                      hr: {
                        content: '<i class="icon f7-icons hr-icon"></i>',
                        onClick(editor, buttonEl) {
                          document.execCommand('insertHorizontalRule', false);
                        },
                      },
                    // property key is the button id
                    logo: {
                      // button html content
                      content: '<i class="icon f7-icons logo-boton-icon"></i>',
                      // button click handler
                      onClick(event, buttonEl) {
                        document.execCommand('insertText', false,'[logo]');
                      }
                    },
                    user: {
                      // button html content
                      content: '<i class="icon f7-icons if-not-md">person_crop_circle</i>',
                      // button click handler
                      onClick(event, buttonEl) {
                          app.dialog.create({
                              title: 'Datos del usuario',
                              text: 'Seleccione dato del usuario',
                              buttons: [
                                {
                                  text: 'Nombre',
                                  onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioNombre]');
                                    }
                                },
                                  {
                                  text: 'Apellidos',
                                  onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioApellidos]');
                                    }
                                },
                                {
                                  text: 'Usuario',
                                    onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioEmail]');
                                    }
                                },
                                {
                                  text: 'Teléfono',
                                    onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioTelefono]');
                                    }
                                },
                              ],
                              verticalButtons: true,
                            }).open();
                        
                      }
                    }
                      
                    ,cuponBienvenida: {
                      // button html content
                      content: '<i class="icon f7-icons">tickets</i>',
                      // button click handler
                      onClick(event, buttonEl) {
                        document.execCommand('insertText', false,'[cuponBienvenida]');
                      }
                    }
                  },
                  // now we use custom button id "hr" to add it to buttons
                  buttons: [
                      ['bold', 'italic', 'underline', 'strikeThrough'],
                      ['orderedList', 'unorderedList'],
                      ['link', 'image'],
                      ['paragraph', 'h1', 'h2', 'h3'],
                      ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],
                      ['subscript', 'superscript'],
                      ['indent', 'outdent'],['hr','logo','user','cuponBienvenida']
                    ]
                });

            }
            else{
                app.dialog.alert('No se pudo recuperar los datos de correo');
            }    
        },
        error:function(e){
            console.log('error');
        }
    });
      
}

function correoBirday() {
    var txt_old=$('#emails-page').html();
    localStorage.setItem("contenidopagina correo", txt_old);
    var txt='<div class="block-title block-title-medium"><a href="" onclick="restaurapaginacorreos();">Emails</a> -> Correo de Cumpleaños</div>';
    $('#emails-page').html(txt);
    
    var server=servidor+'admin/includes/tiposcorreos.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {nombre:'cumple',accion:'leer'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var textomail=obj.textomail;
                
                txt+='<p>Texto correo:</p>'+
                    '<div class="text-editor text-editor-init" style="width: 100%;">'+
                        '<div class="text-editor-content" contenteditable style="border: solid 1px lightgrey;padding-left: 10px;padding-right: 10px;">'+textomail+'</div>'+
                    '</div>'+
                '<div class="block block-strong grid grid-cols-2 grid-gap">'+
                    '<div class=""><a class="button button-fill popup-close button-cancelar" href="#" onclick="restaurapaginacorreos();">Cancelar</a></div>'+
                    '<div class=""><a class="button button-fill save-data" href="#" onclick="guardatextocorreo(\'cumple\',\'correoBirday\');">Guardar</a</div>'+
                '</div>';
                      

                $('#emails-page').html(txt);
                var textEditor = app.textEditor.create({
                  el: '.text-editor',
                  customButtons: {
                      hr: {
                        content: '<i class="material-icons">more_horiz</i>',
                        onClick(editor, buttonEl) {
                          document.execCommand('insertHorizontalRule', false);
                        },
                      },
                    // property key is the button id
                    logo: {
                      // button html content
                      content: '<i class="icon f7-icons logo-boton-icon"></i>',
                      // button click handler
                      onClick(event, buttonEl) {
                        document.execCommand('insertText', false,'[logo]');
                      }
                    },
                    user: {
                      // button html content
                      content: '<i class="icon f7-icons if-not-md">person_crop_circle</i>',
                      // button click handler
                      onClick(event, buttonEl) {
                          app.dialog.create({
                              title: 'Datos del usuario',
                              text: 'Seleccione dato del usuario',
                              buttons: [
                                {
                                  text: 'Nombre',
                                  onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioNombre]');
                                    }
                                },
                                  {
                                  text: 'Apellidos',
                                  onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioApellidos]');
                                    }
                                },
                                {
                                  text: 'Usuario',
                                    onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioEmail]');
                                    }
                                },
                                {
                                  text: 'Teléfono',
                                    onClick: function () {
                                        document.execCommand('insertText', false,'[usuarioTelefono]');
                                    }
                                },
                              ],
                              verticalButtons: true,
                            }).open();
                        
                      }
                    }
                      
                    ,cuponBienvenida: {
                      // button html content
                      content: '<i class="icon f7-icons">tickets</i>',
                      // button click handler
                      onClick(event, buttonEl) {
                        document.execCommand('insertText', false,'[cuponBirthday]');
                      }
                    }
                  },
                  // now we use custom button id "hr" to add it to buttons
                  buttons: [
                      ['bold', 'italic', 'underline', 'strikeThrough'],
                      ['orderedList', 'unorderedList'],
                      ['link', 'image'],
                      ['paragraph', 'h1', 'h2', 'h3'],
                      ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],
                      ['subscript', 'superscript'],
                      ['indent', 'outdent'],['hr','logo','user','cuponBienvenida']
                    ]
                });

            }
            else{
                app.dialog.alert('No se pudo recuperar los datos de correo');
            }
        }
    });
    
}

function guardatextocorreo(nombre,funcion){
    var textEditor = app.textEditor.get('.text-editor');
    var textomail=textEditor.value; 
    var server=servidor+'admin/includes/tiposcorreos.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {nombre:nombre,accion:'guardar',textomail:textomail},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                //console.log(obj.textomail);
                restaurapaginacorreos();
                window[funcion]();
            }
            else{
                app.dialog.alert('No se pudo guardar los datos de correo');
                console.log(obj.textomail);
            }
        }
        ,
        error: function(e){
        console.log('error');
        }
    });
                
           
}


function correoCampaign() {
    var cupones=new Array();
    localStorage.removeItem('cupones');
    localStorage.setItem('cupones', JSON.stringify(cupones));
    var txt_old=localStorage.getItem("contenidopagina-correo");
    localStorage.setItem("contenidopagina-correo", txt_old);
	
	var txt='<div class="block-title block-title-medium"><a href="" onclick="restaurapaginacorreos();">Emails</a> -> Campañas</div><hr>';
    
    txt+='<div class="grid grid-cols-4 grid-gap">'+
            '<div class="">Nombre</div>'+
            '<div class="">Fecha</div>'+
            '<div class="">Alcance</div>'+
            '<div class="">Ver</div>'+
        '</div><hr>'+
        '<div id="campaign-row">'+
            
        '</div><hr>'+
        '<div class="grid grid-cols-1 grid-gap"><button onclick="editaCampaign();" id="add-campaign" class="button button-fill" style="margin:auto;width: 50%;">+ Añadir Campaña</button></div>';
    $('#emails-page').html(txt);
    var server=servidor+'admin/includes/leecampaign.php';
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
                var fechas=obj.fecha;
                var usuario=obj.usuario;
                var grupo=obj.grupo;
                //var realizada=obj.realizada;


                var txt='';
                var fecha='';
                var tipo='';
                var link='';
                var icono='';
                var hoy=new Date();
                for (x=0;x<id.length;x++){
                    // 0123-56-89
                    fecha=fechas[x].substr(8,2)+'/'+fechas[x].substr(5,2)+'/'+fechas[x].substr(0,4);
                    fhasta=new Date(fechas[x].substr(0,4), parseInt(fechas[x].substr(5,2))-1, fechas[x].substr(8,2));
                    link='editaCampaign('+id[x]+',\''+nombre[x]+'\')';
                    icono='<i class="f7-icons">pencil</i>';
                    if (hoy>fhasta){
                         link='verCampaign('+id[x]+')';
                        icono='<i class="f7-icons">eye</i>';
                    }
                    // 1 - todos
                    // 2 - registrados
                    // 3 - grupo
                    if (usuario[x]==1){
                        tipo='Todos';
                    }
                    if (usuario[x]==2){
                        tipo='Registrados';
                    }
                    if (usuario[x]==3){
                        tipo='Grupo';
                    }


                    txt+='<div class="grid grid-cols-4 grid-gap">'+
                        '<div class="">'+nombre[x]+'</div>'+
                        '<div class="">'+fecha+'</div>'+
                        '<div class="">'+tipo+'</div>'+
                        '<div class="" onclick="'+link+';">'+icono+'</div>'+
                    '</div>';
                }
                    $('#campaign-row').html(txt);
            }
            else{
                app.dialog.alert('No se pudo leer las campañas');

            }
        }
    });
    
    server=servidor+'admin/includes/leecupones.php';
    $.ajax({
        type: "POST",
        url: server,
        data: {foo:'foo'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                var nombre=obj.nombre;
                var codigo=obj.codigo;
                for (x=0;x<nombre.length;x++){
                    cupones[x]={'nombre':nombre[x],'codigo':codigo[x]};
                    localStorage.setItem('cupones', JSON.stringify(cupones));
                }
                
            }
            else{
                app.dialog.alert('No se pudo los cupones');

            }
        }
    });  
        
}

function editaCampaign(id=0,nombre='Nueva'){
    var txt_old=$('#emails-page').html();
    localStorage.setItem("contenidopagina-correo", txt_old);
    
    var txt='<div class="block-title block-title-medium"><a href="" onclick="restaurapaginacorreos();">Emails</a> -> <a href="" onclick="correoCampaign();">Campañas</a> -> '+nombre+'</div>';
    //$('#emails-page').html(txt);
    txt+='<form id="campaign-form">'+
            '<input type="hidden" name="idcamp"  value="'+id+'"/>'+
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
                      '<li id="hasta">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Fecha</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="hasta" value="" placeholder="Seleccione fecha" id="calendar-hasta" />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                    '<li id="destino">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Destino</div>'+
                            '<div class="item-input-wrap input-dropdown-wrap" >'+
                                '<select placeholder="Escoja alcance del cupón" id="destino-cupon" onchange="cambiatipodestino(this);">'+
                                    '<option value="1">Todos los usuarios</option>'+
                                    '<option value="2">Solos los usuarios registrados</option>'+
                                    '<option value="3">Grupo fidelización</option>'+
                                   
                                ' </select>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+       
                    '<li id="grupos">'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Grupos</div>'+
                            '<div id="contenido-grupos"></div>'+
                              
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
 
                        '<li>'+
                            '<p>Texto correo:</p>'+
                            '<div class="text-editor text-editor-init" style="width: 100%;">'+
                                '<div class="text-editor-content" contenteditable style="border: solid 1px lightgrey;padding-left: 10px;padding-right: 10px;"></div>'+
                            '</div><br>'+
                        '</li>';
                    '</ul>'+
                '</div>'+         
            '</form>';
    txt+='<div class="grid grid-cols-3 grid-gap">'+
            '<div class=""><a class="button button-fill button-cancelar" href="#" onclick="correoCampaign();">Cancelar</a></div>'+
            '<div class=""><a class="button button-fill" href="#" onclick="probarcampaign();">Probar</a></div>'+
            '<div class=""><a class="button button-fill" href="#" onclick="guardacampaign();">Guardar</a></div>'+
        '</div>';

    $('#emails-page').html(txt); 
    var botones= new Array();
    var cupones = JSON.parse(localStorage.getItem('cupones'));
    //console.log(cupones);
    var codigocupon='';
    for (x=0;x<cupones.length;x++){
        codigocupon=cupones[x]['codigo'];
        
        botones[x]={
            
            text:cupones[x]['nombre'],
            onClick: function () {
                var cupones = JSON.parse(localStorage.getItem('cupones'));
                var id=0;
                for (x=0;x<cupones.length;x++){
                    if(cupones[x]['nombre']==this['text']){
                        id=x;
                    }
                }
                document.execCommand('insertText', false,'[cupon-'+cupones[id]['codigo']+']');
            }   

        };
    }
      
    var textEditor = app.textEditor.create({
      el: '.text-editor',
      customButtons: {
          hr: {
            content: '<i class="material-icons">more_horiz</i>',
            onClick(editor, buttonEl) {
              document.execCommand('insertHorizontalRule', false);
            },
          },
        // property key is the button id
        logo: {
          // button html content
          content: '<i class="icon f7-icons logo-boton-icon"></i>',
          // button click handler
          onClick(event, buttonEl) {
            document.execCommand('insertText', false,'[logo]');
          }
        },
          producto: {
          // button html content
          content: '<i class="icon f7-icons food-icon"></i>',
          // button click handler
          onClick(event, buttonEl) {
            /*
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

                    var server=servidor+'admin/includes/leeproductossearch.php';
                    app.request.postJSON(server, { foo:'foo'}, 
                        function (data) {
                            var obj=Object(data);
                            if (obj.valid==true){
                                var txt='<ul>';
                                for (x=0;x<obj.id.length;x++){
                                     txt+='<li class="item-content style="cursor:pointer;" data-id="'+obj.id[x]+'"data-nombre="'+obj.nombre[x]+'" onclick="app.popup.close();$(\'.text-editor-content\').focus();document.execCommand(\'insertText\', false,\'[producto-'+obj.id[x]+']\');">'+
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




                        },
                        function (xhr, status) { 
                            app.dialog.alert('No se pudo recuperar los productos');
                    });    
              },
                    },
                }); 
            dynamicPopup.open();
            */              
              document.execCommand('insertText', false,'[producto-xxx]');
          }
        },
        cupon: {
              // button html content
              content: '<i class="icon f7-icons">tickets</i>',
              // button click handler
              onClick(event, buttonEl) {
               
                app.dialog.create({
                  title: 'Datos del cupón',
                  text: 'Seleccione dato del usuario',
                  buttons: botones,
                  verticalButtons: true,
                }).open();

              }
            },
        user: {
          // button html content
          content: '<i class="icon f7-icons if-not-md">person_crop_circle</i>',
          // button click handler
          onClick(event, buttonEl) {
              app.dialog.create({
                  title: 'Datos del usuario',
                  text: 'Seleccione dato del usuario',
                  buttons: [
                    {
                      text: 'Nombre',
                      onClick: function () {
                            document.execCommand('insertText', false,'[usuarioNombre]');
                        }
                    },
                      {
                      text: 'Apellidos',
                      onClick: function () {
                            document.execCommand('insertText', false,'[usuarioApellidos]');
                        }
                    },
                    {
                      text: 'Usuario',
                        onClick: function () {
                            document.execCommand('insertText', false,'[usuarioEmail]');
                        }
                    },
                    {
                      text: 'Teléfono',
                        onClick: function () {
                            document.execCommand('insertText', false,'[usuarioTelefono]');
                        }
                    },
                  ],
                  verticalButtons: true,
                }).open();

          }
        }

        
      },
      // now we use custom button id "hr" to add it to buttons
      buttons: [
          ['bold', 'italic', 'underline', 'strikeThrough'],
          ['orderedList', 'unorderedList'],
          ['link', 'image'],
          ['paragraph', 'h1', 'h2', 'h3'],
          ['alignLeft', 'alignCenter', 'alignRight', 'alignJustify'],
          ['subscript', 'superscript'],
          ['indent', 'outdent'],['hr','logo','user','cupon','producto']
        ]
    });

    if (id!=0){
        var server=servidor+'admin/includes/leecampaign.php';
        $.ajax({
            type: "POST",
            url: server,
            data: {foo:'id',id:id},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    var id=obj.id;
                    var nombre=obj.nombre;
                    var fechas=obj.fecha;
                    var usuario=obj.usuario;
                    var grupo=obj.grupo;
                    var texto=obj.texto;
                    fhasta=new Date(fechas[0].substr(0,4), parseInt(fechas[0].substr(5,2))-1, fechas[0].substr(8,2));
                    
                    $('input[name=nombre]').val(nombre);
                    
                    calendarDesde = app.calendar.create({
                        inputEl: '#calendar-hasta',
                        value: [fhasta],
                        openIn: 'customModal',
                        //timePicker: true,
                        toolbarCloseText: 'Hecho',
                        //header: true,
                        closeOnSelect:true,
                        dateFormat: 'dd/mm/yyyy'
                    });
                    if (usuario[0]<3){
                        $('#grupos').hide();
                    }
                    else {
                            rellenagruposbeneficiarios(grupo[0]);
                        $('#grupos').show();
                        
                    }
                    
                    $('#destino-cupon option[value="'+ usuario[0] +'"]').attr("selected",true);
                    textEditor.setValue(texto[0]);
                }
                else{
                    app.dialog.alert('No se pudo leer la campaña');

                }
            }
        });
                
           
    }
    else {
        fhasta=new Date();
        fhasta.setDate(fhasta.getDate() + 1);
        calendarDesde = app.calendar.create({
            inputEl: '#calendar-hasta',
            value: [fhasta],
            openIn: 'customModal',
            //timePicker: true,
            toolbarCloseText: 'Hecho',
            //header: true,
            closeOnSelect:true,
            disabled: {
                from: new Date(2015, 9, 1),
                to: new Date()
            },
            dateFormat: 'dd/mm/yyyy'
        });
        var server=servidor+'admin/includes/leemodelocampaign.php';
        $.ajax({
            type: "POST",
            url: server,
            data: {foo:'foo'},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                if (obj.valid==true){
                    var texto=obj.texto;
                    textEditor.setValue(texto);         
                }
                else{
                    app.dialog.alert('No se pudo leer la campaña');
                }
            }
        });   
    }
    $('#grupos').hide();  
    
}

function guardacampaign(){
    var textEditor = app.textEditor.get('.text-editor');
    var texto=textEditor.getValue();
    var fecha=$('input[name=hasta]').val();
    var nombre=$('input[name=nombre]').val();
    var id=$('input[name=idcamp]').val();
    
    var usuario=$('#destino-cupon').val();
    var grupo='';
    if (usuario==3){
    // si grupo == 3 ver si hay alguno vacío
        var grupos = new Array();
        $('input[name=grupo_promo]:checked').each(function() {
            
            grupo+=$(this).attr('data-id')+',';
        });
        grupo=grupo.slice(0, grupo.length - 1);
    }
    if (nombre==''){
        app.dialog.alert('Introduzca un nombre');
        return;
    }
    if (texto==''){
        app.dialog.alert('Introduzca un texto');
        return;
    }
    var server=servidor+'admin/includes/guardacampaign.php'; 
    $.ajax({
        type: "POST",
        url: server,
        data: {id:id,texto:texto,nombre:nombre,fecha:fecha,usuario:usuario,grupo:grupo},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                 correoCampaign();
            }
            else {	
                app.dialog.alert('Error guardando correo');
            }
        }
    });
                


}

function probarcampaign(){
    var textEditor = app.textEditor.get('.text-editor');
    var texto=textEditor.getValue();
    var fecha=$('input[name=hasta]').val();
    var nombre=$('input[name=nombre]').val();
    var id=$('input[name=idcamp]').val();
    if (nombre==''){
        app.dialog.alert('Introduzca un nombre');
        return;
    }
    if (texto==''){
        app.dialog.alert('Introduzca un texto');
        return;
    }
    //console.log(texto);
    app.dialog.prompt('Introduzca un email', function (name) {
        if(validarEmail(name)) {
            var server=servidor+'admin/includes/probarcampaign.php'; $.ajax({
                type: "POST",
                url: server,
                data: {mail:name,texto:texto,nombre:nombre},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                    if (obj.valid==true){
                         app.dialog.alert('Correo enviado');
                    }
                    else {	
                        app.dialog.alert('Error enviando correo');
                    }
                }
            });
              
        }
        else {
             app.dialog.alert('Email erroneo');
          
        }
    });
}

function validarEmail(valor) {
    emailRegex =/^(?:[^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*|"[^\n"]+")@(?:[^<>()[\].,;:\s@"]+\.)+[^<>()[\]\.,;:\s@"]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (emailRegex.test(valor)) {
        return true;
      
    } 
    else {
        return false;
    }
}