<?php
/*
 *
 * Archivo: leedestacados.php
 *
 * Version: 1.0.0
 * Fecha  : 10/01/2023
 * Se usa en :tienda.js ->muestragrupos()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";

 
$array = json_decode(json_encode($_POST), true);

$checking=false;
if ($array['sitio']=="inicio") {
    $isApp="destacados.inicio";
}
else {
    $isApp="destacados.catalogo";
}
$checking=false;


$where=" WHERE ".$isApp."=1";


$sql="SELECT destacados.id, destacados.producto,destacados.inicio,destacados.catalogo, productos.nombre, productos.imagen, productos.imagen_app1, categorias.nombre AS nomcat, grupos.nombre AS nomgru, categorias.id as idcat, grupos.id as idgru FROM destacados INNER JOIN productos ON productos.id= destacados.producto INNER JOIN categorias ON categorias.id = productos.categoria INNER JOIN grupos ON grupos.id=categorias.grupo".$where." ORDER BY destacados.orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$id=[];
$producto=[];
$nombre=[];
$inicio=[];
$catalogo=[];
$cat=[];
$gru=[];
$idcat=[];
$idgru=[];
$imagen=[];
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
        $idcat[]=$destacados->idcat;
         $idgru[]=$destacados->idgru;
        
        if ($destacados->imagen!=""){
            $img=IMGREVO.$destacados->imagen;
        }
        if ($destacados->imagen_app1!=""){
            $img=IMGAPP.$destacados->imagen_app1;
        }
        $imagen[]=$img;
     }	
}

$database->freeResults();

$json=array("valid"=>$checking,"id"=>$id,"producto"=>$producto,"nombre"=>$nombre,"activo_inicio"=>$inicio,"activo_catalogo"=>$catalogo,"cat"=>$cat,"gru"=>$gru,"imagen"=>$imagen,"idcat"=>$idcat,"idgru"=>$idgru);

echo json_encode($json); 

//print_r($texto);
/*
$file = fopen("zz-catatexto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/
?>
