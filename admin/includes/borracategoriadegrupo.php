<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  borracategoriadegrupo.php
//
//  añade categoria a grupo de modificadores  
//
//  Llamado desde modificadores.js -> borraCategoria()
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;
$nuevo="";
$cat=explode(",", $array['cate']);
for($n=0;$n<count($cat);$n++){
    if ($array['idg']!=$cat[$n]){
        $nuevo=$nuevo.$cat[$n].',';
    }
}
$nuevo=substr($nuevo, 0, -1);  
$sql="UPDATE modifierGroups SET modifierCategories_id='".$nuevo."' WHERE id='".$array['id']."';";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $checking=true;
}

$database->freeResults();


$json=array("valid"=>$checking);

echo json_encode($json); 
/*
$file = fopen("borracategoriadegrupo.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "cate: ". $array['cate'] . PHP_EOL);
fwrite($file, "borrar: ". $array['idg'] . PHP_EOL);
fwrite($file, "nuevo: ". $nuevo . PHP_EOL);
fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
