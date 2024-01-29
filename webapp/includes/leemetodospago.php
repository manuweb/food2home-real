<?php
/*
 *
 * Archivo: leemetodospago.php
 *
 * Version: 1.0.0
 * Fecha  : 29/11/2022
 * Se usa en :comprar.js ->leemetodospago()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
include "../../webapp/config.php";

$sql="SELECT id, nombre, activo, idrevo FROM metodospago ORDER BY id;";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$metodos = $db->loadObjectList();  
$db->freeResults();

if (count($metodos)>0) {
    $checking=true;
    for ($n=0;$n<count($metodos);$n++){
        $id[$n]=$metodos[$n]->id;
        $nombre[$n]=$metodos[$n]->nombre;
        $activo[$n]=$metodos[$n]->activo;   
        $idrevo[$n]=$metodos[$n]->idrevo;   
    }
    
}	

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"activo"=>$activo,"idrevo"=>$idrevo);

ob_end_clean();
echo json_encode($json); 

//print_r($texto);


?>
