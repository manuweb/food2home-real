
//leepedidos(1);
$('#sync-pedidos').on('click', function(){
    $('#pedidos-calendar-range').val('');
    leepedidos(1,'');
});

$("input[name = 'filtro-envio']").change(function(){
    leepedidos(1,'');
});

$("input[name = 'filtro-metodo']").change(function(){
    leepedidos(1,'');
});

function leepedidos(pagina=1,fecha='') {
    var txt='';
    app.popup.destroy();
    var server=servidor+'admin/includes/leepedidos.php';             $.ajax({
        type: "POST",
        url: server,
        data: {pagina:pagina,fecha:$('#pedidos-calendar-range').val(), envio:$("input[name = 'filtro-envio']:checked").val(), metodo:$("input[name = 'filtro-metodo']:checked").val()},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                if  (integracion==2){
                    $('#label-num-revo').html('<i class="f7-icons">printer</i>');
                }
                var id=obj.id;             
                
                var numero=obj.numero;
                var numeroRevo=obj.numeroRevo;
                var fecha=obj.fecha;
                var hora=obj.hora;
                var cliente=obj.cliente;
                var apellidos=obj.apellidos;
                var nombre=obj.nombre;
                var ape_otro=obj.ape_otro;
                var nom_otro=obj.nom_otro;
                var subtotal=obj.subtotal;
                var impuestos=obj.impuestos;
                var descuentos=obj.descuentos;
                var envio=obj.envio;
                var metodo=obj.metodo;
                var portes=obj.portes;
                var total=obj.total;
                var canal=obj.canal;
                var estadoPago=obj.estadoPago;
                var anulado=obj.anulado;
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
                //alert (paginas);
                
                //console.log(hora)
                var txt="";
                txt_foot='<tr style="border-top: 1px solid #ababab;font-style: italic;font-size:16px;">'+
                    '<th class="label-cell"></th>'+
                    '<th class="label-cell"></th>'+
                    '<th class="label-cell"></th>'+
                    '<th class="label-cell"></th>'+
                    '<th class="label-cell"><br><b>Sumas</b></th>'+
                    '<th class="numeric-cell"><br>'+obj.sumaSubtotal+'</th>'+
                    //'<th class="numeric-cell">'+impuestos[x]+'</th>'+
                    '<th class="numeric-cell"><br>'+obj.sumaDescuentos+'</th>'+
                    '<th class="numeric-cell"><br>'+obj.sumaPortes+'</th>'+
                    '<th class="numeric-cell"><br>'+obj.sumaTotal+'</th>'+
                    '<th class="label-cell"></th>'+
                    '<th class="label-cell"></th></tr>';
                txt+=txt_foot;
                var link='';
                var linkRevo='';
                if (id!=null){
                for (x=0;x<id.length;x++){
                    
                    //<tbody id="tabla-pedidos">
                    fecha[x]=fecha[x].substr(8,2)+'/'+fecha[x].substr(5,2)+'/'+fecha[x].substr(0,4);
                    var img_reparto=servidor+'admin/img/reparto.png';
                    if (envio[x]=='2'){
                        img_reparto=servidor+'admin/img/recoger.png';
                    }
                    var img_pago=servidor+'admin/img/efectivo.png';
                    if (metodo[x]==idRedsys){
                        img_pago=servidor+'admin/img/tarjeta.png';
                    }
                    var nom_cliente="";
                    if (cliente[x]==0){
                        nom_cliente=ape_otro[x]+', '+nom_otro[x];
                    }
                    else {
                        nom_cliente=apellidos[x]+', '+nombre[x];
                    }
                    var img_canal="";
                    if (canal[x]=='2') {
                        //app
                        img_canal="device_phone_portrait";
                    }
                    else {
                        //web
                        img_canal="globe";
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
                    
                    link=' onclick="verpedido(\''+id[x]+'\','+anulado[x]+');"';
                    if (integracion==1){
                        linkRevo=link;
                    }
                    else { 
                        if (anulado[x]==0){
                            linkRevo=' onclick="imprimePedido(\''+id[x]+'\');"';
                        
                        }
                        else {
                            linkRevo='';
                        
                        }
                        
                        numeroRevo[x]='<i class="f7-icons size-20">printer</i>';
                    }
                    txt+=
                        '<tr '+color+'>'+
                            '<th class="label-cell"'+link+'>'+numero[x]+'</th>'+
                            '<th class="label-cell"'+linkRevo+'>'+numeroRevo[x]+'</th>'+
                            '<th class="label-cell"'+link+'>'+fecha[x]+'</th>'+
                            '<th class="label-cell"'+link+'>'+hora[x]+'</th>'+
                            '<th class="label-cell"'+link+'>'+nom_cliente+'</th>'+
                            '<th class="numeric-cell"'+link+'>'+subtotal[x]+'</th>'+
                            //'<th class="numeric-cell">'+impuestos[x]+'</th>'+
                            '<th class="numeric-cell"'+link+''+link+'>'+descuentos[x]+'</th>'+
                            '<th class="numeric-cell"'+link+'>'+portes[x]+'</th>'+
                            '<th class="numeric-cell"'+link+'>'+total[x]+'</th>'+
                            '<th class="label-cell"'+link+'><img src="'+img_reparto+'" width="24" height="auto"></th>'+
                        '<th class="label-cell"'+link+'><img src="'+img_pago+'" width="24" height="auto"></th></tr>';
                        
                    
 
                } 
                }
                
                    
                var txt_pie="";
                txt_pie+=""+
                    '<div class="data-table-rows-select">Paginas:'+paginas+'</div>'+
                    '<div class="data-table-pagination">'+
                    '<span class="data-table-pagination-label">'+pagina+' de '+paginas+'</span>';
                
            
                var anterior="";
                var siguiente="";
                
                    if (paginas>1){
                        
                        if (pagina>1){
                            
                            txt_pie+='<a href="#" onclick="leepedidos('+(pagina-1)+');"class="link"><i class="icon icon-prev color-gray"></i></a>';
                        }
                        else {
                            txt_pie+='<a href="#" class="link disabled"><i class="icon icon-prev color-gray"></i></a>';
                        }
                        
                        if (pagina<(paginas)){
                            
                            txt_pie+='<a href="#" onclick="leepedidos('+(pagina+1)+');"class="link"><i class="icon icon-next color-gray"></i></a>';
                        }
                        else {
                            txt_pie+='<a href="#" class="link disabled"><i class="icon icon-next color-gray"></i></a>';
                        }

                    }             
                    
                    txt_pie+='</div>';
                
                $('#tabla-pedidos').html(txt);
                //$('#tabla-pedidos-foot').html(txt_foot);
                $('#tabla-pedidos-pie').html(txt_pie);
                $('#tot-ped-reparto').html(obj.ped_reparto);
                $('#tot-ped-recoger').html(obj.ped_recoger);
                $('#tot-ped-tarjeta').html(obj.ped_tarjeta);
                $('#tot-ped-efectivo').html(obj.ped_efectivo);
            }
            else{
                //app.dialog.alert('No se pudo leer las push');
            }   
        },
        error: function(e){
            console.log('error');
        }
    });
    var calendarPedidosRange = app.calendar.create({
        inputEl: '#pedidos-calendar-range',
        rangePicker: true,
        //header: true,
        closeOnSelect:true,
        dateFormat: 'dd/mm/yyyy',
        header:true,
        headerPlaceholder:'Seleccione fechas',
        //toolbarCloseText:'Hecho',
        openIn:'sheet',
        on: {
            closed: function () {
                cambiavalorfechas();
                //calendarPedidosRange.close();
                //calendarPedidosRange.destroy();

            }
        }
    });

    
}

