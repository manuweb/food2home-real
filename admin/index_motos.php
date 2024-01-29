<?php
session_start(); 
include '../webapp/includes/conexion.php';
include "../webapp/includes/MySQL/DataBase.class.php";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <!--
  Customize this policy to fit your own app's needs. For more guidance, see:
      https://github.com/apache/cordova-plugin-whitelist/blob/master/README.md#content-security-policy
  Some notes:
    * https://ssl.gstatic.com is required only on Android and is needed for TalkBack to function properly
    * Disables use of inline scripts in order to mitigate risk of XSS vulnerabilities. To change this:
      * Enable inline JS: add 'unsafe-inline' to default-src
  -->
  

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
    
  <title>Demo Revo - Repartos</title>
  
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


        
        <?php
        //}
        ?> 
        <!-- Views/Tabs container -->
        <div class="views tabs safe-areas">  
            
            <!-- HOME View --> 
            <div id="view-home" class="view view-main view-init tab tab-active">
                <div class="page" data-name="home">   
                    
                    <div class="navbar">
                        <div class="navbar-bg"></div>
                        <div class="navbar-inner">
                            <div class="left">
                               
                            </div>
                            <div class="title sliding"><img src='img/logo.png' width="auto" height="28px"></div>

                            <div class="right">
                                
                            </div>
               
                            
                        </div>
                    </div>   
                    <!-- FIN navbar -->
                    
                    <div class="page-content">

                    <div id="pedidos-page" class="block block-strong inset"> 

                        <div class="card data-table">
                          <table>
                            <thead>
                              <tr>
                                <th class="label-cell">Nº Pedido</th>
                                <th class="label-cell">Fecha</th>
                                <th class="label-cell">Cliente</th>
                                
                                <th class="numeric-cell">Total</th>
                                
                              </tr>
                            </thead>
                            <tbody id="tabla-pedidos">
                              <tr>
                                <th class="label-cell">Nº Pedido</th>
                                <th class="numeric-cell">Fecha</th>
                                <th class="numeric-cell">Cliente</th>
                                
                                <th class="numeric-cell">Total</th>

                              </tr>
                            </tbody>
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
                        </div>                        
                        
                        
                    </div>
                                         
                    
                    </div>
                </div>
            </div>
            <!-- FIN view-home --> 
            
            
            
        </div> 
            
            <!-- FIN Views/Tabs container -->
    </div>
    <!-- FIN app -->
    
    <!-- Framework7 library -->
    <script src="framework7/framework7-bundle.min.js"></script>
    <script>
        var autentificado='<?php echo $_SESSION["autentificado"];?>';
        
    </script>
    <!-- App scripts -->
   
    <script src="js/cnfg.js"></script>
     <script src="js/app.js"></script>
    <script src="js/pedidos_motos.js"></script>

</body>
</html>