<?php
session_start(); 
if (!isset($_SESSION["autentificado"])){
    $_SESSION["autentificado"]=false;
}
include '../webapp/conexion.php';
include "../webapp/MySQL/DataBase.class.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src * self blob: data: gap:; style-src * self 'unsafe-inline' blob: data: gap:; script-src * 'self' 'unsafe-eval' 'unsafe-inline' blob: data: gap:; object-src * 'self' blob: data: gap:; img-src * self 'unsafe-inline' blob: data: gap:; connect-src self * 'unsafe-inline' blob: data: gap:; frame-src * self blob: data: gap:;">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#000">
    <meta name="background-color" content="#7fcdcd">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
    
    <title>Demo Revo - BackOffice</title>
  
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="assets/icons/apple-touch-icon.png">
    <link rel="icon" href="assets/icons/favicon.png">
    <link rel="stylesheet" href="framework7/framework7-bundle.min.css">
    <link rel="stylesheet" href="css/icons.css">
    <link rel="stylesheet" href="css/app.css">
    
    <script src="../js/jquery.min.js"></script>  
    


</head>
<body>
    
    <div id="app">
        <?php
        //if ($_SESSION["autentificado"]) { 
        ?> 
        <div class="panel-left panel-reveal" style="background-color: #00495E;color: white;position: absolute;width: 260px;display: none;height: 100%;">
            <div class="navbar no-outline">
                <div class="navbar-bg" style="background-color:#00495E;color:white;"></div>
                <div class="navbar-inner">
                    <div class="title"><img src="img/logo-white.png" style="width: 90%;"></div>
                    <div class="right" style='color: white;' onclick="cierrapanel();"><i class="icon f7-icons">xmark</i></div>
                </div>
            </div>
            <p class="text-align-center color-white" style="margin: 0;">BackOffice</p>
            <div class="page-content" style="margin-top: -25px; overflow: hidden;">

                
                <div class="list accordion-list" style="margin-top:0;margin-bottom: -40px;" id="lista-menu">
                    <ul>          
                      <li>
                        <a href="javascript:navegar('#view-home');informes();" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">house</i>
                        </div>
                          <div class="item-inner">
                            <div class="item-title">Inicio</div>
                          </div>
                        </a>
                      </li>
                        
                        <li class="accordion-item info">
                            <a class="item-content item-link" href="#">
                                <div class="item-media"><i class="icon f7-icons tienda-icon"></i></div>
                                <div class="item-inner"><div class="item-title">Productos</div></div>
                            </a>
                            <div class="accordion-item-content">
                                <div class="list">
                                    <ul>   
                                        <li>
                                            <a href="javascript:navegar('#view-productos');" class="item-link item-content"> 
                                                <div class="item-media"><i class="icon f7-icons trans-icon"></i></div>
                                              <div class="item-inner">
                                                <div class="item-title">Grupos</div>
                                              </div>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="javascript:navegar('#view-modificadores');" class="item-link item-content">  
                                                <div class="item-media"><i class="icon f7-icons trans-icon"></i></div>
                                              <div class="item-inner">
                                                <div class="item-title">Modificadores</div>
                                              </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:navegar('#view-modificadores2');" class="item-link item-content">  
                                                <div class="item-media"><i class="icon f7-icons trans-icon"></i></div>
                                              <div class="item-inner">
                                                <div class="item-title">Grupo Modificadores</div>
                                              </div>
                                            </a>
                                        </li>
                                     </ul>
                                </div>
                            </div>
                        </li>
                       <li id="in_push">
                        <a href="javascript:navegar('#view-push');" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">bell</i></div>
                          <div class="item-inner">
                            <div class="item-title">Mensajes Push</div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="javascript:navegar('#view-emails');" class="item-link item-content">
                            <div class="item-media"><i class="icon f7-icons ">envelope</i></div>
                          <div class="item-inner">
                            <div class="item-title">Emails</div>
                          </div>
                        </a>
                      </li> 
                      <li>
                        <a href="javascript:navegar('#view-clientes');clientes();" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons clientes-icon"></i></div>
                          <div class="item-inner">
                            <div class="item-title">Clientes</div>
                          </div>
                        </a>
                      </li>  
                      <li>
                        <a href="javascript:navegar('#view-pedidos');leepedidos(1);" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons pedidos-icon"></i></div>
                          <div class="item-inner">
                            <div class="item-title">Pedidos</div>
                          </div>
                        </a>
                      </li>  
                      <li id="in_prom">
                        <a href="javascript:navegar('#view-promos');promociones();" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">tickets</i></div>
                          <div class="item-inner">
                            <div class="item-title">Promociones</div>
                          </div>
                        </a>
                      </li> 
                        <li>
                        <a href="javascript:navegar('#view-fidelidad');leegruposfidelizacion();" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">giftcard</i></div>
                          <div class="item-inner">
                            <div class="item-title">Fidelización</div>
                          </div>
                        </a>
                      </li> 
                        
                        <li>
                        <a href="javascript:navegar('#view-home');informes(1);" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">printer</i></div>
                          <div class="item-inner">
                            <div class="item-title">Informes</div>
                          </div>
                        </a>
                      </li> 

                      <li>
                        <a href="javascript:navegar('#view-setting');" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons">gear_alt</i></div>
                          <div class="item-inner">
                            <div class="item-title">Ajustes</div>
                          </div>
                        </a>
                      </li> 
                      <li>
                        <a href="javascript:navegar('#view-usuario');" class="item-link item-content">
                          <div class="item-media"><i class="icon f7-icons if-not-md">person_crop_circle</i>
                            <i class="icon material-icons if-md">person</i></div>
                          <div class="item-inner">
                            <div class="item-title">Mis datos</div>
                          </div>
                        </a>
                      </li> 
                    </ul>
                  </div>
                <br><br><br>

                <div style="text-align:center;">
                    <p style="text-align:right;margin-right:15px;font-size:10px;">Integración </p>
                    <img src='img/logo-revo.png' width="auto" height="60px" id='logo-integracion'>
                    <p style="text-align:right;margin-right:15px;font-size:10px;" id="version-backoffice"></p>
                </div>
            </div>
             
            
        </div>
        <?php
        //}
        ?> 
        <div class="views tabs safe-areas">  

            <div class="view view-main tab tab-active" id="view-home">
                <div data-name="home" class="page">
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>

                    <!-- Scrollable page content -->
                    <div class="page-content">
                        <?php
                        if ($_SESSION["autentificado"]!=1) { 
                        ?>
                        
                        <!-- Login Screen -->            

                        <div class="page-content login-screen-content" id="my-login-screen">
                            <div class="login-screen-title">Acceso</div>
                            <div class="list">
                              <ul>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Usuario</div>
                                    <div class="item-input-wrap">

                                      <input type="text" name="username" placeholder="Tu usuario"/>
                                    </div>
                                  </div>
                                </li>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Contraseña</div>
                                    <div class="item-input-wrap">

                                      <input type="password" name="password" placeholder="Tu contraseña"/>
                                    </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                            <div class="list">
                              <ul>
                                <li>

                                  <button class="button button-fill login-button" style="width:50%;margin: auto;">Acceder</button>
                                </li>
                              </ul>
                              <div class="block-footer text-color-red" id="error-login" style="display:none;">Error en usuario y contraseña</div>
                              <div class="block-footer"><a href="">¿Olvidó su contraseña?</a><br/>Clic en <b>Acceder</b> para iniciar sesión.</div>
                            </div>
                          </div>

                        <div class="page-content login-screen-content" id="first-screen" style="display:none;">
                            <div class="login-screen-title">1ª Configuración</div>
                            <div id="breadcrumbs" class="login-screen-title"></div>

                            <div class="list primera-configuracion">
                              <ul>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Nombre empresa</div>
                                    <div class="item-input-wrap">

                                      <input type="text" name="nombre_empresa" placeholder="Nombre empresa"/>
                                    </div>
                                  </div>
                                </li>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Nombre comercia</div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="nombre_comercial" placeholder="Nombre comercial"/>
                                    </div>
                                  </div>
                                </li>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">NIF/CIF</div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="cif_empresa" placeholder="CIF o NIF"/>
                                    </div>
                                  </div>
                                </li>
                                  <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Domicilio</div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="domicilio_empresa" placeholder="Domicilio Discal"/>
                                    </div>
                                  </div>
                                </li>
                                  <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Cod. Postal </div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="cp_empresa" placeholder="Código Postal"/>
                                    </div>
                                  </div>
                                </li>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Población </div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="poblacion_empresa" placeholder="Población"/>
                                    </div>
                                  </div>
                                </li>
                                  <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Provincia </div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="provincia_empresa" placeholder="Provincia"/>
                                    </div>
                                  </div>
                                </li>
                                <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Teléfono </div>
                                    <div class="item-input-wrap">
                                      <input type="text" name="telefono_empresa" placeholder="Teéfono"/>
                                    </div>
                                  </div>
                                </li>

                              </ul>
                            </div>

                             <div class="list segunda-configuracion" style="display:none;">
                              <ul>
                                   <li class="item-content item-input">
                                  <div class="item-inner">
                                    <div class="item-title item-label">Tipo integración</div>
                                    <div class="item-input-wrap">

                                        <div class="grid grid-cols-2 grid-gap">
                                      <div class="text-align-center"><span><label class="radio"><input type="radio" name="tipo_integracion" value="1" checked/><i class="icon-radio"></i></label></span><span><img src='img/logo-revo.png' width="70%" height="auto" style="vertical-align: middle;"></span></div>
                                      <div class="text-align-center"><span><label class="radio"><input type="radio" name="tipo_integracion" value="2"/><i class="icon-radio"></i></label></span> <span><img src='img/logo-star.png' width="70%" height="auto" style="vertical-align: middle;"></span></div>
                                        </div>
                                      </div>
                                       </div>
                                  </li>
                              </ul>
                            </div>
                             <div class="tercera-configuracion" style="display:none;">
                                <div class="list revo-configuracion" style="display:none;">
                                  <ul>
                                        <li class="item-content item-input">
                                          <div class="item-inner">
                                            <div class="item-title item-label">Usuario Revo</div>
                                            <div class="item-input-wrap">

                                              <input type="text" name="usuario_revo" placeholder="Usuario Revo"/>
                                            </div>
                                          </div>
                                        </li>
                                        <li class="item-content item-input">
                                          <div class="item-inner">
                                            <div class="item-title item-label">Token Revo</div>
                                            <div class="item-input-wrap">

                                              <input type="text" name="token_revo" placeholder="Token Revo"/>
                                            </div>
                                          </div>
                                        </li>
                                    </ul>


                                </div>
                                 <div class="list star-configuracion" style="display:none;">
                                    <h3>Configuración impresora</h3>
                                    <div id="deviceList">Buscando . . . </div>
                                    <p>Para conectar un nuevo dispositivo, configure su URL de CloudPRNT en:</p>
                                    <br/>
                                    <div style="background-color: whitesmoke; font-weight: bold;border-color: black;border-style: solid;border-width: 1px;border-radius: 4px;padding: 4px;display: inline-block;"><span id="cpurl">...</span></div>
                                </div>
                            </div>

                            <div class="list">
                              <ul>
                                <li>
                                  <button class="button button-fill primera-confirmar-button" style="width:50%;margin: auto;" data-id='1'>continuar</button>
                                </li>
                              </ul>
                            </div>

                          </div>
                        <?php
                        }
                        else {
             
                        ?>

                        <input type="hidden" value="30" id="input_informes_filtro">
                      <input type="hidden" value="" id="input_contenido_informes_filtro">
                    <div class="grid grid-gap medium-grid-cols-2  xsmall-grid-cols-1" style="margin-bottom: -30px"id="grid-app-en-mantenimiento">
                      <div class="block block-strong inset list" id="app-en-mantenimiento" style="margin-bottom: 10px;margin-top: 15px;">
                            <ul>
                                <li>
                                    <div class="item-content">
                                        <div class="item-inner">
                                            <div class="item-title" style="font-size:12px;">Web/App en mantenimiento</div>
                                            <div class="item-after">
                                                <label class="toggle toggle-init">
                                                  <input type="checkbox" id="web-app-off" onclick="cambiaEstadoWeb(this);"><i class="toggle-icon"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>   
                                </li>   
                            </ul> 
                        </div> 
                         <div class="block block-strong inset" id="status-imprespra" style="display:none; margin-bottom: 10px;margin-top: 15px;">
                            <p style="margin-top: 10px;"> <span id="printer-name">Estado</span> <span id="status-imprespra-icon" style="float: right"><i class="f7-icons">printer</i></span></p>
                        </div>
                        </div>
                        <div class="block-title block-title-medium title-informes">Informes</div>
                        <div id="informes-page"> 
                            <div class="grid grid-gap medium-grid-cols-3  xsmall-grid-cols-1" style="padding: 15px;padding-bottom: 0;">
                                <div class="block block-strong inset" style="margin: 0;">
                                    <div id='ventas-por-dia' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(1);">Ver informes</a></div>
                                </div>
                                <div class="block block-strong inset" style="margin: 0;" >
                                    <div id='pedidos-por-dia' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(2);">Ver informes</a></div>
                                </div>
                                <div class="block block-strong inset" style="margin: 0;" >
                                    <div id='media-por-dia' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(3);">Ver informes</a></div>
                                </div>
                                <div class="block block-strong inset" style="margin: 0;" >
                                    <div id='top-productos' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(4);">Ver informes</a></div>
                                </div>
                                <div class="block block-strong inset" style="margin: 0;" >
                                    <div id='medios-pagos' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(5);">Ver informes</a></div>
                                </div>
                                <div class="block block-strong inset" style="margin: 0;" >
                                    <div id='medios-envio' style="height: 150px; width: 100%;"></div>
                                    <div style="margin-top: 15px;"><a href="#" onclick="informeDetallado(6);">Ver informes</a></div>
                                </div>
                            </div>
                        </div>
                        <div id="informes-resultados-page" style="display:none;">
                            
                            <div class="block block-strong inset" id='informes-filtro' style="display: flex;">
                               
    
                           </div> 
                            <div class="block block-strong inset">
                                <div id='informes-resultados-completo' style="height: 300px; width: 100%;"></div>
                            </div>
                            <div class="block block-strong inset">
                                <div id='tabla-resultados-completo' style="width: 100%;"></div>
                            </div>
                        </div>
                        
                      <div class="block block-strong inset list" id="app-novedades" style="margin-bottom: 10px;margin-top: 15px;">
                          <p style=""display: flex;><i class="f7-icons size-20">doc_text</i> Registro de cambios</p>
                          <div class="block" style="font-size: 0.8em;margin-top: 0;margin-bottom: 0;" id="app-novedades-txt">
                              <p>lorep insum lorep insum lorep insum lorep insum v lorep insum lorep insum lorep insum lorep insum lorep insum v lorep insum</p>
                              <p>lorep insum lorep insum lorep insum lorep insum v lorep insum lorep insum lorep insum lorep insum lorep insum v lorep insum</p>
                              <p>lorep insum lorep insum lorep insum lorep insum v lorep insum lorep insum lorep insum lorep insum lorep insum v lorep insum</p>
                              <p>lorep insum lorep insum lorep insum lorep insum v lorep insum lorep insum lorep insum lorep insum lorep insum v lorep insum</p>
                          </div>
                        </div>
                        
                        <!--
                        <div class="block block-strong inset grid grid-cols-2" style="margin-top: 15px;" id="desarrollado-para">
                            <div>
                                <p class="block-title text-align-center">Para</p>
                                <div  style="text-align: center;">
                                    <img src='img/logo-revo.png' id="desarrollado-para" width="40%" height="auto" style='max-width:200px;'>
                                </div>
                            </div>
                            <div>
                                <div class="block-title text-align-center">Desarrollado por</div>
                                <div  style="text-align: center;">
                                    <img src='img/logo-cloud-tech.png' width="40%" height="auto" style='max-width:200px;'>
                                </div>
                            </div>
                        </div>
                        
                 
                        <div id='iphone' style="text-align:center;">
                            <iframe id="safari_window" src="../index.html"  border="0" scrolling="no" marginwidth="0" marginheight="0">
                            </iframe>
                        </div>
                        
                        -->
                        <?php
                        }
                        ?> 



                    </div>
                </div>
            </div>
            <!-- Fin View main -->

            
            <!-- INFORMES View -->
            <div id="view-informes" class="view view-init tab" data-name="informes" >

                <div class="page" data-name="informes">
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   

                  <div class="page-content">
                      
                    </div>
                  </div>
                </div>          

            <!-- Fin INFORMES View -->   
            
            <!-- PEDIDOS View -->
            <div id="view-pedidos" class="view view-init tab" data-name="usuario" data-url="/pedidos/">

                <div class="page" data-name="pedidos">
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   

                  <div class="page-content">
                      <div class="block-title block-title-medium" ><span id="pedidos-titulo">Pedidos</span> <span style="float: right;"><button class="button button-fill" onclick="hacerPedido();" id="boton-hacer-pedido">Hacer pedido</button></span></div>

                    <div id="pedidos-page" class=""> 
                        <div class="grid grid-gap medium-grid-cols-3 small-grid-cols-2 xsmall-grid-cols-1" style="padding: 15px;padding-bottom: 0;">
                           <div class="block block-strong inset" style="margin: 0;">
                                 <b>Filtrar fechas</b>
                                 <div class="list" style="display: flex;margin: 0;">
                                    <ul>
                                      <li>
                                        <div class="item-content item-input">
                                          <div class="item-inner">
                                            <div class="item-input-wrap" style="display: flex;">
                                              <input type="text" placeholder="Filtrar fechas" readonly="readonly" id="pedidos-calendar-range" />&nbsp;&nbsp;<i class="material-icons size-28" id="sync-pedidos">sync</i>
                                            </div>
                                          </div>
                                        </div>
                                      </li>
                                    </ul>
                                  </div>     
                           </div>                           
                           <div class="block block-strong inset" style="margin: 0;">
                                <b>Método envío</b>:<br><label class="radio"><input type="radio" name="filtro-envio" value="0" checked="checked"><i class="icon-radio"></i></label>&nbsp;Todos&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-envio" value="2" ><i class="icon-radio"></i></label> &nbsp;<img src="img/recoger.png" width="24" height="24" style="vertical-align: middle;">&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-envio" value="1" id="tratamiento-sra"><i class="icon-radio"></i></label> &nbsp;<img src="img/reparto.png" width="24" height="24" style="vertical-align: middle;">
                            </div>
                          <div class="block block-strong inset" style="margin: 0;">
                                <b>Método pago</b>:<br><label class="radio"><input type="radio" name="filtro-metodo" value="0" checked="checked"><i class="icon-radio"></i></label>&nbsp;Todos&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-metodo" value="1" id="metodo-pago-tarjeta"><i class="icon-radio"></i></label> &nbsp;<img src="img/tarjeta.png" width="24" height="24"style="vertical-align: middle;">&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-metodo" value="2" ><i class="icon-radio"></i></label> &nbsp;<img src="img/efectivo.png" width="24" height="24" style="vertical-align: middle;">
                            </div>
  
                        </div>
                     
                        <div class="card data-table">
                          <table>
                            <thead>
                              <tr>
                                <th class="label-cell">Nº Pedido</th>
                                  <th class="label-cell" id="label-num-revo">Nº Revo</th>
                                <th class="label-cell">Fecha</th>
                                  <th class="label-cell">Hora</th>
                                <th class="label-cell" style="min-width: 200px;">Cliente</th>
                                <th class="numeric-cell">Subtotal</th>
                                <!--<th class="numeric-cell">Impuestos</th> -->
                                <th class="numeric-cell">Descuentos</th>
                                  <th class="numeric-cell">Portes</th>
                                <th class="numeric-cell">Total</th>
                                <th class="label-cell">Envio</th>
                                <th class="label-cell">Método pago</th>
                                
                              </tr>
                            </thead>
                            <tbody id="tabla-pedidos">
                              <tr>
                                <th class="label-cell">Nº Pedido</th>
                                <th class="label-cell">Nº Revo</th>
                                <th class="numeric-cell">Fecha</th>
                                <th class="numeric-cell">Cliente</th>
                                <th class="numeric-cell">Subtotal</th>
                                <!--<th class="numeric-cell">Impuestos</th>-->
                                <th class="numeric-cell">Descuentos</th>
                                <th class="numeric-cell">Total</th>
                                <th class="label-cell">Envio</th>
                                <th class="label-cell">Pago</th>
                              </tr>
                            </tbody>
                            <tfoot id="tabla-pedidos-foot">
                            </tfoot>
                          </table>
                          <div class="data-table-footer" id="tabla-pedidos-pie">
                            <div class="data-table-rows-select">
                              Paginas:
                              
                            </div>
                            <div class="data-table-pagination">
                              <span class="data-table-pagination-label">1-5 of 10</span>
                              <a href="#" class="link disabled">
                                <i class="icon icon-prev color-gray"></i>
                              </a>
                              <a href="#" class="link">
                                <i class="icon icon-next color-gray"></i>
                              </a>
                            </div>
                          </div>
                            <div style="display:flex;">Totales:&nbsp; &nbsp; 
                                <img src="img/reparto.png" width="24" height="24"> (<b><span id="tot-ped-reparto"></span></b> &nbsp;Reparto) &nbsp;&nbsp;<img src="img/recoger.png" width="24" height="24"> (<b><span id="tot-ped-recoger"></span></b>&nbsp;Recoger) &nbsp;&nbsp;<img src="img/tarjeta.png" width="24" height="24"> (<b><span id="tot-ped-tarjeta"></span></b>&nbsp;Tarjeta) &nbsp;&nbsp;<img src="img/efectivo.png" width="24" height="24"> (<b><span id="tot-ped-efectivo"></span></b>&nbsp;Efectivo)
                            </div>
                            
                        </div>                        
                        
                        
                    </div>
                    <div id="pedido-manual" style="display:none;"></div>  
                      
                  </div>
                </div>          

            </div>
            <!-- Fin PEDIDOS View -->             
            
            <!-- PRODUCTOS View -->
            <div id="view-productos" class="view view-init tab" data-name="productos" >
                <div class="page" data-name="productos">    
                    
                     <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->     
                          
                    <div class="page-content">
                        <div class="block-title block-title-medium" id="titulo-productos">Grupos</div>
                        <div id="product-page" class="block"> </div>
                        <br>
                        <div id="boton-add-grupo" style="width: 50%; margin: auto;"><a class="button button-fill button-add-grupo" href="#" >Añadir</a></div>
                    </div>
                    
                </div>          
            </div>
            <!-- Fin PRODUCTOS View -->     
            
            <!-- modificadores View -->
             <div id="view-modificadores" class="view view-init tab" data-name="modificadores" >
                <div class="page" data-name="modificadores">
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">
                        <div class="block-title block-title-medium"  id="titulo-modificadores">Categoría de modificador</div>
                        <div id="modificadores-page" class="block block-strong inset"> 
                
                            modificadores

                        </div>
                    </div>
                    
                </div>          
            </div>
            <!-- Fin modificadores View -->     
            
            <!-- Setting View -->
            <div id="view-setting" class="view view-init tab" data-name="seting" >

                <div class="page" data-name="catalog">   
                  
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">
                        <div class="block-title block-title-medium">Ajustes</div> 
                        <div id="setting-page" class="block block-strong inset">                    
                            <div class="list">
                                <ul>
                                  <li>
                                      <a href="javascript:navegar('#view-setting-store');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons store-icon"></i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Empresa</div>
                                          </div>
                                      </a>
                                  </li>
                                 <li>
                                      <a href="javascript:navegar('#view-setting-tiendas');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon material-icons">location_pin</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Establecimientos</div>
                                          </div>
                                      </a>
                                  </li>
                                    <li>
                                      <a href="javascript:navegar('#view-setting-integra');integration();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">gear_alt</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Integración</div>
                                          </div>
                                      </a>
                                  </li>
                                    <!--
                                  <li>
                                      <a href="javascript:navegar('#view-setting-taxes');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons tax-icon"></i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Impuestos</div>
                                          </div>
                                      </a>
                                  </li>
