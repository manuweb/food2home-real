$('#filtro-clientes-button').on('click', function(){
    var filtro=$('#filtro-cliente').val();
    var orden=$('input[name=filtro-cliente-orden]:checked').val();
    
    clientes(1,filtro, orden);
});



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
                var server=servidor+'admin/includes/leeuncliente.php';     $.ajax({
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
                              '<div class="item-inner">'+
                                '<div class="item-title item-label">Monedero</div>'+
                                '<div class="item-input-wrap">'+
                                  '<input type="text" name="monedero" placeholder="Monedero" value="'+obj.monedero+'" required validate '+disabled+'/>'+
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
function GuardaCliente(){
    var formData = app.form.convertToData('#cliente-form');
    var idCliente=formData['idCliente'];
    var tratamiento=formData['tratamiento'];
    var nombre=formData['nombre'];
    var apellidos=formData['apellidos'];
    var nacimiento=formData['nacimiento'];
    //console.log(nacimiento);
    var telefono=formData['telefono'];
    var monedero=formData['monedero'];
    var usuario=formData['email'];
    var publi=formData['publi-checkbox'];
    if (publi!='1'){
        publi='0';  
    }
    var acepto=formData['privacidad-checkbox'];
    

    var server=servidor+'admin/includes/guardacliente.php';
    $.ajax({
        url: server,
        data:{idCliente:idCliente, nombre:nombre, apellidos:apellidos, nacimiento:nacimiento, telefono:telefono, usuario:usuario, publi:parseInt(publi), tratamiento:tratamiento, monedero:monedero},
        method: "post",
        dataType:"json",
        success: function(data){ 
            var obj=Object(data);
            if (obj.valid==true){
                app.popup.close();
                var filtro=$('#filtro-cliente').val();
                clientes(1,filtro);
            }
        },
        error: function(e){
            console.log('error');
        }
    });
    
}
    

    