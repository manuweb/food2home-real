<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;
$destino = '../../webapp/img/revo/';
$syncimagen=$array['syncimagen'];

$sql="SELECT * FROM categorias WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {
    if ($syncimagen=='true'){
        $sql="UPDATE categorias SET nombre='".$array['nombre']."', grupo='".$array['grupo']."', orden='".$array['orden']."', imagen='".$array['imagen']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."' WHERE id='".$array['id']."';";   
        $temp='https://storage.googleapis.com/revo-cloud-bucket/xef/comomola/images/'.$array['imagen'];
            captureImage($temp, $destino.$array['imagen']);
    }
    else {
     $sql="UPDATE categorias SET nombre='".$array['nombre']."', grupo='".$array['grupo']."', orden='".$array['orden']."', impuesto='".$array['impuesto']."', activo='".$array['activo']."', modifier_category_id='".$array['modifier_category_id']."', modifier_group_id='".$array['modifier_group_id']."' WHERE id='".$array['id']."';";  
    }
}

else {
     if ($syncimagen=='true'){
        $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$array['id']."','".$array['nombre']."','".$array['grupo']."', '".$array['orden']."', '".$array['imagen']."', '".$array['impuesto']."', '".$array['activo']."','".$array['modifier_category_id']."', '".$array['modifier_group_id']."');";   
         $temp='https://storage.googleapis.com/revo-cloud-bucket/xef/comomola/images/'.$array['imagen'];
            captureImage($temp, $destino.$array['imagen']);
    }
    else {
     $sql="INSERT INTO categorias (id, nombre, grupo, orden, imagen, impuesto, activo, modifier_category_id, modifier_group_id) VALUES ('".$array['id']."','".$array['nombre']."','".$array['grupo']."', '".$array['orden']."', '', '".$array['impuesto']."', '".$array['activo']."','".$array['modifier_category_id']."', '".$array['modifier_group_id']."');"; 
    }   
    
}

$database->setQuery($sql);
$result = $database->execute();


if ($result) {
   $checking=true; 
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 

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
