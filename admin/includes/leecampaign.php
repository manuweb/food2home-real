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
$sql="SELECT id,nombre, fecha, usuario, grupo, CONVERT(texto USING utf8) AS texto, alcance, emails FROM campaign".$where." ORDER BY ID DESC;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    while ($camp = $result->fetch_object()) {
        $checking=true;
        $nombre[]=$camp->nombre;
        $id[]=$camp->id;
        $fecha[]=$camp->fecha;
        $usuario[]=$camp->usuario;
        $grupo[]=$camp->grupo;
        $texto[]=$camp->texto;
        $alcance[]=$camp->alcance;
        $emails[]=str_replace(array("\r\n", "\r", "\n"), "<br>", $camp->emails);
    }
}	

$database->freeResults();

$json=array("valid"=>$checking, "nombre"=>$nombre, "id"=>$id, "fecha"=>$fecha, "usuario"=>$usuario, "grupo"=>$grupo, "texto"=>$texto, "alcance"=>$alcance, "emails"=>$emails);

echo json_encode($json); 

/*
$file = fopen("campaign.txt", "w");
fwrite($file, "foo: ". $array['foo'] . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);
fwrite($file, "texto 0: ". $txto[0] . PHP_EOL);
fclose($file);
*/

?>
