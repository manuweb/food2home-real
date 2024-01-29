<?php
/*
 *
 * Archivo: leeproductossearch.php
 *
 * Version: 1.0.0
 * Fecha  : 25/11/2022
 * Se usa en :tienda.js ->muestraproductoinicio()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
//*************************************************************************************************
//
//  leeproductos.php
//
//  Lee datos de la tabla productos 
//
//  Llamado desde tienda.js ->muestraproductos()
//
//*************************************************************************************************


$array = json_decode(json_encode($_POST), true);


$checking=false;

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="productos.activo_web='1'";
}
else {
    $isApp="productos.activo_app='1'";;
}
if (!isset($array['id']))
{
    $isId="";
}
else {
    if (($array['id']=="")||($array['id']==null)||(!isset($array['id'])) ){
        $isId="";
    }
    else {
        $isId=" AND productos.id=".$array['id'];
    }
    
}


$sql="SELECT productos.id, productos.nombre, productos.categoria, categorias.nombre AS catnombre, grupos.id AS grupoid, grupos.nombre AS gruponombre,productos.precio_web, productos.precio_app, productos.modifier_category_id, productos.modifier_group_id, impuestos.porcentaje AS impuesto FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE ".$isApp.$isId." AND productos.tienda=".$array['tienda']." GROUP BY productos.nombre";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;

    while ($grupo = $result->fetch_object()) {
        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        $cat_id[]=$grupo->categoria;
        $gru_id[]=$grupo->grupoid;
        $cat_nombre[]=$grupo->catnombre;
        $gru_nombre[]=$grupo->gruponombre;
        $precio_web[]=$grupo->precio_web;
        $precio_app[]=$grupo->precio_app;
        $iva[]=$grupo->impuesto;
        $modifier_category_id[]= $grupo->modifier_category_id;
        $modifier_group_id[]= $grupo->modifier_group_id;
    }
  
}

$database->freeResults(); 
 
$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio_web"=>$precio_web,"precio_app"=>$precio_app,"cat_id"=>$cat_id,"gru_id"=>$gru_id,"cat_nombre"=>$cat_nombre,"gru_nombre"=>$gru_nombre,"iva"=>$iva,"modifier_category_id"=>$modifier_category_id, "modifier_group_id"=>$modifier_group_id);
ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("zztxt.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/
?>
