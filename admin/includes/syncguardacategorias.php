<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);




$checking=false;
$destino = '../../webapp/img/revo/';
$syncimagen=$array['syncimagen'];
$syncimagen_png=$array['syncimagen_png'];

$destino = '../../webapp/img/revo/';

$url_img_revo='https://storage.googleapis.com/revo-cloud-bucket/xef/';

$revo_img=URLREVO;
if (strpos($revo_img, 'integrations') !== false) {
    $url_img_revo='https://integrations.revoxef.works/storage/';
  
}

$temp=$url_img_revo.USUARIOREVOMASTER.'/images/'.$array['imagen'];

$sql="SELECT * FROM categorias WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$existe='UPDATE';
// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    if ($syncimagen=='true'){
        $sql="UPDATE categorias SET nombre='".$array['nombre']."', grupo='".$array['grupo']."', orden='".$array['orden']."', imagen='".$array['imagen']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."' WHERE id='".$array['id']."';";   
        
        //$temp='https://storage.googleapis.com/revo-cloud-bucket/xef/comomola/images/'.$array['imagen'];
        if ($array['imagen']!=''){
             $sep=explode('.',$array['imagen']);
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
     $sql="UPDATE categorias SET nombre='".$array['nombre']."', grupo='".$array['grupo']."', orden='".$array['orden']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."' WHERE id='".$array['id']."';";  
    }
}

else {
    $existe='NEW';
     if ($syncimagen=='true'){
        $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$array['id']."','".$array['nombre']."','".$array['grupo']."', '".$array['orden']."', '".$array['imagen']."', '".$array['impuesto']."', '".$array['activo']."','".$array['modifier_category_id']."', '".$array['modifier_group_id']."');";   
         
         if ($array['imagen']!=''){
            $sep=explode('.',$array['imagen']);
            if ($syncimagen_png=='true'){
                GuardaImagenWebp($temp, $destino,$sep[0],80) ;
                
             }
             else {
                GuardaImagenOrigin($temp, $destino,$sep[0],80);
                
             }
         }
    }
    else {
        $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$array['id']."','".$array['nombre']."','".$array['grupo']."', '".$array['orden']."', '', '".$array['impuesto']."', '".$array['activo']."','".$array['modifier_category_id']."', '".$array['modifier_group_id']."');"; 
    }   
    
}

$database->setQuery($sql);
$result = $database->execute();

$file = fopen("synccategorias.txt", "a+");
fwrite($file,  PHP_EOL); 

fwrite($file, "Categoría: ".$array['nombre']. ' ('.$array['id'].')'); 

if ($result) {
   $checking=true; 
    fwrite($file, " - ".$existe." - OK".  PHP_EOL); 
}
else {
    fwrite($file, " - ".$existe." - KO".  PHP_EOL);
}
fclose($file);


$database->freeResults();

$json=array("valid"=>$checking);
ob_end_clean();

echo json_encode($json); 



function GuardaImagenWebp($origen, $destino,$nombre,$calidad) {
    $ext=get_image_type($origen);

    switch ($ext) {
        case "png":
            $img = imagecreatefrompng($tempArchivo);
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

function GuardaImagenOrigin($origen, $destino,$nombre,$calidad) {
    //echo $origen."<br>";
    $calidad=$calidad/10;
    $ext=get_image_type($origen);
    //echo $ext."<br>";
    //echo $calidad."<br>";

    switch ($ext) {
        
        case "png":
            $img = imagecreatefrompng($origen);
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

/*
$file = fopen($array['id'].".txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
fwrite($file, "nombre: ". $array['nombre'] . PHP_EOL); 
fwrite($file, "mod_gru: ". $array['modifier_group_id'] . PHP_EOL); 
fwrite($file, "mod_cat: ". $array['modifier_category_id'] . PHP_EOL); 
fclose($file);
*/
?>
