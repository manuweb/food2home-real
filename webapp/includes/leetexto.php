<?php
/*
 *
 * Archivo: leetexto.php
 *
 * Version: 1.0.0
 * Fecha  : 28/11/2022
 * 
 * lee un archivo txt y lo devuelve
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

$nombre_fichero='../docs/'.$array['archivo'];

if (file_exists($nombre_fichero)) {
    $archivo = fopen($nombre_fichero,'r');
    while ($linea = fgets($archivo)) {
        $aux[] = $linea;    
        $numlinea++;
    }
    fclose($archivo);
    $checking=true;
}



$json=array("valid"=>$checking,"lineas"=>$aux);

ob_end_clean();
echo json_encode($json); 

/*

$file = fopen("texto.txt", "w");
fwrite($file, "archivo: ". $nombre_fichero. PHP_EOL);
//fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
