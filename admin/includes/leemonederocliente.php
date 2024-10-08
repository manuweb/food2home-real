<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


$cliente=$array['cliente'];

$checking=false;

$sql="SELECT 'P' as pedido, pedidos.id, pedidos.numero, pedidos.dia as fecha, pedidos.subtotal, pedidos.monedero, pedidos.importe_fidelizacion, pedidos.total, pedidos.anulado, '' as nick FROM pedidos WHERE ((pedidos.estadoPago>=0 AND pedidos.metodoPago=2) OR (pedidos.estadoPago=1 AND pedidos.metodoPago!=2)) AND pedidos.cliente=".$cliente." UNION SELECT 'M' as pedido, monedero.id, 0 as numero, monedero.fecha, 0 as subtotal, (CASE WHEN monedero.importe>0 THEN ABS(monedero.importe) ELSE 0 END) AS monedero, (CASE WHEN monedero.importe<0 THEN ABS(monedero.importe) ELSE 0 END) as importe_fidelizacion, 0 as total, '0' as anulado, usuarios.nick FROM monedero LEFT JOIN usuarios on usuarios.id=monedero.usuario WHERE monedero.cliente=".$cliente." ORDER BY fecha;";




$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result->num_rows>0) {
    $checking=true;
    $elsaldo=0;
    while ($elmonedero = $result->fetch_object()){
        
    
        $pedido[]=$elmonedero->pedido;
        $id[]=$elmonedero->id;
        $numero[]=$elmonedero->numero;
        $fecha[]=$elmonedero->fecha;
        $subtotal[]=$elmonedero->subtotal;
        $anulado[]=$elmonedero->anulado;
        $usado[]=$elmonedero->monedero;
        $acumulado[]=$elmonedero->importe_fidelizacion;
        $total[]=$elmonedero->total;
        $nick[]=$elmonedero->nick;
        $elsaldo=$elsaldo+$elmonedero->importe_fidelizacion - $elmonedero->monedero;
        $saldo[]= $elsaldo;  
    }
}	

$database->freeResults();  

$json=array("valid"=>$checking,"pedido"=>$pedido,"id"=>$id,"numero"=>$numero,"fecha"=>$fecha,"subtotal"=>$subtotal,"total"=>$total,"usado"=>$usado,"acumulado"=>$acumulado,"saldo"=>$saldo,"anulado"=>$anulado,"nick"=>$nick);
ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("zz-leeclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
