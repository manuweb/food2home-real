<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
//*************************************************************************************************
//
//  leenosotros.php
//
//  Lee datos de la tabla nosotros 
//
//  Llamado desde apps.js 
//
//*************************************************************************************************

$array = json_decode(json_encode($_POST), true);

$checking=false;

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="web='1'";
}
else {
    $isApp="APP='1'";;
}

$sql="SELECT id, tipo, texto FROM nosotros  WHERE ".$isApp." ORDER BY orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$id=[];
$tipo=[];
$texto=[];
// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
  
    $num_results = $result->num_rows;
    
    if ($num_results > 0) {
        while ($row = $result->fetch_object()) {
            $id[]=$row->id;
            $tipo[]=$row->tipo;
            $texto[]=$row->texto;
        }
    }
    else {
        $id=[];
    }
}

$database->freeResults();


$json=array("valid"=>$checking,"id"=>$id,"tipo"=>$tipo,"texto"=>$texto);
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
