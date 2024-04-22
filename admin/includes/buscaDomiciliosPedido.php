<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);

$checking=false;

//$cliente='N';
//$nombre='Perico';
//$apellidos='de los Palotes';

$cliente=$array['id'];
$nombre=$array['nombre'];
$apellidos=$array['apellidos'];


if ($cliente=='N'){
    $sql='SELECT pedidos_domicilios.direccion, pedidos_domicilios.complementario, pedidos_domicilios.cod_postal, pedidos_domicilios.poblacion, pedidos_domicilios.provincia FROM pedidos_domicilios left join pedidos_clientes ON pedidos_domicilios.idPedido=pedidos_clientes.idPedido WHERE pedidos_clientes.nombre="'.$nombre.'" AND pedidos_clientes.apellidos="'.$apellidos.'";';
}
else {
    $sql='SELECT direccion, complementario,cod_postal, poblacion, provincia FROM domicilios WHERE usuario='.$cliente.';';
}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$domicilios=[];
if ($result->num_rows>0) {
    $checking=true;
    
    
    while ($domis = $result->fetch_object()) {
        $domicilios[]=[
            'direccion'=>$domis->direccion,
            'complementario'=>$domis->complementario,
            'cod_postal'=>$domis->cod_postal,
            'poblacion'=>$domis->poblacion,
            'provincia'=>$domis->provincia    
        ];
    }
}
$database->freeResults();  
$json=array("valid"=>$checking, "domicilios"=>$domicilios);

ob_end_clean();
echo json_encode($json); 






?>
