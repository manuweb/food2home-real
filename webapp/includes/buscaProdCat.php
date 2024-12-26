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

if($array['tipo']=='producto'){

    $sql="SELECT productos.id, productos.nombre, productos.categoria, categorias.nombre AS catnombre, grupos.id AS grupoid, grupos.nombre AS gruponombre,productos.precio_web, productos.precio_app, productos.modifier_category_id, productos.modifier_group_id FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo WHERE productos.id=".$array['id']." AND productos.tienda=".$array['tienda'].";";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $grupo = $result->fetch_object();
    if ($result->num_rows>0) {
        $checking=true;
        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        $cat_id[]=$grupo->categoria;
        $gru_id[]=$grupo->grupoid;
        $cat_nombre[]=$grupo->catnombre;
        $gru_nombre[]=$grupo->gruponombre;
        $precio_web[]=$grupo->precio_web;
        $modifier_category_id[]= $grupo->modifier_category_id;
        $modifier_group_id[]= $grupo->modifier_group_id;
    }
    $database->freeResults(); 
    $json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio_web"=>$precio_web,"precio_app"=>$precio_app,"cat_id"=>$cat_id,"gru_id"=>$gru_id,"cat_nombre"=>$cat_nombre,"gru_nombre"=>$gru_nombre,"iva"=>$iva,"modifier_category_id"=>$modifier_category_id, "modifier_group_id"=>$modifier_group_id);
    ob_end_clean();
    echo json_encode($json); 

}
else {
    $sql="SELECT categorias.id, categorias.nombre, grupos.id AS grupoid, grupos.nombre AS gruponombre FROM categorias LEFT JOIN grupos ON grupos.id=categorias.grupo WHERE  categorias.id=".$array['id'].";";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $grupo = $result->fetch_object();
    if ($result->num_rows>0) {
        $checking=true;
        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        $gru_id[]=$grupo->grupoid;
        $gru_nombre[]=$grupo->gruponombre;    
    }
    $database->freeResults(); 
    $json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"gru_id"=>$gru_id,"gru_nombre"=>$gru_nombre);
    ob_end_clean();
    echo json_encode($json); 
    
}


?>
