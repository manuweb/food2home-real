
leepedidos(1);
function leepedidos(pagina=1) {
    
    if (autentificado!=1){
        location.href='index.php';
        
    }
    else {
        //console.log('autentificado:'+autentificado);
        var server=servidor+'admin/includes/logout.php';
        $.ajax({
            type: "POST",
            url: server,
            data:{username:'username'},
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
    var txt='';
    var server=servidor+'admin/includes/leepedidos_motos.php';       $.ajax({
        type: "POST",
        url: server,
        data: {pagina:pagina},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){   
                
                var idPedido=obj.idPedido;
 //"idPedido"=>$idPedido,"numero"=>$numero,"fecha"=>$fecha,cliente"=>$cliente,"apellidos"=>$apellidos,"nombre"=>$nombre,"ape_otro"=>$ape_otro,"nom_otro"=>$nom_otro,"subtotal"=>$subtotal,"impuestos"=>$impuestos ,"descuentos"=>$descuentos,"total"=>$total,"envio"=>$envio,"metodo"=>$metodo,"registros"=>$numReg               
                
                var numero=obj.numero;
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
                var total=obj.total;
                var canal=obj.canal;
                var estadoPago=obj.estadoPago;
                
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
                var txt="";
                for (x=0;x<idPedido.length;x++){
                    
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
                    var color='';
                    if (estadoPago[x]==-1){
                        //anulado
                        color='style="color:red;"';
                    }
                    txt+=
                        '<tr onclick="verpedido(\''+idPedido[x]+'\');" '+color+'>'+
                            '<th class="label-cell">'+numero[x]+'</th>'+
                            '<th class="label-cell">'+fecha[x]+'</th>'+
                            '<th class="label-cell">'+hora[x]+'</th>'+
                            '<th class="label-cell">'+nom_cliente+'</th>'+
                            '<th class="numeric-cell">'+total[x]+'</th>'+
                        '</tr>';
 
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
                $('#tabla-pedidos-pie').html(txt_pie);
                
            }
            else{
                //app.dialog.alert('No se pudo leer las push');
            }   
        },
        error: function(e){
            console.log('error');
        }
    });
    
}

function verpedido(idpedido){
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
                //var carrito=order['carrito'];
                
                
                fecha=order['fecha'].substr(8,2)+'/'+order['fecha'].substr(5,2)+'/'+order['fecha'].substr(0,4);
                var txt ='';
                if (order['tratamiento']==1){
                    tratamiento='Sr.';
                }
                else {
                    tratamiento='Sra.';
                }
                txt +='Cliente: <b>'+tratamiento+' '+order['nombre']+' '+order['apellidos']+'</b><br>';
                txt +='Teléfono: <b><a href="tel:'+order['telefono']+'">'+order['telefono']+'</a></b> <br>';

     
                
                if (order['domicilio']['direccion']!=""){
                    var txtDir=order['domicilio']['direccion']+'+'+order['domicilio']['cod_postal']+'+'+order['domicilio']['poblacion']+'+'+order['domicilio']['provincia'];
                    txtDir=txtDir.replace(" ", "+");
                    txt +=order['domicilio']['direccion']+'<br>';
                    if (order['domicilio']['complementario']!=""){
                        txt +=order['domicilio']['complementario']+'<br>';
                    }
                    txt +=order['domicilio']['cod_postal']+' - '+order['domicilio']['poblacion']+' ('+order['domicilio']['provincia']+')<br>';
                }
                

                
                app.dialog.confirm(txt+'¿Abrir Ruta?', function () {
                    window.open("https://www.google.cl/maps/place/"+txtDir); 
                     
                });
                
            }
            //$('#detallepedido').html(txt);

       },
        error: function(e){
            console.log('error');
        }
    });

    
    
}



