<?php
/*
 *
 * Archivo: leezonasreparto.php
 *
 * Version: 1.0.1
 * Fecha  : 11/10/2023
 * Se usa en :comprar.js ->comprapaso3Nuevo() y seleccionardomicilio()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

$sql="select id, nombre, precio, minimo, reparto ,zona FROM zona_repartos";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$grupo = $db->loadObjectList();  
$db->freeResults();


if (count($grupo)>0) { 
   for ($n=0;$n<count($grupo);$n++) { 
        $id[]=$grupo[$n]->id;
        $nombre[]=$grupo[$n]->nombre;
        $precio[]=$grupo[$n]->precio;
        $minimo[]=$grupo[$n]->minimo;
        $reparto[]=$grupo[$n]->reparto;
        $zona=array_map('trim', preg_split('/\R/', $grupo[$n]->zona));
        $coor=array();
        for ($j=0;$j<count($zona);$j++) {
            $var=explode(",", $zona[$j]);
            $coor[]=array('lat'=>$var[1],'lng'=>$var[0]);
        }
        $zonas[]=$coor;
   }
    $checking=true;
}


 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio"=>$precio,"minimo"=>$minimo,"reparto"=>$reparto,"zonas"=>$zonas);
ob_end_clean();
echo json_encode($json); 



?>
