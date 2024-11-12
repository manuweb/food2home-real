<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$sql="SELECT integracion.master FROM integracion WHERE integracion.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result_empresa = $database->execute();
$integra= $result_empresa->fetch_object();
$master=$integra->master;
//$database->freeResults();



$array = json_decode(json_encode($_POST), true);



$checking=false;

$datosRevo=$array['datosRevo'];

//file_put_contents('zzzz.txt', print_r($array, true),FILE_APPEND);

//$database = DataBase::getInstance();

$syncimagen=$array['syncimagen'];
$syncimagen_png=$array['syncimagen_png'];
$estados=$array['estados'];
$destino = '../../webapp/img/revo/';


if ($array['tipo']=='grupos'){
    $sql="SELECT * FROM grupos WHERE id='".$datosRevo['id']."';";
    
    
    
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    if ($result->num_rows>0) {
        
        if ($syncimagen=='true'){
            $txt_estado='';
            if ($estados=='true'){
                $txt_estado=", activo_web='".$datosRevo['activo']."'";
            }
            $sql="UPDATE grupos SET nombre='".quitaComillas($datosRevo['nombre'])."', orden='".$datosRevo['orden']."', imagen='".$datosRevo['imagen']."', impuesto='".$datosRevo['impuesto']."', activo='".$datosRevo['activo']."'".$txt_estado." WHERE id='".$datosRevo['id']."';";   
        }
        else {
            $txt_estado='';
            if ($estados=='true'){
                $txt_estado=", activo_web='".$datosRevo['activo']."'";
            }
            $sql="UPDATE grupos SET nombre='".quitaComillas($datosRevo['nombre'])."', orden='".$datosRevo['orden']."', impuesto='".$datosRevo['impuesto']."', activo='".$datosRevo['activo']."'".$txt_estado." WHERE id='".$datosRevo['id']."';";  
        }
         

    }

    else {
        $existe='NEW';
         if ($syncimagen=='true'){
            
            $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,,activo_web,imagen_app) VALUES ('".$datosRevo['id']."','".quitaComillas($datosRevo['nombre'])."', '".$datosRevo['orden']."', '".$datosRevo['imagen']."', '".$datosRevo['impuesto']."', '".$datosRevo['activo']."','".$datosRevo['activo']."','');";   
        }
        else {
            
            $sql="INSERT INTO grupos (id, nombre, orden, imagen, impuesto, activo,activo_web,imagen_app) VALUES ('".$datosRevo['id']."','".quitaComillas($datosRevo['nombre'])."', '".$datosRevo['orden']."', '', '".$datosRevo['impuesto']."', '".$datosRevo['activo']."','".$datosRevo['activo']."','');";   
        }   
        
    }  
    $file = fopen('zz-sync.txt', "a+");
        fwrite($file,  $sql . PHP_EOL);
        fclose($file);
    

}

