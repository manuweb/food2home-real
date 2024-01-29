<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  actualizapush.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde push.js ->
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;




$sql="SELECT max(id) AS maxid from push";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result && $result->num_rows != 0) {
    $max=$push->maxid;
    $sql="UPDATE push SET ios_ok='".$array['iok']."', ios_ko='".$array['iko']."', android_ok='".$array['aok']."', android_ko='".$array['ako']."', web_ok='".$array['wok']."', web_ko='".$array['wko']."' WHERE id='".$max."';";
        
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {
        $checking=true;
    }   
    
}
$database->freeResults(); 



$json=array("valid"=>$checking);

echo json_encode($json); 
/*

$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);

fwrite($file, "jason: ".json_encode($json). PHP_EOL);

fclose($file);

*/
?>
