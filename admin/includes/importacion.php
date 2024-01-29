<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');

/*
TRUNCATE `categorias`;
TRUNCATE `grupos`;
TRUNCATE `productos`;
*/

$checking=false;
$impuestos=array(0,0,10,21,4);
if (isset($_FILES)) {
    $error=false;
    $fileName = $_FILES['archivo']['name'];
    //$fileType = $_FILES['archivo']['type'];
    //$fileError = $_FILES['archivo']['error'];
    //$fileContent = file_get_contents($_FILES['archivo']['tmp_name']);
    $lineas = file($_FILES['archivo']['tmp_name']);
    $porciones = explode(";", $lineas[0]);
    if ($porciones[0]!='id'){
        $error=true;
    }
    if ($porciones[1]!='category.group.name'){
        $error=true;
    }
    if ($porciones[2]!='category.group.id'){
        $error=true;
    }
    if ($porciones[3]!='category.name'){
        $error=true;
    }
    if ($porciones[4]!='category.id'){
        $error=true;
    }

    if ($error==false){

        $checking=true;
        $contadorG=0;
        $contadorC=0;
        $contadorP=0;
        $grupo=0;
        $categoria=0;
        $producto=0;
        $database = DataBase::getInstance();
        $file = fopen("zz.txt", "w");
        for ($x=1;$x<count($lineas);$x++){
            $porciones = explode(";", $lineas[$x]);
            //id 0 ;category.group.name 1 ;category.group.id 2 ;category.name 3 ;category.id 4;name 5;price 6;tax 7
            $idP=$porciones[0];
            $idG=$porciones[2];
            $idC=$porciones[4];
            $impuesto=array_search($porciones[7], $impuestos);
            $porciones[6]=str_replace(",",".",$porciones[6]);
            if ($idG!=$grupo) {
                //nuevo grupo
                $contadorG++;
                $grupo=$idG;
                $sql='INSERT INTO grupos (id, nombre, imagen, impuesto, activo, activo_web, activo_app, imagen_app) VALUES ('.$contadorG.',"'.$porciones[1].'","",'.$impuesto.',1,1,1,"");';
                
                fwrite($file, "sql: ". $sql . PHP_EOL);
                
                $database->setQuery($sql);
                $result = $database->execute();
                if ($result) {    
                    $checking=true;
                }
                else {
                    $checking=false;
                }
                
            }
            if ($idC!=$categoria) {
                //nuevo categoria
                $contadorC++;
                $categoria=$idC;
                $sql='INSERT INTO categorias (id,nombre,grupo,imagen,impuesto,activo,activo_web,activo_app,imagen_app) VALUES ('.$contadorC.',"'.$porciones[3].'",'.$contadorG.',"",'.$impuesto.',1,1,1,"");';
                
                fwrite($file, "sql: ". $sql . PHP_EOL);
                
                $database->setQuery($sql);
                $result = $database->execute();
                if ($result) {    
                    $checking=true;
                }
                else {
                    $checking=false;
                }

            }
            if ($idP!=$producto) {
                $contadorP++;
                $producto=$idP;
                //nuevo producto
                $sql='INSERT INTO productos (id, nombre, categoria, imagen, impuesto, activo, precio, info, alergias, precio_web, precio_app, activo_web, activo_app, imagen_app1) VALUES ('.$contadorP.',"'.$porciones[5].'",'.$contadorC.',"",'.$impuesto.',1,'.$porciones[6].',"","",'.$porciones[6].','.$porciones[6].',1,1,"");';
                
                fwrite($file, "sql: ". $sql . PHP_EOL);
                
                $database->setQuery($sql);
                $result = $database->execute();
                if ($result) {    
                    $checking=true;
                }
                else {
                    $checking=false;
                }

            }
            
        }
        $database->freeResults();
        
        fclose($file);
    }
}

    
$json=array("valid"=>$checking,"contadorG"=>$contadorG,"contadorC"=>$contadorC,"contadorP"=>$contadorP);
ob_end_clean();
echo json_encode($json); 




/*



$file = fopen("texto.txt", "w");
fwrite($file, "Datos: ". $archivo . PHP_EOL);
//fwrite($file, "Sql: ". $sql . PHP_EOL); 

fclose($file);
*/

?>
