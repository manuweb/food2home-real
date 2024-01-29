<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


//$pagina=2;


$checking=false;

$sql="SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha, count(*) as cantidad, SUM(total) AS suma_total FROM pedidos where estadoPago>=0 GROUP BY DATE_FORMAT(fecha, '%Y-%m-%d') ORDER BY fecha DESC LIMIT 7;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result){
    
    $checking=true;
    $txt="";
    while ($pedidos = $result->fetch_object()) {
        $fecha[]=$pedidos->fecha;
        $cantidad[]=$pedidos->cantidad;
        $suma_total[]=$pedidos->suma_total;
        $txt.="'".$pedidos->fecha."',";
    }
    $txt = substr($txt, 0, -1);
    $sql="SELECT pedidos_lineas.idArticulo, productos.nombre, SUM(pedidos_lineas.canidad) AS TotalVentas FROM pedidos_lineas LEFT JOIN pedidos on pedidos.id=pedidos_lineas.idPedido LEFT JOIN productos on productos.id=pedidos_lineas.idArticulo where DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") GROUP BY pedidos_lineas.idArticulo ORDER BY SUM(pedidos_lineas.canidad) DESC LIMIT 8;";

    $database->setQuery($sql);
    $resulta = $database->execute();
    if ($resulta){
        while ($prod = $resulta->fetch_object()) {
            $nombreP[]=$prod->nombre;
            $cantidadP[]=$prod->TotalVentas;

        }
    }
    $sql="SELECT metodoEnvio, metodoPago FROM pedidos WHERE DATE_FORMAT(fecha, '%Y-%m-%d') IN (".$txt.") ";

    $database->setQuery($sql);
    
    $resultm = $database->execute();
    if ($resultm){
        $cantEnvio=0;
        $cantRecoger=0;
        $cantEfectivo=0;
        $cantTarjetas=0;
        while ($metodo = $resultm->fetch_object()) {
            if ($metodo->metodoEnvio==1){ //enviar
                $cantEnvio++;
            }
            else {
                $cantRecoger++;
            }
            if ($metodo->metodoPago==2){ //efectivo
                $cantEfectivo++;
            }
            else {
                $cantTarjetas++;
            }

        }
    }
}


$database->freeResults();  

$json=array("valid"=>$checking, "fecha"=>$fecha, "cantidad"=>$cantidad, "suma_total"=>$suma_total, "nombreP"=>$nombreP, "cantidadP"=>$cantidadP, "cantEnvio"=>$cantEnvio, "cantRecoger"=>$cantRecoger, "cantEfectivo"=>$cantEfectivo, "cantTarjetas"=>$cantTarjetas);

echo json_encode($json); 



?>
