<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$elgrupo = new Producto;

$grupos=$elgrupo->leeproducto($array['id']);



                                     
if ($grupos['modifier_category_id']!=''){
    $grupos['modifierCategories'][]=$elgrupo->leemodifierCategories($grupos['modifier_category_id']);
    
}
if ($grupos['modifier_group_id']!=''){
    
    
    $grupos['modifierCategories']=$elgrupo->leemodifierGroups($grupos['modifier_group_id']);
}

$json=array("valid"=>true, "producto"=>$grupos);

ob_end_clean();
echo json_encode($json); 








?>