-->
                                  <li>
                                      <a href="javascript:navegar('#view-setting-pagos');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">creditcard</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Métodos de pago</div>
                                          </div>
                                      </a>
                                  </li>

                                  <li>
                                      <a href="javascript:navegar('#view-setting-alergenos');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons alergeno-icon"></i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Alergenos</div>
                                          </div>
                                      </a>
                                  </li>
                            
                                </ul>
                            </div>
                                
                            <div class="card-content list accordion-list" style="margin-bottom:0;margin-top:-40px;">
                                <ul>
                                    <li class="accordion-item info">
                                        <a class="item-content item-link" href="">
                                            <div class="item-media"><i class="icon f7-icons reparto-icon"></i></div>
                                            <div class="item-inner"><div class="item-title">Envios</div></div>
                                        </a>
                                        <div class="accordion-item-content">
                                            <div class="list">
                                                <ul>  
                                                    <li>
                                                        <a href="javascript:navegar('#view-setting-repartos');ajustesrepartos();" class="item-link item-content"> 

                                                          <div class="item-media"><i class="icon f7-icons ">gear</i></div>  
                                                          <div class="item-inner">
                                                            <div class="item-title">Ajustes reparto</div>
                                                          </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:navegar('#view-setting-repartos');muestrazonasrepartos();" class="item-link item-content"> 

                                                          <div class="item-media"><i class="icon f7-icons zona-reparto-icon"></i></div>  
                                                          <div class="item-inner">
                                                            <div class="item-title">Zonas reparto</div>
                                                          </div>
                                                        </a>
                                                    </li> 
                                                    <li>
                                                        <a href="javascript:navegar('#view-setting-repartos');muestrahorasrepartos();" class="item-link item-content">   <div class="item-media"><i class="icon material-icons">schedule</i></div>
                                                          <div class="item-inner">
                                                            <div class="item-title">Horarios reparto</div>
                                                          </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:navegar('#view-setting-repartos');muestrahorascocina();" class="item-link item-content">   <div class="item-media"><i class="icon f7-icons hora-cocina-icon"></i></div>
                                                          <div class="item-inner">
                                                            <div class="item-title">Horarios entregas</div>
                                                          </div>
                                                        </a>
                                                    </li>
                                                 </ul>
                                            </div>
                                        </div>
                                    </li> 
                                 </ul>
                            </div>                                    
                            <div class="list" style="margin-top:0;">
                                <ul>  
                                  <li id="li-sincronizacion">
                                      <a href="javascript:navegar('#view-setting-sync');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons if-not-md">arrow_2_circlepath_circle_fill</i><i class="icon material-icons if-md">autorenew</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Sincronización</div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:navegar('#view-setting-destacados');destacados();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">star_fill</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Productos destacados</div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:navegar('#view-setting-inicio');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons if-not-md">house_fill</i><i class="icon material-icons if-md">home</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Página inicio</div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:navegar('#view-setting-nosotros');" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons if-not-md">info_circle</i><i class="icon material-icons if-md">info_circle</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Página nosotros</div>
                                          </div>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="javascript:navegar('#view-setting-aspecto');leeestilos();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons ">device_phone_portrait</i>
                                          </div>
                                          <div class="item-inner">
                                            <div class="item-title">Estilo de la App</div>
                                          </div>
                                      </a>
                                  </li>
                                </ul>
                            </div>                           
                        </div>
                    </div>                      
                    
                </div>          
            </div>
            <!-- Fin Setting View -->             
 
            <!-- Setting empresa View -->
            <div id="view-setting-store" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="store">  
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Empresa</div> 
                        <div id="store-page" class="block block-strong">     

                            <div class="card">

                                <div class="card-content card-content-padding">
                                    <div class="list">
                                        <ul>
                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title"></div>
                                                <div class="item-after"><i class="f7-icons" id="edit-datos-empresa">pencil</i></div>
                                              </div>
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Nombre: <span id='nombre-empresa' class="text-color-black"></span></div>
                                                <div class="item-after"></div>
                                              </div>
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">N.Comerial: <span id='nombre-comercial-empresa' class="text-color-black"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Nif/Cif: <span id='nif-empresa' class="text-color-black"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                           <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Logo: <span style="vertical-align: top" ><img src="" id="img-empresa" width='80%' height="auto"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                            <li id="logo-impresora" style="display:none;">
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Logo impresora: <span style="vertical-align: top" ><img src="" id="img-impresora-empresa" width='80%' height="auto"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                            <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Icono: <span style="vertical-align: top" ><img src="" id="ico-empresa" width='64' height="auto"></span></div>

                                              </div>
                                            </a>
                                          </li>

                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Domicilio: <span id='domicilio-empresa' class="text-color-black"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                          <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">C.Postal: <span id='cpostal-empresa' class="text-color-black"></span></div>

                                              </div>
                                            </a>
                                          </li>
                                           <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Población: <span id='poblacion-empresa' class="text-color-black"></span></div>
                                              </div>
                                            </a>
                                          </li>
                                            <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Provincia: <span id='provincia-empresa' class="text-color-black"></span></div>
                                              </div>
                                            </a>
                                          </li>
                                            <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Teléfono: <span id='telefono-empresa' class="text-color-black"></span></div>
                                              </div>
                                            </a>
                                          </li> 
                                           <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Móvil: <span id='movil-empresa' class="text-color-black"></span></div>
                                              </div>
                                            </a>
                                          </li> 
                                            <li>
                                            <a href="#" class="item-content">
                                              <div class="item-media"></div>
                                              <div class="item-inner">
                                                  <div class="item-title">Email: <span id='email-empresa' class="text-color-black"></span></div>
                                              </div>
                                            </a>
                                          </li> 

                                        </ul>   
                                    </div>                          
                                </div>         
                            </div>
                        </div>    
                    </div>                    
                        
                </div>          
            </div>     
            <!-- Fin Setting empresa -->  
            
            <!-- Setting integra View -->
            <div id="view-setting-integra" class="view view-init tab" data-name="integra" >
                <div class="page" data-name="integra">  
                                      
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Integración</div> 
                        <div id="integra-page" class="block block-strong inset">     

                        </div>    
                    </div>                    
                        
                </div>          
            </div>     
            <!-- Fin Setting integra -->  
            
            <!-- Setting Impuestos View -->
            <div id="view-setting-taxes" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="taxes">  
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->                      
                    
                    <div class="page-content">    
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Impuestos</div> 
                        <div id="taxes-page" class="block block-strong">     
                            <div class="row no-gap">
                                <div class="col-55 medium-25"><b>Nombre</b></div>
                                <div class="col-30 medium-25 text-align-right"><b>%</b></div>
                                <div class="col-15 medium-25"></div>
                            </div>
                            <hr>
                            <div  id="tabla-impuestos">

                            </div>                            
                        </div>
                    </div>                    

                </div>          
            </div>
            <!-- Fin Impuestos View -->      
    
            <!-- Setting Pagos View -->
            <div id="view-setting-pagos" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="pagos">  
                                      
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>  
                    <!-- FIN navbar -->                   
                    
                    <div class="page-content">    
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Métodos de pago</div> 
                        <div id="taxes-page" class="block block-strong inset">     
                            <div class=" grid grid-cols-5 grid-gap">
                                <div class=""><b>Nombre</b></div>
                                <div class="text-align-right"><b>Activo</b></div>
                                <div class="text-align-right">id Revo</div>
                                <div class="text-align-right">RedSys</div>
                                <div class="text-align-center">Editar</div>
                            </div>
                            <hr>
                            <div  id="tabla-pagos">

                            </div>                            
                        </div>
                    </div>                    

                </div>          
            </div>
            <!-- Fin Pagos View -->      
    
            <!-- Setting Promos View -->
            <div id="view-promos" class="view view-init tab" data-name="promos" >
                <div class="page" data-name="promos">  
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->     
                    
                    
                    <div class="page-content">    
                        
                        <div class="block-title block-title-medium">Promociones</div> 
                        <div id="promos-especiales-page" class="block block-strong inset">     
                            <div class="grid grid-cols-6 grid-gap">
                                <div class=""><b>Nombre</b></div>
                                <div class=""><b>Código</b></div>
                                <div class=""><b>Activo</b></div>
                                <div class=""><b>Activo</b></div>
                                <div class="">Edi.</div>
                                <div class=""></div>
                            </div>
                            <hr>
                            <div  id="tabla-promos-especiales"></div>   
                            <div class="grid grid-gap"><button onclick="editapromoespecial();" id="add-promo-especial" class="button button-fill" style="margin:auto;width: 50%;">+ Añadir promoción</button></div>
                        </div>
                    </div>                    

                </div>          
            </div>
            <!-- Fin Promos View -->      
          
            <!-- Setting Alergenos View -->
            <div id="view-setting-alergenos" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="alergenos">  
                                     
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">      
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Alergenos</div>                         
                         <div id="alergenos-page" class="block block-strong inset">
                            <div class="grid grid-cols-3 grid-gap">
                              <div class=""><b>Nombre</b></div>
                              <div class="text-align-right"></div>
                              <div class=""></div>
                            </div>
                            <hr>
                            <div  id="tabla-alergenos">
                            </div>                           
                        </div>   
                    </div>                    
                </div>          
            </div>  
            <!-- Fin Alergenos View --> 
            
            <!-- Setting Sync View -->
            <div id="view-setting-sync" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="sync">
   
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                    
                    <div class="page-content">  
                        <div class="block-title block-title-medium"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Sincronizar</div> 
                        <div id="sync-page" class="block block-strong inset">   
                            <div class="list simple-list">
                                  <ul>
                                    <li>
                                      <span>Grupos</span>
                                      <label class="toggle toggle-init">
                                        <input id="chk-grupos" type="checkbox" checked />
                                        <span class="toggle-icon"></span>
                                      </label>

                                    </li>
                                    <li id="li-grupos-progressbar" style="display:none;"><span class="progressbar-infinite" id="grupos-progressbar"></span></li>
                                      <li id="li-grupos-progress" style="display:none;"><span ></span></li>
                                    <li>
                                      <span>Categorías</span>
                                      <label class="toggle toggle-init">
                                        <input id="chk-categorias" type="checkbox" checked />
                                        <span class="toggle-icon"></span>
                                      </label>
                                    </li>
                                      <li id="li-categorias-progressbar" style="display:none;"><span class="progressbar-infinite" id="categorias-progressbar"></span></li>
                                      <li id="li-categorias-progress" style="display:none;"><span ></span></li>
                                     <li>
                                      <span>Productos</span>
                                      <label class="toggle toggle-init">
                                        <input id="chk-productos" type="checkbox" checked />
                                        <span class="toggle-icon"></span>
                                      </label>
                                    </li>
                                    <li id="li-productos-progressbar" style="display:none;"><span class="progressbar-infinite" id="productos-progressbar"></span></li>
                                      <li id="li-productos-progress" style="display:none;"><span ></span></li>
                                    <li>
                                      <span>Modificadores</span>
                                      <label class="toggle toggle-init">
                                        <input id="chk-modificadores" type="checkbox" checked />
                                        <span class="toggle-icon"></span>
                                      </label>
                                    </li>
                                    <li id="li-modificadores-progressbar" style="display:none;"><span class="progressbar-infinite" id="modificadores-progressbar"></span></li>
                                      <li id="li-modificadores-progress" style="display:none;"></li>
                                    <li>

                                    <label class="item-checkbox item-content">
                                      <input type="checkbox" id="chk-imagenes" value="chk-imagenes" />
                                      <i class="icon icon-checkbox"></i>
                                      <div class="item-inner">
                                        <div class="item-title">Sincronizar imagenes</div>
                                      </div>
                                    </label>
                                  </li>  
                                  <li>

                                    <label class="item-checkbox item-content">
                                      <input type="checkbox" id="chk-precios" value="chk-precios" />
                                      <i class="icon icon-checkbox"></i>
                                      <div class="item-inner">
                                        <div class="item-title">Sincronizar precios</div>
                                      </div>
                                    </label>
                                  </li>  

                                </ul>
                            </div>
                            <div class="text-align-center">
                                <button class="button button-fill sync-button" style="width:50%;margin: auto;"><i class="icon f7-icons if-not-md">arrow_2_circlepath_circle</i><i class="icon material-icons if-md">autorenew</i> Sincronizar</button>
                            </div>    
                        </div>
                        
                    </div> 
                </div>          
            </div>     
            <!-- Fin Setting Sync -->       
 
            <!-- Setting Repartos View -->
            <div id="view-setting-repartos" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="repartos">    
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->                      
                    
                    <div class="page-content">      
                        <div class="block-title block-title-medium" id="titulo-repartos"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Repartos</div>                         
                         <div id="repartos-page" class="block block-strong inset">                         
                        </div>   
                    </div>                    
                </div>          
            </div>  
            <!-- Fin  Repartos View -->           

            <!-- Setting destacados View -->
            <div id="view-setting-destacados" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="destacados">    
                                   
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>  
                    <!-- FIN navbar -->                      
                    
                    <div class="page-content">      
                        <div class="block-title block-title-medium" id="titulo-destacados"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Productos destacados</div> 
                        <div id="destacados-page" class="block block-strong inset">  
                            <div class="" style="width:100%;display:flex;">
                                <div class="col-70" style="width:70%;"><b>Nombre</b></div>
                                <div class="col-10" style="width:10%;"><b>Inicio</b></div>
                                <div class="col-10" style="width:10%;"><b>Catálogo</b></div>
                                <div class="col-10" style="width:10%;"><b>Borrar</b></div>
                            </div>
                            <div class="list sortable sortable-opposite list-outline-ios list-dividers-ios sortable-enabled" id="lista-destacados">
                            </div>  
                            <div class="row"><button onclick="editadestacado();" id="add-bloque-destacado" class="col-60 button button-fill" style="margin:auto;width: 50%;">+ Añadir producto</button></div>
                        </div>
                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin setting destacados View -->             

             <!-- Setting inicio View -->
            <div id="view-setting-inicio" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="inicio">  
                                      
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>
                    <!-- FIN navbar -->                       
                    
                    <div class="page-content">      
                        <div class="block-title block-title-medium" id="titulo-inicio"><a href="javascript:navegar('#view-setting');paginainicio();" class="link">Ajustes</a> -> Página de inicio<span id="button-guardar-inicio"class="button button-fill float-right" style="display:none;">Guardar</span></div> 
                        <div id="inicio-page" class="block block-strong inset">  
                            
                            
                        </div>
                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin setting inicio View -->             
           
            
            <!-- Setting nosotros View -->
            <div id="view-setting-nosotros" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="nosotros">  
                                      
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>
                    <!-- FIN navbar -->                      
                    
                    <div class="page-content">     
                        <div class="block-title block-title-medium" id="titulo-inicio"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Página Nosotros<span id="button-guardar-nosotros"class="button button-fill float-right" style="display:none;">Guardar</span></div> 
                        <div id="nosotros-page" class="block block-strong inset">  


                        </div>
                        

                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin setting nosotros View -->   
            
             <!-- Setting aspecto View -->
            <div id="view-setting-aspecto" class="view view-init tab" data-name="aspecto" >
                <div class="page" data-name="aspecto">  
                                      
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->                      
                    
                    <div class="page-content">      
                        <div class="block-title block-title-medium" id="titulo-estilos"><a href="javascript:navegar('#view-setting');" class="link">Ajustes</a> -> Estilo de la App <span style="float: right;"> <button class="button button-fill guardar-estilos">Guardar</button></span></div> 
                        <div id="aspecto-page" class="block block-strong inset">  

                            <div class="grid grid-cols-2 ">
                                <div>
                                    <p><label class="toggle toggle-init">
                                    <input type="checkbox" name="toggle-oscuro" id="toggle-oscuro" value="yes" /><i class="toggle-icon"></i>
                                    </label> Modo oscuro</p>

                                    <div class="list">
                                        <ul>    
                                            <li>
                                                <div class="item-content item-input">
                                                    <div class="item-media">
                                                        <i class="icon demo-list-icon" id="color-picker-primario-value"></i>
                                                    </div>
                                                    <div class="item-inner">
                                                        <div class="item-title item-label">Primario</div>
                                                        <div class="item-input-wrap">
                                                          <input type="text" name="primario" placeholder="#FF0000" required validate value="#FF0000" id="color-picker-primario" data-error-message="Formato para color: #ABCDEF o #ABC" class="pickcolor"/>
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                            <li>
                                                <div class="item-content item-input">
                                                    <div class="item-media">
                                                        <i class="icon demo-list-icon" id="color-picker-secundario-value"></i>
                                                    </div>
                                                    <div class="item-inner">
                                                        <div class="item-title item-label">Secundario</div>
                                                        <div class="item-input-wrap">
                                                          <input type="text" name="secundario" placeholder="#FF0000" required validate value="#FF0000" id="color-picker-secundario" pattern="/^#([A-F0-9]{3}|[A-F0-9]{6})$/i" data-error-message="Formato para color: #ABCDEF o #ABC" class="pickcolor"/>
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                    <p><label class="toggle toggle-init">
                                    <input type="checkbox" name="toggle-breadcrumbs" id="toggle-breadcrumbs" value="yes" /><i class="toggle-icon"></i>
                                    </label> Breadcrumbs</p>
                                </div>
                                <div  style="height:340px;">

                                    <div class="page-content" id="elemento-oscuro" >
                                        <div style="margin-top: -60px;margin-left: 10px;margin-right: 10px; margin-bottom: -35px;">
                                        <div class="block-title">Hola mundo, <a href=""#>esto es un link</a></div>
                                        <div class="list list-outline-ios list-strong-ios list-dividers-ios">
                                          <ul>
                                            <li>
                                              <a class="item-link item-content">
                                                <div class="item-media"><i class="icon f7-icons">person_crop_circle</i></div>
                                                <div class="item-inner">
                                                  <div class="item-title">Ivan Petrov</div>
                                                  <div class="item-after">CEO</div>
                                                </div>
                                              </a>
                                            </li>
                                            <li>
                                              <a class="item-link item-content">
                                                <div class="item-media"><i class="icon f7-icons">person_crop_circle</i></div>
                                                <div class="item-inner">
                                                  <div class="item-title">John Doe</div>
                                                  <div class="item-after">Cleaner</div>
                                                </div>
                                              </a>
                                            </li>
                                            <li>
                                              <a class="item-link item-content">
                                                <div class="item-media"><i class="icon f7-icons">person_crop_circle</i></div>
                                                <div class="item-inner">
                                                  <div class="item-title">Jenna Smith</div>
                                                </div>
                                              </a>
                                            </li>
                                          </ul>
                                        </div>

                                        <div class="grid grid-cols-2 grid-gap">
                                            <button class="button button-fill button-primario">primario</button>
                                            <button class="button button-fill button-secundario">secundario</button>

                                        </div>
                                        <br><br><br><br>
                                        </div>

                                <div class="toolbar tabbar tabbar-icons no-outline">
                                    <div id="boton-principal" style="bottom: -20px;width: 80px;height: 80px;text-align: center;position: absolute; border-radius: 50px;left:50%;margin-left: -50px;border: 10px solid transparent;z-index: 2;">
                                        <a href="javascript:navegar('#view-catalog');" class="tab-link tab-link-menus tab-link-estilo" id="tab-catalog" style="padding-top: 14px;">
                                            <i class="icon material-icons" id="i-menu" style="font-size: 48px;bottom: 10px;">restaurant</i>
                                            <span class="tabbar-label" id="boton_menu" >Menús</span>
                                        </a>
                                    </div>
                                    <div class="toolbar-inner">
                                        <a class="tab-link tab-link-active tab-link-estilo">
                                          <i class="icon f7-icons" id="i-inicio">house_fill</i>
                                        <span class="tabbar-label" id="boton_inicio">Inicio</span>
                                        </a>

                                        <a class="tab-link ">

                                        </a>

                                        <a class="tab-link tab-link-estilo">
                                          <i class="icon material-icons" id="i-carrito"><span class="badge color-red badage-cart" >4</span>shopping_cart</i>
                                        <span class="tabbar-label" id="boton_carrito">Carrito</span>
                                        </a>

                                    </div>
                                </div>  

                                </div>
                                </div>  
                            </div>
                            
                            <div class="grid grid-cols-3 grid-gap">    
                                <div>
                                    <div class="list">
                                        <ul>
                                            <li>
                                                <div class="item-content item-input">
                                                    <div class="item-inner">
                                                        <div class="item-title item-label">Texto Inicio</div>
                                                        <div class="item-input-wrap">
                                                          <input type="text" name="texto-boton-inicio" placeholder="Texto botón inicio" required  id="texto-boton-inicio" vaidate data-error-message="Formato de 4 a 15 caracteres" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <p>Estilo botón</p>
                                    <p><label class="radio"><input type="radio" name="tipo-boton-inicio" checked value="1"><i class="icon-radio"></i></label> <i class="icon f7-icons size-28">house_fill</i></p>
                                    <p><label class="radio"><input type="radio" name="tipo-boton-inicio" value="2"><i class="icon-radio"></i></label> <i class="icon f7-icons size-28">house_alt_fill</i></p>
                                </div>
                                <div>
                                    <div class="list">
                                        <ul>
                                            <li>
                                                <div class="item-content item-input">
                                                    <div class="item-inner">
                                                        <div class="item-title item-label">Texto Menú</div>
                                                        <div class="item-input-wrap">
                                                          <input type="text" name="texto-boton-menu" placeholder="Texto botón menú" required  id="texto-boton-menu" vaidate data-error-message="Formato de 4 a 15 caracteres"/>
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="grid grid-cols-2 grid-gap"> 
                                    <div>
                                        <p>Estilo botón</p>
                                        <p><label class="radio"><input type="radio" name="tipo-boton-menu" checked value="1"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">restaurant</i></p>
                                        <p><label class="radio"><input type="radio" name="tipo-boton-menu" value="2"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">room_service</i></p>
                                        <p><label class="radio"><input type="radio" name="tipo-boton-menu" value="3"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">fastfood</i></p>
                                        <p><label class="radio"><input type="radio" name="tipo-boton-menu" value="0"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">settings</i></p>
                                    </div>
                                    <div>
                                        <p>Tamaño</p>
                                        <p><label class="radio"><input type="radio" name="tam-boton-menu" checked value="1"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">restaurant</i></p>
                                        <p><label class="radio"><input type="radio" name="tam-boton-menu" checked value="2"><i class="icon-radio"></i></label> <i class="icon material-icons size-48">restaurant</i></p>
                                    </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="list">
                                        <ul>
                                            <li>
                                                <div class="item-content item-input">
                                                    <div class="item-inner">
                                                        <div class="item-title item-label">Texto carrito</div>
                                                        <div class="item-input-wrap">
                                                          <input type="text" name="texto-boton-carrito" placeholder="Texto botón carrito" required  id="texto-boton-carrito" vaidate data-error-message="Formato de 4 a 15 caracteres" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <p>Estilo botón</p>
                                    <p><label class="radio"><input type="radio" name="tipo-boton-carrito" checked value="1"><i class="icon-radio"></i></label> <i class="icon material-icons size-28">shopping_cart</i></p>
                                    <p><label class="radio"><input type="radio" name="tipo-boton-carrito" value="2"><i class="icon-radio"></i></label> <i class="icon f7-icons size-28">cart_fill</i></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 grid-gap"> 
                                <div>
                                    <p><label class="radio" style="vertical-align: top;"><input type="radio" name="tipo-diseno" checked value="0" ><i class="icon-radio"></i></label> <img src="img/estilo1.png" style="border:solid 1px;"></p>
                                </div>
                                <div>
                                    <p><label class="radio" style="vertical-align: top;"><input type="radio" name="tipo-diseno" checked value="1"><i class="icon-radio"></i></label> <img src="img/estilo2.png" style="border:solid 1px;"></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin setting aspecto View -->         
            
            <!-- Push View -->
            <div id="view-push" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="nosotros">           

                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                                       
                    <div class="page-content">      
                        <div class="block-title block-title-medium" id="titulo-push">Mensajes Push</div> 
                        <div id="push-send" class="block block-strong" style="display:none;"> 
                            <p>Progreso</p>
                            <p><span data-progress="1" class="progressbar" id="progressbar-send"></span></p>
                            <div class="grid grid-cols-3 grid-gap" >
                                <div class="text-align-center"><b>iOS</b> <i class="f7-icons" >logo_apple</i></div>
                                <div class="text-align-center"><b>Android</b> <i class="f7-icons" >logo_android</i></div>
                                <div class="text-align-center"><b>Web</b> <i class="material-icons" >language</i></div>
                            </div>
                            <div class="grid grid-cols-6 grid-gap" >
                                <div class="text-color-green text-align-center" id="send-ios-ok"></div>
                                <div class="text-color-red text-align-center" id="send-ios-ko"></div>
                                <div class="text-color-green text-align-center" id="send-android-ok"></div>
                                <div class="text-color-red text-align-center" id="send-android-ko"></div>
                                <div class="text-color-green text-align-center" id="send-web-ok"></div>
                                <div class="text-color-red text-align-center" id="send-web-ko"></div>
                            </div>
                            <div class="button button-fill" id="cerrar-progreso-push" onclicK="cerrarprogresopush();" style="display:none;width: 50%;margin:auto;">Cerrar</div>
                            
                        </div>  
                        <div id="push-page" class="block block-strong inset">  
                            <div class="grid grid-cols-5 grid-gap" style="font-size:12px;">
                                <div class=""><b>Titulo</b></div>
                                <div class=""><b>Fecha</b></div>
                                <div class="c"><b>Img</b></div>
                                <div class=""><b>Tipo</b></div>
                                <div class="">Ver</div>
                            </div>
                            <hr>
                            <div  id="tabla-push"></div>   
                            <div class="grid grid-cols-1 grid-gap"><button onclick="addpush();" id="add-push" class="button button-fill" style="margin:auto;width: 50%;">+ Crear push</button></div>                           
                        </div>
                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin PUSH View --> 
            
            <!-- Emails View -->
            <div id="view-emails" class="view view-init tab" data-name="seting" >
                <div class="page" data-name="emails">           

                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>   
                    <!-- FIN navbar -->   
                                       
                    <div class="page-content">   
                         
                        <div id="emails-page" class="block block-strong inset">   
                            <div class="block-title block-title-medium">Emails</div>
                            <div class="list">
                                <ul>
                                  <li>
                                      <a href="javascript:correoAjustes();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">gear</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Ajustes de correo</div>
                                          </div>
                                      </a>
                                  </li>
                                    <li>
                                      <a href="javascript:correoBienvenida();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">person_crop_rectangle</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Correo de Bienvenida</div>
                                          </div>
                                      </a>
                                  </li>
                                    <li id="in_mail_birday">
                                      <a href="javascript:correoBirday();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">person_crop_circle_badge_checkmark</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Correo de Cumpleaños</div>
                                          </div>
                                      </a>
                                  </li>
                                    <li id="in_mail_campain">
                                      <a href="javascript:correoCampaign();" class="item-link item-content">  
                                          <div class="item-media"><i class="icon f7-icons">speaker_1</i></div>
                                          <div class="item-inner">
                                            <div class="item-title">Campañas</div>
                                          </div>
                                      </a>
                                  </li>
                                    
                                </ul>
                            </div>
                        </div>
                        

                        
                    </div>                    
                </div>          
            </div>  
            <!-- Fin MAIL View --> 
 
            <!-- Setting Fidelizacion View -->
            <div id="view-fidelidad" class="view view-init tab" data-name="promos" >
                <div class="page" data-name="fidelidad"> 
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>  
                    <!-- FIN navbar -->     
                    
                    <div class="page-content">    
                        <div class="block-title block-title-medium">Fidelización clientes</div> 
                        
                        <div class="list block-strong inset">
                            <ul>
                                <li>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title" style="font-size:12px;">Usar fidelización</div>
                                        <div class="item-after">
                                            <label class="toggle toggle-init">
                                              <input type="checkbox" id="fidelidad-off" onclick="cambiaEstadoFidelidad(this);" checked><i class="toggle-icon"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>                                        
                                </li>    
                                <li id='id-fidelidad-por'>
                                <div class="item-content">
                                    <div class="item-inner">
                                        <div class="item-title" style="font-size:12px;">Porcentaje</div>
                                        <div class="item-after">

                                              <input type="text" id="fidelidad-por" onchange="cambiaporcentajefidelidad(this);" class="text-align-right" value='0.00'>

                                        </div>
                                    </div>
                                </div>                                        
                                </li>    
                            </ul> 
                        </div>    
                        <div id="fidelidad-page" class="block block-strong inset">
                            <div class="grid grid-cols-5 grid-gap">
                                <div class=""><b>Nombre</b></div>
                                <div class=""><b>%</b></div>
                                <div class=""></div>
                                <div class=""></div>
                                <div class="c"></div>
                            </div>
                            <div id="fidelidad-tabla"></div>
                            <div class=""><br><button onclick="editgrupofidelidad();"
                            class="button button-fill" style="margin:auto;width: 50%;">Crear grupo</button></div>
                        </div>
                        
                            
                            
                    </div>                    

                </div>          
            </div>
            <!-- Fin Promos View -->   
            
            <!-- Clientes View -->
            <div id="view-clientes" class="view view-init tab" data-name="promos" >
                <div class="page" data-name="clientes"> 
                    
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>  
                    <!-- FIN navbar -->     
                    
                    <div class="page-content">    
                        <div class="block-title block-title-medium">Clientes</div> 
                        
  
                        <div id="clientes-page" class="block">
                            <div class="grid small-grid-cols-3 xsmall-grid-cols-1">
                                <div class="list block block-strong inset" style="margin-top: 15px;margin-bottom: 0;">
                                <ul>
                                  <li>
                                    <div class="item-content item-input">
                                      <div class="item-inner">
                                        <div class="item-input-wrap" >
                                          <input type="text" placeholder="Filtrar cliente" id="filtro-cliente" />
                                        </div>
                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                               <div class="block block-strong inset" style="margin-top: 15px;margin-bottom: 0;">
                                <b>Orden</b>:&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-cliente-orden" value="0" checked="checked"><i class="icon-radio"></i></label> &nbsp;<i class="f7-icons" style="vertical-align: middle;">person_crop_circle</i>&nbsp;&nbsp;<label class="radio"><input type="radio" name="filtro-cliente-orden" value="1" ><i class="icon-radio"></i></label> &nbsp;<i class="material-icons" style="vertical-align: middle;">money</i>
                            </div>
                                <div class="" style="margin-top: 30px;margin-bottom: 0;"><button class="button button-fill button-round" id="filtro-clientes-button" style="margin: auto;width: 50%;">Filtrar</button></div> 
                            </div>
                            <div class="card data-table">
                          <table>
                            <thead>
                              <tr>
                                <th class="label-cell"><i Class="f7-icons">person_crop_circle</i></th>
                                <th class="label-cell">Nombre</th>
                                <th class="label-cell">Teléfono</th>
                                <th class="label-cell">Email</th>
                                <th class="numeric-cell">Monedero</th>
                                <th class="numeric-cell">Compras</th>
                                
                                
                              </tr>
                            </thead>
                            <tbody id="tabla-clientes">
                              
                            </tbody>
                          </table>
                          <div class="data-table-footer" id="tabla-clientes-pie">
                            <div class="data-table-rows-select">
                              Paginas:
                              
                            </div>
                            <div class="data-table-pagination">
                              <span class="data-table-pagination-label">1-5 of 10</span>
                              <a href="#" class="link disabled">
                                <i class="icon icon-prev color-gray"></i>
                              </a>
                              <a href="#" class="link">
                                <i class="icon icon-next color-gray"></i>
                              </a>
                            </div>
                          </div>
                            
                        </div>
                        
                            
                            
                    </div>                    

                </div>          
            </div>
            <!-- Fin Clientes View -->  
            
            
        </div> 
            
            <!-- USUARIO View -->
            <div id="view-usuario" class="view view-init tab" data-name="usuario" data-url="/usuario/">

                <div class="page" data-name="usuario">
                    <!-- Top Navbar -->
                    <div class="navbar no-outline">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left"><a href="#" onclick="abrepanel();" class="link icon-only "><i class="icon f7-icons menu-left">menu</i></a></div>
                            <div class="title title-principal"></div>
                            <div class="right select-tienda list">Master</div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="block-title block-title-medium">Mis datos</div>

                        <div id="user-page" class="block block-strong inset"> 

                        <div class="card">
                            <div class="card-header">
                                <button class="button button-fill" id="cerrar-sesion" style="width:60%;margin: auto;">Cerrar sesión &nbsp;&nbsp;<i class="f7-icons">power</i></button>
                            </div>
                            <div class="card-content card-content-padding">
                                <div class="list">
                                    <ul>
                                      <li>
                                        <a href="#" class="item-content">
                                          <div class="item-media"></div>
                                          <div class="item-inner">
                                              <div class="item-title">Nick: <span id='nick-user' class="text-color-black"></span></div>
                                            <div class="item-after"></div>
                                          </div>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#" class="item-content">
                                          <div class="item-media"></div>
                                          <div class="item-inner">
                                              <div class="item-title">Email: <span id='email-user' class="text-color-black" style="font-size:12px;"></span></div>
                                            <div class="item-after"><i class="f7-icons" id="edit-email-user">pencil</i></div>
                                          </div>
                                        </a>
                                      </li>
                                      <li>
                                        <a href="#" class="item-content">
                                          <div class="item-media"></div>
                                          <div class="item-inner">
                                              <div class="item-title">Clave: <span class="text-color-black" style="font-size:12px;">********</span></div>
                                            <div class="item-after"><i class="f7-icons" id="edit-pass-user">pencil</i></div>
                                          </div>
                                        </a>
                                      </li>
                                    </ul>
                                </div>                   

                            </div>
                            <div class="card-footer"></div>
                        </div>

                    </div>

                    </div>
                </div>          

            </div>
            <!-- Fin USUARIOS View -->
        
        </div>
        <!-- FIN Views/Tabs container -->
    </div>
    <!-- FIN app -->
    
    <!-- Framework7 library -->
    <script src="framework7/framework7-bundle.min.js"></script>
    <script>
        var autentificado='<?php echo $_SESSION["autentificado"];?>';
    </script>
    <!--
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsv3MEv58JZ42mQIqNyE_Zwpyo361dzhs&libraries=places&loading=async" async></script>
        
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDsv3MEv58JZ42mQIqNyE_Zwpyo361dzhs"></script>
        -->
    
    
    <!-- App scripts -->
    <script>
        var mathrandom=Math.random();
        var scriptsjs = [
            "js/cnfg.js?" + mathrandom,
            "js/integra.js?" + mathrandom,
            "js/app.js?" + mathrandom,
            "js/modificadores.js?" + mathrandom,
            "js/impuestos.js?" + mathrandom,
            "js/empresa.js?" + mathrandom,
            "js/alergenos.js?" + mathrandom,
            "js/sync.js?" + mathrandom,
            "js/productos.js?" + mathrandom,
            "js/repartos.js?" + mathrandom,
            "js/productosdestacados.js?" + mathrandom,
            "js/paginainicio.js?" + mathrandom,
            "js/paginanosotros.js?" + mathrandom,
            "js/metodospago.js?" + mathrandom,
            "js/promociones.js?" + mathrandom,
            "js/push.js?" + mathrandom,
            "js/fidelizacion.js?" + mathrandom,
            "js/pedidos.js?" + mathrandom,
            "js/hacerpedido.js?" + mathrandom,
            "js/informes.js?" + mathrandom,
            "js/correos.js?" + mathrandom,
            "js/config.js?" + mathrandom,
            "js/clientes.js?" + mathrandom,
            "js/canvasjs.min.js?" + mathrandom
            
            
        ];
        for (j=0;j<scriptsjs.length;j++){
            document.write('<script src="'+scriptsjs[j]+'"><\/script>');
        }
        
    </script>


    
    <?php
        if ($_SESSION["autentificado"]) { 
        ?> 
    <script>
    informes();
    </script>
    <?php
        }
    ?>
</body>
</html>