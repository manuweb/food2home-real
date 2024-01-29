<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
//include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');

$array = json_decode(json_encode($_POST), true);
$checking=false;

//$array['id']=1;
//$syncimagen=$array['syncimagen'];

//echo 'id='.$array['id']."<br>";

$sql="SELECT id, nombre, orden, imagen, impuesto, activo, activo_web, activo_app, imagen_app FROM grupos WHERE tienda=0;";

//echo $sql."<br><br>";

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
            $impuesto[]=$grupo->impuesto;
            $imagen_app[]=$grupo->imagen_app;
            $orden[]=$grupo->orden;
            $activo[]=$grupo->activo;
            $activo_web[]=$grupo->activo_web;
            $activo_app[]=$grupo->activo_app;           
        }
        ////echo 'Total:'.count($id);
       
        for ($x=0;$x<count($id);$x++){
            $sql="INSERT INTO grupos (id, tienda, nombre, orden, imagen, impuesto, activo, activo_web, activo_app, imagen_app) VALUES (".$id[$x].", ".$array['id'].", '".$nombre[$x]."', ".$orden[$x].", '".$imagen[$x]."', '".$impuesto[$x]."', '".$activo[$x]."', '".$activo_web[$x]."', '".$activo_app[$x]."', '".$imagen_app[$x]."');";
            
            //echo $sql."<br>";
           
            $database->setQuery($sql);
            $result = $database->execute();
            
            if ($result) {
                $checking=true;
            }
            else {
                $checking=false;
            }
           
        }
    
       
    }
}

$sql="SELECT id, nombre, grupo, orden, imagen, impuesto, activo, activo_web, activo_app, imagen_app, modifier_category_id, modifier_group_id FROM categorias WHERE tienda=0;";
//echo $sql."<br><br>";
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $num_results = $result->num_rows;
    $checking=true;
    if ($num_results > 0) {
        while ($categ = $result->fetch_object()) {
            $idC[]=$categ->id;
            $nombreC[]=$categ->nombre;
            $grupoC[]=$categ->grupo;
            $imagenC[]=$categ->imagen;
            $impuestoC[]=$categ->impuesto;
            $imagen_appC[]=$categ->imagen_app;
            $ordenC[]=$categ->orden;
            $activoC[]=$categ->activo;
            $activo_webC[]=$categ->activo_web;
            $activo_appC[]=$categ->activo_app;
            $modifier_category_idC[]=$categ->modifier_category_id;
            $modifier_group_idC[]=$categ->modifier_group_id;
            
        }
        for ($x=0;$x<count($idC);$x++){
            ////echo 'Grupo:'.$grupoC[$x]."<br>";
            $sql="INSERT INTO categorias (id, tienda, nombre, grupo, orden, imagen, impuesto, activo, activo_web, activo_app, imagen_app, modifier_category_id, modifier_group_id) VALUES ('".$idC[$x]."', '".$array['id']."', '".$nombreC[$x]."', '".$grupoC[$x]."',  '".$ordenC[$x]."', '".$imagenC[$x]."', '".$impuestoC[$x]."', '".$activoC[$x]."', '".$activo_webC[$x]."', '".$activo_appC[$x]."', '".$imagen_appC[$x]."', '".$modifier_category_idC[$x]."', '".$modifier_group_idC[$x]."');";
            
            //echo $sql."<br>";
            
            $database->setQuery($sql);
            
            $result = $database->execute();
            if ($result) {
                $checking=true;
            }
            else {
                $checking=false;
            }
            
        }
    }

}

$sql="SELECT id, nombre, categoria, orden, imagen, impuesto, activo, precio, info, alergias,  precio_web, precio_app, activo_web, activo_app, imagen_app1, modifier_category_id, modifier_group_id, modificadores, esMenu FROM productos WHERE tienda=0;";
//echo $sql."<br><br>";
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $num_results = $result->num_rows;
    $checking=true;
    if ($num_results > 0) {
        while ($prod = $result->fetch_object()) {
            $idP[]=$prod->id;
            $nombreP[]=$prod->nombre;
            $categoriaP[]=$prod->categoria;
            $imagenP[]=$prod->imagen;
            $impuestoP[]=$prod->impuesto;
            $imagen_appP[]=$prod->imagen_app1;
            $ordenP[]=$prod->orden;
            $activoP[]=$prod->activo;
            $precioP[]=$prod->precio;
            $infoP[]=$prod->info;
            $alergiasP[]=$prod->alergias;
            $precio_webP[]=$prod->precio_web;
            $precio_appP[]=$prod->precio_app;
            $modificadoresP[]=$prod->modificadores;
            $esMenuP[]=$prod->esMenu;
        
            $activo_webP[]=$prod->activo_web;
            $activo_appP[]=$prod->activo_app;
            $modifier_category_idP[]=$prod->modifier_category_id;
            $modifier_group_idP[]=$prod->modifier_group_id;
            
        }
        for ($x=0;$x<count($idP);$x++){
            $sql="INSERT INTO productos (id, tienda, nombre, categoria, orden, imagen, impuesto, activo, precio, info, alergias,  precio_web, precio_app, activo_web, activo_app, imagen_app1, modifier_category_id, modifier_group_id, modificadores, esMenu) VALUES ('".$idP[$x]."', '".$array['id']."', '".$nombreP[$x]."',  '".$categoriaP[$x]."', '".$ordenP[$x]."', '".$imagenP[$x]."', '".$impuestoP[$x]."', '".$activoP[$x]."', '".$precioP[$x]."', '".$infoP[$x]."', '".$alergiasP[$x]."', '".$precio_webP[$x]."', '".$precio_appP[$x]."', '".$activo_webP[$x]."', '".$activo_appP[$x]."', '".$imagen_appP[$x]."', '".$modifier_category_idP[$x]."', '".$modifier_group_idP[$x]."', '".$modificadoresP[$x]."',  '".$esMenuP[$x]."');";
            //echo $sql."<br>";
            
            $database->setQuery($sql);
            $result = $database->execute();
            if ($result) {
                $checking=true;
            }
            else {
                $checking=false;
            }
            
        }
    }

}

$database->freeResults();
 

$json=array("valid"=>$checking);

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
