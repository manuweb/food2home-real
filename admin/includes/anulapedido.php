<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");



    $array = json_decode(json_encode($_POST), true);


//$numero='H137BL0T';
//
$idpedido=$array['idpedido'];
//$idpedido=58;


$checking=false;

$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);


//$estadoPago=$order['estadoPago'];
$numero=$order['pedido'];
$OrderId=generate_string(8);


//$file = fopen("zzz-anula.txt", "w");


$sql="SELECT fecha, hora, cliente, subtotal, impuestos, portes, descuento, tipo_descuento, cupon, codigoCupon, monedero, importe_fidelizacion, total, metodoEnvio, metodoPago, estadoPago, canal FROM pedidos WHERE id=".$idpedido.";";
//fwrite($file, "sql: ". $sql . PHP_EOL);

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $pedido = $result->fetch_object();
    $fecha=$pedido->fecha;
    $hora=$pedido->hora;
    $cliente=$pedido->cliente;
    $subtotal=$pedido->subtotal;
    $impuestos=$pedido->impuestos;
    $portes=$pedido->portes;
    $descuento=$pedido->descuento;
    $tipo_descuento=$pedido->tipo_descuento;
    $cupon=$pedido->cupon;
    $codigoCupon=$pedido->codigoCupon;
    $monedero=$pedido->monedero;
    $importe_fidelizacion=$pedido->importe_fidelizacion;
    $total=$pedido->total;
    $metodoEnvio=$pedido->metodoEnvio;
    $metodoPago=$pedido->metodoPago;
    $estadoPago=$pedido->estadoPago;
    $canal=$pedido->canal;
    $nueva_fecha=date('Y-m-d h:i:s');
    
    $sql="INSERT INTO pedidos (numero,numeroRevo,fecha,hora,cliente,subtotal,impuestos,portes,descuento,tipo_descuento,cupon, codigoCupon, monedero, importe_fidelizacion, total,metodoEnvio,metodoPago,estadoPago,canal,comentario,anulado) VALUES ('".$OrderId."', '1', '".$nueva_fecha."', '".$hora."',".$cliente.",".generaNegativo($subtotal).",".generaNegativo($impuestos).",".generaNegativo($portes).",".generaNegativo($descuento).",'".$tipo_descuento."','".generaNegativo($cupon)."','".$codigoCupon."',".generaNegativo($monedero).",".generaNegativo($importe_fidelizacion).",".generaNegativo($total).",".$metodoEnvio.",".$metodoPago.",1,".$canal.",'ANULACION PEDIDO: ".$numero."',2);";
    
//fwrite($file, "sql: ". $sql . PHP_EOL);


    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $resulta = $database->execute();
    
    if ($resulta) {
        $checking=true;
    }
    $database->freeResults();
}
$database->freeResults();

if ($checking){
    $sql="SELECT id FROM pedidos WHERE numero='".$OrderId."';";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $resulta = $database->execute();
    $pedido = $resulta->fetch_object();  
    $idPedido=$pedido->id;
    $database->freeResults();
    $checking=true; 
//fwrite($file, "sql: ". $sql . PHP_EOL);

}
  
if ($checking){
    $order['pedido']=$OrderId;
    $order['portes']=generaNegativo($order['portes']);
    $order['descuento']=generaNegativo($order['descuento']);
    $order['monedero']=generaNegativo($order['monedero']);
    $order['importe_fidelizacion']=generaNegativo($order['importe_fidelizacion']);
    $order['subtotal']=generaNegativo($order['subtotal']);
    $order['impuestos']=generaNegativo($order['impuestos']);
    $order['total']=generaNegativo($order['total']);
    $fecha_y_hora = date("Y-m-d H:i:s");
    $order['fecha']=$fecha_y_hora;
    $carrito=$order['carrito'];
    
}

