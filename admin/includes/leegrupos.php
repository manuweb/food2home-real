<?php

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');

$array = json_decode(json_encode($_POST), true);
$checking=false;

//$syncimagen=$array['syncimagen'];

$sql="select g.id, g.nombre, g.imagen, g.imagen_app, g.orden, g.activo,g.activo_web,g.activo_app, c.total FROM grupos g LEFT JOIN (SELECT grupo, COUNT(*) as 'total' FROM categorias WHERE tienda=".$array['tienda']." GROUP BY grupo) c on g.id = c.grupo  WHERE g.tienda=".$array['tienda']." ORDER BY g.orden;";





$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $num_results = $result->num_rows;
    $checking=true;
    if ($num_results > 0) {
        while ($grupo = $result->fetch_object()) {
            $id[]=$grupo->id;
            $nombre[]=$grupo->nombre;
            $imagen[]=$grupo->imagen;
            $imagen_app[]=$grupo->imagen_app;
            $orden[]=$grupo->orden;
            $activo[]=$grupo->activo;
            $activo_web[]=$grupo->activo_web;
            $activo_app[]=$grupo->activo_app;
            $total[]=$grupo->total;
        }
    }
    else {
        $id=[];
    }
    $checking=true;
}
$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app"=>$imagen_app,"orden"=>$orden,"total"=>$total,"activo"=>$activo,"activo_web"=>$activo_web,"activo_app"=>$activo_app);
ob_end_clean();
echo json_encode($json); 


/*
$file = fopen($array['id'].".txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);
*/
?>
