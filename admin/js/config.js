var colordeTemaAdmin=app.colors['primary'];
$('.demo-list-icon').css ('width','28px');
$('.demo-list-icon').css ('height','28px');
$('.demo-list-icon').css ('border-radius:','6px');
$('.demo-list-icon').css ('box-sizing','border-box');
$('.tab-link-estilo').css('flex-direction', 'column')
var patterntcolor='^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$';
var patterntcolorExp=/#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/g;
var patternTextoBotones='^[A-Za-z0-9\s]{4,15}$';
$('#texto-boton-inicio').prop("pattern",patternTextoBotones);
$('#texto-boton-menu').prop("pattern",patternTextoBotones);
$('#texto-boton-carrito').prop("pattern",patternTextoBotones);
//leeestilos();
function leeestilos(){
    var server=servidor+'admin/includes/leeestilos.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{id:'foo'},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
               //console.log(obj);
                   colores=app.utils.colorThemeCSSProperties(obj.primario);
                if (obj.breadcrumbs==1){
                    $('input[name=toggle-breadcrumbs]').prop('checked', true);
                }
                if (obj.modooscuro==1){
                    
                    $('input[name=toggle-oscuro]').prop('checked', true);
                    $('#elemento-oscuro').addClass('dark');
                     $('#elemento-oscuro').css('color','white');
                    $('#elemento-oscuro').css('background-color','black');
                }
                else {
                    $('input[name=toggle-oscuro]').prop('checked', false);
                    $('.toolbar-inner').css( "background-color","rgba(255, 255, 255, 0.7)" );
                }
                
                // estilo botones
                if (obj.estilo_boton_inicio==1){
                    $('#i-inicio').html('house_fill');
                }
                else {
                    $('#i-inicio').html('house_alt_fill');
                }
                if (obj.estilo_boton_menu==1){
                    $('#i-menu').html('restaurant');
                }
                else {
                    if (obj.estilo_boton_menu==2){
                        $('#i-menu').html('room_service');
                        
                    }
                    else {
                        $('#i-menu').html('fastfood');
                        
                    }
                }
                if (obj.estilo_boton_carrito==1){
                    $('#i-carrito').html('<span class="badge badage-cart">4</span>shopping_cart');
                    $('#i-carrito').removeClass('f7-icons');
                    $('#i-carrito').addClass('material-icons');
                }
                else {
                    $('#i-carrito').html('<span class="badge  badage-cart">4</span>cart_fill');
                    $('#i-carrito').removeClass('material-icons');
                    $('#i-carrito').addClass('f7-icons');
                }
                $('input[name=tipo-boton-inicio]').each(function() {
                    if ($(this).val()==obj.estilo_boton_inicio){
                        $(this).prop('checked',true);
                    }
                });
                
                $('input[name=tipo-boton-menu]').each(function() {
                    if ($(this).val()==obj.estilo_boton_menu){
                        $(this).prop('checked',true);
                    }
                });
                
                $('input[name=tipo-boton-carrito]').each(function() {
                    if ($(this).val()==obj.estilo_boton_carrito){
                        $(this).prop('checked',true);
                    }
                });
                $('input[name=tam-boton-menu]').each(function() {
                    if ($(this).val()==obj.tam_boton_menu){
                        $(this).prop('checked',true);
                    }
                });
                $('input[name=tipo-diseno]').each(function() {
                    if ($(this).val()==obj.estilo_app){
                        $(this).prop('checked',true);
                    }
                });
                
                if (obj.tam_boton_menu==1){
                    $('#i-menu').css('font-size','28px');
                    $('#i-menu').css('bottom','0');
                }
                else {
                    $('#i-menu').css('font-size','48px');
                    $('#i-menu').css('bottom','10px');
                }
                
                $('.button-primario').css('background-color',colores['light']["--f7-ios-primary"]);
                
                 $('#color-picker-primario').val(obj.primario);
                colores=app.utils.colorThemeCSSProperties(obj.secundario);
                $('.button-secundario').css('background-color',colores['light']["--f7-ios-primary"]);
                $('.badage-cart').css('background-color',colores['light']["--f7-ios-primary"]);
                $('#color-picker-secundario').val(obj.secundario);
            
                
                colorPickerWheelPrimario.value={hex: obj.primario};
                
                colorPickerWheelSecundario.value={hex: obj.secundario};
                
                
                $('#color-picker-primario-value').css('background-color',obj.primario);
                $('#color-picker-secundario-value').css('background-color',obj.secundario);
                
                $('#boton_inicio').html(obj.boton_inicio);
                $('#texto-boton-inicio').val(obj.boton_inicio);
                $('#boton_menu').html(obj.boton_menu);
                $('#texto-boton-menu').val(obj.boton_menu);
                $('#boton_carrito').html(obj.boton_carrito);
                $('#texto-boton-carrito').val(obj.boton_carrito);
                
                app.setColorTheme(obj.primario);
   
            }
            else{
                app.dialog.alert('No se pudo recuperar los estilos');
            }
        }
    });
    
}
$("#toggle-oscuro").change(function() {
    //console.log($('input[name=toggle-oscuro]').is(':checked'));
    if ($('input[name=toggle-oscuro]').is(':checked')){
    
        $('#elemento-oscuro').addClass('dark');
        $('#elemento-oscuro').css('color','white');
        $('#elemento-oscuro').css('background-color','black');
        $('.toolbar-inner').css( "background-color","rgba(0,0,0,0)" );
    }
    else {
        $('#elemento-oscuro').removeClass('dark');
        $('#elemento-oscuro').css('color','black');
        $('#elemento-oscuro').css('background-color','white');
        $('.toolbar-inner').css( "background-color","rgba(255, 255, 255, 0.7)" );
    }
  });

