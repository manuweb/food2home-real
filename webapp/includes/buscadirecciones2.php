<?php
/*
 *
 * Archivo: buscadirecciones2.php
 *
 * Version: 1.0.1
 * Fecha  : 11/10/2023
 * Se usa en :comprar.js ->seleccionardomicilio() 
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



$sql="SELECT id, direccion, complementario, cod_postal, poblacion, provincia, lat, lng FROM domicilios WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result && $result->num_rows != 0) {
    $domicilios = $result->fetch_object();
    $checking=true;
    $id=$domicilios->id;
    $direccion=$domicilios->direccion;
    $complementario=$domicilios->complementario;
    $cod_postal=$domicilios->cod_postal;
    $poblacion=$domicilios->poblacion;
    $provincia=$domicilios->provincia;
    $lat=$domicilios->lat;
    $lng=$domicilios->lng;

}	
$database->freeResults();   
$json=array("valid"=>$checking, "id"=>$id, "direccion"=>$direccion, "complementario"=>$complementario, "cod_postal"=>$cod_postal, "poblacion"=>$poblacion, "provincia"=>$provincia, "lat"=>$lat, "lng"=>$lng);

ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("buscadirecciones.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
