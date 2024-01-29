<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$checking=false;
if (isset($_FILES)) {

    $uploaddir = '../../webapp/img/empresa/';
    $uploaddir2 = '../../img/';
    $uploaddir3 = '../../admin/img/';
    $archivo=$_FILES['imagen']['name'];
    $archivo2='logo.png';
    $sep=explode('image/',$_FILES["imagen"]["type"]); 
    $tipo=$sep[1]; 
    if($tipo == "png"){ 
        //unlink($uploaddir.$archivo);
        
        $resultado2 = copy($_FILES["imagen"]["tmp_name"], $uploaddir2.$archivo2);
        $resultado3 = copy($_FILES["imagen"]["tmp_name"], $uploaddir3.$archivo2);
        
        $resultado = move_uploaded_file($_FILES["imagen"]["tmp_name"], $uploaddir.$archivo);
        
        

        if ($resultado){
            
            $sql="UPDATE empresa SET logo='".$archivo."' WHERE id=1;";

            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $result = $database->execute();

            if ($result) {  

                $checking=true;

            }	
            $database->freeResults();        
        }
        
    }
}

$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$sql="UPDATE alergenos SET imagen='".$nombre_imagen."' WHERE id='".$array['id']."';";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$alergeno = $db->loadObjectList();  
$db->freeResults();

if (count($alergeno)==0) {
    $checking=true;
    
}	

$json=array("valid"=>$checking);

echo json_encode($json); 



$file = fopen("texto.txt", "w");
fwrite($file, "Datos: ". $archivo . PHP_EOL);
//fwrite($file, "Sql: ". $sql . PHP_EOL); 

fclose($file);
*/

?>