if ($array['tipo']=='categorias'){
    $temp='https://storage.googleapis.com/revo-cloud-bucket/xef/'.$master.'/images/'.$datosRevo['imagen'];
    
    
    $sql="SELECT * FROM categorias WHERE id='".$datosRevo['id']."';";
    
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    if ($result->num_rows>0) {
        $txt_estado='';
        if ($estados=='true'){
            $txt_estado=", activo_web='".$datosRevo['activo']."'";
        }
        if ($syncimagen=='true'){
            $nombre_img=$datosRevo['imagen'];
            if ($nombre_img!=''){
                $sep=explode('.',$nombre_img);

                if ($syncimagen_png=='true'){
                    $nombre_img=$sep[0].'.webp';
                }
            }
            
            
            $sql="UPDATE categorias SET nombre='".quitaComillas($datosRevo['nombre'])."', grupo='".$datosRevo['grupo']."', orden='".$datosRevo['orden']."', imagen='".$nombre_img."', impuesto='".$datosRevo['impuesto']."', activo='".$datosRevo['activo']."'".$txt_estado.", modifier_category_id='".$datosRevo['modifier_category_id']."', modifier_group_id='".$datosRevo['modifier_group_id']."' WHERE id='".$datosRevo['id']."';";   

            if ($datosRevo['imagen']!=''){
                 
                if ($syncimagen_png=='true'){
                    GuardaImagenWebp($temp, $destino,$sep[0],80) ;

                 }
                 else {
                    GuardaImagenOrigin($temp, $destino,$sep[0],80);

                 }
            }

                //captureImage($temp, $destino.$array['imagen']);
        }
        else {
            $sql="UPDATE categorias SET nombre='".quitaComillas($datosRevo['nombre'])."', grupo='".$datosRevo['grupo']."', orden='".$datosRevo['orden']."', impuesto='".$datosRevo['impuesto']."',activo='".$datosRevo['activo']."'".$txt_estado.", modifier_category_id='".$datosRevo['modifier_category_id']."', modifier_group_id='".$datosRevo['modifier_group_id']."' WHERE id='".$datosRevo['id']."';";  
        }
          
        
    }

    else {
        $existe='NEW';
        if ($syncimagen=='true'){
            $nombre_img=$datosRevo['imagen'];
            if ($nombre_img!=''){
                $sep=explode('.',$nombre_img);

                if ($syncimagen_png=='true'){
                    $nombre_img=$sep[0].'.webp';
                }
            }
            
            $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$array['id']."','".quitaComillas($datosRevo['nombre'])."','".$datosRevo['grupo']."', '".$datosRevo['orden']."', '".$nombre_img."', '".$datosRevo['impuesto']."', '".$datosRevo['activo']."','".$datosRevo['modifier_category_id']."', '".$datosRevo['modifier_group_id']."');";   

             if ($datosRevo['imagen']!=''){
                $sep=explode('.',$datosRevo['imagen']);
                if ($syncimagen_png=='true'){
                    GuardaImagenWebp($temp, $destino,$sep[0],80) ;

                 }
                 else {
                    GuardaImagenOrigin($temp, $destino,$sep[0],80);

                 }
             }
        }
        else {
            $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$datosRevo['id']."','".quitaComillas($datosRevo['nombre'])."','".$datosRevo['grupo']."', '".$datosRevo['orden']."', '', '".$datosRevo['impuesto']."', '".$datosRevo['activo']."','".$datosRevo['modifier_category_id']."', '".$datosRevo['modifier_group_id']."');"; 
        }   
        
        
    }   
    

}
  

