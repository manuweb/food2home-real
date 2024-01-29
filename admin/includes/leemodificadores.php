<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

$sql="SELECT id, nombre, activo, precio, autoseleccionado FROM modifiers WHERE category_id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    while ($modifiers = $result->fetch_object()) {
        $id[]=$modifiers->id;
        $nombre[]=$modifiers->nombre;
        $activo[]=$modifiers->activo;
        $precio[]=$modifiers->precio;
        $autoseleccionado[]=$modifiers->autoseleccionado;
    }

}

$database->freeResults();

$json=array("valid"=>$checking,"nombre"=>$nombre,"id"=>$id,"precio"=>$precio,"activo"=>$activo,"autoseleccionado"=>$autoseleccionado);


echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "jason: ". json_encode($json) . PHP_EOL);
fclose($file);
*/

?>
