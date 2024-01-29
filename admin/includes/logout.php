<?php
session_start(); 

header("Access-Control-Allow-Origin: *");


$checking=true;

$_SESSION["autentificado"]=false;

$json=array("valid"=>$checking);

echo json_encode($json);    



?>
