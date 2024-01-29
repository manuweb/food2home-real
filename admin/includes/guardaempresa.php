<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="UPDATE empresa SET nombre='".$array['nombre']."', nombre_comercial='".$array['nombre_comercial']."',nif_cif='".$array['nif']."', domicilio='".$array['domicilio']."', cod_postal='".$array['cpostal']."', poblacion='".$array['poblacion']."', provincia='".$array['provincia']."', telefono='".$array['telefono']."', movil='".$array['movil']."', email='".$array['email']."' WHERE id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
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
