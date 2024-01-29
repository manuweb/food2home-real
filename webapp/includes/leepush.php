<?php
/*
 *
 * Archivo: leepush.php
 *
 * Version: 1.0.0
 * Fecha  : 28/11/2022
 * Se usa en :masdatos.js ->buscapush()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;
$pagina=$array['pagina'];


$checking=false;
$tamPagina=5;

if (isset($array['id'])){
    $sql="SELECT id, titulo, body,imagen, fecha, tipo FROM push WHERE id='".$array['id']."';";  
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result && $result->num_rows != 0) {
        $checking=true;
        $push = $result->fetch_object();
        $id=$push->id;
        $titulo=$push->titulo;
        $body=$push->body;
        $imagen=$push->imagen;
        $fecha=substr($push->fecha,0,10);
        $hora=substr($push->fecha,11,5);
        $tipo=$push->tipo;
        $total=1;
        //0123456789012345678
        //2022-03-02 09:00:00
    }
        
}
else {
    
    $sql="SELECT count(*) AS cantidad FROM push WHERE cliente=0 OR cliente=".$array['cliente'].";";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();  
    
    $total=$result->num_rows;
    
    $numReg=$total;
    $limitInf=($pagina-1)*$tamPagina;
    
    $sql="SELECT id, titulo, body,imagen, fecha, tipo FROM push WHERE cliente=0 OR cliente=".$array['cliente']." ORDER BY fecha DESC LIMIT ".$limitInf.",".$tamPagina.";";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    

    if ($result && $result->num_rows != 0) {
        
        $checking=true;
        
        while ($push = $result->fetch_object()) {
        //for ($n=0;$n<count($push);$n++){
            $id[]=$push->id;
            $titulo[]=$push->titulo;
            $body[]=$push->body;
            $imagen[]=$push->imagen;
            $fecha[]=substr($push->fecha,0,10);
            $hora[]=substr($push->fecha,11,5);
            $tipo[]=$push->tipo;
            
            //0123456789012345678
            //2022-03-02 09:00:00
        }
    }    

    
}

$database->freeResults();


$json=array("valid"=>$checking, "id"=>$id, "titulo"=>$titulo, "body"=>$body, "imagen"=>$imagen, "fecha"=>$fecha, "hora"=>$hora, "tipo"=>$tipo, "total"=>$total);

echo json_encode($json); 

/*
$file = fopen("leepush.txt", "w");


fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "JSON: ". $sql . PHP_EOL); 
fwrite($file, "pagina: ". $pagina . PHP_EOL); 

fclose($file);
*/

?>
