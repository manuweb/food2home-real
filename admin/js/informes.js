
function informes() {
   
    $(".title-informes").html("Informes <span style='float: right;font-weight: normal;font-size: 12px;'>Últimos 7 días de ventas</span>");
    $('#informes-page').show();
    $('#desarrollado-para').show();
    $('#informes-resultados-page').hide();
    
    $('#app-en-mantenimiento').show();
    $("#input_contenido_informes_filtro").val('');
    $("#input_informes_filtro").val('30');
    var txt_informes_filtro=''+
        '<button class="button button-small button-outline" style="width: 30px;" onclick="cambiaFiltroInformes(-1,1);"><</button>&nbsp;&nbsp;<button class="button button-small button-outline" style="width: auto;text-transform: unset;" id="cambia-filtro-informes" onclick="cambiaFiltroInformes(0,1);"><i class="f7-icons size-18">calendar</i>&nbsp;Últimos 30 días</button>&nbsp;&nbsp;<button class="button button-small button-outline" style="width: 30px;" onclick="cambiaFiltroInformes(1,1);">></button>';
    $("#informes-filtro").html(txt_informes_filtro);
    var server=servidor+'admin/includes/informe_ventas.php';           $.ajax({
        type: "POST",
        url: server,
        data: {rango:'limit',tipo:'productos'},
        dataType:"json",
        success: function(data){
            var obj=Object(data);
            //console.log(obj);
            if (obj.valid==true){
                var dataP=[];
                var dataP2=[];
                var dataP3=[];
                var dataP4=[];
                
                var fecha=obj.fecha;
                var cantidad=obj.cantidad;
                var suma_total=obj.suma_total;
                var nombreP=obj.nombreP;
                var cantidadP=obj.cantidadP;
                var sumEnv=parseInt(obj.cantEnvio)+parseInt(obj.cantRecoger);
                var sumPag=parseInt(obj.cantEfectivo)+parseInt(obj.cantTarjetas);
                
                var cantEnvio=(parseInt(obj.cantEnvio)*100/sumEnv).toFixed(2);
                var cantRecoger=(parseInt(obj.cantRecoger)*100/sumEnv).toFixed(2);
                var cantEfectivo=(parseInt(obj.cantEfectivo)*100/sumPag).toFixed(2);
                var cantTarjetas=(parseInt(obj.cantTarjetas)*100/sumPag).toFixed(2);
                
                if (fecha!=null){

                    var sumaTotal=0;
                    var cantidadTotal=0;
                    for (x=0;x<fecha.length;x++){

                        
                        dataP.push({ x: new Date(fecha[x]), y: Number(suma_total[x]),color: "#f35605"});
                        
                        dataP2.push({ x: new Date(fecha[x]), y: Number(cantidad[x]),color: "#f35605"});
                        
                        dataP3.push({ x: new Date(fecha[x]), y: Number( (parseFloat(suma_total[x])/ parseInt(cantidad[x])).toFixed(2)),color: "#f35605"});
                        
                        sumaTotal+=parseFloat(suma_total[x]);
                        cantidadTotal+=parseInt(cantidad[x]);
                    }
                    
                    for (x=0;x<nombreP.length;x++){
                        dataP4.push({ y: Number(cantidadP[x]),name:nombreP[x]});
                    }
                    
                    CanvasJS.addCultureInfo("es", 
                        {      
                            decimalSeparator: ",",// Observe ToolTip Number Format
                            digitGroupSeparator: ".", // Observe axisY labels                   
                            days: ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"],
                            shortMonths:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
                        });

                   
                    var chart = new CanvasJS.Chart("ventas-por-dia", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Ventas por día ("+(sumaTotal/fecha.length).toFixed(2)+" €)",
                            fontSize:16,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left" 
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                        toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5 
                         },
                        data: [{        
                            type: "line",
                            lineColor:"#f35605",
                            indexLabelFontSize: 16,
                            dataPoints: dataP
                        }]
                    });
                          
                    
                    var chart2 = new CanvasJS.Chart("pedidos-por-dia", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Pedidos por día ("+parseInt(cantidadTotal/fecha.length)+")",
                            fontSize:18,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left"
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                         toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5
                         },
                        data: [{        
                            type: "line",
                            lineColor:"#f35605",
                            indexLabelFontSize: 16,
                            dataPoints: dataP2

                        }]
                    });
                    
                    var chart3 = new CanvasJS.Chart("media-por-dia", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Pedido medio por día ("+((sumaTotal/fecha.length)/(cantidadTotal/fecha.length)).toFixed(2)+" €)",
                            fontSize:16,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left" 
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                        toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5 
                         },
                        data: [{        
                            type: "line",
                            lineColor:"#f35605",
                            indexLabelFontSize: 16,
                            dataPoints: dataP3
                        }]
                    });
                    
                    var chart4 = new CanvasJS.Chart("top-productos", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Top productos",
                            fontSize:16,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left" 
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                        toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5 
                         },
                        data: [{      
                            type: "pie",
                            radius:  "95%", 
                            //showInLegend: true,
                            toolTipContent: "{name}: <strong>{y}</strong>",
                            indexLabel: "{name}",
                            
                            dataPoints: dataP4
                        }]
                    });
                    
                    var chart5 = new CanvasJS.Chart("medios-pagos", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Medios de pago",
                            fontSize:16,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left" 
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                        toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5 
                         },
                        data: [{      
                            type: "pie",
                            radius:  "95%", 
                            //showInLegend: true,
                            toolTipContent: "{name}: <strong>{y}%</strong>",
                            indexLabel: "{name}",
                            
                            dataPoints: [
				                { y: cantTarjetas, name: "Tarjetas" }, 
                                { y: cantEfectivo, name: "Efectivo" }
                                ]
                        }]
                    });
                    
                    var chart6 = new CanvasJS.Chart("medios-envio", {
                        culture:  "es",
                        animationEnabled: true,
                        theme: "light2",
                        title:{
                            text: "Medios de envío",
                            fontSize:16,
                            margin: 30,
                            fontWeight: "bold",
                            horizontalAlign: "left" 
                        },
                        axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                        },
                        toolTip: {
                            fontColor: "#00495e",
                            borderColor:"#00495e",
                            cornerRadius: 5 
                         },
                        data: [{      
                            type: "pie",
                            radius:  "95%", 
                            //showInLegend: true,
                            toolTipContent: "{name}: <strong>{y}%</strong>",
                            indexLabel: "{name}",
                            
                            dataPoints: [
				                { y: cantEnvio, name: 'Domicilio' }, 
                                { y: cantRecoger, name: "Recogida" }
                            ]
                        }]
                    });
                    //console.log(dataPoints);  
                    
                    
                    chart.render();
                    chart2.render();
                    chart3.render();
                    chart4.render();
                    chart5.render();
                    chart6.render();
                    //
                    // eliminación marca de agua
                    //
                    $("span:contains('Mpgyi')" ).remove();
                }

            }
        },
        error: function(e){
            console.log('error');
        }
    });

}

