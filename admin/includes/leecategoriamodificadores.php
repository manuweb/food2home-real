<?php
//*************************************************************************************************
//
//  leecategoriamodificadores.php
//
//  Lee modifierCategories 
//
//*************************************************************************************************

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;

if((isset($array['order']))&&($array['order']!=false)){
    $order='FIELD(id, '.$array['order'].'), nombre';
}
else{
    $order='nombre';
    
}

$sql="SELECT id, activo, nombre, opciones, forzoso, maximo, modificadores FROM modifierCategories ORDER BY ".$order.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $n=0;
    
    //$file = fopen("texto3.txt", "w");
    //fwrite($file, "sql: ". $sql . PHP_EOL);
    while ($categorias = $result->fetch_object()) {
        
        $id[$n]=$categorias->id;
        $nombre[$n]=$categorias->nombre;
        $activo[$n]=$categorias->activo;
        
        $opciones[$n]=$categorias->opciones;
        $forzoso[$n]=$categorias->forzoso;
        $maximo[$n]=$categorias->maximo;
        $modificadores[$n]=$categorias->modificadores;
        $lista="";
        $txtmodificadores[$n]="Sin modificadores";
        
        if ($modificadores[$n]!="") {
            $sql2="SELECT nombre FROM modifiers WHERE id IN (".$modificadores[$n].");";
            //fwrite($file, "sql2: ". $sql2 . PHP_EOL);
            $database = DataBase::getInstance();
            $database->setQuery($sql2);
            $result2 = $database->execute();
            
            if ($result2) {   
                while ($modifiers = $result2->fetch_object()) {
              
                    $lista=$lista.$modifiers->nombre.', ';
                    //fwrite($file, "modi: ". $modifiers->nombre . PHP_EOL);
                }
            }
            $txtmodificadores[$n]=substr($lista, 0, -2); 
            //fwrite($file, "lista: ". $txtmodificadores[$n] . PHP_EOL);
        
        }   
           
        /*
        $sql2="SELECT id, nombre FROM modifiers WHERE category_id='".$id[$n]."';";
            
        $db->setQuery($sql2);  
        $modifiers = $db->loadObjectList();  
        $db->freeResults();

        for ($j=0;$j<count($modifiers);$j++) {
            $lista=$lista.$modifiers[$j]->nombre.', ';
            $listaIds=$listaIds.$modifiers[$j]->id.',';
        }
        $txtmodificadores[$n]=substr($lista, 0, -2); 
        $listaIds=substr($listaIds, 0, -1);
        


        if (count($modifiers)>0){
            //update
            $sql3="UPDATE modifierCategories SET modificadores='".$listaIds."' WHERW id='".$id[$n]."';";
            $db->setQuery($sql3);  
            $modifiers = $db->loadObjectList();  
            $db->freeResults();               
        }
        else {
            $txtmodificadores[$n]="No hay";
        }  
        */
        
      $n++;  
    }
    

    $checking=true;
}

$database->freeResults();
 //fclose($file);

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"activo"=>$activo,"opciones"=>$opciones,"forzoso"=>$forzoso,"maximo"=>$maximo,"modificadores"=>$modificadores,"lista"=>$txtmodificadores);

echo json_encode($json); 





   

?>
