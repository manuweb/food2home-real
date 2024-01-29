<?php
/*
 *
 * Archivo: cambiapreferenciadireccion.php
 *
 * Version: 1.0.1
 * Fecha  : 02/10/2023
 * Se usa en :masdatos.js ->guardaDomicilio() y desde compras.js ->guardaDomicilioCompra()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

 $preferida=1;

if ($array['iddomi']=="NUEVO"){
    $sql="SELECT id FROM domicilios WHERE usuario='".$array['id']."';";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    
    if ($result && $result->num_rows != 0) {
         $preferida=0;
    }
    
    
    $sql="INSERT INTO domicilios (usuario, alias,preferida,direccion, complementario, cod_postal, poblacion, provincia, lat, lng) VALUES ('".$array['id']."', '".$array['alias']."', ".$preferida.", '".$array['direccion']."', '".$array['complementario']."', '".$array['cod_postal']."', '".$array['poblacion']."', '".$array['provincia']."', '".$array['lat']."', '".$array['lng']."');";
  
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    if ($result) {
        $checking=true;  
    }
}
else {
    $sql="UPDATE domicilios SET alias='".$array['alias']."', direccion='".$array['direccion']."', complementario='".$array['complementario']."', cod_postal='".$array['cod_postal']."', poblacion='".$array['poblacion']."', provincia='".$array['provincia']."', lat='".$array['lat']."', lng='".$array['lng']."' WHERE id='".$array['iddomi']."';";
        
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {
        $checking=true;  
    }
}
$database->freeResults();  

$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("actualizacion_telefono.txt", "w");
fwrite($file, "Sql: ". $sql . PHP_EOL);
fclose($file);	
*/

?>