function cambiavalorfechas(){
    var fechas=$('#pedidos-calendar-range').val();
    //console.log (fechas[0]+'-'+fechas[1])
    app.calendar.destroy('#pedidos-calendar-range');
    leepedidos(1,fechas);
}

function verpedido(idpedido,anulado){
    
    var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><button class="button button-fill button-round color-red" id="boton-anular-pedido" onclick="anularPedido();" style="margin: auto;width: 30%;float: left;">ANUALAR</button><br><br>'+
                '<div id="detallepedido"></div>'+         
            '</div>'+
          '</div>'  ,
        on: {
         open: function (popup) {
            //$('#detallepedido').html(numero);
             
              var txt='';
            var server=servidor+'admin/includes/leeunpedido.php';     $.ajax({
                type: "POST",
                url: server,
                data: {idpedido:idpedido},
                dataType:"json",
                success: function(data){
                    var obj=Object(data);
                   //console.log(obj);
                     
                    if (obj.valid==true){
                        //console.log(obj.order);
                        var order=obj.order;
                        var carrito=order['carrito'];
                        /*
                        if (obj.estadoPago==-1){
                            anulado='<span style="color:red;"> --> <b>ANULADO</b></span>';
                        }
                        else {
                            anulado='';
                        }
                        */
                        var haymenu=0;
                        for(var x=0;x<carrito.length;x++){
                           if (carrito[x]['menu']>0){
                               haymenu=1;
                           } 
                        }
                        

                        var fecha=order['fecha'].substr(8,2)+'/'+order['fecha'].substr(5,2)+'/'+order['fecha'].substr(0,4);
                        txt +='Pedido: <b>'+order['pedido']+'</b> Fecha: <b>'+fecha+'</b>  ('+order['fecha'].substr(11,5)+') Hora:<b> '+order['hora']+'</b><br>';
                        //txt +='Pedido: <b>'+order['pedido']+'</b> Fecha: <b>'+fecha+'</b>  ('+order['fecha'].substr(11,5)+') Hora:<b> '+order['hora']+'</b> '+anulado+'<br>';
                        txt +='Nombre: <b>'+order['apellidos']+','+order['nombre']+'</b> <br>';
                        txt +='Teléfono: <b>'+order['telefono']+'</b> <br>';
                        txt +='Email: <b>'+order['email']+'</b> <hr>';
                        if (order['domicilio']['direccion']!=""){
                            txt +='<b>ENVIADO A</b>:<br>';
                            txt +='&emsp;'+order['domicilio']['direccion']+'<br>';
                            if (order['domicilio']['complementario']!=""){
                                txt +='&emsp;'+order['domicilio']['complementario']+'<br>';
                            }
                            txt +='&emsp;'+order['domicilio']['cod_postal']+' - '+order['domicilio']['poblacion']+' ('+order['domicilio']['provincia']+')<br>';
                        }
                        else {
                            txt +='<b>RECOGIDA EN LOCAL</b><br>';
                        }
                        if (order['comentario']!=''){
                            txt +='<hr>Notas: '+order['comentario'] ;
                        }
                        if (order['tarjeta']!=idRedsys){
                            txt +='<hr>Pago: <b>EFECTIVO</b><hr>';
                        }
                        else {
                            txt +='<hr>Pago: <b>TARJETA</b><hr>';
                        }
                        txt+='<div class="card data-table">'+
                            '<table>'+
                                '<thead>'+
                                    '<tr>'+
                                        '<th class="numeric-cell">Cantidad</th>'+
                                        '<th class="label-cell">Articulo</th>'+
                                        '<th class="numeric-cell">Precio</th>'+
                                        '<th class="numeric-cell">Total</th>'+
                                    '</tr>'+
                                '</thead>'+
                                '<tbody>';
                                    
                        for(var x=0;x<carrito.length;x++){
                            txt+=''+
                                    '<tr>'+
                                        '<th class="numeric-cell">'+carrito[x]['cantidad']+'</th>'+
                                        '<th class="label-cell">'+carrito[x]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['precio_sin']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['cantidad']*carrito[x]['precio']).toFixed(2)+'</th>'+
                                    '</tr>';

                            if (typeof carrito[x]['elmentosMenu']!='undefined'){
                                for(var j=0;j<carrito[x]['elmentosMenu'].length;j++){
                                    txt+=''+
                                    '<tr style="font-size:11px;font-style:italic;">'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell">'+carrito[x]['elmentosMenu'][j]['cantidad']+' x '+carrito[x]['elmentosMenu'][j]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['elmentosMenu'][j]['precio']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell"></th>'+
                                    '</tr>';
                                }
                            }   
                            if (typeof carrito[x]['modificadores']!='undefined'){
                                for(var j=0;j<carrito[x]['modificadores'].length;j++){
                                    txt+=''+
                                    '<tr style="font-size:11px;font-style:italic;">'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell">'+carrito[x]['modificadores'][j]['nombre']+'</th>'+
                                        '<th class="numeric-cell">'+parseFloat(carrito[x]['modificadores'][j]['precio']).toFixed(2)+'</th>'+
                                        '<th class="numeric-cell"></th>'+
                                    '</tr>';
                                }
                            }      
                            if (carrito[x]['comentario']!=''){
                                txt+=''+
                                    '<tr>'+
                                        '<th class="numeric-cell"></th>'+
                                        '<th class="label-cell"><i>'+carrito[x]['comentario']+'</i></th><th class="numeric-cell"></th><th class="numeric-cell"></th>'+
                                    '</tr>';
                            }    
                        }
                        txt+=''+
                            '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Subtotal</th>'+
                                '<th class="numeric-cell">'+(parseFloat(order['subtotal'])).toFixed(2)+'</th>'+
                            '</tr>';
                        if (order['portes']!=0){
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Portes</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['portes']).toFixed(2)+'</th>'+
                            '</tr>';
                        }      
                        if (order['descuento']!=0){
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Descuento</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['descuento']).toFixed(2)+'</th>'+
                            '</tr>';
                        }
                         if (order['monedero']!=0){
                             
                           txt+= '<tr>'+
                                '<th class="numeric-cell"></th>'+
                                '<th class="label-cell"></th>'+
                                '<th class="label-cell">Monedero</th>'+
                                '<th class="numeric-cell">'+parseFloat(order['monedero']).toFixed(2)+'</th>'+
                            '</tr>';
                        }

                        /*
                        txt+='<tr>'+
                                    '<th class="numeric-cell"></th>'+
                                    '<th class="label-cell"></th>'+
                                    '<th class="label-cell">Impuestos</th>'+
                                    '<th class="numeric-cell">'+parseFloat(impuestos).toFixed(2)+'</th>'+
                                '</tr>';
                        */                                  
                        txt+='<tr>'+
                            '<th class="numeric-cell"></th>'+
                            '<th class="label-cell"></th>'+
                            '<th class="label-cell">Total</th>'+
                            '<th class="numeric-cell">'+parseFloat(order['total']).toFixed(2)+'</th>'+
                        '</tr>';  
                            
                        txt+=''+
                                '</tbody>'+
                            '</table><br><br>'+
                        '</div>';
                    }
                    $('#detallepedido').html(txt);
                    
                    if (anulado>=1){
                        $('#boton-anular-pedido').attr("onclick","");
                        $('#boton-anular-pedido').removeClass('button-fill');
                        $('#boton-anular-pedido').addClass('button-outline');
                        $('#boton-anular-pedido').html('YA ANULADO');
                        if (anulado==2){
                            $('#boton-anular-pedido').html('ES ANULACIÓN');
                        }
                        
                        //$('#boton-anular-pedido').hide();
                        
                    }
                    else {
                        
                        var hoy=new Date().getTime();;
                        var lafecha = new Date(order['fecha'].substr(0,10)).getTime();
                        var dias=parseInt((hoy-lafecha)/(1000*60*60*24));
                        if (dias>14){
                            $('#boton-anular-pedido').hide();
                        }
                        else {
                            $('#boton-anular-pedido').attr("onclick","anularPedido("+idpedido+",'"+order['pedido']+"')");
                        }
                        
                    }
                },
                error: function (e){
                    console.log('error');
                }
            });
            
            $('#detallepedido').html(txt);
         },
          opened: function (popup) {
            //console.log('Popup opened');
          },
        }
    });  
        
 
    
    dynamicPopup.open(); 

}