if ($array['tipo']=='productos'){
    $sql="SELECT * FROM productos WHERE id='".$datosRevo['id']."';";

    
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    
    $temp='https://storage.googleapis.com/revo-cloud-bucket/xef/'.$master.'/images/'.$datosRevo['imagen'];
    $sep=explode('.',$datosRevo['imagen']);
    $destino = '../../webapp/img/revo/';
    
    //GuardaImagenOrigin($temp, $destino,$sep[0],80);

    $datosRevo['alergias']=str_replace(";",",",$datosRevo['alergias']);
    
    if ($result->num_rows>0) {
        $imagen='';
        $precio='';
        $syncimagen='true';
        $txt_estado='';
        if ($estados=='true'){
            $txt_estado=", activo_web='".$datosRevo['activo']."'";
        }
        if ($syncimagen=='true'){
            if ($datosRevo['imagen']!=''){
                $sep=explode('.',$datosRevo['imagen']);
                if ($syncimagen_png=='true'){
                    GuardaImagenWebp($temp, $destino,$sep[0],80);
                    //{
                        $imagen=", imagen='".$sep[0].'.webp'."'";
                         //captureImage($temp, $destino.$array['imagen']);
                    //}
                }
                else {
                    GuardaImagenOrigin($temp, $destino,$sep[0],80);
                    //{
                        $imagen=", imagen='".$sep[0].'.png'."'";
                         //captureImage($temp, $destino.$array['imagen']);
                    //}
                }
            }

        }

        if ($syncprecio=='true'){
            $precio=", precio='".$datosRevo['precio']."', precio_web='".$datosRevo['precio']."', precio_app='".$datosRevo['precio']."'";
        }
        
        $sql="UPDATE productos SET nombre='".quitaComillas($datosRevo['nombre'])."', categoria='".$datosRevo['categoria']."', orden='".$datosRevo['orden']."'".$imagen.", impuesto='".$datosRevo['impuesto']."', activo='".$datosRevo['activo']."'".$txt_estado.$precio.", info='".quitaComillas($datosRevo['info'])."', alergias='".$datosRevo['alergias']."', modifier_category_id='".$datosRevo['modifier_category_id']."', modifier_group_id='".$datosRevo['modifier_group_id']."', esMenu='".$datosRevo['esMenu']."' WHERE id='".$datosRevo['id']."';";  
        
        
        

    }

    else {
        $existe='NEW';
     
        $imagen=''; 
        $txt_imagen="";
        $precio='';
        $txt_precio='';
        if ($syncimagen=='true'){

            if ($datosRevo['imagen']!=''){
               $sep=explode('.',$datosRevo['imagen']);
                if ($syncimagen_png=='true'){
                    GuardaImagenWebp($temp, $destino,$sep[0],80);
                    //{
                        $imagen=", imagen='".$sep[0].'.webp'."'";
                         //captureImage($temp, $destino.$array['imagen']);
                    //}
                }
                else {
                    GuardaImagenOrigin($temp, $destino,$sep[0],80);
                    //{
                        $imagen=", imagen='".$sep[0].'.png'."'";
                         //captureImage($temp, $destino.$array['imagen']);
                    //}
                } 
                $txt_imagen=", imagen";



            }
            else {
                $txt_imagen=", imagen";
                $imagen=", imagen='".$datosRevo['imagen']."'";
            }


        }

        $sql="INSERT INTO productos (id, nombre, categoria, orden".$txt_imagen.", impuesto, activo, precio, precio_web, precio_app, info,alergias, modifier_category_id, modifier_group_id, esMenu) VALUES ('".$datosRevo['id']."','".quitaComillas($datosRevo['nombre'])."','".$datosRevo['categoria']."', '".$datosRevo['orden']."'".$imagen.", '".$datosRevo['impuesto']."', '".$datosRevo['activo']."', '".$datosRevo['precio']."' ,'".$datosRevo['precio']."' ,'".$datosRevo['precio']."' , '".quitaComillas($datosRevo['info'])."', '".$datosRevo['alergias']."', '".$datosRevo['modifier_category_id']."', '".$datosRevo['modifier_group_id']."', '".$datosRevo['esMenu']."');"; 
        
        $file = fopen('SQL-PROD.txt', "a+");
        fwrite($file,  $sql . PHP_EOL);
    }   
    
}


if ($array['tipo']=='modificadores'){
    $sql="SELECT * FROM modifiers WHERE id='".$datosRevo['id']."';";
    
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    if ($result->num_rows>0) {
        $sql="UPDATE modifiers SET nombre='".$datosRevo['nombre']."', activo='".$datosRevo['activo']."', precio='".$datosRevo['precio']."', category_id='".$datosRevo['category_id']."', autoseleccionado='".$datosRevo['autoSelected']."' WHERE id='".$datosRevo['id']."';";  

    }

    else {
        $existe='NEW';
        $sql="INSERT INTO modifiers (id, nombre, precio, activo, category_id, autoseleccionado) VALUES ('".$datosRevo['id']."','".$datosRevo['nombre']."', '".$datosRevo['precio']."', '".$datosRevo['activo']."', '".$datosRevo['category_id']."', '".$datosRevo['autoSelected']."');";  

    }
    

}

if ($array['tipo']=='catmod'){
    $sql2="SELECT id FROM modifiers WHERE category_id='".$datosRevo['id']."';";

    $database->setQuery($sql2);
    $resultMod = $database->execute();

    $lista=""; 
    
    if ($resultMod){
        while ($modifiers = $resultMod->fetch_object()) {
             $lista.=$modifiers->id.",";
        }




        $lista=trim($lista, ',');

    }

    $sql="SELECT * FROM modifierCategories WHERE id='".$datosRevo['id']."';";


    $database->setQuery($sql);
    $resultModCat = $database->execute();
    $existe='UPDATE';
    if ($resultModCat->num_rows>0){

        $sql="UPDATE modifierCategories SET nombre='".$datosRevo['nombre']."', activo='".$datosRevo['activo']."', opciones='".$datosRevo['opciones']."', forzoso='".$datosRevo['forzoso']."', 
        minimo='".$datosRevo['min']."', 
        maximo='".$datosRevo['max']."', modificadores='".$lista."'  WHERE id='".$datosRevo['id']."';";  

    }
    else {
        $existe='NEW';
        $sql="INSERT INTO modifierCategories (id, nombre, activo, opciones, forzoso, minimo, maximo, modificadores) VALUES ('".$datosRevo['id']."','".$datosRevo['nombre']."','".$datosRevo['activo']."','".$datosRevo['opciones']."','".$datosRevo['forzoso']."','".$datosRevo['min']."','".$datosRevo['max']."','".$lista."');";  

    }
}

