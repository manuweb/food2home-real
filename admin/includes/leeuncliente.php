<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


$tipo=$array['tipo'];
$email=$array['email'];

$checking=false;

if ($tipo=='S'){
    $sql="SELECT usuarios_app.id AS id, usuarios_app.tratamiento AS tratamiento, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, usuarios_app.nacimiento as nacimiento, usuarios_app.username as email, usuarios_app.publicidad as publicidad, usuarios_app.telefono as telefono, usuarios_app.monedero as monedero, usuarios_app.id as idCliente FROM usuarios_app WHERE username='".$email."';";
}
else {
    $sql="SELECT '0' as id,pedidos_clientes.tratamiento AS tratamiento, pedidos_clientes.apellidos AS apellidos , pedidos_clientes.nombre AS nombre, pedidos_clientes.email AS email, pedidos_clientes.publicidad as publicidad, pedidos_clientes.telefono as telefono, '0' AS monedero, '' as nacimiento, '0' AS idCliente FROM `pedidos_clientes` WHERE pedidos_clientes.email='".$email."' GROUP BY pedidos_clientes.email;";
}





$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    $clientes = $result->fetch_object();
    $idcliente=$clientes->id;
    $tratamiento=$clientes->tratamiento;
    $apellidos=$clientes->apellidos;
    $nombre=$clientes->nombre;
    $email=$clientes->email;
    $telefono=$clientes->telefono;
    $nacimiento=$clientes->nacimiento;
    $publicidad=$clientes->publicidad;
    $monedero=$clientes->monedero;
    $idCliente=$clientes->idCliente;
    

    if ($result) {
        $checking=true;
        $n=0;
        if ($tipo=='S'){
            $sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha,pedidos.hora as hora, pedidos.subtotal as subtotal, pedidos.descuento, pedidos.monedero, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos.anulado  FROM pedidos WHERE cliente=".$idcliente." ORDER BY pedidos.fecha DESC LIMIT 0,8;";
            $database->setQuery($sql);
            $result = $database->execute();
            while ($pedidos = $result->fetch_object()) {
                $id[$n]=$pedidos->id;
                $numero[$n]=$pedidos->numero;
                $numeroRevo[$n]=$pedidos->numeroRevo;
                $fecha[$n]=$pedidos->fecha;
                $hora[$n]=$pedidos->hora;
                $subtotal[$n]=$pedidos->subtotal;
                //$descuento[$n]=$pedidos->descuento;
                //$monedero[$n]=$pedidos->monedero;
                $total[$n]=$pedidos->total;
                $envio[$n]=$pedidos->envio;
                $metodo[$n]=$pedidos->metodo;
                $anulado[$n]=$pedidos->anulado;
                $estadoPago[$n]=$pedidos->estadoPago;
                $n++;
            }
        }
        else {
            $sql="SELECT pedidos_clientes.idPedido as id,pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha,pedidos.hora as hora, pedidos.subtotal as subtotal, pedidos.descuento, '0' AS monedero, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos.anulado FROM pedidos LEFT JOIN pedidos_clientes  on pedidos.id=pedidos_clientes.idPedido WHERE pedidos_clientes.email='".$email."'";
            $database->setQuery($sql);
            $result = $database->execute();
            while ($pedidos = $result->fetch_object()) {
                $id[$n]=$pedidos->id;
                $numero[$n]=$pedidos->numero;
                $numeroRevo[$n]=$pedidos->numeroRevo;
                $fecha[$n]=$pedidos->fecha;
                $hora[$n]=$pedidos->hora;
                $subtotal[$n]=$pedidos->subtotal;
                //$descuento[$n]=$pedidos->descuento;
                //$monedero[$n]=$pedidos->monedero;
                $total[$n]=$pedidos->total;
                $envio[$n]=$pedidos->envio;
                $metodo[$n]=$pedidos->metodo;
                $anulado[$n]=$pedidos->anulado;
                $estadoPago[$n]=$pedidos->estadoPago;
                $n++;
            }
        }

    }	
    
}	

$database->freeResults();  

$json=array("valid"=>$checking,"idCliente"=>$idCliente,"tratamiento"=>$tratamiento,"apellidos"=>$apellidos,"nombre"=>$nombre,"email"=>$email,"telefono"=>$telefono,"publicidad"=>$publicidad,"monedero"=>$monedero,"nacimiento"=>$nacimiento,"id"=>$id,"numero"=>$numero,"numeroRevo"=>$numeroRevo,"fecha"=>$fecha,"hora"=>$hora,"subtotal"=>$subtotal,"total"=>$total,"envio"=>$envio,"metodo"=>$metodo,"canal"=>$canal,"estadoPago"=>$estadoPago,"anulado"=>$anulado);
ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("zz-leeclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
