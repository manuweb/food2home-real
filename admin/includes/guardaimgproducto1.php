<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

//$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;
if (isset($_FILES)) {

    $uploaddir = '../../webapp/img/productos/';
    $newFileName = uniqid('', true);
    

    $arvhivo=$_FILES['imagen']['name'];
    $sep=explode('image/',$_FILES["imagen"]["type"]); 
    $tipo=$sep[1]; 
    if($tipo == "jpg" || $tipo == "jpeg" || $tipo == "png" || $tipo == "gif"){ 
        //unlink($uploaddir.$arvhivo);
        $resultado = move_uploaded_file($_FILES["imagen"]["tmp_name"], $uploaddir.$newFileName.'.'.$tipo);
        if ($resultado){
            
            $sql="UPDATE productos SET imagen_app1='".$newFileName.'.'.$tipo."' WHERE id='".$array['id']."' AND tienda='".$array['tienda']."';";

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


?>
