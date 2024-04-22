<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;
$checking=false;

$sql="select id, nombre, precio, minimo, reparto ,zona FROM zona_repartos";


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
            $reparto[]=$grupo->reparto;  
            $precio[]=$grupo->precio; 
            $minimo[]=$grupo->minimo; 
            $zona=array_map('trim', preg_split('/\R/', $grupo->zona));
            $coor=array();
            for ($j=0;$j<count($zona);$j++) {
                $var=explode(",", $zona[$j]);
                $coor[]=array('lat'=>$var[1],'lng'=>$var[0]);
            }
            $zonas[]=$coor;
        }
    }	
    else {
        $id=[];
    }
}

$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"reparto"=>$reparto,"precio"=>$precio,"minimo"=>$minimo,"zonas"=>$zonas);
ob_end_clean();
echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "nombre: ". $nombre[0] . PHP_EOL);

fclose($file);
*/
?>
