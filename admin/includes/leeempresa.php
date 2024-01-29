<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

 $_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;



$sql="SELECT empresa.nombre, empresa.nombre_comercial,empresa.nif_cif, empresa.logo, empresa.icono,empresa.domicilio, empresa.cod_postal, empresa.poblacion,empresa.provincia,  empresa.telefono, empresa.movil, empresa.email, integracion.tipo, integracion.logo AS logo2 FROM empresa LEFT JOIN integracion ON integracion.id=empresa.id WHERE empresa.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

// Verificar si se obtuvieron resultados
if ($result) {    
    $checking=true;
    $empresa = $result->fetch_object();
        
        $checking=true;
        $nombre=$empresa->nombre;
        $nombre_comercial=$empresa->nombre_comercial;
        $nif=$empresa->nif_cif;
        $logo=$empresa->logo;
        $icono=$empresa->icono;
        $domicilio=$empresa->domicilio;
        $cod_postal=$empresa->cod_postal;
        $poblacion=$empresa->poblacion;
        $provincia=$empresa->provincia;
        $telefono=$empresa->telefono;
        $movil=$empresa->movil;
        $email=$empresa->email;
        $tipo_integracion=$empresa->tipo;
        $logo_impresora=$empresa->logo2;

     	
}
$database->freeResults();


$json=array("valid"=>$checking,"nombre"=>$nombre,"nombre_comercial"=>$nombre_comercial,"nif"=>$nif,"logo"=>$logo,"icono"=>$icono,"domicilio"=>$domicilio,"cod_postal"=>$cod_postal,"poblacion"=>$poblacion,"provincia"=>$provincia,"telefono"=>$telefono,"movil"=>$movil,"email"=>$email,"tipo_integracion"=>$tipo_integracion,"logo_impresora"=>$logo_impresora);

echo json_encode($json); 



?>