function informeDetallado(id){
    var desde=new Date();
    var hasta=new Date();
    var dia = 1
    var title='';
    var contenido_informe_filtro=$("#input_contenido_informes_filtro").val();
    var informe_filtro=$("#input_informes_filtro").val();
    //informes-filtro
    var txt_informes_filtro=''+
        '<button class="button button-small button-outline" style="width: 30px;" onclick="cambiaFiltroInformes(-1,'+id+');"><</button>&nbsp;&nbsp;<button class="button button-small button-outline" style="width: auto;text-transform: unset;" id="cambia-filtro-informes" onclick="cambiaFiltroInformes(0,'+id+');"><i class="f7-icons size-18">calendar</i>&nbsp;Últimos 30 días</button>&nbsp;&nbsp;<button class="button button-small button-outline" style="width: 30px;" onclick="cambiaFiltroInformes(1,'+id+');">></button>';
    
    //$(".title-informes").html(title);
    $('#informes-page').hide();
    $('#desarrollado-para').hide();
    $('#informes-resultados-page').show();
    $('#app-en-mantenimiento').hide();
    $("#informes-filtro").html(txt_informes_filtro);
    if (id==1){
        title='Informe de ventas';
    }
    if (id==2){
        title='Informe Pedidos por día';
    }
    if (id==3){
        title='Informe Pedidos medio';
    }
    if (id==4){
        title='Informe Top productos';
    }
    if (id==5){
        title='Informe Medios de pago';
    }
    if (id==6){
        title='Informe Medios de envío';
    }
    $(".title-informes").html('<a href="javascript:navegar(\'#view-home\');informes();">Informes</a> -> '+title);
    if (informe_filtro=='30') {
        dia = 30;
        var addMlSeconds = 60*60000*24*dia;
        //paso el dia a segundos
        //dia = 24*60*60*dia;
        // ver fechas
        var numberOfMlSeconds=hasta.getTime();
        contenido_informe_filtro=$("#input_contenido_informes_filtro").val();
        if (contenido_informe_filtro==''){
            
            desde= new Date(numberOfMlSeconds - addMlSeconds); 

            $('#cambia-filtro-informes').html('<i class="f7-icons size-18">calendar</i>&nbsp;Últimos 30 días');
            $("#input_contenido_informes_filtro").val(pasaFechaPhp(desde)+' - '+pasaFechaPhp(hasta));
        }
        else {
            $('#cambia-filtro-informes').html('<i class="f7-icons size-18">calendar</i>&nbsp;'+$("#input_contenido_informes_filtro").val());
        }
        
        
       contenido_informe_filtro=$("#input_contenido_informes_filtro").val();
        
       
    }
    var server=servidor+'admin/includes/informe_ventas_detalle.php';   $.ajax({
        type: "POST",
        url: server,
        data: {rango:contenido_informe_filtro,tipo:id},
        dataType:"json",
        success: function(data){
            var obj=Object(data);

            if (obj.valid==true){
               
                muestraDatosInforme(data,id);     
            }
            else {
                $('#informes-resultados-completo').html('Sin datos');
                $('#tabla-resultados-completo').html('Sin datos');
            }
        },
        error: function(e){
            console.log('error');
        }
    });   
}

