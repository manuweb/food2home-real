<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;

$where='';
if ($array['foo']=='id'){
    $where=' WHERE id='.$array['id'];
}
$sql="SELECT id,nombre, codigo FROM promos WHERE id>2 and activo=1;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    while ($prom = $result->fetch_object()) {
        $checking=true;
        $nombre[]=$prom->nombre;
        $id[]=$prom->id;
        $codigo[]=$prom->codigo;
    }
}	

$database->freeResults();

$json=array("valid"=>$checking,"nombre"=>$nombre,"id"=>$id,"codigo"=>$codigo);

echo json_encode($json); 

/*
$file = fopen("campaign.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
