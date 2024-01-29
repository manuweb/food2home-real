<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


    $ruta_archivo = '../version.txt';
    $archivo = fopen($ruta_archivo, 'r');

    while(!feof($archivo)) {
            $version= fgets($archivo);
    }

    fclose($archivo);

    

    $json=array("valid"=>true,"actual"=>$version,);

    echo json_encode($json); 




?>
