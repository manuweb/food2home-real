<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//*************************************************************************************************
//
//  leegrupomodificador.php
//
//  Lee modifierGroups 
//
//  Llamado desde modificadores.js -> editagrupomodificadores()
//
//*************************************************************************************************
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


//$array = json_decode(json_encode($_POST), true);

$array['id']=34;

$checking=false;

$sql="SELECT id, nombre, modifierCategories_id FROM modifierGroups WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
echo $sql."<br>";
if ($result) {    
    $checking=true;
    $n=0;

    while ($categorias = $result->fetch_object()) {
        $id[$n]=$categorias->id;
        $nombre[$n]=$categorias->nombre;
        $modificadores[$n]=$categorias->modifierCategories_id;
        
        if ($modificadores[$n]!=''){
            $sql2="SELECT id, nombre FROM modifierCategories WHERE id IN (".$modificadores[$n].");";

            echo $sql2."<br>";
            $database = DataBase::getInstance();
            $database->setQuery($sql2);
            $result2 = $database->execute();

            if ($result2) {  
                $lista='';
                $listaIds='';   
                while ($modifiers = $result2->fetch_object()) {
                    $lista=$lista.$modifiers->nombre.', ';
                    $listaIds=$listaIds.$modifiers->id.',';
                }
                $txtmodificadores[$n]=substr($lista, 0, -2); 
                $listaIds=substr($listaIds, 0, -1);
            }
            else{
                $txtmodificadores[$n]="No hay";
            } 
             
        }
        else{
                $txtmodificadores="No hay";
        } 
        
        
        $n++;
    }
    
    $checking=true;
}
$database->freeResults();
 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"modificadores"=>$modificadores,"lista"=>$txtmodificadores);

echo json_encode($json); 


$file = fopen("zzZZgM.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "id: ". $array['id'] . PHP_EOL); 
 
fclose($file);


?>
