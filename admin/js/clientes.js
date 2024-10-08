$('#filtro-clientes-button').on('click', function(){
    var filtro=$('#filtro-cliente').val();
    var orden=$('input[name=filtro-cliente-orden]:checked').val();
    
    clientes(1,filtro, orden);
});

var objmonedero;

function clientes(pagina=1,filtro='', orden=0) {

    var txt='';
    var server=servidor+'admin/includes/leeclientes.php';             $.ajax({
        type: "POST",
        url: server,
        data: {pagina:pagina,filtro:filtro, orden:orden},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                var tipo=obj.tipo;
                var tratamiento=obj.tratamiento;
                var apellidos=obj.apellidos;
                var nombre=obj.nombre;
                var email=obj.email;
                var telefono=obj.telefono;
                var publicidad=obj.publicidad;
                var monedero=obj.monedero;
                var compras=obj.compras;
                var sumamonedero=obj.sumamonedero;
                var sumamPedidos=obj.sumamPedidos;
                var txt='';
                
                var numReg=obj.registros;
                var paginas=numReg/12;
                var resto=numReg%12;
                var pagina_actual=pagina;
                if (pagina_actual==0){
                    //pagina_actual=1;
                }
                if (resto>0){
                    paginas=Math.trunc(paginas)+1;
                }
                var color='';
                var img_cliente='<i Class="f7-icons">person_crop_circle_badge_checkmark</i>';
                txt+=
                      '<tr>'+
                        '<th class="label-cell"></th>'+
                        '<th class="label-cell"></th>'+
                        '<th class="label-cell"></th>'+
                        '<th class="label-cell">Sumas</th>'+
                        '<th class="numeric-cell">'+(parseFloat(sumamonedero).toFixed(2))+'</th>'+
                    '<th class="numeric-cell">'+(parseFloat(sumamPedidos).toFixed(2))+'</th>'+
                      '</tr>';
                if (tipo!=null){
                for (x=0;x<tipo.length;x++){
                    color='';
                    
                    if (tipo[x]=='S') {
                        img_cliente='<i Class="f7-icons">person_crop_circle_badge_checkmark</i>';
                        
                    }
                    else {
                        img_cliente='<i Class="f7-icons">person_crop_circle_badge_xmark</i>';
                        color='style="color:gray;"';
                    }
                    txt+=
                      '<tr onclick="vercliente(\''+tipo[x]+'\',\''+email[x]+'\');" '+color+'>'+
                        '<th class="label-cell">'+img_cliente+'</th>'+
                        '<th class="label-cell">'+apellidos[x]+', '+nombre[x]+'</th>'+
                        '<th class="label-cell">'+telefono[x]+'</th>'+
                        '<th class="label-cell">'+email[x]+'</th>'+
                        '<th class="numeric-cell">'+(parseFloat(monedero[x]).toFixed(2))+'</th>'+
                        '<th class="numeric-cell">'+(parseFloat(compras[x]).toFixed(2))+'</th>'+
                      '</tr>';
                    
                }
                }
                var txt_pie="";
                txt_pie+=
                    '<div class="data-table-footer" >'+
                        '<div class="data-table-rows-select">Paginas:'+paginas+'</div>'+
                        '<div class="data-table-pagination">'+
                            '<span class="data-table-pagination-label">'+pagina+' de '+paginas+'</span>';
                    
                var anterior="";
                var siguiente="";

                if (paginas>1){
                    var filtro=$('#filtro-cliente').val();
                    if (pagina>1){

                        txt_pie+='<a href="#" onclick="clientes('+(pagina-1)+',\''+filtro+'\','+orden+');"class="link"><i class="icon icon-prev color-gray"></i></a>';
                    }
                    else {
                        txt_pie+='<a href="#" class="link disabled"><i class="icon icon-prev color-gray"></i></a>';
                    }

                    if (pagina<(paginas)){

                        txt_pie+='<a href="#" onclick="clientes('+(pagina+1)+',\''+filtro+'\','+orden+');"class="link"><i class="icon icon-next color-gray"></i></a>';
                    }
                    else {
                        txt_pie+='<a href="#" class="link disabled"><i class="icon icon-next color-gray"></i></a>';
                    }

                }             

                txt_pie+='</div>';
                
                $('#tabla-clientes').html(txt);
                $('#tabla-clientes-pie').html(txt_pie);
            }
            else{
                app.dialog.alert('No se pudo leer los clientes');
            }   
        },
        error: function(e){
            console.log('error');
        }
    });
    
}

