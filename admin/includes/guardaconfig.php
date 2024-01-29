<?php
session_start(); 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);

$checking=false;


$update='';
if ($array['usuario_revo']!=''){
   $update=", usuario='".$array['usuario_revo']."', token='".$array['token_revo']."'"; 
}

$sql="UPDATE integracion SET tipo='".$array['tipo_integracion']."' ".$update." WHERE id=1";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) { 
    $sql="UPDATE empresa SET nombre='".$array['nombre_empresa']."', nombre_comercial='".$array['nombre_comercial']."', nif_cif='".$array['cif_empresa']."', domicilio='".$array['domicilio_empresa']."', cod_postal='".$array['cp_empresa']."', poblacion='".$array['poblacion_empresa']."', 
    provincia='".$array['provincia_empresa']."', 
    telefono='".$array['telefono_empresa']."' WHERE id=1;";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
        $nombre_fichero = 'first.txt';
        $file = fopen($nombre_fichero, "w");
        fwrite($file, "OK" . PHP_EOL);

        fclose($file);
    }
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json);    



?>
