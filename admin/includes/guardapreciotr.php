<?php 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;

$sql="UPDATE integracion SET precio_en_tr='".$array['precio_en_tr']."'WHERE id=1";

    
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {    
    $checking=true; 
}
$database->freeResults();

$json=array("valid"=>$checking);
    
ob_end_clean();
echo json_encode($json);  



?>