colorPickerWheelPrimario = app.colorPicker.create({
    inputEl: '#color-picker-primario-value',
    targetEl: '#color-picker-primario-value',
    targetElSetBackgroundColor: true,
    modules: ['sb-spectrum', 'hue-slider'],
    openIn: 'popover',
    value: {
      hex: '#FF0000',
    },
  });


colorPickerWheelSecundario = app.colorPicker.create({
        inputEl: '#color-picker-secundario-value',
        targetEl: '#color-picker-secundario-value',
        targetElSetBackgroundColor: true,
        modules: ['sb-spectrum', 'hue-slider'],
        openIn: 'popover',
        value: {
          hex: '#FF0000',
        },
      });


$('.pickcolor').prop("pattern",  patterntcolor );

$( "#color-picker-primario-value" ).on( "change", function() {
    
    $('#color-picker-primario').val(getBgColorHex('#color-picker-primario-value').toUpperCase());
    
    var color=$('#color-picker-primario').val();
    colores=app.utils.colorThemeCSSProperties(color);
    $('.button-primario').css('background-color',colores['light']["--f7-ios-primary"]);
    //console.log(colores);
    //$('.color-primario').css('color',colores['light']["--f7-ios-primary"]);
    app.setColorTheme(colores['light']["--f7-ios-primary"]);
    
    
});

$( "#color-picker-secundario-value" ).on( "change", function() {
    $('#color-picker-secundario').val(getBgColorHex('#color-picker-secundario-value').toUpperCase());
    var color=$('#color-picker-secundario').val();
    colores=app.utils.colorThemeCSSProperties(color);
    $('.button-secundario').css('background-color',colores['light']["--f7-ios-primary"]);
    $('.badage-cart').css('background-color',colores['light']["--f7-ios-primary"]);
} );

//cambios tipo boton
$('input[name=tipo-boton-inicio]').on( "change", function() {
    if ($(this).val()==1){
        $('#i-inicio').html('house_fill');
    }
    else {
        $('#i-inicio').html('house_alt_fill');
    }
});
$('input[name=tipo-boton-menu]').on( "change", function() {
    if ($(this).val()==1){
        $('#i-menu').html('restaurant');
    }
    else {
        if ($(this).val()==2){
            $('#i-menu').html('room_service');

        }
        else {
            $('#i-menu').html('fastfood');

        }
    }
});
$('input[name=tipo-boton-carrito]').on( "change", function() {
    if ($(this).val()==1){
        $('#i-carrito').html('<span class="badge badage-cart">4</span>shopping_cart');
        $('#i-carrito').removeClass('f7-icons');
        $('#i-carrito').addClass('material-icons');
    }
    else {
        $('#i-carrito').html('<span class="badge  badage-cart">4</span>cart_fill');
        $('#i-carrito').removeClass('material-icons');
        $('#i-carrito').addClass('f7-icons');
    }
    $('.badage-cart').css('background-color',$('#color-picker-secundario').val());
               
});
$('input[name=tam-boton-menu]').on( "change", function() {
    if ($(this).val()==1){
        $('#i-menu').css('font-size','28px');
        $('#i-menu').css('bottom','0');
    }
    else {
        $('#i-menu').css('font-size','48px');
        $('#i-menu').css('bottom','10px');
    }
});

