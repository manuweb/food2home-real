<?php

//*************************************************************************************************
//
//  leecategoriamodificador.php
//
//  Lee modifierCategories 
//
//  Llamado desde modificadores.js -> editacategoriamodificador()
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

$sql="SELECT id, activo, nombre, opciones, forzoso, maximo, modificadores FROM modifierCategories WHERE id='".$array['id']."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {    
    $checking=true;
    $n=0;
    while ($categorias = $result->fetch_object()) {
        $id[$n]=$categorias->id;
        $nombre[$n]=$categorias->nombre;
        $activo[$n]=$categorias->activo;

        $opciones[$n]=$categorias->opciones;
        $forzoso[$n]=$categorias->forzoso;
        $maximo[$n]=$categorias->maximo;
        $modificadores[$n]=$categorias->modificadores;
        
        
        /*
        if ($modificadores[$n]!="") {
            $sql="SELECT nombre FROM modifiers WHERE id IN (".$modificadores[$n].");";
            $db->setQuery($sql);  
            $modifiers = $db->loadObjectList();  
            $db->freeResults();
            for ($j=0;$j<count($modifiers);$j++) {
                $lista=$lista.$modifiers[$j]->nombre.', ';
            }
            $txtmodificadores[]=substr($lista, 0, -2);   
        }
        */
        $sql2="SELECT id, nombre FROM modifiers WHERE category_id='".$id[$n]."';";
            
        $database = DataBase::getInstance();
        $database->setQuery($sql2);
        $result = $database->execute();
        $lista='';
        $listaIds='';
        if ($result) {    
            $checking=true;
            while ($modifiers = $result->fetch_object()) {

       
                    $lista=$lista.$modifiers->nombre.', ';
                    $listaIds=$listaIds.$modifiers->id.',';
            }
            $txtmodificadores[$n]=substr($lista, 0, -2); 
            $listaIds=substr($listaIds, 0, -1);

        }



        if ($result->num_rows > 0){
            //update
            $sql3="UPDATE modifierCategories SET modificadores='".$listaIds."' WHERE id='".$id[$n]."';";
            
            $database2 = DataBase::getInstance();
            $database2->setQuery($sql3);
            $result2 = $database2->execute();

    
            //fwrite($file, "sql3: ". $sql3 . PHP_EOL);
        }
        else {
            $txtmodificadores[$n]="No hay";
        }          
        $n++;
    }
    
    $checking=true;
}
$database->freeResults();
 $database2->freeResults();

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"activo"=>$activo,"opciones"=>$opciones,"forzoso"=>$forzoso,"maximo"=>$maximo,"modificadores"=>$modificadores,"lista"=>$txtmodificadores);

echo json_encode($json); 





?>
