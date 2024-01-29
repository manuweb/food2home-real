
<?php
/*
 *
 * Archivo: borradireccion.php
 *
 * Version: 1.0.1
 * Fecha  : 02/10/2023
 * Se usa en :masdatos.js ->borraDomicilio()
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

$sql="DELETE FROM domicilios WHERE id='".$array['id']."';"
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true; 
}	

$database->freeResults();  

$json=array("valid"=>$checking,);

echo json_encode($json); 

?>
