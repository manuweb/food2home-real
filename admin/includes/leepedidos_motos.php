<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


//$pagina=2;
$pagina=$array['pagina'];


$checking=false;
$tamPagina=12;


$sql="SELECT pedidos.id FROM pedidos  WHERE pedidos.metodoEnvio=1 AND pedidos.anulado<1 ORDER BY pedidos.fecha DESC;";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$pedidos = $db->loadObjectList();  
$db->freeResults();
$numReg=count($pedidos);
$limitInf=($pagina-1)*$tamPagina;

$sql="SELECT pedidos.id as idPedido, pedidos.numero as numero, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.hora as hora,  pedidos.cliente as cliente, usuarios_app.id, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, pedidos.subtotal as subtotal, pedidos.impuestos as impuestos, (pedidos.descuento+pedidos.monedero) as descuentos, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos_clientes.apellidos AS ape_otro, pedidos_clientes.nombre as nom_otro FROM pedidos LEFT JOIN usuarios_app ON usuarios_app.id=pedidos.cliente LEFT JOIN pedidos_clientes ON pedidos_clientes.idPedido = pedidos.id  WHERE pedidos.metodoEnvio=1 AND pedidos.anulado<1 ORDER BY pedidos.fecha DESC LIMIT ".$limitInf.",".$tamPagina.";";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$pedidos = $db->loadObjectList();  
$db->freeResults();

if (count($pedidos)>0) {
    $checking=true;
    for ($n=0;$n<count($pedidos);$n++){
        $idPedido[$n]=$pedidos[$n]->idPedido;
        $numero[$n]=$pedidos[$n]->numero;
        $fecha[$n]=$pedidos[$n]->fecha;
        $hora[$n]=$pedidos[$n]->hora;
        $cliente[$n]=$pedidos[$n]->cliente;
        $apellidos[$n]=$pedidos[$n]->apellidos;
        $nombre[$n]=$pedidos[$n]->nombre;
        $ape_otro[$n]=$pedidos[$n]->ape_otro;
        $nom_otro[$n]=$pedidos[$n]->nom_otro;
        $subtotal[$n]=$pedidos[$n]->subtotal;
        $impuestos[$n]=$pedidos[$n]->impuestos;
        $descuentos[$n]=$pedidos[$n]->descuentos;
        $total[$n]=$pedidos[$n]->total;
        $envio[$n]=$pedidos[$n]->envio;
        $metodo[$n]=$pedidos[$n]->metodo;
        $canal[$n]=$pedidos[$n]->canal;
        $estadoPago[$n]=$pedidos[$n]->estadoPago;
               
    }
  
}	

$json=array("valid"=>$checking,"idPedido"=>$idPedido,"numero"=>$numero,"fecha"=>$fecha,"hora"=>$hora,"cliente"=>$cliente,"apellidos"=>$apellidos,"nombre"=>$nombre,"ape_otro"=>$ape_otro,"nom_otro"=>$nom_otro,"subtotal"=>$subtotal,"impuestos"=>$impuestos,"descuentos"=>$descuentos,"total"=>$total,"envio"=>$envio,"metodo"=>$metodo,"canal"=>$canal,"estadoPago"=>$estadoPago,"registros"=>$numReg);

echo json_encode($json); 
/*
echo('<pre>');
var_dump($json);
echo('</pre><br>');

echo $sql;

*/

?>