function vercliente(tipo,email){
    var txt='';
    app.popup.destroy();
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+
                '<div id="detallecliente"></div>'+         
            '</div>'+
          '</div>'  ,
        on: {
            open: function (popup) {
                var server=servidor+'admin/includes/leeuncliente.php';     
                $.ajax({
                    type: "POST",
                    url: server,
                    data: {tipo:tipo,email:email},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        if (obj.valid==true){
                            var id=obj.id;             
                            var disabled='';
                            if (tipo=='N'){
                                disabled='disabled';
                            }
                            var numero=obj.numero;
                           
                            var fecha=obj.fecha;
                            var hora=obj.hora;
                            
                            var envio=obj.envio;
                            var metodo=obj.metodo;
                            var total=obj.total;
                            var anulado=obj.anulado;
                            var estadoPago=obj.estadoPago;
                            txt+=''+
        '<div class="swiper swiper-init" space-between="50" style="padding:10px;padding-top: 0;">'+
            '<div class="swiper-wrapper">'+
                '<div class="swiper-slide" style="border: 2px solid gray;border-radius: 25px;">'+
                    '<div class="block-title" style="margin-top: 10px;margin-bottom: -2px;">Datos</div>' +  
                    '<form class="list" id="cliente-form" enctype="multipart/form-data" style="min-height: 400px;">'+
                        '<input type="hidden" name="idCliente" value="'+obj.idCliente+'"/>'+
                        '<ul>'+
                            '<li>'+
                                '<div class="item-content item-input">'+
                                    '<div class="item-inner">'+
                                       // '<div class="item-title item-label">Tratamiento</div>'+
                                        '<div class="item-input-wrap">'+
                                            '<p style="margin-bottom: 0;font-size: 14px;">Sr.&nbsp;<label class="radio"><input type="radio" name="tratamiento" id="tratamiento-sr" value="1" checked '+disabled+'/><i class="icon-radio icon-tratamiento"></i></label> &nbsp;Sra.&nbsp;<label class="radio"><input type="radio" name="tratamiento" value="2" id="tratamiento-sra" '+disabled+'/><i class="icon-radio icon-tratamiento"></i></label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Publicidad&nbsp;<label class="checkbox"><input type="checkbox" name="publi-checkbox" value="1" '+disabled+'/><i class="icon-checkbox"></i></label> </p>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</li>'+
                          '<li>'+
                            '<div class="item-content item-input">'+
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Nombre</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="nombre" placeholder="Nombre" value="'+obj.nombre+'" required '+disabled+'/>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</li>'+
                          '<li>'+
                            '<div class="item-content item-input">'+
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Apellidos</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="apellidos" placeholder="Apellidos" value="'+obj.apellidos+'" required '+disabled+'/>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</li>'+
                            '<li>'+
                            '<div class="item-content item-input">'+
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Email</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="email" placeholder="Email" value="'+obj.email+'" required validate '+disabled+'/>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</li>'+
                            '<li>'+
                            '<div class="item-content item-input">'+
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Teléfono</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="telefono" placeholder="Teléfono" value="'+obj.telefono+'" required validate '+disabled+'/>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</li>'+
                            '<li id="li-nacimiento">'+
                            '<div class="item-content item-input">'+
                                '<div class="item-inner">'+
                                    '<div class="item-title item-label">Fecha nacimiento</div>'+
                                    '<div class="item-input-wrap">'+
                                        '<input type="text" name="nacimiento" id="nacimiento-cliente" placeholder="Fecha nacimiento" required validate '+disabled+'/>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</li>'+
                        '<li id="li-monedero">'+
                            '<div class="item-content item-input">'+
                                '<div class="item-media busca-movimiento-monedero" id="busca-movimiento-monedero" onclick="muestraMonedero('+obj.idCliente+',\''+obj.apellidos+', '+obj.nombre+'\','+obj.monedero+');">'+
                            '<i class="icon f7-icons">search</i>'+
                        '</div>'+
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Monedero</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="monedero" id="antiguo-monedero" placeholder="Monedero" value="'+obj.monedero+'" disabled/>'+
                                '</div>'+
                              '</div>'+
                            '</div>'+
                          '</li>'+
                        '</ul>'+
                    '</form>'+
                    '<div class="block block-strong grid grid-cols-2 grid-gap" style="margin-top: -35px;margin-bottom: 20px;" id="botones-guardar">'+
                        '<div class=""><a class="button button-fill popup-close button-cancelar" href="#">Cancelar</a></div>'+
                        '<div class=""><a class="button button-fill" onclick="GuardaCliente();" href="#">Guardar</a></div>'+
                    '</div>'+
                                
                '</div>'+
                '<div class="swiper-slide" style="border: 2px solid gray;border-radius: 25px;">'+
                    '<div class="block-title">Pedidos</div>' +
                    '<div class="card data-table" style="min-height: 455px;">'+
                      '<table>'+
                        '<thead>'+
                          '<tr>'+
                            '<th class="label-cell">Nº Pedido</th>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Total</th>'+
                            '<th class="label-cell">Envio</th>'+
                            '<th class="label-cell">Pago</th>'+   
                          '</tr>'+
                        '</thead>'+
                        '<tbody id="tabla-pedidos">';
                         if (id!=null){
                            for (x=0;x<id.length;x++){

                                //<tbody id="tabla-pedidos">
                                fecha[x]=fecha[x].substr(8,2)+'/'+fecha[x].substr(5,2)+'/'+fecha[x].substr(0,4);
                                var img_reparto=servidor+'admin/img/reparto.png';
                                if (envio[x]=='2'){
                                    img_reparto=servidor+'admin/img/recoger.png';
                                }
                                var img_pago=servidor+'admin/img/efectivo.png';
                                if (metodo[x]=='1'){
                                    img_pago=servidor+'admin/img/tarjeta.png';
                                }
                                var color='style="cursor:pointer;"';
                                if (anulado[x]==1){
                                    //anulado
                                    color='style="color:gray;cursor:pointer;"';
                                }
                                if (anulado[x]==2){
                                    //anulado
                                    color='style="color:darkorange;cursor:pointer;"';
                                }
                               
                                if (estadoPago[x]==-1){
                                    //anulado
                                    //color='style="color:red;"';
                                }
                                else {
                                txt+=
                                    '<tr onclick="verpedido(\''+id[x]+'\','+anulado[x]+');" '+color+'>'+
                                        '<th class="label-cell">'+numero[x]+'</th>'+
                                        '<th class="label-cell">'+fecha[x]+' ('+hora[x]+')</th>'+

                                        '<th class="numeric-cell">'+total[x]+'</th>'+
                                        '<th class="label-cell"><img src="'+img_reparto+'" width="24" height="auto"></th>'+
                                    '<th class="label-cell"><img src="'+img_pago+'" width="24" height="auto"></th>'+
                                '</tr>';

                                }

                            } 
                        }   
                        txt+=''+   
                        '</tbody>'+
                      '</table>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="swiper-pagination"></div>'+
        '</div>';
                        }
                        else {

                        }
                        $('#detallecliente').html(txt);
                        
                        
                        if(obj.publicidad=='1'){
                           $('#cliente-form input[name*="publi-checkbox"]').attr('checked', true); 
                        }
                        
                        $('#cliente-form input[name*="tratamiento"][value="'+obj.tratamiento+'"]').attr('checked', true);
                        $('#cliente-form input[name*="nacimiento"]').val(obj.nacimiento);
                        var swiper = app.swiper.create('.swiper', {
                            speed: 400,
                            spaceBetween: 20,
                            allowTouchMove:true,
                            pagination: {
                            	  el: '.swiper-pagination',
                            	  type: 'bullets',
                            	},
                        });
                        //1965-07-17
                        var calendarNacimiento = app.calendar.create({
                            inputEl: '#nacimiento-cliente',
                            value: [new Date(obj.nacimiento.substr(0,4), parseInt(obj.nacimiento.substr(5,2))-1, obj.nacimiento.substr(8,2))],
                            openIn: 'customModal',
                            toolbarCloseText:'Ok',
                            //header: true,
                            closeOnSelect:true,
                            dateFormat: 'dd/mm/yyyy'
                            //footer: true,
                        });
                        if(tipo=='N'){
                            $('#li-nacimiento').hide();
                            $('#li-monedero').hide();
                            $('#botones-guardar').hide();
                            $('#cliente-form').css('min-height','473px');
                        }
                    },
                    error: function(e){
                        console.log('error');
                    }
                });
    
            }
        }
    });
    dynamicPopup.open();
    

    
}