if ($checking){
    for ($x=0;$x<count($carrito);$x++){
        //$carrito[$x]['cantidad']=generaNegativo($carrito[$x]['cantidad']);
        $carrito[$x]['cantidad']=$carrito[$x]['cantidad'];
        $carrito[$x]['descuento']=generaNegativo($carrito[$x]['descuento']);
        $carrito[$x]['subtotal']=generaNegativo($carrito[$x]['subtotal']);
        $carrito[$x]['precio']=generaNegativo($carrito[$x]['precio']);
        $carrito[$x]['precio_sin']=generaNegativo($carrito[$x]['precio_sin']);
        $carrito[$x]['nuevo_subtotal']=generaNegativo($carrito[$x]['nuevo_subtotal']);
        $carrito[$x]['base']=generaNegativo($carrito[$x]['base']);
        $carrito[$x]['iva_calculado']=generaNegativo($carrito[$x]['iva_calculado']);
        $carrito[$x]['iva_calculado']=generaNegativo($carrito[$x]['iva_calculado']);

        $articulo= explode ('-',$carrito[$x]['id']);
        $idarticulo=$articulo[0];
        $descripcion=$carrito[$x]['nombre'];
        $modificador="";
        $carrito[$x]['id']=$idarticulo;

        $sql="INSERT INTO pedidos_lineas (idPedido,idArticulo,descripcion,modificadores,canidad,precio,impuesto,menu,comentario) VALUES (".$idPedido.",".$idarticulo.",'".$descripcion."','".$modificador."',".$carrito[$x]['cantidad'].",".$carrito[$x]['precio_sin'].",".$carrito[$x]['iva'].",".$carrito[$x]['menu'].",'".$carrito[$x]['comentario']."');"; 
        //fwrite($file, "sql: ". $sql . PHP_EOL);
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result) { 
            $checking=true;
            $sql="SELECT MAX(id) AS idLineaPedido FROM pedidos_lineas WHERE idPedido='".$idPedido."';";
            $database->setQuery($sql);
            $resulta = $database->execute();
            $linea = $resulta->fetch_object();    
            $idLineaPedido=$linea->idLineaPedido;
            
            if (isset($carrito[$x]['elmentosMenu'])) {
                for($j=0;$j<count($carrito[$x]['elmentosMenu']);$j++){
                    $carrito[$x]['elmentosMenu'][$j]['cantidad']=generaNegativo($carrito[$x]['elmentosMenu'][$j]['cantidad']);
                    
                    $sql="INSERT INTO pedidos_lineas_menu (idLinea,idArticulo,descripcion,cantidad,precio,impuesto) VALUES (".$idLineaPedido.", ".$carrito[$x]['elmentosMenu'][$j]['id'].", '".$carrito[$x]['elmentosMenu'][$j]['nombre']."', ".$carrito[$x]['elmentosMenu'][$j]['cantidad'].", '".$carrito[$x]['elmentosMenu'][$j]['precio']."', '".$carrito[$x]['elmentosMenu'][$j]['iva']."');";
                    
                    $database->setQuery($sql);
                    $resultaEM = $database->execute();
                }
            }
            if (isset($carrito[$x]['modificadores'])) {
                for($j=0;$j<count($carrito[$x]['modificadores']);$j++){
                    $carrito[$x]['modificadores'][$j]['precio']=generaNegativo($carrito[$x]['modificadores'][$j]['precio']);
                    
                    $sql="INSERT INTO pedidos_lineas_modificadores (idLineaPedido,idModificador,descripcion,precio) VALUES (".$idLineaPedido.",'".$carrito[$x]['modificadores'][$j]['id']."','".$carrito[$x]['modificadores'][$j]['nombre']."','".$carrito[$x]['modificadores'][$j]['precio']."');"; 
                    //fwrite($file, "sql: ". $sql . PHP_EOL);

                    $database->setQuery($sql);
                    $resultaEM = $database->execute();
                }
            }

        }
        else {
             $checking=false;
        }
        $database->freeResults();

    }
        
        

}
  
if ($cliente<1){
    //add cliente       
    $sql="INSERT INTO pedidos_clientes (idPedido,tratamiento,nombre,apellidos,telefono,email,publicidad) VALUES (".$idPedido.",'".$tratamiento."','".$nombre."','".$apellidos."','".$telefono."','".$email."','".$publicidad."');"; 
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
    }
    else {
        $checking=false;
    }
    $database->freeResults();
//fwrite($file, "sql: ". $sql . PHP_EOL);

} 
else {  
    //$order['monedero']
    //$order['importe_fidelizacion'
   if($order['importe_fidelizacion']<0){
       //fwrite($file, "Fidelizacion ". PHP_EOL);
        /*
        $elmonedero = new Monedero;
        $saldo=$elmonedero->leeMonedero($order['cliente']);

        $nuevo_monedero=$saldo+($order['importe_fidelizacion']-$order['monedero']);
        $actualizacione=$elmonedero->guardaMonedero($order['cliente'],$nuevo_monedero);
       */
        
        $sql="UPDATE usuarios_app SET monedero=monedero+".($order['importe_fidelizacion']-$order['monedero'])." WHERE id=".$cliente.";";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $database->freeResults();
        
    }
    //fwrite($file, "sql: ". $sql . PHP_EOL);

}
if ($order['metodo']<2){
        //add domicilio
    $domicilio=$order['domicilio'];
    $sql="INSERT INTO pedidos_domicilios (idPedido,direccion,complementario,cod_postal,poblacion,provincia) VALUES (".$idPedido.",'".$domicilio['direccion']."','".$domicilio['complementario']."','".$domicilio['cod_postal']."','".$domicilio['poblacion']."','".$domicilio['provincia']."');"; 
    

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
    }
    else {
        $checking=false;
    }
    $database->freeResults();
}

if ($checking){
    $sql="UPDATE pedidos SET anulado=1 WHERE id=".$idpedido.";";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
    }

    $database->freeResults();
    
    /*
    
    $sql="INSERT INTO orders (idPedido,datos) VALUES (".$idPedido.",'".json_encode($order,JSON_UNESCAPED_UNICODE)."');"; 
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
    }
    else {
        $checking=false;
    }
    $database->freeResults();
    //fwrite($file, "sql: ". $sql . PHP_EOL);
    
    */
}
    

$database->freeResults();  






$json=array("valid"=>$checking, "metodoPago"=>$metodoPago,  "id_antigua"=>$idpedido, "id_nueva"=>$idPedido);

ob_end_clean();
echo json_encode($json); 



function generaNegativo($numero){
    if($numero=NULL){
       $numero = 0;
    }
    $nuevo=0-$numero;
    return $nuevo;
}



//fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
//fclose($file);


/*
fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "numero: ". $array['numero'] . PHP_EOL); 
fwrite($file, "sql: ". $sql . PHP_EOL); 
fclose($file);
*/



?>
