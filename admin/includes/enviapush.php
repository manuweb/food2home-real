<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  enviapush.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde push.js ->enviapush()
//
//*************************************************************************************************


$checking=false;



//insert
//guardar imagenes::
$uploaddir = '../../webapp/img/upload/';
$arvhivo="";

if (isset($_FILES["files"])){
    
        $newFileName = uniqid('', true);

     
        $sep=explode('image/',$_FILES["files"]["type"]); 
        $tipo=$sep[1]; 
        
        $arvhivo=$_FILES["files"]["name"];

        //unlink($uploaddir.$arvhivo);
        move_uploaded_file($_FILES["files"]["tmp_name"], $uploaddir.$newFileName.'.'.$tipo);
        $arvhivo=$newFileName.'.'.$tipo;
        
}

$sql="SELECT idtel, plataforma FROM telefonos;";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result && $result->num_rows != 0) {
    $n=0;
    while ($telefonos = $result->fetch_object()) {

        $idtel[$n]=$telefonos->idtel;
        $plataforma[$n]=$telefonos->plataforma;
        $n++;

    }
}
$database->freeResults();  

$sql="INSERT INTO push (titulo, body, imagen, fecha, tipo) VALUES ('".$_POST['titulo']."', '".$_POST['body']."','".$arvhivo."', CURRENT_TIMESTAMP, '".$_POST['tipo']."');";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $checking=true;
}
$database->freeResults(); 
$json=array("valid"=>$checking, "idtel"=>$idtel, "plataforma"=>$plataforma,"imagen"=>$arvhivo);

echo json_encode($json); 

/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);

fwrite($file, "jason: ".json_encode($json). PHP_EOL);

fclose($file);
*/

?>