function muestraMonedero(id, nombre,monedero){
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p>'+
        '<input type="hidden" id="id-cliente" value="'+id+'">'+
               ' <div class="block-title block-title-medium" style="margin-top: 0;">Consulta monedero</div>'+
                '<div class="block" style="margin-bottom:0;">'+
                '<p style="font-size:16px;">Nombre: <b>'+nombre+'</b></p>'+
                '<p style="font-size:16px;">Monedro: <b><span id="monedero-actual">'+monedero.toFixed(2)+'</span></b> €</p>'+
                '<div class="data-table">'+
                    '<table>'+
                        '<thead>'+
                        '<th class="label-cell">Fecha</th>'+
                        '<th class="label-cell">Usuario</th>'+
                        '<th class="numeric-cell">Usado</th>'+
                        '<th class="numeric-cell">Acumuladdo</th>'+
                        '<th class="numeric-cell">Saldo</th>'+
                        '</thead>'+
                        '<tbody id="detalle-monedero-cliente">'+
                        '</tbody>'+
                    '</table>'+
        '<div class="data-table-footer" id="pagination-monedero-cliente">'+
        
        '</div>'+ 
                '</div>'+   
                '</div>'+         
            
            '<div class="list list-strong-ios list-dividers-ios inset-ios" style="margin-top: 0;margin-bottom: 15px;">'+
              '<ul>'+
                '<li class="item-content item-input">'+
                  '<div class="item-inner">'+
                    '<div class="item-title item-label">Nuevo monedero</div>'+
                    '<div class="item-input-wrap">'+
                      '<input type="text" id="nuevo-monedero" placeholder="Nuevo monedero" value="'+monedero.toFixed(2)+'" onfocus="$(\'#guarda-monedero\').show();">'+
                      '<span class="input-clear-button"></span>'+
                    '</div>'+
                  '</div>'+
                '</li>'+
                '</ul>'+
            '</div>'+
        '<div style="display:none;width: 50%;margin: auto;" id="guarda-monedero"><a class="button button-fill" onclick="GuardaMonedero();" href="#">Guardar</a></div>'+
        '</div>'+
          '</div>'  ,
        on: {
            open: function (popup) {   
                var server=servidor+'admin/includes/leemonederocliente.php';
                $.ajax({
                    type: "POST",
                    url: server,
                    data: {cliente:id},
                    dataType:"json",
                    success: function(data){
                        var obj=Object(data);
                        var fechaCas='';
                        if (obj.valid==true){
                            objmonedero=obj;
                            var pedido=obj.pedido;
                            var porpaginas=5;
                            var total=pedido.length;
                            var paginas=Math.floor(total/porpaginas);
                            if (total % porpaginas >0){
                               paginas++; 
                            };
                            versaldomonedero(paginas);
                            
                            
                        }
                    },
                    error: function(e){
                        console.log('error');
                    }
                });
                
            },
            close: function (){
                app.popup.close();
            }
        }
    });
    dynamicPopup.open();
            
}

