<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


//$pagina=2;
$pagina=$array['pagina'];


$checking=false;
$tamPagina=12;
$rangofecha='1';
$rangoenvio='';
$rangometodo='';
if ($array['fecha']!=''){
    $fechasjs=explode(' - ',$array['fecha']);
    // 01/34/6789
    $rangofecha="pedidos.dia>='".substr($fechasjs[0],6,4)."-".substr($fechasjs[0],3,2)."-".substr($fechasjs[0],0,2)." 00:00:00"."' AND pedidos.dia<='".substr($fechasjs[1],6,4)."-".substr($fechasjs[1],3,2)."-".substr($fechasjs[1],0,2)." 23:59:59"."'";
}
if ($array['metodo']!=0){ 
    $rangometodo=' AND pedidos.metodoPago='.$array['metodo'];
}
if ($array['envio']!=0){ 
    $rangoenvio=' AND pedidos.metodoEnvio='.$array['envio'];
}


$sql="SELECT pedidos.id, pedidos.subtotal, pedidos.portes, pedidos.total, pedidos.metodoEnvio, pedidos.metodoPago, (pedidos.descuento+pedidos.monedero) as descuentos, pedidos.anulado FROM pedidos  WHERE ".$rangofecha.$rangometodo.$rangoenvio." AND estadoPago>='0';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$sumaSubtotal=0;
$sumaPortes=0;
$sumaTotal=0;
$sumaDescuentos=0;
$aTarjeta=0;
$aEfectivo=0;
$aEnviar=0;
$aRecoger=0;
$numReg=$result->num_rows;
$limitInf=($pagina-1)*$tamPagina;
while ($pedid = $result->fetch_object()) {
    $sumaSubtotal+=$pedid->subtotal;
    $sumaPortes+=$pedid->portes;
    $sumaTotal+=$pedid->total;
    $sumaDescuentos+=$pedid->descuentos;
    if ($pedid->metodoEnvio==1){
        $aEnviar++;
    }
    else {
        $aRecoger++;
    }
    if ($pedid->metodoPago==2){
        $aEfectivo++;
    }
    else {
        $aTarjeta++;
    }
}

//$sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos_delivery.resultado AS num_delivery, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.dia as dia, pedidos.hora as hora, pedidos.cliente as cliente, usuarios_app.id AS idcliente, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, pedidos.subtotal as subtotal, pedidos.portes as portes,
pedidos.impuestos as impuestos, (pedidos.descuento+pedidos.monedero) as descuentos, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos_clientes.apellidos AS ape_otro, pedidos_clientes.nombre as nom_otro,  pedidos.anulado, tickets.impreso FROM pedidos LEFT JOIN usuarios_app ON usuarios_app.id=pedidos.cliente LEFT JOIN pedidos_clientes ON pedidos_clientes.idPedido = pedidos.id LEFT OUTER JOIN pedidos_delivery ON pedidos_delivery.idpedido = pedidos.id LEFT OUTER JOIN tickets ON tickets.ticket = pedidos.numero WHERE ".$rangofecha.$rangometodo.$rangoenvio." AND estadoPago>=0 ORDER BY pedidos.dia DESC, pedidos.hora DESC LIMIT ".$limitInf.",".$tamPagina.";";

$sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos_delivery.resultado AS num_delivery, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.dia as dia, pedidos.hora as hora, pedidos.cliente as cliente, usuarios_app.id AS idcliente, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, pedidos.subtotal as subtotal, pedidos.portes as portes,
pedidos.impuestos as impuestos, (pedidos.descuento+pedidos.monedero) as descuentos, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos_clientes.apellidos AS ape_otro, pedidos_clientes.nombre as nom_otro,  pedidos.anulado, tickets.impreso FROM pedidos LEFT JOIN usuarios_app ON usuarios_app.id=pedidos.cliente LEFT JOIN pedidos_clientes ON pedidos_clientes.idPedido = pedidos.id LEFT OUTER JOIN pedidos_delivery ON pedidos_delivery.idpedido = pedidos.id LEFT OUTER JOIN (SELECT tickets.ticket, tickets.impreso FROM tickets GROUP BY ticket) tickets ON tickets.ticket = pedidos.numero WHERE ".$rangofecha.$rangometodo.$rangoenvio." AND estadoPago>=0 ORDER BY pedidos.dia DESC, pedidos.hora DESC LIMIT ".$limitInf.",".$tamPagina.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    $n=0;
    /*
    $sumaSubtotal=0;
    $sumaTotal=0;
    $sumaDescuentos=0;
    */
    while ($pedidos = $result->fetch_object()) {
        $id[$n]=$pedidos->id;
        $numero[$n]=$pedidos->numero;
        $numeroRevo[$n]=$pedidos->numeroRevo;
        $num_delivery[$n]=$pedidos->num_delivery;
        $fecha[$n]=$pedidos->fecha;
        $dia[$n]=$pedidos->dia;
        $hora[$n]=$pedidos->hora;
        $cliente[$n]=$pedidos->cliente;
        $apellidos[$n]=$pedidos->apellidos;
        $nombre[$n]=$pedidos->nombre;
        $ape_otro[$n]=$pedidos->ape_otro;
        $nom_otro[$n]=$pedidos->nom_otro;
        $subtotal[$n]=$pedidos->subtotal;
        $portes[$n]=$pedidos->portes;
        $impuestos[$n]=$pedidos->impuestos;
        $descuentos[$n]=$pedidos->descuentos;
        $total[$n]=$pedidos->total;
        $envio[$n]=$pedidos->envio;
        $metodo[$n]=$pedidos->metodo;
        $canal[$n]=$pedidos->canal;
        $estadoPago[$n]=$pedidos->estadoPago;
        $anulado[$n]=$pedidos->anulado;
        $impreso[$n]=$pedidos->impreso;
        /*
        $sumaSubtotal+=$subtotal[$n];
        $sumaTotal+=$total[$n];
        $sumaDescuentos+=$descuentos[$n];
        */
        $n++;
    }
  
}	

$database->freeResults();  

$json=array("valid"=>$checking,"id"=>$id,"numero"=>$numero,"numeroRevo"=>$numeroRevo,"fecha"=>$fecha,"dia"=>$dia,"hora"=>$hora,"cliente"=>$cliente,"apellidos"=>$apellidos,"nombre"=>$nombre,"ape_otro"=>$ape_otro,"nom_otro"=>$nom_otro,"subtotal"=>$subtotal,"portes"=>$portes,"sumaTotal"=>number_format(round($sumaTotal,2),2,'.',','),"sumaPortes"=>number_format(round($sumaPortes,2),2,'.',','),"sumaDescuentos"=>number_format(round($sumaDescuentos,2),2,'.',','),"sumaSubtotal"=>number_format(round($sumaSubtotal,2),2,'.',','),"impuestos"=>$impuestos,"descuentos"=>$descuentos,"total"=>$total,"envio"=>$envio,"metodo"=>$metodo,"canal"=>$canal,"estadoPago"=>$estadoPago,"registros"=>$numReg,"ped_reparto"=>$aEnviar,"ped_recoger"=>$aRecoger,"ped_efectivo"=>$aEfectivo,"ped_tarjeta"=>$aTarjeta,"anulado"=>$anulado, "num_delivery"=>$num_delivery,"impreso"=>$impreso);

echo json_encode($json); 

/*

$file = fopen("zz-leepedidos.txt", "w");
fwrite($file, "metodo: ". $array['metodo'] . PHP_EOL);
fwrite($file, "envio: ". $array['envio'] . PHP_EOL);
fwrite($file, "sql: ". $sql . PHP_EOL);


fclose($file);
*/
?>
