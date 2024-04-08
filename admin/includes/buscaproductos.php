<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");

$elgrupo = new Producto;
$grupos=$elgrupo->leeproductosactivos();


$json=array("valid"=>true, "productos"=>$grupos);

ob_end_clean();
echo json_encode($json); 






?>
