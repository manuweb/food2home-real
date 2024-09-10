<?php
header("Access-Control-Allow-Origin: *");
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";



if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

$sql="SELECT MerchantCode, MerchantKey, terminal, bizum FROM redsys WHERE id=1;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $redsys = $result->fetch_object();
    $MerchantCode=$redsys->MerchantCode;
    $MerchantKey=$redsys->MerchantKey;
    $terminal=$redsys->terminal;
    $bizum=$redsys->bizum;
}	
$database->freeResults();

$json=array("valid"=>$checking,"MerchantKey"=>$MerchantKey,"MerchantCode"=>$MerchantCode,"terminal"=>$terminal,"bizum"=>$bizum);

ob_end_clean();
echo json_encode($json); 

?>
