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

$sql="SELECT productos.id, productos.nombre, productos.imagen, productos.info, productos.alergias, productos.activo, productos.activo_web, productos.activo_app, productos.modificadores, productos.imagen_app1, productos.imagen_app2, productos.imagen_app3, productos.precio, productos.precio_web, productos.precio_app, productos.modifier_category_id, productos.modifier_group_id, impuestos.porcentaje AS impuesto FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE productos.id='".$array['id']."' AND productos.tienda='".$array['tienda']."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    //while ($grupo = $result->fetch_object()) {
        $grupo = $result->fetch_object();
        $nombre=$grupo->nombre;
        //if ($grupo->imagen!=''){
            $imagen=$grupo->imagen;
        //}
        //else {
          //  $imagen[]="";
        //}
        $activo=$grupo->activo;
        $activo_web=$grupo->activo_web;
        $activo_app=$grupo->activo_app;
        $modificadores=$grupo->modificadores;
        $impuesto=$grupo->impuesto;
        $info=$grupo->info;
        $alergias=$grupo->alergias;
        $imagen_app1=$grupo->imagen_app1;
        $imagen_app2=$grupo->imagen_app2;
        $imagen_app3=$grupo->imagen_app3;
        $precio=$grupo->precio;
        $precio_web=$grupo->precio_web;
        $precio_app=$grupo->precio_app;
        $modifier_category_id=$grupo->modifier_category_id;
        $modifier_group_id=$grupo->modifier_group_id;
        $alergenos= [];
        if ($alergias!=""){

            $sql2="SELECT id,nombre, imagen FROM alergenos WHERE id IN (".$alergias.");";
            $database = DataBase::getInstance();
            $database->setQuery($sql2);
            $result = $database->execute();

            if ($result) {    
                $checking=true;
                $j=0;
                while ($ale = $result->fetch_object()) {
                    $alergenos[$j]['id']=$ale->id;
                    $alergenos[$j]['nombre']=$ale->nombre;
                    $alergenos[$j]['imagen']=$ale->imagen;   
                    $j++;
                }
            }
        

        }
    //}
}	
$database->freeResults();
$json=array("valid"=>$checking,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app1"=>$imagen_app1,"imagen_app2"=>$imagen_app2,"imagen_app3"=>$imagen_app3,"precio"=>$precio,"precio_web"=>$precio_web,"precio_app"=>$precio_app,"activo"=>$activo,"activo_web"=>$activo_web,"activo_app"=>$activo_app,"impuesto"=>$impuesto,"info"=>$info, "modifier_category_id"=>$modifier_category_id, "modifier_group_id"=>$modifier_group_id, "alergenos"=>$alergenos, "modifcadores"=>$modificadores);


echo json_encode($json); 

/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "alergias: ". $alergias . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);
fclose($file);
*/

?>
