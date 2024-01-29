<?php
// nuevoPedidoaRevo.php

$idRedsys=0;
$sql="SELECT id, idrevo FROM metodospago WHERE esRedsys=1;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {    
    $redsys = $result->fetch_object();
    $idRedsys=$redsys->idrevo;
}


$sql="SELECT datos FROM orders WHERE idPedido='".$idpedido."';";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$pedido = $result->fetch_object();
$database->freeResults();
$datos=$pedido->datos;
$order = json_decode($datos,JSON_UNESCAPED_UNICODE);
$carrito=$order['carrito'];

$ivaenvio=$order['ivaenvio'];
$idenvio=$order['idenvio'];

$database->freeResults();


$sql="SELECT usuario,token FROM integracion WHERE id=1;"; 



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$integracion = $result->fetch_object();
$database->freeResults();

$token = $integracion->token;
$user= $integracion->usuario;

$sql="SELECT id, nombre, porcentaje FROM impuestos ORDER BY porcentaje;";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result){
    $num_results_impuestos = $result->num_rows;
    if ($num_results_impuestos > 0) {
        while ($impuestosGenerales = $result->fetch_object()) {
            $porcentajeImpuesto[]=$impuestosGenerales->porcentaje;  
            $idImpuesto[]=$impuestosGenerales->id; 
            $baseImpuesto[]=0;
            $ivaImpuesto[]=0;    
        }
    }
}


$database->freeResults();

  

// pasamos los datos del pedido al formato Revo
$sumaivas=0;
$sumabases=0;

for ($x=0;$x<count($carrito);$x++){
    $sumaivas+=$carrito[$x]['iva_calculado'];
    $sumabases+=$carrito[$x]['base'];
    
    $productos[$x]['item_id']=$carrito[$x]['id'];
    $productos[$x]['quantity']=$carrito[$x]['cantidad'];
    
    //$productos[$x]['menuContents']=null;
    if (isset($carrito[$x]['elmentosMenu'] )){
        $mod=[];
        for ($j=0;$j<count($carrito[$x]['elmentosMenu']);$j++){
            $mod[$j]['item_id'] = $carrito[$x]['elmentosMenu'][$j]['id'];
            $mod[$j]['quantity'] = $carrito[$x]['elmentosMenu'][$j]['cantidad'];
            $mod[$j]['name'] = $carrito[$x]['elmentosMenu'][$j]['nombre'];
            $mod[$j]['price'] = $carrito[$x]['elmentosMenu'][$j]['precio'];
        }
        $productos[$x]['menuContents']=$mod;
    }
    //$productos[$x]['subtotal']=$carrito[$x]['base'];
    //$productos[$x]['itemPrice']=$carrito[$x]['nuevo_precio'];
    $productos[$x]['itemPrice']=$carrito[$x]['precio_sin'];
    for ($j=0;$j<$num_results_impuestos;$j++){
        if ($carrito[$x]['iva']==$porcentajeImpuesto[$j]){
            //$productos[$x]['tax']=$idImpuesto[$j];
        }
    }
    if (isset($carrito[$x]['modificadores'] )){
        
        $mod=[];
        for ($j=0;$j<count($carrito[$x]['modificadores']);$j++){
            $mod[$j]['id'] = $carrito[$x]['modificadores'][$j]['id'];
            $mod[$j]['price'] = $carrito[$x]['modificadores'][$j]['precio'];
            
            $mod[$j]['name'] = $carrito[$x]['modificadores'][$j]['nombre'];
        }
        $productos[$x]['modifiers']=$mod;
    }
    
    //$productos[$x]['total']=$carrito[$x]['nuevo_subtotal'];
    //$productos[$x]['taxAmount']=$carrito[$x]['iva_calculado'];
    
    $productos[$x]['notes']=eliminar_acentos($carrito[$x]['comentario']);
    // mirar si portes
    
    
}


if ($order['portes']>0){
        for ($j=0;$j<$num_results_impuestos;$j++){
            if ($ivaEnvio==$porcentajeImpuesto[$j]){
                //$productos[$x]['tax']=$idImpuesto[$j];
            }
        }
        $productos[$x]['item_id']=$idenvio;
        $productos[$x]['quantity']=1;
        //$productos[$x]['menuContents']=null;
        //$tem_base=calcula_base($order['portes'],$ivaenvio);
        //$productos[$x]['subtotal']=$tem_base;
        $productos[$x]['itemPrice']=$order['portes'];
        //$tem_base=calcula_base($portes,$ivaEnvio);
        $//productos[$x]['total']=$order['portes'];
        //$productos[$x]['taxAmount']=calcula_iva($tem_base,$ivaenvio);
        //$productos[$x]['notes']="";
        $sumaivas+=$productos[$x]['taxAmount'];
        $sumabases+=$tem_base;
 }

$orders['notes']=eliminar_acentos($order['comentario'])." || Hora:".$order['hora'];
$orders['contents']=$productos;
$orders['subtotal']=$order['subtotal'];
$orders['total']=round($order['total'],2);

$descuentoGlobal=round($order['descuento']+$order['monedero'],2);
$monedero=round($order['monedero'],2);
$descuento=round($order['descuento'],2);
$descuentoNombre='';


if ($descuento>0){
    $descuentoNombre=' Cupón '.$order['codigocupon'];
}
if ($monedero>0){
    if ($descuento>0){
        $descuentoNombre.=' y Fidelización';
    }
    else {
        $descuentoNombre.='Fidelización';
    }
}
if ($descuentoGlobal>0){
    $orders['orderDiscount']=array(
        'name'  => $descuentoNombre,
        'amount'  => '-'.$descuentoGlobal
    );
}

if ($order['tarjeta']==$idRedsys) { // tarjeta
    $pago['amount']=$order['total'];
    $pago['tip']=0;
    $pago['payment_reference']=$numero;
    $pago['payment_method_id']=$order['tarjeta']; 
    // 1 tarjeta, 2 Efectivo 8 Efectivo a la entrega             
    $orders['payment']=$pago;
}


$customer['name']=$order['nombre']." ".$order['apellidos'];
$customer['email']=$order['email'];
$customer['address']=$order['domicilio']['direccion'];
$customer['city']=$order['domicilio']['poblacion'];
$customer['state']=$order['domicilio']['provincia'];
$customer['postalCode']=$order['domicilio']['cod_postal'];
$customer['phone']=$order['telefono'];
    
$mifecha= date(substr($order['fecha'],0,10)." ". $order['hora'].":00"); 
$NuevaFecha = strtotime ( '-1 hour' , strtotime ($mifecha) ) ; 
$NuevaFecha = date ( 'Y-m-d H:i:s' , $NuevaFecha); 


//$delivery['channel']=22;

    
if ($order['metodo']==1) { //1=enviar, 2=recoger
    $delivery['address']=$order['domicilio']['direccion'];     
    $delivery['city']=$order['domicilio']['poblacion']; 
}

$delivery['phone']=$order['telefono'];


$delivery['date']=$NuevaFecha;


             


$clienttoken=CLIENTTOKEN;
$url=URLREVO.'api/loyalty/orders';

$topost="customer=".json_encode($customer)."&order=".json_encode($orders)."&delivery=".json_encode($delivery);
$header=array(
        'tenant: ' . $user,"Authorization: Bearer ". $token, "client-token: ".$clienttoken
          );


$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => $header,
        'content' => $topost
    )
);


$context = stream_context_create($opts);

$result = file_get_contents($url, true, $context);

$resultado=json_decode($result);

$revoid=$resultado->{'order_id'};


//$revoid=1;


?>
