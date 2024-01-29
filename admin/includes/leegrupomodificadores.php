<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//*************************************************************************************************
//
//  leecgrupomodificadores.php
//
//  Lee modifierGroups 
//
//  Llamado desde modificadores.js -> muestraGrumodificadores()
//
//*************************************************************************************************

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


$checking=false;

$sql="SELECT id, nombre, modifierCategories_id FROM modifierGroups ORDER BY nombre;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $n=0;
    while ($categorias = $result->fetch_object()) {
        $id[$n]=$categorias->id;
        $nombre[$n]=$categorias->nombre;
        $modificadores[$n]=$categorias->modifierCategories_id;
        $lista="";
        $txtmodificadores[$n]="Sin categorias";
        
        if ($modificadores[$n]!="") {
            //fwrite($file, "modif: ". $modificadores[$n] . PHP_EOL);
            $cat=explode(",", $modificadores[$n]);
            
            $tot=count($cat);
            for ($j=0;$j<$tot;$j++){

                $sql2="SELECT nombre FROM modifierCategories WHERE id='".$cat[$j]."';";
                $database = DataBase::getInstance();
                $database->setQuery($sql2);
                $result2 = $database->execute();

                if ($result2) {

                    while ($modifiers = $result2->fetch_object()){
                   

                        $lista=$lista.$modifiers->nombre.', ';
                        
                    }
                   
                    
                }
      
            }
            $txtmodificadores[$n]=substr($lista, 0, -2);  
        }   

        
        $n++;
    }
    
    $checking=true;
}
$database->freeResults();

 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"modifierCategories_id"=>$modificadores, "lista"=>$txtmodificadores);

echo json_encode($json); 


/*
$file = fopen("texto2.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fclose($file);
*/

?>
