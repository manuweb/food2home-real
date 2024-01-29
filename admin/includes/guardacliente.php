<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;

$nacimiento=substr($array['nacimiento'],6,4).'-'.substr($array['nacimiento'],3,2).'-'.substr($array['nacimiento'],0,2);
$sql="UPDATE usuarios_app SET username='".$array['usuario']."', ".$update." tratamiento='".$array['tratamiento']."', nombre='".$array['nombre']."', apellidos='".$array['apellidos']."', nacimiento='".$nacimiento."', telefono='".$array['telefono']."', telefono='".$array['telefono']."',monedero='".$array['monedero']."' WHERE id='".$array['idCliente']."';";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
    
}	
$json=array("valid"=>$checking);
ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("zz-guardaclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
