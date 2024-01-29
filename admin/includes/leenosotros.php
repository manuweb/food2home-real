<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  leeinicio.php
//
//  Lee datos de la tabla nosotros 
//
//  Llamado desde paginanosotros.js ->paginanosotros()
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$where="";
if(isset($array['id'])){
    $where="WHERE id='".$array['id']."'";
}

$sql="SELECT id, tipo,nombre,web,app, texto FROM nosotros ".$where." ORDER BY orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
        $checking=true;
    $num_results = $result->num_rows;
    
    if ($num_results > 0) {
        while ($inicio = $result->fetch_object()) {

            $id[]=$inicio->id;
            $tipo[]=$inicio->tipo;
            $nombre[]=$inicio->nombre;
            $web[]=$inicio->web;
             $app[]=$inicio->app;
            $texto[]=$inicio->texto;
         }	
    }
    else {
        $id=[];
    }
}
$database->freeResults();

$json=array("valid"=>$checking,"id"=>$id,"tipo"=>$tipo,"nombre"=>$nombre,"activo_web"=>$web,"activo_app"=>$app,"texto"=>$texto);
ob_end_clean();
echo json_encode($json); 

/*
//print_r($texto);
$file = fopen("nosotros2.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/
?>