if ($array['tipo']=='grumod'){
    $sql2="SELECT * FROM modifierGroups WHERE id='".$datosRevo['id']."';";
    $database->setQuery($sql2);
    $resultMod = $database->execute();
    $existe='UPDATE';
    if ($resultMod->num_rows>0) {
        $sql="UPDATE modifierGroups SET nombre='".$datosRevo['nombre']."',modifierCategories_id=''  WHERE id='".$datosRevo['id']."';";  

    }

    else {
        $existe='NEW';
        $sql="INSERT INTO modifierGroups (id, nombre) VALUES ('".$datosRevo['id']."','".$datosRevo['nombre']."');";  

    }
}

if ($array['tipo']=='pivmod'){
    $sql="UPDATE modifierGroups SET modifierCategories_id= CASE WHEN modifierCategories_id!='' THEN concat(modifierCategories_id,',".$array['categoria']."') ELSE '".$array['categoria']."' END where id=".$array['id'].";";
    $existe='UPDATE';
}

if ($array['tipo']=='catmod'){
    $fichero2="synccatmodificadoresSql.txt";
    $file2 = fopen($fichero2, "a+");
    fwrite($file2,  $sql . PHP_EOL);
    fclose($file2);
}

if ($array['tipo']=='menuMenuCategories'){
    $sql="SELECT * FROM MenuCategories WHERE id='".$datosRevo['id']."';";
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    if ($result->num_rows>0) {
        $sql="UPDATE MenuCategories SET nombre='".$datosRevo['nombre']."', orden='".$datosRevo['orden']."', eleMulti='".$datosRevo['eleMulti']."', min='".$datosRevo['min']."', max='".$datosRevo['max']."', producto='".$datosRevo['producto']."'  WHERE id='".$datosRevo['id']."';";  
    }
    else {
        $existe='NEW';
        $sql="INSERT INTO MenuCategories (id, nombre, orden, eleMulti, min, max, producto) VALUES ('".$datosRevo['id']."','".$datosRevo['nombre']."','".$datosRevo['orden']."','".$datosRevo['eleMulti']."','".$datosRevo['min']."','".$datosRevo['max']."','".$datosRevo['producto']."');";  
    }
}

if ($array['tipo']=='menuMenuItemCategoryPivots'){
    $sql="SELECT * FROM MenuItems WHERE id='".$datosRevo['id']."';";
    $database->setQuery($sql);
    $result = $database->execute();
    $existe='UPDATE';
    // Verificar si se obtuvieron resultados
    if ($result->num_rows>0) {
        $sql="UPDATE MenuItems SET activo='".$datosRevo['activo']."', orden='".$datosRevo['orden']."', precio='".$datosRevo['precio']."', producto='".$datosRevo['producto']."', category_id='".$datosRevo['category_id']."', modifier_group_id='".$datosRevo['modifier_group_id']."', addPrecioMod='".$datosRevo['addPrecioMod']."'  WHERE id='".$datosRevo['id']."';";  
    }
    else {
        $existe='NEW';
        $sql="INSERT INTO MenuItems (id, activo, orden, precio, producto, category_id, modifier_group_id,addPrecioMod) VALUES ('".$datosRevo['id']."','".$datosRevo['activo']."','".$datosRevo['orden']."','".$datosRevo['precio']."','".$datosRevo['producto']."','".$datosRevo['category_id']."','".$datosRevo['modifier_group_id']."','".$datosRevo['addPrecioMod']."');";  
    }
}



$database->setQuery($sql);
$result = $database->execute();

$fichero="synccategorias.txt";

