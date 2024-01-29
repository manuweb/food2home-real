<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  addcategoriagrupo.php
//
//  añade categoria a grupo de modificadores  
//
//  Llamado desde modificadores.js -> addcategoria()
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="SELECT modifierCategories_id FROM modifierGroups WHERE id='".$array['id']."';";

   

$cat=$array['idcat'];


//$sql="UPDATE modifierGroups SET modifierCategories_id=modifierCategories_id+','".array['idcat']."' WHERE id='".array['id']."';";  

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    while ($modifierGroup = $result->fetch_object()) {
        
        if($modifierGroup->modifierCategories_id!=""){
            $cat=$modifierGroup->modifierCategories_id.','.$array['idcat'];
        }
    }
    
    //$checking=true;   
}
$sql="UPDATE modifierGroups SET modifierCategories_id='".$cat."' WHERE id='".$array['id']."';";  
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
}

$database->freeResults();


$json=array("valid"=>$checking);

echo json_encode($json); 



?>
