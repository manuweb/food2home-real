<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Access-Control-Allow-Origin: *");
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


//select pedidos.id, pedidos.numero, pedidos.numeroRevo, pedidos.estadoPago, pedidos.fecha FROM pedidos WHERE fecha like '2023-12-16%' ORDER BY pedidos.fecha DESC; 

//$sql="SELECT pedidos.id, pedidos.numero, pedidos.numeroRevo, pedidos.estadoPago, pedidos.fecha , pedidos.anulado, pedidos.total FROM pedidos  WHERE fecha >= '".date("Y")."-".date("m")."-".date("d")." 00:00:01' ORDER BY `pedidos`.`id` DESC;";
$sql="SELECT pedidos.id, pedidos.numero, pedidos.numeroRevo, pedidos.estadoPago, pedidos.dia , pedidos.anulado, pedidos.total FROM pedidos  WHERE dia = '".date("Y")."-".date("m")."-".date("d") ORDER BY `pedidos`.`id` DESC;";


//

echo "sql:<br>";
echo $sql;
echo "<hr>";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$fechaFinal = date('d-m-Y H:i:s');
$fechaFinalSegundos = strtotime($fechaFinal);
$aquitar = [];
echo "<style> td {border: solid 1px;padding:2px;}</style>";
    echo "<table>";
while ($pedidos = $result->fetch_object()) {
    $fecha=$pedidos->dia;
    $id=$pedidos->id;
    $numero=$pedidos->numero;
    $numeroRevo=$pedidos->numeroRevo;
    $estadoPago=$pedidos->estadoPago;
    $estadoAnula=$pedidos->anulado;
    $importe=$pedidos->total;
    $estado='';
    $color='';

    
    if ($estadoPago==-1){
        $estado='ANULADO';
        $color='style="color:red;"';
    }
    if ($estadoPago==0){
        $estado='PENDIENTE';
        $color='style="color:black;"';
    }
    if ($estadoPago==1){
        $estado='PAGADO';
        $color='style="color:black;"';
    }
    if ($estadoAnula==1){
        $estado='ANULADO';
        $color='style="color:gray;"';
    }
    if ($estadoAnula==2){
        $estado='ES ANULACION';
        $color='style="color:darkorange;"';
    }
    $fechaInicial = date($fecha);
    
    $NuevaFecha = strtotime ( '+1 hour' , strtotime ($fechaInicial) ) ;
    //$fechaInicial = date ( 'Y-m-d H:i:s' , $NuevaFecha); 

    // Las convertimos a segundos
    $fechaInicialSegundos = strtotime($fechaInicial);
    
    
    // Hacemos las operaciones para calcular los dias entre las dos fechas y mostramos el resultado
    $segundos=($fechaFinalSegundos - $fechaInicialSegundos);
    $minutos = $segundos / 60;
    echo "<tr>";
    //if ($minutos>15){
        $aquitar[]=$id;
        echo "<td>".$id."</td><td><b>".$numero."</b></td><td> <b>".$numeroRevo."</b></td><td><span ".$color."><b>".$estado."</b></span></td><td style='text-align: right;'>".$importe."</td> <td><b>". round($minutos)  . "</b> minutos.</td>" ;
    echo "</tr>";
    //}

}

echo "</table>";
/*
for($x=0;$x<count($aquitar);$x++){
    $sql='UPDATE pedidos SET estadoPago=-1 WHERE id='.$aquitar[$x];
    $database->setQuery($sql);
    $result2 = $database->execute();
    if ($result2) {
        echo "Pedido=".$aquitar[$x]. "actualizado<br>";
    }
    
}
*/

$database->freeResults();  

?>
