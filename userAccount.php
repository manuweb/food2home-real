<?php
/*
 *
 * Archivo: userAccount.php
 *
 * Version: 1.0.0
 * Fecha  : 26/11/2022
 * Se usa en :en el mail enviado desde restablecerclave.php
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');

include "webapp/conexion.php";
include "webapp/MySQL/DataBase.class.php";
include "webapp/config.mail.php";
include "webapp/config.php";



if (isset($_POST)){
    $sql = "SELECT username FROM usuarios_app WHERE username='".$_POST['usuario']."' AND seguridad='".$_POST['seguridad']."'";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    if ($result) {
        $sql = "UPDATE usuarios_app SET clave=MD5('".$_POST['password']."') WHERE username='".$_POST['usuario']."' AND seguridad='".$_POST['seguridad']."'";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
    }
    $database->freeResults();
    ?>

    <html>
    <head>
      <meta charset="utf-8">
        <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">
        <title>Restablecer contraseña</title>
    </head>
    <body>
         <p><img src='<?php echo URLServidor;?>/webapp/img/empresa/<?php echo LOGOEMPRESA;?>' width='240' height='auto' ></p>
        <h1>Contraseña actualizada.</h1>
    </body>
    </html>
    <?php
    
    
}


?>



