<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
/*
 *
 * Archivo: leegrupo.php
 *
 * Version: 1.0.1
 * Fecha  : 08/10/2023
 * Se usa en :tienda.js ->muestragrupos()
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
// ONLY_FULL_GROUP_BY, sql_mode


$array = json_decode(json_encode($_POST), true);


$checking='false';

//$array['isapp']=false;
//$array['tienda']=0;

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="activo_web";
}
else {
    $isApp="activo_app";
}
$sql="SELECT estilo_app FROM estilo WHERE id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $row = $database->loadObject(); 
    $estilo_app = $row->estilo_app;
    $checking=true;
}
$database->freeResults();


$sql1="SELECT grupos.id,COUNT(productos.id) AS cantidad FROM grupos LEFT JOIN categorias ON categorias.grupo=grupos.id LEFT JOIN productos ON productos.categoria=categorias.id where grupos.".$isApp."=1 AND categorias.".$isApp."=1 AND productos.".$isApp."=1 AND grupos.tienda=".$array['tienda']." AND categorias.tienda=".$array['tienda']." AND productos.tienda=".$array['tienda']." GROUP BY grupos.id ORDER BY grupos.orden;";


$database = DataBase::getInstance();
$database->setQuery($sql1);
$result = $database->execute();



if ($result->num_rows>0) {

    $checking=true;
    $x=0;
    while ($grupo = $result->fetch_object()) {
        $elementos[$x]=$grupo->cantidad;
   
        $x++;
    }
}





$sql="SELECT id, nombre, imagen,imagen_app FROM grupos WHERE grupos.".$isApp."='1' AND tienda=".$array['tienda']." ORDER BY orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$tex_prev='<div style="background:'.$array['colorprimario'].' ;background-repeat: no-repeat;width:126px;height:34px;border-radius: 0 34px 0 0;margin-left: -16px;" ></div>';

$tex_last='<div style="background: '.$array['colorprimario'].';background-repeat: no-repeat;width:126px;height:34px;border-radius: 0 0 34px 0;margin-left: -16px;margin-top: -1px;" ></div>';
$tex_ant='<div class="list media-list no-hairlines-between" style="margin-top:0;margin-bottom:0;margin-left: -16px;">'.'<ul style="margin-top: -5px;">';
$tex_pos=' </ul>'.'</div>';
$tex_div='<div style="background: '.$array['colorprimario'].';width:126px;height:30px;margin-left: -16px;margin-top: -1px;" ></div>';


if ($result->num_rows>0) {
    $checking=true;
    $texto='';
    $x=0;
    $tot=$result->num_rows;
    while ($grupos = $result->fetch_object()) {
        if ($estilo_app==0) {
            $texto.=$tex_ant.'<li style="background: linear-gradient(to right, '.$array['colorprimario'].' 126px, transparent 0);" ><a href="javascript:muestracategorias(\''.$grupos->id.'\',\''.$grupos->nombre.'\');" class="item-link item-content" style="margin-left: -16px;">';
        }

        $id[]=$grupos->id;
        $nombre[]=$grupos->nombre;
        
        if ($grupos->imagen!=''){
            $imagen[]=IMGREVO.$grupos->imagen;
        }
        else {
            $imagen[]="no-imagen.jpg";
            
        }
        if ($grupos->imagen_app!=''){
            $imagen_app[]=$grupos->imagen_app;
        }
        else {
            
            if ($grupos->imagen==''){
                $imagen_app[]="no-imagen.jpg";
            }
                
        }
        
        
        if ($grupos->imagen_app==""){
            if ($estilo_app==0) {
                if ($grupos->imagen!=""){

                    $texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGREVO.$grupos->imagen.'" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
                }
                else {
                    $texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGREVO.'no-imagen.jpg" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
                }
            }
        }
        else {
            if ($estilo_app==0) {
                $texto.='<div class="item-media" style="z-index:+1;"><img src="'.IMGAPP.$grupos->imagen_app.'" width="88" height="88" style="border-radius:44px;margin-left: 16px;" /></div>';
            }
        }
        if ($estilo_app==0) {
            $texto.='<div class="item-inner" style="background-color: white;border-radius: 44px 15px 15px 44px;margin-left: -34px;margin-right: 20px;padding-left: 70px;padding-top: 25px;box-shadow: 0 0 6px 1px lightgrey;margin-top: 8px;margin-bottom: 8px;">'.' <div class="item-title-row lista-menus">'.'<div class="item-title" style="font-size:22px;">'.ucfirst(strtolower($grupos->nombre)) .'</div>'.'</div>'.'<div class="item-subtitle" style="color:lightgray;top:-5px;">('.$elementos[$x].' productos)</div>'.'</div>'.'</a></li>'.$tex_pos;
        }
        else {
            $texto.='<div class="grupo-lista" onclick="javascript:muestracategorias(\''.$grupos->id.'\',\''.$grupos->nombre.'\');" style="margin:-15px;">'.' <div class="grupo-imagen" style="text-align:center;">'.'<img src="'.IMGAPP.$grupos->imagen_app.'" width="95%" height="auto" style="border-radius:30px;" />'.'</div>'.'<div class="item-title" style="font-size:22px;text-align:center;">'.ucfirst(strtolower($grupos->nombre)) .'</div>'.'<div class="item-subtitle" style="color:lightgray;top:-5px;text-align:center;">('.$elementos[$x].' productos)</div>'.'</div><br><br>';
        }
        
        
        if ($x<($tot-1)){
            if ($estilo_app==0) {
                $texto.=$tex_div;
            }
        }
        $x++;
    }
    if ($estilo_app==0) {
        $texto=$tex_prev.$texto.$tex_last;
    }
}	


$database->freeResults(); 



$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app"=>$imagen_app,"texto"=>$texto,"cantidades"=>$elementos);


ob_end_clean();

echo json_encode($json); 


/*
$file = fopen("grupos.txt", "w");
fwrite($file, "isApp ". $array['isapp'] . PHP_EOL);
fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