if ($array['tipo']=='productos'){
    $fichero="syncproductos.txt";
}
if ($array['tipo']=='grupos'){
    $fichero="syncgrupos.txt";
}
if ($array['tipo']=='modificadores'){
    $fichero="syncmodificadores.txt";
}
if ($array['tipo']=='catmod'){
    $fichero="synccatmod.txt";
}  
if ($array['tipo']=='grumod'){
    $fichero="syncgrumod.txt";
}
if ($array['tipo']=='pivmod'){
    $fichero="syncpivmod.txt";
}

if ($array['tipo']=='menuMenuCategories'){
    $fichero="syncmenuMenuCategories.txt";
}
if ($array['tipo']=='menuMenuItemCategoryPivots'){
    $fichero="syncmenuMenuItemCategoryPivots.txt";
}

$file = fopen($fichero, "a+");
//fwrite($file,  $sql . PHP_EOL);

if (($array['tipo']!='pivmod' ) || ($array['tipo']!='menuMenuItemCategoryPivots')){
    fwrite($file, $datosRevo['nombre']. ' ('.$datosRevo['id'].')'. PHP_EOL);
}
else {
    fwrite($file, $sql . PHP_EOL);
}
//fwrite($file, "sql: ". $sql . PHP_EOL);
if (($array['tipo']!='pivmod' ) || ($array['tipo']!='menuMenuItemCategoryPivots')){
    if ($result) {
        
       $checking=true; 
        fwrite($file, " - ".$existe." - OK".  PHP_EOL); 
    }
    else {
        fwrite($file, " - ".$existe." - KO".  PHP_EOL);
    }
}
else {
    if ($result) {
       $checking=true; 
    }
}


fclose($file);




$database->freeResults();

$json=array("valid"=>$checking);
ob_end_clean();

echo json_encode($json); 



function GuardaImagenOrigin($origen, $destino,$nombre,$calidad) {
    //echo $origen."<br>";
    $calidad=$calidad/10;
    $ext=get_image_type($origen);
    //echo $ext."<br>";
    //echo $calidad."<br>";

    $img = imagecreatefrompng($origen);
    imagealphablending($img, true); // setting alpha blending on
        
    imagesavealpha($img, true); // save alphablending setting (important)  
    imagepng($img, $destino.$nombre.'.png',$calidad);
           
    
    imagedestroy($img);
    
}

function GuardaImagenWebp($origen, $destino,$nombre,$calidad) {
    $ext=get_image_type($origen);

    switch ($ext) {
        case "png":
            $img = imagecreatefrompng($tempArchivo);
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            
            break;
        case "bmp":
            $img = imagecreatefrombmp($tempArchivo);
            break;
        default:   
            $img = imagecreatefromjpeg($tempArchivo);
            break;
    }
    imagewebp($img, $destino.$nombre.'.webp',$calidad);
    imagedestroy($img);
    
}

function get_image_type($image_path){

        $extension  = array(IMAGETYPE_GIF => "gif",
        IMAGETYPE_JPEG => "jpeg",
        IMAGETYPE_PNG => "png",
        IMAGETYPE_SWF => "swf",
        IMAGETYPE_PSD => "psd",
        IMAGETYPE_BMP => "bmp",
        IMAGETYPE_TIFF_II => "tiff",
        IMAGETYPE_TIFF_MM => "tiff",
        IMAGETYPE_JPC => "jpc",
        IMAGETYPE_JP2 => "jp2",
        IMAGETYPE_JPX => "jpx",
        IMAGETYPE_JB2 => "jb2",
        IMAGETYPE_SWC => "swc",
        IMAGETYPE_IFF => "iff",
        IMAGETYPE_WBMP => "wbmp",
        IMAGETYPE_XBM => "xbm",
        IMAGETYPE_ICO => "ico");

        return $extension[exif_imagetype($image_path)];
}


function quitaComillas($nombre){
    $text=$nombre;
    if (strlen($nombre)>500){
        $text=substr($nombre,0,499);
    }
    $text = str_replace("'", "´", $text);
    if (str_contains($nombre, "'")) {
        $file = fopen('Reemplazo comillas.txt', "a+");
        fwrite($file, "Reemplazado: ". $nombre. " --> " .$text. PHP_EOL);
        fclose($file);
    }
    
    return $text;
}




?>