function muestraDatosInforme(data,id){
    //$('#informes-resultados-completo').html('');
    $('#tabla-resultados-completo').show();
    var obj=Object(data);
    CanvasJS.addCultureInfo("es", 
    {      
        decimalSeparator: ",",// Observe ToolTip Number Format
        digitGroupSeparator: ".", // Observe axisY labels                   
        days: ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"],
        shortMonths:["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
    });
    if (id==1){
        var dataP=[];
                
        var fecha=obj.fecha;
        var cantidad=obj.cantidad;
        var total=obj.total;
        var subtotal=obj.subtotal;
        var portes=obj.portes;
        var descuento=obj.descuento;
        var monedero=obj.monedero;
        var importe_fidelizacion=obj.importe_fidelizacion;
        if (fecha!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Pedidos</th>'+
                            '<th class="numeric-cell">Subtotal</th>'+
                            '<th class="numeric-cell">Portes</th>'+
                            '<th class="numeric-cell">Descuento</th>'+
                            '<th class="numeric-cell">Monedero</th>'+
                            '<th class="numeric-cell">Fidelizacion</th>'+
                            '<th class="numeric-cell">Total</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<fecha.length;x++){
                
                dataP.push({ x: new Date(fecha[x]), y: Number(total[x]),color: "#f35605"});
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+pasaFechadePhpaNormal(fecha[x])+'</th>'+
                        '<th class="numeric-cell">'+cantidad[x]+'</th>'+
                        '<th class="numeric-cell">'+subtotal[x]+'</th>'+
                        '<th class="numeric-cell">'+portes[x]+'</th>'+
                        '<th class="numeric-cell">'+descuento[x]+'</th>'+
                        '<th class="numeric-cell">'+monedero[x]+'</th>'+
                        '<th class="numeric-cell">'+importe_fidelizacion[x]+'</th>'+
                        '<th class="numeric-cell">'+total[x]+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);
                
       
            
            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Ventas por día",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                axisX:{
                  valueFormatString: "D MMM YYYY"
                  //tickLength: 0
                },
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{        
                    type: "line",
                    lineColor:"#f35605",
                    indexLabelFontSize: 16,
                    dataPoints: dataP
                }]
            });
            chart.render();
        }
        
    }

    if (id==2){
        var dataP=[];
                
        var fecha=obj.fecha;
        var cantidad=obj.cantidad;
        
        if (fecha!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Pedidos</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<fecha.length;x++){
                
                dataP.push({ x: new Date(fecha[x]), y: Number(cantidad[x]),color: "#f35605"});
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+pasaFechadePhpaNormal(fecha[x])+'</th>'+
                        '<th class="numeric-cell">'+cantidad[x]+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);

            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Pedidos por día",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                axisX:{
                  valueFormatString: "D MMM YYYY"
                  //tickLength: 0
                },
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{        
                    type: "line",
                    lineColor:"#f35605",
                    indexLabelFontSize: 16,
                    dataPoints: dataP
                }]
            });
            chart.render();
        }
        
    }

    if (id==3){
        var dataP=[];
                
        var fecha=obj.fecha;
        var cantidad=obj.cantidad;
        var total=obj.total;
        
        
        if (fecha!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Pedidos</th>'+
                            '<th class="numeric-cell">Total</th>'+
                            '<th class="numeric-cell">Media</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<fecha.length;x++){
                
                dataP.push({ x: new Date(fecha[x]), y: Number((total[x]/cantidad[x]).toFixed(2)),color: "#f35605"});
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+pasaFechadePhpaNormal(fecha[x])+'</th>'+
                        '<th class="numeric-cell">'+cantidad[x]+'</th>'+
                        '<th class="numeric-cell">'+total[x]+'</th>'+
                        '<th class="numeric-cell">'+(total[x]/cantidad[x]).toFixed(2)+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);

            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Pedidos medio por día",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                axisX:{
                  valueFormatString: "D MMM YYYY"
                  //tickLength: 0
                },
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{        
                    type: "line",
                    lineColor:"#f35605",
                    indexLabelFontSize: 16,
                    dataPoints: dataP
                }]
            });
            chart.render();
        }
        
    }
    
    if (id==4){
        var dataP=[];
                
        var nombreP=obj.nombreP;
        var cantidadP=obj.cantidadP;
        
        
        if (nombreP!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Producto</th>'+
                            '<th class="numeric-cell">Cantidad</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<nombreP.length;x++){
                
                dataP.push({ y: Number(cantidadP[x]),label:nombreP[x]});
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+nombreP[x]+'</th>'+
                        '<th class="numeric-cell">'+cantidadP[x]+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);

            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Top productos",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{        
                    type: "column",
                    //lineColor:"#f35605",
                    //indexLabelFontSize: 10,
                    //indexLabelWrap: false,
                    dataPoints: dataP
                }]
            });
            chart.render();
        }
        
    }
    
    if (id==6){
        var dataP=[];
        var dataP2=[];
                
    
        var Metodo1=obj.Metodo1;
        var Metodo2=obj.Metodo2;
        var fecha=obj.fecha;
        var TotMetodoEnvio=obj.TotMetodoEnvio;
        var TotMetodoRecoger=obj.TotMetodoRecoger;
        
        if (Metodo1!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Total Enviar</th>'+
                            '<th class="numeric-cell">Nº Envíos</th>'+
                            '<th class="numeric-cell">Total Recoger</th>'+
                            '<th class="numeric-cell">Nº Recoger</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<fecha.length;x++){
                dataP.push(
                    { x: new Date(fecha[x]), y: Number(parseFloat(Metodo1[x]).toFixed(2) ),legendText: "Enviar" });
                dataP2.push(
                    { x: new Date(fecha[x]), y: Number(parseFloat(Metodo2[x]).toFixed(2)),legendText: "Recoger"  });

                
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+pasaFechadePhpaNormal(fecha[x])+'</th>'+
                        '<th class="numeric-cell">'+parseFloat(Metodo1[x]).toFixed(2)+'</th>'+
                        '<th class="numeric-cell">'+TotMetodoEnvio[x]+'</th>'+
                        '<th class="numeric-cell">'+parseFloat(Metodo2[x]).toFixed(2)+'</th>'+
                        '<th class="numeric-cell">'+TotMetodoRecoger[x]+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);

            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Metodos de envío",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                axisX:{
                  valueFormatString: "D MMM YYYY"
                  //tickLength: 0
                },
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{      
                    type: "column",
                    showInLegend: true,
                    legendText: "Enviar",
                    dataPoints: dataP
                } , {     
                    type: "column",
                    showInLegend: true,
                    legendText: "Recoger",
                    dataPoints: dataP2
                }]
            });
            chart.render();
        }
        
    }
    
    if (id==5){
        var dataP=[];
        var dataP2=[];
                
    
        var Metodo1=obj.MetodoEfectivo;
        var Metodo2=obj.MetodoTarjeta;
        var fecha=obj.fecha;
        var TotMetodoEfectivo=obj.TotMetodoEfectivo;
        var TotMetodoTarjeta=obj.TotMetodoTarjeta;
        
        if (Metodo1!=null){
            var tabla_txt='<div class="data-table">'+
                '<table>'+
                    '<thead>'+
                        '<tr>'+
                            '<th class="label-cell">Fecha</th>'+
                            '<th class="numeric-cell">Total Efectivo</th>'+
                            '<th class="numeric-cell">Nº Efecivos</th>'+
                            '<th class="numeric-cell">Total Tarjetas</th>'+
                            '<th class="numeric-cell">Nº Tarjetas</th>'+
                        '</tr>'+
                    '<thead>'+
                    '<tbody>';
                
            for (x=0;x<fecha.length;x++){
                dataP.push(
                    { x: new Date(fecha[x]), y: Number(parseFloat(Metodo1[x]).toFixed(2) ),legendText: "Efectivo" });
                dataP2.push(
                    { x: new Date(fecha[x]), y: Number(parseFloat(Metodo2[x]).toFixed(2)),legendText: "Tarjeta"  });

                
                
                tabla_txt+=''+
                    '<tr>'+
                        '<th class="label-cell">'+pasaFechadePhpaNormal(fecha[x])+'</th>'+
                        '<th class="numeric-cell">'+parseFloat(Metodo1[x]).toFixed(2)+'</th>'+
                        '<th class="numeric-cell">'+TotMetodoEfectivo[x]+'</th>'+
                        '<th class="numeric-cell">'+parseFloat(Metodo2[x]).toFixed(2)+'</th>'+
                        '<th class="numeric-cell">'+TotMetodoTarjeta[x]+'</th>'+
                    '</tr>';
            }

              
            tabla_txt+='</tbody>'+
                    '</table>'+
                '</div>';                                                            
            $('#tabla-resultados-completo').html(tabla_txt);

            var chart = new CanvasJS.Chart("informes-resultados-completo", {
                culture:  "es",
                animationEnabled: true,
                theme: "light2",
                title:{
                    text: "Metodos de pago",
                    fontSize:16,
                    margin: 30,
                    fontWeight: "bold",
                    horizontalAlign: "left" 
                },
                axisX:{
                  valueFormatString: "D MMM YYYY"
                  //tickLength: 0
                },
                toolTip: {
                    fontColor: "#00495e",
                    borderColor:"#00495e",
                    cornerRadius: 5 
                 },
                data: [{      
                    type: "column",
                    showInLegend: true,
                    legendText: "Efectivo",
                    dataPoints: dataP
                } , {     
                    type: "column",
                    showInLegend: true,
                    legendText: "Tarjeta",
                    dataPoints: dataP2
                }]
            });
            chart.render();
        }
        
    }
    
}

