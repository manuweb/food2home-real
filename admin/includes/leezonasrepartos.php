<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;
$sql="select id, nombre, reparto FROM zona_repartos ORDER BY orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $num_results = $result->num_rows;
    $checking=true;
    if ($num_results > 0) {
        
        while ($grupo = $result->fetch_object()) {
            $id[]=$grupo->id;
            $nombre[]=$grupo->nombre;
            $reparto[]=$grupo->reparto;  
        }
    }	
    else {
        $id=[];
    }
}

$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"reparto"=>$reparto);
ob_end_clean();
echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "nombre: ". $nombre[0] . PHP_EOL);

fclose($file);
*/
?>
