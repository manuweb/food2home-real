<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


$cliente=$array['cliente'];
$usuario=$array['usuario'];
$aSumar=-1*$array['aSumar'];
$monedero=$array['monedero'];
$checking=false;

$sql="INSERT INTO monedero(fecha, cliente, importe, usuario) VALUES (CURRENT_DATE(), ".$cliente.", ".$aSumar.", ".$usuario.");";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$sql="UPDATE usuarios_app SET  monedero=".$monedero." WHERE id=".$cliente.";";
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $checking=true;
   
}	

$database->freeResults();  

$json=array("valid"=>$checking);
ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("zz-leeclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
