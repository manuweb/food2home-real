<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
/*
 *
 * Archivo: leeproductos.php
 *
 * Version: 1.0.2
 * Fecha  : 208/10/2023
 * Se usa en :tienda.js -> muestraproductos()
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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);



$checking=false;

/*
$array['tienda']=1;
$array['idgrupo']=4;
$array['nombregrupo']='BEBIDAS';
$array['idcategoria']=19;
$array['nombrecategoria']='CERVEZAS';
$array['isapp']='false';
*/

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="productos.activo_web='1'";
}
else {
    $isApp="productos.activo_app='1'";;
}





//$sql="select id, nombre, precio_web,imagen,imagen_app1,imagen_app2,imagen_app3 FROM productos WHERE categoria='".$array['categoria']."' AND activo_web='1' ORDER BY orden;";
/*

$sql="SELECT productos.id, productos.nombre, productos.imagen, productos.imagen_app1, productos.imagen_app2, productos.imagen_app3,  productos.precio_web, productos.precio_app,productos.modificadores,productos.modifier_category_id, productos.modifier_group_id, impuestos.porcentaje AS impuesto, modifierCategories.modificadores AS listamodificadores, categorias.grupo AS elgrupo,
if (productos.modifier_category_id!='',1,
    	if (productos.modifier_group_id!='',1,
            if (categorias.modifier_category_id!='', 1,
                if (categorias.modifier_group_id!='', 1, 0)
               )
            )
    ) as haymod FROM productos LEFT JOIN modifierCategories ON modifierCategories.id=productos.modifier_category_id LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE productos.categoria='".$array['idcategoria']."' AND ".$isApp." ORDER BY productos.orden;";
*/

$sql="SELECT id, nombre,impuesto, imagen, precio, precio_web, precio_app, imagen_app1, modifier_category_id AS modi_cat, modifier_group_id as modi_gru, modificadores FROM productos WHERE categoria='".$array['idcategoria']."' AND ".$isApp." AND tienda='".$array['tienda']."' ORDER BY productos.orden;";



//die ($sql);

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;
    $texto="";
    $tot=$result->num_rows;
    $texto.='<div class="grid grid-cols-2 medium-grid-cols-3 grid-gap">';
    
    $columna=0;
    
    while ($grupo = $result->fetch_object()) {

        $id[]=$grupo->id;
        $nombre[]=$grupo->nombre;
        $precio_web[]=$grupo->precio_web;
        $precio_app[]=$grupo->precio_app;
        
        //$elgrupo=$grupo->elgrupo;
        $modifi[]=$grupo->modificadores;

        $desde="";
		if ($grupo->modi_cat!='' || $grupo->modi_gru!=''){
            
			
				$desde="desde";
			
        }
        if ($grupo->modificadores==1){
				$desde="";
			}
        if ($grupo->imagen!=''){
            $imagen[]=IMGREVO.$grupo->imagen;
        }
        else {
            $imagen[]="no-imagen.jpg";
        }
        $imagen_app1[]=IMGAPP.$grupo->imagen_app1;
        //$imagen_app2[]=IMGAPP.$grupo->imagen_app2;
        //$imagen_app3[]=IMGAPP.$grupo->imagen_app3;
        //$iva[]=$grupo->impuesto;
        $modifier_category_id[]= $grupo->modi_cat;
        $modifier_group_id[]= $grupo->modi_gru;
        $modificadores[]=[];
        
        if($columna==0){
            //$texto.='<div class="grid grid-cols-2 medium-grid-cols-3 grid-gap">';  
        }
        $img_a_usar='';
        
        if ($grupo->imagen_app1==""){
            if ($grupo->imagen!=""){
                $img_a_usar=IMGREVO.$grupo->imagen;
            }
            else {
                $img_a_usar=IMGREVO.'no-imagen.jpg';
            }
        }
        else {
            $img_a_usar=IMGAPP.$grupo->imagen_app1;
        }
        $precio=0;
        if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
            $precio=$grupo->precio_web;
        }
        else {
            $precio=$grupo->precio_app;
        }
        
        //$precio=number_format($precio, 2, ",", ".");
        $decimales = explode(".",$precio);
        $decimales[1]=substr($decimales[1].'00',0,2);
       
        $texto.='<div class="text-align-center">';
        $texto.='<div style="position: relative;display: inline-block;margin-bottom: 10px;" onclick="muestraelproducto(\''.
            $grupo->id.'\',\''.$grupo->nombre.'\',\''.$array['idgrupo'].'\',\''.$array['nombregrupo'].'\',\''.$array['idcategoria'].'\',\''.$array['nombrecategoria'].'\',\''.$grupo->modi_cat.'\',\''.$grupo->impuesto.'\');"><img src="'.$img_a_usar.'" width="100%" height="auto" class="imagen imagen-producto-lista"><div style="color:'.$array['colorprimario'].';font-size:16px;font-weight: bold;margin-top: -5px;">'.$grupo->nombre.'</div><div style="font-size:18px;font-weight: bold;"><span style="font-size:14px">'.$desde."</span> ".$decimales[0].'<span style="font-size:16px">,'.$decimales[1].'</span> €</div></div>';                  
        
        $texto.='</div>';
        

        
    }
 
    $texto.='</div>'; 
    $checking=true;
    
   
    $sql="SELECT count(*) AS cantidad FROM categorias WHERE grupo=".$array['idgrupo']." AND tienda='".$array['tienda']."';";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();


    if ($result->num_rows>0) {
        $categorias = $result->fetch_object();
        $totCategorias=$categorias->cantidad;
    }
    
    
}

$database->freeResults(); 
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio_web"=>$precio_web,"precio_app"=>$precio_app,"imagen"=>$imagen,"imagen_app1"=>$imagen_app1,"modifier_category_id"=>$modifier_category_id, "modifier_group_id"=>$modifier_group_id,"modifi"=>$modifi,"totCategorias"=>$totCategorias,"texto"=>$texto);

ob_end_clean();

echo json_encode($json); 


/*
$file = fopen("productos.txt", "w");
//fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "categoria: ". $array['idcategoria'] . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/
?>
