<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardanosotros.php
//
//  guarda tabla nosotros 
//
//  Llamado desde paginanosotros.js ->guardanosotros()
//
//*************************************************************************************************


$checking=false;


if($_POST['id']>0){
    //update
    $sql="UPDATE nosotros SET nombre='".$_POST['nombre']."', texto='".$_POST['texto']."' WHERE id=".$_POST['id']."";
    
}
else {
    //insert
    //guardar imagenes::
    $uploaddir = '../../webapp/img/upload/';
    $texto="";
    $articulos=explode("||",$_POST['texto']);
    if (isset($_FILES["files"])){
        
 
        for($i=0;$i<count($_FILES["files"]["name"]);$i++)
        {
            $newFileName = uniqid('', true);

            $arvhivo=$_FILES["files"]["name"][$i];
            $sep=explode('image/',$_FILES["files"]["type"][$i]); 
            $tipo=$sep[1]; 


            unlink($uploaddir.$arvhivo);
            move_uploaded_file($_FILES["files"]["tmp_name"][$i], $uploaddir.$newFileName.'.'.$tipo);
            $texto.=$newFileName.'.'.$tipo."##".$articulos[$i]."||";


        }
    }
    else {
        $texto=$_POST['texto'];
    }
       
    
    $sql="INSERT INTO nosotros (web,app, orden, tipo, nombre, texto) VALUES ('1','1','".$_POST['orden']."','".$_POST['tipo']."','".$_POST['nombre']."','".$texto."');";
}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) { 
    $checking=true;

}
$database->freeResults();
$json=array("valid"=>$checking);
echo json_encode($json); 
/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "jason: ". json_encode($json) . PHP_EOL);
fwrite($file, "jason: ". json_encode($_POST) . PHP_EOL);
fclose($file);
*/
?>
