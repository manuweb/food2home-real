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




$array = json_decode(json_encode($_POST), true);



$checking=false;

/*
$array['tienda']=0;
$array['idgrupo']=4;
$array['nombregrupo']='BEBIDAS';
$array['idcategoria']=89;
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

//$sql="SELECT id, nombre,impuesto, imagen, precio, precio_web, precio_app, imagen_app1, modifier_category_id AS modi_cat, modifier_group_id as modi_gru, modificadores, esMenu FROM productos WHERE categoria='".$array['idcategoria']."' AND ".$isApp." AND tienda='".$array['tienda']."' ORDER BY productos.orden;";

$sql="SELECT productos.id, productos.nombre, productos.imagen, productos.imagen_app1, productos.esMenu, productos.precio, productos.precio_web,productos.modifier_category_id AS modi_cat, productos.modifier_group_id AS modi_gru,productos.modificadores, categorias.modifier_category_id as modcatcat, categorias.modifier_group_id as modgrucat FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria  WHERE categorias.id='".$array['idcategoria']."' AND ".$isApp." AND productos.tienda='".$array['tienda']."' ORDER BY productos.orden;";

echo $sql;

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
        
        
        $modcatcat= $grupo->modcatcat;
        if ($modcatcat!="") {
            $modifier_category_id[]=$modcatcat;
            $mc=$modcatcat;
        }
        else {
            $modifier_category_id[]= $grupo->modi_cat;
            $mc=$grupo->modi_cat;
        }
        $modgrucat= $grupo->modgrucat;
        if ($modgrucat!="") {
            $modifier_group_id[]=$modgrucat;
            $mg=$modgrucat;
        }
        else {
            $modifier_group_id[]= $grupo->modi_gru;
            $mg=$grupo->modi_gru;
        }
        
        
		if ($mc!='' || $mg!=''){
            
			 $hay_precios=miramodifi($mc,$mg);
            if ($hay_precios){
				$desde="desde";
            }
			
        }
        
        if ($grupo->modificadores==1){
				$desde="";
        }
        
        if ($grupo->esMenu==1){
            $desde="desde";
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

function miramodifi($mc,$mg){
    $devolver=false;
    $database = DataBase::getInstance();
    if ($mc!=''){
        $sql="SELECT modificadores FROM modifierCategories WHERE id=".$mc;
        
        $database->setQuery($sql);
        $result = $database->execute();
        $mc_res = $result->fetch_object();
        $modif=$mc_res->modificadores;
        $sql="SELECT precio FROM modifiers WHERE id IN (".$modif.");";
        $database->setQuery($sql);
        $result = $database->execute();
        while ($modificadores = $result->fetch_object()) {
            if ($modificadores->precio>0){
               $devolver=true; 
            }
        }
        
    }
    if ($mg!=''){
        //modifierCategories_id

        $sql="SELECT modifierCategories_id FROM modifierGroups WHERE id=".$mg;

        $database->setQuery($sql);
        $result = $database->execute();

        while ($lismod = $result->fetch_object()) {
            $sql="SELECT modificadores,nombre FROM modifierCategories WHERE id IN (".$lismod->modifierCategories_id.");";
            
            $database->setQuery($sql);
            $result = $database->execute();
            while ($mc_res = $result->fetch_object()) {
                $sql1="SELECT id, precio FROM modifiers WHERE id IN (".$mc_res->modificadores.");";

                $database->setQuery($sql1);
                $result2 = $database->execute();
                while ($modificadores = $result2->fetch_object()) {

                    if ($modificadores->precio>0){
                       $devolver=true; 
                    }
                }  
            }
                
        }

    }
        
    $database->freeResults(); 
    return $devolver;
    
    
}

/*
$file = fopen("productos.txt", "w");
//fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "categoria: ". $array['idcategoria'] . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/
?>