$( "#color-picker-primario" ).on( "change", function() {

    var regExpEmail = patterntcolorExp.test($( "#color-picker-primario" ).val());
      if (regExpEmail) {
          console.log($('#color-picker-primario').val());
            $('#color-picker-primario-value').css('background-color',$('#color-picker-primario').val());
          var color=$('#color-picker-primario').val();
          colores=app.utils.colorThemeCSSProperties(color);
           $('.button-primario').css('background-color',colores['light']["--f7-ios-primary"]);
      }
} );

$( "#color-picker-secundario" ).on( "change", function() {
    var regExpEmail = patterntcolorExp.test($( "#color-picker-secundario" ).val());
      if (regExpEmail) {
            $('#color-picker-secundario-value').css('background-color',$('#color-picker-secundario').val());
        var color=$('#color-picker-secundario').val();
          colores=app.utils.colorThemeCSSProperties(color);
           $('.button-secundario').css('background-color',colores['light']["--f7-ios-primary"]);
      }
});



$('#texto-boton-inicio').on( "change", function() {
    $('#boton_inicio').html($('#texto-boton-inicio').val()); 
});
$('#texto-boton-menu').on( "change", function() {
    $('#boton_menu').html($('#texto-boton-menu').val()); 
});
$('#texto-boton-carrito').on( "change", function() {
    $('#boton_carrito').html($('#texto-boton-carrito').val()); 
});

$('.guardar-estilos').on( "click", function() {
    var texto_boton_inicio=$('#texto-boton-inicio').val();
    var texto_boton_menu=$('#texto-boton-menu').val();
    var texto_boton_carrito=$('#texto-boton-carrito').val();
    var tipo_boton_inicio=$('input[name=tipo-boton-inicio]:checked').val();
    var tipo_boton_menu=$('input[name=tipo-boton-menu]:checked').val();
    var tipo_boton_carrito=$('input[name=tipo-boton-carrito]:checked').val();
    
    var estilo_app=$('input[name=tipo-diseno]:checked').val();
    var tam_boton_menu=$('input[name=tam-boton-menu]:checked').val();
    var primario=$("#color-picker-primario").val();
    var secundario=$("#color-picker-secundario").val();
    var oscuro=0;
    var breadcrumbs=0;
    if ($('input[name=toggle-oscuro]').is(':checked')){
        oscuro=1;
    }
    if ($('input[name=toggle-breadcrumbs]').is(':checked')){
        breadcrumbs=1;
    }
    //console.log('Guardar');
    var server=servidor+'admin/includes/guardaestilos.php';
    $.ajax({
        type: "POST",
        url: server,
        dataType:"json",
        data:{oscuro:oscuro,primario:primario,secundario:secundario,texto_boton_inicio:texto_boton_inicio,texto_boton_menu:texto_boton_menu,texto_boton_carrito:texto_boton_carrito,tipo_boton_inicio:tipo_boton_inicio,tipo_boton_menu:tipo_boton_menu,tipo_boton_carrito:tipo_boton_carrito,tam_boton_menu:tam_boton_menu,breadcrumbs:breadcrumbs, estilo_app:estilo_app},
        success: function(data){
            var obj=Object(data);
            if (obj.valid==true){
                app.dialog.alert('Estilos guardados');
            }
            else {
                app.dialog.alert('Error guardando estilos');
            }
        }
    });
    
});



function getBgColorHex(elem){
    var color =  $(elem).css('background-color');
    var hex;
    if(color.indexOf('#')>-1){
        //for IE
        hex = color;
    } else {
        var rgb = color.match(/\d+/g);
        hex = '#'+ ('0' + parseInt(rgb[0], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2);
    }
    return hex;
}