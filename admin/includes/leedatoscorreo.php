<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
$checking=false;



$sql="SELECT nombreremitente, mail,usuariomail,clavemail,host,puerto,SMTPSecure,sender,pie FROM mail WHERE id=1";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
    $configmail = $result->fetch_object();
    $nombreremitente=$configmail->nombreremitente;
    $mail=$configmail->mail;
    $usuariomail=$configmail->usuariomail;
    $clavemail=$configmail->clavemail;
    $host=$configmail->host;
    $puerto=$configmail->puerto;
    $SMTPSecure=$configmail->SMTPSecure;
    $sender=$configmail->sender;
    $pie=$configmail->pie;
}	

$database->freeResults();

$json=array("valid"=>$checking,"nombreremitente"=>$nombreremitente,"mail"=>$mail,"usuariomail"=>$usuariomail,"clavemail"=>$clavemail,"host"=>$host,"puerto"=>$puerto,"SMTPSecure"=>$SMTPSecure,"sender"=>$sender,"pie"=>$pie);

echo json_encode($json); 



?>
