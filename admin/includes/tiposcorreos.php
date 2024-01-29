<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;


if ($array['accion']=='leer'){
    $sql="SELECT textomail FROM tiposcorreos WHERE nombre='".$array['nombre']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    if ($result) { 

        $checking=true;
        $textomail=''; 
        if ($result->num_rows > 0){
            $mail = $result->fetch_object();
            $textomail=$mail->textomail;   
        }	

    }
}
else {
    $sql="UPDATE tiposcorreos SET textomail='".$array['textomail']."' WHERE nombre='".$array['nombre']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    if ($result) { 

        $checking=true;
        $textomail=$array['textomail'];   

    }
}


$database->freeResults();

$json=array("valid"=>$checking,"textomail"=>$textomail);

echo json_encode($json); 



?>
