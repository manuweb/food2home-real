<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
//*************************************************************************************************
//
//  leedestacados.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde paginainicio.js ->paginainicio()
//
//*************************************************************************************************

//$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;

$where=" ";
//$where=" WHERE productos.activo_web=1 OR productos.activo_app=1";
if(isset($array['id'])){
    $where=" WHERE destacados.id='".$array['id']."'";
}

$sql="SELECT destacados.id, destacados.producto,destacados.inicio,destacados.catalogo, productos.nombre, productos.imagen, productos.imagen_app1, categorias.nombre AS nomcat, grupos.nombre AS nomgru FROM destacados INNER JOIN productos ON productos.id= destacados.producto INNER JOIN categorias ON categorias.id = productos.categoria INNER JOIN grupos ON grupos.id=categorias.grupo".$where." ORDER BY destacados.orden;";

//$sql="SELECT destacados.id, destacados.producto,destacados.inicio,destacados.catalogo, productos.nombre, productos.imagen, productos.imagen_app1, categorias.nombre AS nomcat, grupos.nombre AS nomgru FROM destacados INNER JOIN productos ON productos.id= destacados.producto INNER JOIN categorias ON categorias.id = productos.categoria INNER JOIN grupos ON grupos.id=categorias.grupo ORDER BY destacados.orden;";
/*

*/

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    while ($destacados = $result->fetch_object()) {
        $id[]=$destacados->id;
        $producto[]=$destacados->producto;
        $nombre[]=$destacados->nombre;
        $inicio[]=$destacados->inicio;
        $catalogo[]=$destacados->catalogo;
        $cat[]=$destacados->nomcat;
        $gru[]=$destacados->nomgru;
        if ($destacados->imagen!=""){
            $imagen[]=IMGREVO.$destacados->imagen;
        }
        else {
            $imagen[]=IMGAPP.$destacados->imagen_app1;
        }
     }	
}

$database->freeResults();

$json=array("valid"=>$checking,"id"=>$id,"producto"=>$producto,"nombre"=>$nombre,"activo_inicio"=>$inicio,"activo_catalogo"=>$catalogo,"cat"=>$cat,"gru"=>$gru,"imagen"=>$imagen);



echo json_encode($json); 


/*
//print_r($texto);
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
