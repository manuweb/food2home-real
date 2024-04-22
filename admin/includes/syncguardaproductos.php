<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  synguardaproductos.php
//
//  guarda los productos sincronizarlos  
//
//*************************************************************************************************
$array = json_decode(json_encode($_POST), true);

$checking=false;


/*
$array['id']=632;
$array['categoria']=84;
$array['nombre']='Nueces Pecanas';
$array['esMenu']=0;
$array['imagen']='fhYgyFKtzY.png';
$array['impuesto']='';
$array['orden']=5;
$array['activo']=1;
$array['precio']=4.90;
$array['info']='';
$array['alergias']='';
$array['modifier_group_id']='';
$array['modifier_category_id']='';
$array['syncimagen']=true;
$array['syncimagen_png']=false;
$array['syncprecio']=true;
*/

$syncimagen=$array['syncimagen'];
$syncimagen_png=$array['syncimagen_png'];
$syncprecio=$array['syncprecio'];

$sql="SELECT * FROM productos WHERE id='".$array['id']."';";

$destino = '../../webapp/img/revo/';

$url_img_revo='https://storage.googleapis.com/revo-cloud-bucket/xef/';

$revo_img=URLREVO;
if (strpos($revo_img, 'integrations') !== false) {
    $url_img_revo='https://integrations.revoxef.works/storage/';
  
}
$temp=$url_img_revo.USUARIOREVOMASTER.'/images/'.$array['imagen'];
//
$array['alergias']=str_replace(";",",",$array['alergias']);



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();




// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    $existe='UPDATE';
    $imagen='';
    $precio='';
    
    if ($syncimagen=='true'){
        if ($array['imagen']!=''){
            $sep=explode('.',$array['imagen']);
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
                    $imagen=", imagen='".$sep[0].'.'.$sep[1]."'";
                     //captureImage($temp, $destino.$array['imagen']);
                //}
            }
        }
        
    }

    if ($syncprecio=='true'){
        $precio=", precio='".$array['precio']."', precio_web='".$array['precio']."', precio_app='".$array['precio']."'";
    }
        //esMenu si es menu=1
    $sql="UPDATE productos SET nombre='".$array['nombre']."', categoria='".$array['categoria']."', orden='".$array['orden']."'".$imagen.", impuesto='".$array['impuesto']."', activo='".$array['activo']."'".$precio.", info='".$array['info']."', alergias='".$array['alergias']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."', esMenu='".$array['esMenu']."' WHERE id='".$array['id']."';";  
       
        
}

else {
    $existe='NEW';
    $imagen=''; 
    $txt_imagen="";
    $precio='';
    $txt_precio='';
    if ($syncimagen=='true'){
        
        if ($array['imagen']!=''){
           $sep=explode('.',$array['imagen']);
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
                    $imagen=", imagen='".$sep[0].'.'.$sep[1]."'";
                     //captureImage($temp, $destino.$array['imagen']);
                //}
            } 
            $txt_imagen=", imagen";
            
            
            
        }
        
        
    }
    //if ($syncprecio=='true'){
        $precio="', '".$array['precio']."', '".$array['precio'];
        $txt_precio=", precio_web, precio_app";
    //}
    
    $sql="INSERT INTO productos (id, nombre, categoria, orden".$txt_imagen.", impuesto, activo, precio".$txt_precio.", info,alergias, modifier_category_id, modifier_group_id, esMenu) VALUES ('".$array['id']."','".$array['nombre']."','".$array['categoria']."', '".$array['orden']."'".$imagen.", '".$array['impuesto']."', '".$array['activo']."', '".$array['precio'].$precio."' , '".$array['info']."', '".$array['alergias']."', '".$array['modifier_category_id']."', '".$array['modifier_group_id']."', '".$array['esMenu']."');"; 
  
}



$database->setQuery($sql);
$result = $database->execute();


if ($result) {
   $checking=true; 
}
$database->freeResults();



$json=array("valid"=>$checking);
ob_end_clean();
echo json_encode($json); 




$file = fopen("syncproductos.txt", "a+");
fwrite($file,  PHP_EOL); 

fwrite($file, "Producto: ".$array['nombre']. ' ('.$array['id'].')'); 

if ($result) {
   $checking=true; 
    fwrite($file, " - ".$existe." - OK".  PHP_EOL); 
}
else {
    fwrite($file, " - ".$existe." - KO".  PHP_EOL);
}
fclose($file);
/*
$file = fopen('Producto-'.$array['id'].".txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL); 

fclose($file);

*/

function GuardaImagenWebp($origen, $destino,$nombre,$calidad) {
    $ext=get_image_type($origen);

    switch ($ext) {
        case "png":
            $img = imagecreatefrompng($origen);
            break;
        case "bmp":
            $img = imagecreatefrombmp($origen);
            break;
        default:   
            $img = imagecreatefromjpeg($origen);
            break;
    }
    imagewebp($img, $destino.$nombre.'.webp',$calidad);
    imagedestroy($img);
    
}

function GuardaImagenOrigin($origen, $destino,$nombre,$calidad) {
    //echo $origen."<br>";
    $calidad=$calidad/10;
    $ext=get_image_type($origen);
    //echo $ext."<br>";
    //echo $calidad."<br>";

    switch ($ext) {
        
        case "png":
            $img = imagecreatefrompng($origen);
            imagesavealpha($img, true);
            imagepng($img, $destino.$nombre.'.'.$ext,$calidad);
            break;
        case "bmp":
            $img = imagecreatefrombmp($origen);
            imagebmp($img, $destino.$nombre.'.'.$ext,$calidad);
            break;
        default:
            $img = imagecreatefromjpeg($origen);
            imagejpeg($img, $destino.$nombre.'.'.$ext,$calidad);
            break;
    }
    //imagewebp($img, $destino.$nombre.'.webp',$calidad);
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


function captureImage($origin, $destination) { 
    $mi_curl = curl_init ($origin); 
    $fp_destination = fopen ($destination, "w"); 
    curl_setopt ($mi_curl, CURLOPT_FILE, $fp_destination); 
    curl_setopt ($mi_curl, CURLOPT_HEADER, 0); 
    curl_exec ($mi_curl); 
    curl_close ($mi_curl); 
    fclose ($fp_destination); 
}





?>