function anularPedido(idpedido, numero){
    app.dialog.confirm('¿Borrar pedido <b>'+numero+'</b>?',function(){
        var server=servidor+'admin/includes/anulapedido.php';     $.ajax({
            type: "POST",
            url: server,
            data: {idpedido:idpedido},
            dataType:"json",
            success: function(data){
                var obj=Object(data);
                
                if (obj.valid==true){
                    app.popup.close();
                    console.log(obj);
                    var metodoPago=obj.metodoPago;
                    var id_antigua=obj.id_antigua;
                    var id_nueva=obj.id_nueva;
                    var tarjeta=false;
                    if (metodoPago==idRedsys){
                        tarjeta=true;
                    }
                    if (integracion==1){
                        // revo
                         
                    }
                    else {
                        // star
                        
                    }
                    console.log(idRedsys);
                    if (metodoPago==idRedsys){
                        // redsys
                        // tienda, id_antigua
                        
                        var server=servidor+'admin/includes/tvpdevolucion.php'; 
                        $.ajax({
                            url: server,
                            data:{idpedido:id_antigua },
                            method: "post",
                            dataType:"json",
                            success: function(data){ 
                                var obj=Object(data);
                                if (obj.valid==true){
                                    
                                    app.dialog.alert('Tarjeta anulada correctamente<br><br>Importe:'+obj.importe+' €');
                                    
                                }
                                else {
                                    app.dialog.alert('Error anulando pago tarjeta');
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError){
                                console.log(xhr.status);
                                console.log(thrownError);
                            }
                        });  
                        
                        
                                
                    }
                    var server=servidor+'webapp/includes/envioemail.php'; 
                    $.ajax({
                        url: server,
                        data:{tipo:'devolucion',idpedido:id_antigua },
                        method: "post",
                        dataType:"json",
                        success: function(data){ 
                            var obj=Object(data);
                            if (obj.valid==true){

                            }
                            else {

                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError){
                            console.log(xhr.status);
                            console.log(thrownError);
                        }
                    });  
                    leepedidos();
                }
            },
            error: function (xhr, ajaxOptions, thrownError){
                console.log(xhr.status);
                console.log(thrownError);
            }
        });          
    })
    
}

function imprimePedido(idpedido){
    
    var server=servidor+'admin/includes/imprimeticket.php';     $.ajax({
        type: "POST",
        url: server,
        data: {idpedido:idpedido},
        dataType:"json",
        success: function(data){
            var obj=Object(data);

            if (obj.valid==true){
                app.dialog.alert('Ticket enviado a impresora');
            }
        },
        error: function (xhr, ajaxOptions, thrownError){
            console.log(xhr.status);
            console.log(thrownError);
        }
    });    
    
}
