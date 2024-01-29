<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardametodo.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde metodospago.js ->guardametodo()
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;


    //update
$sql="UPDATE metodospago SET nombre='".$array['nombre']."', idrevo='".$array['idrevo']."', activo=".$array['activo'].", esRedsys='0' WHERE id=".$array['id']."";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {   
    if($array['esRedsys']=='1') {
        $sql="UPDATE metodospago SET esRedsys='1' WHERE id=".$array['id']."";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        
        if($array['MerchantKey']!='') {
            $sql="UPDATE redsys SET MerchantCode='".$array['MerchantCode']."', MerchantKey='".$array['MerchantKey']."', terminal='".$array['terminal']."' WHERE id=1";

            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $result = $database->execute();
        }
        
    }
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking);


ob_end_clean();
echo json_encode($json); 

?>
