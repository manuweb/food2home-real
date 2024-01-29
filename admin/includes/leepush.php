<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;


if (isset($array['id'])){
    
    $sql="SELECT id, titulo, body,imagen, fecha, tipo, ios_ok, ios_ko, android_ok, android_ko, web_ok, web_ko FROM push WHERE id='".$array['id']."';";

    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $checking=true;
        while ($push = $result->fetch_object()) {
    
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
    $database->freeResults();    
}
else {
    $tamano_pagina=15;
    $sql="SELECT count(*) AS cantidad FROM push;";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $push = $database->loadObject(); 
        $total = $push->cantidad;
    }
    $database->freeResults();  
    
    if (isset($array['pagina'])){
        $sql="SELECT id, titulo, body,imagen, fecha, tipo, ios_ok, ios_ko, android_ok, android_ko, web_ok, web_ko FROM push ORDER BY fecha DESC limit ".($tamano_pagina*$array['pagina']).",".$tamano_pagina.";";
    }
    else {
        $sql="SELECT id, titulo, body,imagen, fecha, tipo, ios_ok, ios_ko, android_ok, android_ko, web_ok, web_ko FROM push ORDER BY fecha DESC;";
    }
 
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $checking=true;
        $n=0;
        while ($push = $result->fetch_object()) {
            $id[$n]=$push->id;
            $titulo[$n]=$push->titulo;
            $body[$n]=$push->body;
            $imagen[$n]=$push->imagen;
            $fecha[$n]=substr($push->fecha,0,10);
            $hora[$n]=substr($push->fecha,11,5);
            $tipo[$n]=$push->tipo;
            $ios_ok[$n]=$push->ios_ok;
            $ios_ko[$n]=$push->ios_ko;
            $android_ok[$n]=$push->android_ok;
            $android_ko[$n]=$push->android_ko;
            $web_ok[$n]=$push->web_ok;
            $web_ko[$n]=$push->web_ko;
            //0123456789012345678
            //2022-03-02 09:00:00
            $n++;
        }
    }   
    $database->freeResults(); 

    
}




$json=array("valid"=>$checking, "id"=>$id, "titulo"=>$titulo, "body"=>$body, "imagen"=>$imagen, "fecha"=>$fecha, "hora"=>$hora, "tipo"=>$tipo, "ios_ok"=>$ios_ok, "ios_ko"=>$ios_ko, "android_ok"=>$android_ok, "android_ko"=>$android_ko, "web_ok"=>$web_ok, "web_ko"=>$web_ko, "total"=>$total);

echo json_encode($json); 


?>
