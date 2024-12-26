<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  guardainicio.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde paginainicio.js ->guardainicio()
//
//*************************************************************************************************


$checking=false;

if (isset($_FILES)) {
    if (isset($_FILES['imagen'])) {
     
        $uploaddir = '../../webapp/img/upload/';
        $archivo=$_FILES['imagen']['name'];

        $newFileName = uniqid('', true);
        $atributos= getimagesize($_FILES['imagen']['tmp_name']);
        
        $sep=explode('image/',$_FILES["imagen"]["type"]); 
        $tipo=$sep[1]; 
        $msg='ok';
        if($tipo == "png" || $tipo == "jpeg"){ 

            $resultado = move_uploaded_file($_FILES["imagen"]["tmp_name"], $uploaddir.$newFileName.'.'.$tipo);  
            
        }
        else {
            $msg='Tipo no soportado';
        } 
        $checking=true;
        $nombre=$newFileName.'.'.$tipo;
    }    
}

    if (isset($_POST['id'])){
        if($_POST['id']>0){
            $sql="UPDATE inicio SET nombre='".$_POST['nombre']."', texto='".$_POST['texto']."' WHERE id=".$_POST['id'].";";
        }
        else {
            $sql="INSERT INTO inicio (web,app, orden, tipo, nombre, texto) VALUES ('1','1','".$_POST['orden']."','".$_POST['tipo']."','".$_POST['nombre']."','".$_POST['texto']."');";
            
        }
        

        
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        

        // Verificar si se obtuvieron resultados
        if ($result) { 
            $checking=true;

        }
        $database->freeResults();
    }

/*
if (isset($_POST['id'])){
    if($_POST['id']>0){
        //update
        $sql="UPDATE inicio SET nombre='".$_POST['nombre']."', texto='".$_POST['texto']."' WHERE id=".$_POST['id']."";

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
                $Ext=$_FILES["files"]["type"][$i];



                $tempArchivo=$_FILES["files"]["tmp_name"][$i];

                switch ($Ext) {
                    case "image/jpeg":
                        $img = imagecreatefromjpeg($tempArchivo);
                        break;
                    case "image/png":
                        $img = imagecreatefrompng($tempArchivo);
                        break;
                    case "image/bmp":
                        $img = imagecreatefrombmp($tempArchivo);
                        break;
                }


                imagewebp($img, $uploaddir.$newFileName.'.webp',80);
                imagedestroy($img);

                $texto.=$newFileName.'.webp'."##".$articulos[$i]."||";


            }
        }

        else {
            $texto=$_POST['texto'];
        }
        
        $sql="INSERT INTO inicio (web,app, orden, tipo, nombre, texto) VALUES ('1','1','".$_POST['orden']."','".$_POST['tipo']."','".$_POST['nombre']."','".$texto."');";
    }

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    // Verificar si se obtuvieron resultados
    if ($result) { 
        $checking=true;

    }
}
$database->freeResults();
*/

$file = fopen('zz-ghgh.txt', "w");
fwrite($file,'id:'.$_POST['id'] . PHP_EOL);
fwrite($file,$sql . PHP_EOL);
fclose($file);
$json=array("valid"=>$checking,"msg"=>$msg,'nombre'=>$nombre);
echo json_encode($json); 


?>
