<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";

include "tiempo.php";



$sql="SELECT pedidos.id, pedidos.numero, pedidos.numeroRevo, pedidos.cliente, pedidos.estadoPago, pedidos.fecha, pedidos.importe_fidelizacion, pedidos.monedero FROM pedidos  WHERE fecha >= '".date("Y")."-".date("m")."-".date("d")." 00:00:01' ORDER BY `pedidos`.`id` DESC;";

//echo "sql:<br>";
//echo $sql;
//echo "<hr>";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$fechaFinal = date('d-m-Y H:i:s');
$fechaFinalSegundos = strtotime($fechaFinal);


$aquitar = [];

while ($pedidos = $result->fetch_object()) {
    $fecha=$pedidos->fecha;
    $id=$pedidos->id;
    $numero=$pedidos->numero;
    $numeroRevo=$pedidos->numeroRevo;
    $estadoPago=$pedidos->estadoPago;
    $importe_fidelizacion=$pedidos->importe_fidelizacion;
    $monedero=$pedidos->monedero;
    $cliente=$pedidos->cliente;
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
    
    $fechaInicial = date($fecha);
    
    $NuevaFecha = strtotime ( '+1 hour' , strtotime ($fechaInicial) ) ;
    //$fechaInicial = date ( 'Y-m-d H:i:s' , $NuevaFecha); 

    // Las convertimos a segundos
    $fechaInicialSegundos = strtotime($fechaInicial);
    
    
    // Hacemos las operaciones para calcular los dias entre las dos fechas y mostramos el resultado
    $segundos=($fechaFinalSegundos - $fechaInicialSegundos);
    $minutos = $segundos / 60;
    

        echo "(".$id.") nº: <b>".$numero."</b> idRevo: <b>".$numeroRevo."</b> Estado: <span ".$color."><b>".$estado."</b></span> Tiempo: <b>". round($minutos)  . "</b> minutos.<br>" ;
    if ($estadoPago==0){
        if (round($minutos)>$tiempo){
            if ($numeroRevo=='0'){
                $aquitar[]=$id;
                $fidelizacion[]=$importe_fidelizacion;
                $losClientes[]=$cliente;
                $losMonederos[]=$monedero;
            }
        }
    }
}
        
echo "<hr>";
for($x=0;$x<count($aquitar);$x++){

    $sql='UPDATE pedidos SET estadoPago=-1 WHERE id='.$aquitar[$x].' AND numeroRevo="0"';
    echo $sql;
    echo "<hr>";
    $database->setQuery($sql);
    $result2 = $database->execute();
    if ($result2) {
        /*
        if ($fidelizacion[$x]>0){
            $sql2="UPDATE usuarios_app SET monedero=monedero+".($losMonederos[$x]-$fidelizacion[$x])." WHERE id=".$losClientes[$x].";";
            
            $database->setQuery($sql2);
            $result3 = $database->execute();
        }
        */
        echo "Pedido=".$aquitar[$x]. "actualizado<br>";
    }

}

$database->freeResults();  

?>
