<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../conexion.php";
include "../MySQL/DataBase.class.php";
include "../config.php";
//*************************************************************************************************
//
//  leeinicio.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde apps.js 
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="web='1'";
}
else {
    $isApp="APP='1'";;
}

$sql="SELECT id, tipo, texto FROM inicio  WHERE ".$isApp." ORDER BY orden;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    $num_results = $result->num_rows;
    
    if ($num_results > 0) {
        while ($inicio = $result->fetch_object()) {
            $id[]=$inicio->id;
            $tipo[]=$inicio->tipo;
            $texto[]=$inicio->texto;
         }
    }	
    else {
        $id=[];
    }
}
$database->freeResults();
$json=array("valid"=>$checking,"id"=>$id,"tipo"=>$tipo,"texto"=>$texto);

ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("texto_inicio.txt", "w");
fwrite($file, "archivo: leeinicio.php".  PHP_EOL);
fwrite($file, "sql: ". $sql. PHP_EOL);
fwrite($file, "json: ". json_encode($json) . PHP_EOL);
fwrite($file, "isApp: ". $array['isapp'] . PHP_EOL);

fclose($file);
*/

?>
