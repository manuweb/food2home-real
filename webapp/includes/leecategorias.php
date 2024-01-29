<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
/*
 *
 * Archivo: leecategorias.php
 *
 * Version: 1.0.1
 * Fecha  : 08/10/2023
 * Se usa en :tienda.js ->muestracategorias()
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
$array['isapp']=false;
$array['tienda']=1;
$array['grupo']=4;
$array['colorprimario']='#ff0000';
*/

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="activo_web";
}
else {
    $isApp="activo_app";
}
$checking=false;

$tex_prev='<div style="background:'.$array['colorprimario'].' ;width:126px;height:34px;border-radius: 0 34px 0 0;margin-left: -16px;" ></div>';

$tex_last='<div style="background:'.$array['colorprimario'].' ;width:126px;height:34px;border-radius: 0 0 34px 0;margin-left: -16px;margin-top: -1px;" ></div>';


$tex_ant='<div class="list media-list no-hairlines-between" style="margin-top:0;margin-bottom:0;margin-left: -16px;">'.'<ul style="margin-top: -5px;">';
$tex_pos=' </ul>'.'</div>';
$tex_div='<div style="background: '.$array['colorprimario'].';width:126px;height:30px;margin-left: -16px;margin-top: -1px;" ></div>';


$sql="select g.id, g.nombre, g.imagen, g.imagen_app, c.total FROM categorias g LEFT JOIN (SELECT categoria, COUNT(*) as 'total' FROM productos  WHERE ".$isApp."=1 AND tienda=".$array['tienda']." GROUP BY categoria) c on g.id = c.categoria WHERE g.grupo='".$array['grupo']."' AND g.".$isApp."=1 AND g.tienda=".$array['tienda']." ORDER BY g.orden;";





$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result->num_rows>0) {
    $checking=true;
    $texto='';
    $texto.='<div class="grid grid-cols-2 medium-grid-cols-3 grid-gap">';
    $x=0;
    $tot=$result->num_rows;
    while ($grupos = $result->fetch_object()) {
        $id[$x]=$grupos->id;
        $nombre[$x]=$grupos->nombre;
        
        //$texto.=$tex_ant.'<li style="background: linear-gradient(to right, '.$array['colorprimario'].' 126px, transparent 0);" >'.'<a href="javascript:muestraproductos(\''.$array['grupo'].'\',\''.$array['nombregrupo'].'\',\''.$grupos->id.'\',\''.$grupos->nombre.'\');" class="item-link item-content" style="margin-left: -16px;">';

        if ($grupos->imagen!=''){
            $imagen[$x]=IMGREVO.$grupos->imagen;
        }
        else {
            $imagen[$x]="";
            
        }
        $imagen_app[$x]=$grupos->imagen_app;
        
        if ($grupos->imagen_app==""){
            if ($grupos->imagen!=""){
                $img_a_usar=IMGREVO.$grupos->imagen;
                //$texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGREVO.$grupos->imagen.'" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
            }
            else {
                $img_a_usar=IMGREVO.'no-imagen.jpg';
                //$texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGREVO.'no-imagen.jpg" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
            }
            
        }
        else {
            $img_a_usar=IMGAPP.$grupos->imagen_app;
            //$texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGAPP.$grupos->imagen_app.'" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
        }
        
        $total[$x]=$grupos->total;
        
        //$texto.='<div class="item-inner" style="background-color: white;border-radius: 44px 15px 15px 44px;margin-left: -34px;margin-right: 20px;padding-left: 70px;padding-top: 25px;box-shadow: 0 0 6px 1px lightgrey;margin-top: 8px;margin-bottom: 8px;">'.' <div class="item-title-row lista-menus">'.'<div class="item-title" style="font-size:22px;top:0;">'.ucfirst(strtolower($grupos->nombre)).'</div>'.'</div>'.'<div class="item-subtitle" style="color:lightgray;top:-5px;">('.$grupos->total.' productos)</div>'.'</div>'.'</a>'.' </li>'.$tex_pos;
        if ($x<($tot-1)){
            //$texto.=$tex_div;
        }
        
        $texto.='<div class="text-align-center">';
        $texto.='<div style="position: relative;display: inline-block;margin-bottom: 10px;" onclick="javascript:muestraproductos(\''.$array['grupo'].'\',\''.$array['nombregrupo'].'\',\''.$grupos->id.'\',\''.$grupos->nombre.'\');"><img src="'.$img_a_usar.'" width="100%" height="auto" class="imagen imagen-producto-lista"><div class="item-title" style="font-size:22px;text-align:center;">'.ucfirst(strtolower($grupos->nombre)).'</div>'.'<div class="item-subtitle" style="color:lightgray;top:-5px;text-align:center;">('.$grupos->total.' productos)</div>'.'</div>';                  
        
        $texto.='</div>';
        $x++;
    }
   $texto.='</div>';
    //$texto=$tex_prev.$texto.$tex_last;
}

$database->freeResults(); 
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"imagen"=>$imagen,"total"=>$total,"imagen_app"=>$imagen_app,"texto"=>$texto);


ob_end_clean();

echo json_encode($json); 


/*
$file = fopen("categorias.txt", "w");
//fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
*/


?>
