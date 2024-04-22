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

$idcliente=$array['id'];
$nombre=$array['nombre'];
$apellidos=$array['apellidos'];


if ($idcliente=='N'){
    $sql="SELECT pedidos_clientes.idPedido as id,pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.dia as dia, pedidos.hora as hora, pedidos.subtotal as subtotal, pedidos.portes as portes, pedidos.descuento as descuento, '0' AS monedero, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos.anulado FROM pedidos LEFT JOIN pedidos_clientes  on pedidos.id=pedidos_clientes.idPedido WHERE pedidos_clientes.nombre='".$nombre."' AND pedidos_clientes.apellidos='".$apellidos."' AND pedidos.anulado=0 ORDER BY pedidos.fecha DESC LIMIT 15;";
    
    
    
}
else {
    $sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.dia as dia, pedidos.hora as hora, pedidos.subtotal as subtotal, pedidos.portes as portes, pedidos.descuento as descuento, pedidos.monedero, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos.anulado  FROM pedidos WHERE cliente=".$idcliente." AND pedidos.anulado=0 ORDER BY pedidos.fecha DESC LIMIT 15;";
}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$pedidos=[];
if ($result->num_rows>0) {
    $checking=true;
    
    
    while ($pedi = $result->fetch_object()) {
        $pedidos[]=[
            'id'=>$pedi->id,
            'numero'=>$pedi->numero,
            'numeroRevo'=>$pedi->numeroRevo,
            'fecha'=>$pedi->fecha,
            'dia'=>$pedi->dia,
            'hora'=>$pedi->hora,
            'subtotal'=>$pedi->subtotal,
            'portes'=>$pedi->portes,
            'descuento'=>$pedi->descuento,
            'monedero'=>$pedi->monedero,
            'total'=>$pedi->total,
            'envio'=>$pedi->envio,
            'metodo'=>$pedi->metodo,
            'anulado'=>$pedi->anulado,
            'estadoPago'=>$pedi->estadoPago
            ];
    }
}
$database->freeResults();  
$json=array("valid"=>$checking, "pedidos"=>$pedidos);

ob_end_clean();
echo json_encode($json); 






?>
