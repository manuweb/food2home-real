<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
//*************************************************************************************************
//
//  synguardaproductos.php
//
//  guarda los productos sincronizarlos  
//
//*************************************************************************************************
 
ini_set('max_execution_time', 6000);

$sql="SELECT id,imagen FROM categorias;";





$destino = '../../webapp/img/revo/catego/';

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result->num_rows>0) {

    for ($n=0;$n<count($grupo);$n++){
    
        $imagen=$grupo[$n]->imagen;
        
        //echo "imagen: ".$imagen."<br>";
     
        if ($imagen!=''){
            
            $temp='https://storage.googleapis.com/revo-cloud-bucket/xef/mamalupemaster/images/'.$imagen;
            captureImage($temp, $destino.$imagen);
  /*
            $url = $temp;
            $dir = $destino;

                $archivoInicial = fopen($url, "r");
            $archivoFinal   = fopen($dir . basename($url), "w");

            while(!feof($url)) 
                fwrite($archivoFinal, fread($archivoInicial, 1), 1);

            fclose($archivoFinal);
            fclose($archivoInicial);
    */
            echo "Se ha copiado correctamente la imagen: ".$imagen."<br>";
            
        }
    }

    
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
fwrite($file, "activo: ". $array['activo'] . PHP_EOL); 
fclose($file);
*/




?>
