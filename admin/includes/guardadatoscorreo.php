<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;

$sql="UPDATE mail SET nombreremitente='".$array['nombreremitente']."', mail='".$array['mail']."',usuariomail='".$array['usuariomail']."', clavemail='".$array['clavemail']."', host='".$array['host']."', puerto='".$array['puerto']."', SMTPSecure='".$array['SMTPSecure']."', sender='".$array['sender']."', cco='".$array['cco']."', cco_registro='".$array['cco_registro']."', cco_pedidos='".$array['cco_pedidos']."', cco_contacto='".$array['cco_contacto']."', pie='".$array['pie']."' WHERE id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
}	

$database->freeResults();

$json=array("valid"=>$checking);
echo json_encode($json); 

/*
$file = fopen("empresa.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
