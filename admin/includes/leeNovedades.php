<?php
header('Access-Control-Allow-Origin: *');


$checking=true;
$txt='';
$ruta_archivo = '../changelog.txt';
$archivo = fopen($ruta_archivo, 'r');

while(!feof($archivo)) {
        $txt.= fread($archivo, filesize($ruta_archivo));
}

fclose($archivo);
$json=array("valid"=>$checking,"txt"=>$txt);

ob_end_clean();
echo json_encode($json);    



?>
