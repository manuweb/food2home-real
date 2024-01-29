<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;



$incexc="";

if ($array['tipo']=='add'){
    $incexc=" WHERE grupoclientes!='".$array['id']."' AND grupoclientes='0'"; 
}
else {
    $incexc=" WHERE grupoclientes='".$array['id']."';"; 
}

$sql="SELECT id, apellidos, nombre, username, grupoclientes FROM usuarios_app".$incexc.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result->num_rows>0) {
    $checking=true;
    while ($grupos = $result->fetch_object()) {
        $id[]=$grupos->id;
        $nombre[]=$grupos->nombre;
        $apellidos[]=$grupos->apellidos;
        $username[]=$grupos->username;
    }
}	

$database->freeResults(); 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"apellidos"=>$apellidos,"username"=>$username);

echo json_encode($json); 

/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "jsonl: ". json_encode($json) . PHP_EOL);
fclose($file);
*/


?>
