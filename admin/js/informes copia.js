function informeglobal() {
    var txt='';
    var dynamicPopup = app.popup.create({
        content: ''+
          '<div class="popup">'+
            '<div class="block page-content">'+
              '<p class="text-align-right"><a href="#" class="link popup-close"><i class="icon f7-icons ">xmark</i></a></p><br><br>'+
                '<div class="title">Informe Global de pedidos</div>'+
                '<form>'+
                '<div class="list">'+
                    '<ul>'+
                       '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Desde</div>'+
                            '<div class="item-input-wrap">'+
                              '<input type="text" name="desde" placeholder="Fecha desde" value=""/>'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</li>'+ 
                      '<li>'+
                        '<div class="item-content item-input">'+
                          '<div class="item-inner">'+
                            '<div class="item-title item-label">Hasta</div>'+
                            '<div class="item-input-wrap" >'+
                              '<input type="text" name="hasta" value="" placeholder="Fecha hasta"  />'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                     '</li>'+
                     '<li>'+
                         '<a href="#" onclick="imprimeinforme(this);" data-id="global-pedidos" data-salida="pdf" class="item-link item-content">'+
                            '<div class="item-media"><i class="icon f7-icons pdf-icon"></i></div>'+
                            '<div class="item-inner">'+
                                '<div class="item-title">Pdf</div>'+
                            '</div>'+
                        '</a>'+
                    '</li>'+
                    '<li>'+
                         '<a href="#" onclick="imprimeinforme(this);" data-id="global-pedidos" data-salida="excel"" class="item-link item-content">'+
                            '<div class="item-media"><i class="icon f7-icons excel-icon"></i></div>'+
                            '<div class="item-inner">'+
                                '<div class="item-title">Excel</div>'+
                            '</div>'+
                        '</a>'+
                    '</li>'+
                   '</ul>'+   
                 '</div>'+

                '</form>'+
                
         
            '</div>'+
          '</div>'  ,
     on: {
            open: function (popup) {
                
                calendardesde = app.calendar.create({
                    inputEl: 'input[name=desde]',
                    value: [new Date()],
                    openIn: 'customModal',
                    //header: true,
                    closeOnSelect:true,
                    dateFormat: 'dd/mm/yyyy'
                    //footer: true,
                });
                calendarhasta = app.calendar.create({
                    inputEl: 'input[name=hasta]',
                    value: [new Date()],
                    openIn: 'customModal',
                    //header: true,
                    closeOnSelect:true,
                    dateFormat: 'dd/mm/yyyy'
                    //footer: true,
                });
                
            }
        }
    });  
    
    dynamicPopup.open(); 
    
}

function imprimeinforme(e){
    var id=e.getAttribute('data-id');
    var salida=e.getAttribute('data-salida');
    var errores="";

    if (id=='global-pedidos'){
        if ($('input[name=desde]').val()==""){
            errores+='Desde, ';
        }
        if ($('input[name=hasta]').val()==""){
            errores+='Hasta, ';
        }
        if (errores!='') {
            app.dialog.alert('Errores: '+errores);
        }
        else {
            if (salida=='pdf'){
                w=window.open("pdfs/listado_pedidos.php?startDate="+$('input[name=desde]').val()+"&endDate="+$('input[name=hasta]').val(),"_blank","width=800,height=500,top=0,left=0,scrollbars=no,resizable=yes,directories=no,location=no,menubar=no,status=yes,titlebar=yestoolbar=no"); 
                w.focus();
            }
            else {
                w=window.open("pdfs/listado_pedidos_excel.php?startDate="+$('input[name=desde]').val()+"&endDate="+$('input[name=hasta]').val(),"_blank","width=800,height=500,top=0,left=0,scrollbars=no,resizable=yes,directories=no,location=no,menubar=no,status=yes,titlebar=yestoolbar=no");
                
            }
        }
    }
}


