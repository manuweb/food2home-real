<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);


$checking=false;



$sql="select id, nombre, precio, minimo, reparto,zona FROM zona_repartos Where id='".$array['id']."'";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
$checking=true;
        while ($grupo = $result->fetch_object()) {

            $id=$grupo->id;
            $nombre=$grupo->nombre;
            $precio=$grupo->precio;
            $minimo=$grupo->minimo;
            $reparto=$grupo->reparto;
            $zona=array_map('trim', preg_split('/\R/', $grupo->zona));

            for ($n=0;$n<count($zona);$n++) {
                $var=explode(",", $zona[$n]);
                $coor[]=array('lat'=>$var[1],'lng'=>$var[0]);
            }

        }
    

}

$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio"=>$precio,"minimo"=>$minimo,"reparto"=>$reparto,"zona"=>$coor);

ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("zz-zonatexto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "json: ".json_encode($json). PHP_EOL);
fclose($file);
*/

?>
