<?php

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardacategoriamodificador.php
//
//  guarda los categorias de modificador  
//
//*************************************************************************************************
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

if($array['activo']=='false'){
    $array['activo']=0;
}
else {
    $array['activo']=1;
}
if ($array['nuevo']!='si'){

    $sql="UPDATE modifierCategories SET id='".$array['id']."', nombre='".$array['nombre']."', activo='".$array['activo']."', opciones='".$array['opciones']."', forzoso='".$array['forzoso']."', maximo='".$array['maximo']."' WHERE id='".$array['nuevo']."';";
}
else {

    $sql="INSERT INTO modifierCategories (id,nombre,activo,opciones,forzoso, maximo) VALUES ('".$array['id']."', '".$array['nombre']."', '".$array['activo']."', '".$array['opciones']."', '".$array['forzoso']."', '".$array['maximo']."');";  
}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    
    $sql2="SELECT id FROM modifiers WHERE category_id='".$array['id']."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql2);
    $result = $database->execute();

    if ($result) {

        while ($modifiers = $result->fetch_object()) {
            $listaIds=$listaIds.$modifiers->id.',';
        }

        $listaIds=substr($listaIds, 0, -1);

        //update
        $sql3="UPDATE modifierCategories SET modificadores='".$listaIds."' WHERE id='".$array['id']."';";
        $database = DataBase::getInstance();
        $database->setQuery($sql2);
        $result = $database->execute();
    }

}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 


//$file = fopen("leecategoriamodificadores.txt", "w");
/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "sql3: ". $sql3 . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