function cambiaFiltroInformes(x,id){
    var desde=new Date();
    var hasta=new Date();
    var contenido_informe_filtro=$("#input_contenido_informes_filtro").val();
    var informe_filtro=$("#input_informes_filtro").val();
    if (informe_filtro=='30'){
        var fechas=pasaFechaFiltrosaJs(contenido_informe_filtro);
        var addMlSeconds = 60*60000*24*30;
        //paso el dia a segundos
        //dia = 24*60*60*dia;
        // ver fechas
        desde=fechas[0];
        hasta=fechas[1];
        var numberOfMlSeconds=desde.getTime();
        if (x<0){

            desde= new Date(numberOfMlSeconds - addMlSeconds); 
            numberOfMlSeconds=desde.getTime();
            hasta=new Date(numberOfMlSeconds + addMlSeconds); 

        }
        if (x>0){
            desde= new Date(numberOfMlSeconds + addMlSeconds); 
            numberOfMlSeconds=desde.getTime();
            hasta=new Date(numberOfMlSeconds + addMlSeconds); 
        } $("#input_contenido_informes_filtro").val(pasaFechaPhp(desde)+' - '+pasaFechaPhp(hasta));   
        $('#cambia-filtro-informes').html('<i class="f7-icons size-18">calendar</i>&nbsp;'+$("#input_contenido_informes_filtro").val());
        
        informeDetallado(id);
    }
    
}

function pasaFechaFiltrosaJs(valores){
    // 01/34/6789
    var fechas=valores.split(' - ');
    var aFechas=[new Date(fechas[0].substr(6,4)+'-'+fechas[0].substr(3,2)+'-'+fechas[0].substr(0,2)), new Date(fechas[1].substr(6,4)+'-'+fechas[1].substr(3,2)+'-'+fechas[0].substr(1,2))];
    return aFechas;
}

function pasaFechaPhp(fecha){
    var dia=fecha.getDate();
    if (dia<=9){
        dia='0'+dia;
    }
    var ano=fecha.getFullYear();
    var mes=fecha.getMonth()+1;
    if (mes<=9){
        mes='0'+mes;
    }
   
    return dia+'/'+mes+'/'+ano;
    
}

function pasaFechadePhpaNormal(fecha){
    // 0123-56-89
    return fecha.substr(8,2)+'/'+fecha.substr(5,2)+'/'+fecha.substr(0,4);
}