function GuardaCliente(){
    
    var formData = app.form.convertToData('#cliente-form');
    var idCliente=formData['idCliente'];
    var tratamiento=formData['tratamiento'];
    var nombre=formData['nombre'];
    var apellidos=formData['apellidos'];
    var nacimiento=formData['nacimiento'];
    //console.log(nacimiento);
    var telefono=formData['telefono'];
    //var monedero=formData['monedero'];
    var usuario=formData['email'];
    var publi=formData['publi-checkbox'];
    if (publi!='1'){
        publi='0';  
    }
    var acepto=formData['privacidad-checkbox'];
    

    var server=servidor+'admin/includes/guardacliente.php';
    $.ajax({
        url: server,
        data:{idCliente:idCliente, nombre:nombre, apellidos:apellidos, nacimiento:nacimiento, telefono:telefono, usuario:usuario, publi:parseInt(publi), tratamiento:tratamiento},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                app.popup.close();
                var filtro=$('#filtro-cliente').val();
                
                clientes(1);
            }
        },
        error: function(e){
            console.log('error');
        }
    });
    
}
    
function versaldomonedero(pagina) {
    var obj=objmonedero;
    var pedido=obj.pedido;
    var idP=obj.id;
    var numero=obj.numero;
    var fecha=obj.fecha;
    var usado=obj.usado;
    var acumulado=obj.acumulado;
    var saldo=obj.saldo;
    var anulado=obj.anulado;
    var nick=obj.nick;
    var txt='';
    var porpaginas=5;
    var total=pedido.length;
    var paginas=Math.floor(total/porpaginas);
    if (total % porpaginas >0){
       paginas++; 
    };
    var desde=(pagina-1)*porpaginas;
    var hasta=pagina*porpaginas;
    if (hasta>total) {
        hasta=total;
    }
    var txt_pie="";
    txt_pie+=
    '<div class="data-table-rows-select">Paginas:'+paginas+'</div>'+
    '<div class="data-table-pagination">'+
        '<span class="data-table-pagination-label">'+pagina+' de '+paginas+'</span>';

    var anterior="";
    var siguiente="";

    if (paginas>1){
        var filtro=$('#filtro-cliente').val();
        if (pagina>1){

            txt_pie+='<a href="#" onclick="versaldomonedero('+(pagina-1)+');"class="link"><i class="icon icon-prev color-gray"></i></a>';
        }
        else {
            txt_pie+='<a href="#" class="link disabled"><i class="icon icon-prev color-gray"></i></a>';
        }

        if (pagina<(paginas)){

            txt_pie+='<a href="#" onclick="versaldomonedero('+(pagina+1)+');"class="link"><i class="icon icon-next color-gray"></i></a>';
        }
        else {
            txt_pie+='<a href="#" class="link disabled"><i class="icon icon-next color-gray"></i></a>';
        }

    }             

    txt_pie+='</div>';
    $('#pagination-monedero-cliente').html(txt_pie);
    var link='';
    var usuario='';
    for (var x=desde;x< hasta;x++){
       if (pedido[x]=='P') {
           link='onclick="verpedido('+idP[x]+','+anulado[x]+');"';
           usuario='Pedido';
        }
        else {
           link='';
            usuario=nick[x];
            
        }
    fechaCas=fecha[x].substr(8,2)+'/'+fecha[x].substr(5,2)+'/'+fecha[x].substr(0,4);
       txt+='<tr '+link+'>'+
        '<td class="label-cell">'+fechaCas+'</td>'+
        '<td class="label-cell">'+usuario+'</td>'+
        '<td class="numeric-cell">'+usado[x]+'</td>'+
        '<td class="numeric-cell">'+acumulado[x]+'</td>'+
        '<td class="numeric-cell">'+saldo[x].toFixed(2)+'</td>'+
           '</tr>';
    }
    $('#detalle-monedero-cliente').html(txt);
}

$('input#nuevo-monedero').blur(function(){
    var num = parseFloat($(this).val());
    var cleanNum = num.toFixed(2);
    $(this).val(cleanNum);
  });

$('input#nuevo-monedero').focus(function(){
    $('#guarda-monedero').show();
});



function GuardaMonedero(){
    var id=$('#id-cliente').val();
    var nMonedero=parseFloat($('#nuevo-monedero').val());
    var cleanNum = nMonedero.toFixed(2);
    $('#nuevo-monedero').val(cleanNum);
    if (cleanNum=='NaN'){
        app.dialog.alert('Error valor monedero');
        $('#nuevo-monedero').val('');
       return;
    }
    
    var aMonedero=parseFloat($('#monedero-actual').html());


   var aSumar=nMonedero-aMonedero;
    
    if (aSumar==0){
        app.popup.close();
    }
    var server=servidor+'admin/includes/guardamonedero.php';
    $.ajax({
        url: server,
        data:{cliente:id, aSumar:aSumar, monedero:nMonedero, usuario:idUsu},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                app.popup.close();
                //clientes();
            }
        },
        error: function(e){
            console.log('error');
        }
    });
    
}