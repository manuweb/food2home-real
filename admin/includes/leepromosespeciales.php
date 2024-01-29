<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$sql="SELECT id, nombre, codigo,activo,envio_recoger, usuario, grupo,dias, desde, hasta, tipo, logica FROM promos;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;
    $n=0;
    while ($promos = $result->fetch_object()) {

        $id[$n]=$promos->id;
        $nombre[$n]=$promos->nombre;
        $codigo[$n]=$promos->codigo;
        $activo[$n]=$promos->activo; 
        $envio_recoger[$n]=$promos->envio_recoger; 
        $usuario[$n]=$promos->usuario; 
        $grupo[$n]=$promos->grupo; 
        $dias[$n]=$promos->dias; 
        $desde[$n]=$promos->desde; 
        $hasta[$n]=$promos->hasta;
        $tipo[$n]=$promos->tipo;   
        $logica[$n]=$promos->logica;   
        $n++;
    }
    
}	

$database->freeResults(); 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"codigo"=>$codigo,"activo"=>$activo,"envio_recoger"=>$envio_recoger,"usuario"=>$usuario,"grupo"=>$grupo,"desde"=>$desde,"hasta"=>$hasta,"dias"=>$dias,"tipo"=>$tipo,"logica"=>$logica);

ob_end_clean();

echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "json: ".json_encode($json). PHP_EOL);
fclose($file);
*/

?>
