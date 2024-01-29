<?php
/*
 *
 * Archivo: restablecerclave.php
 *
 * Version: 1.0.0
 * Fecha  : 26/11/2022
 * Se usa en :usuario.js ->RecuperarClave() -> en el mail enviado
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');

include "webapp/conexion.php";
include "webapp/MySQL/DataBase.class.php";
include "webapp/config.php";
include "webapp/config.mail.php";




$seguridad = $_GET['seguridad'];

if (isset($seguridad)){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">

        <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, viewport-fit=cover">


        <title>Restablecer contraseña</title>


    </head>
    <body>
        <style>
     .container {
        width: 80%;
        margin: 0 auto;
        background-color: #f7f7f7;
        color: #757575;
        font-family: 'Raleway', sans-serif;
        text-align: left;
        padding: 30px;
    }
    h2 {
        font-size: 30px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .container p {
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .regisFrm input[type="text"], .regisFrm input[type="email"], .regisFrm input[type="password"] {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        outline: none;
        color: #000;
        font-weight: 500;
        font-family: 'Roboto', sans-serif;
    }
    .send-button {
        text-align: center;
        margin-top: 20px;
    }
    .send-button input[type="submit"] {
        padding: 10px 0;
        width: 70%;
        font-family: 'Roboto', sans-serif;
        font-size: 18px;
        font-weight: 500;
        border: none;
        outline: none;
        color: #FFF;
        background-color: #f5bb40;
        cursor: pointer;
    }
    .send-button input[type="submit"]:hover {
        background-color: #f5bb40;
    }
    a.logout{float: right;}
    p.success{color:#34A853;}
    p.error{color:#EA4335;}   
        </style>
        <p><img src='<?php echo $http.'://' . $_SERVER["HTTP_HOST"];?>/webapp/img/empresa/<?php echo LOGOEMPRESA;?>' width='240' height='auto' ></p>
        <h2>Resetear tu clave de acceso</h2>

        <div class="container">

            <form action="userAccount.php" method="post" onsubmit="return myFunction();" class="regisFrm">
                <input type="email" name="usuario" placeholder="Su email" required="">
                <input type="password" id="pass1" name="password" placeholder="Contraseña" required="">
                <input type="password" id="pass2" name="confirm_password" placeholder="Confirme contraseña" required="">
                <div class="send-button">
                    <input type="hidden" name="seguridad" value="<?php echo $seguridad;?>"/>
                    <input type="submit" name="resetSubmit" value="Resetear">
                </div>
            </form>
        </div>
        <script>
        function myFunction(){
            pass1 = document.getElementById('pass1');
            pass2 = document.getElementById('pass2');     
            if (pass1.value != pass2.value) {

                alert("las contraseñas no coinciden");


                return false;
            }

            else {
                return true;
            }
        }
        </script>
    </body>
    </html>

    <?php
    
    
    
}
 


?>



