<?php
/*
 *
 * Archivo: addpedido.php
 *
 * Version: 1.0.1
 * Fecha  : 11/10/2023
 * Se usa en :comprar.js ->terminarcompra() 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


$array = json_decode(json_encode($_POST), true);

$carrito=$array['carrito'];


$domicilio=$array['domicilio'];

$tratamiento=$array['tratamiento'];
$publicidad=0;
if (!empty($array['publicidad'])){
    $publicidad=$array['publicidad'][0];
}
$nombre=$array['nombre'];

$apellidos=$array['apellidos'];
$telefono=$array['telefono'];
$email=$array['email'];
$envio=$array['envio'];
$tarjeta=$array['tarjeta'];
$cliente=$array['cliente'];
$portes=floatval($array['portes']);
$descuento=floatval($array['descuento']);
$tipo_descuento=$array['tipo_descuento'];
$monedero=floatval($array['monedero']);

$importe_fidelizacion=$array['importe_fidelizacion'];
$hora=$array['hora'];
$canal=$array['canal'];

$subtotal=round($array['subtotal'],2);
$total=round($array['total'],2);
$cupon=round($array['cupon'],2);

$comentario=$array['comentario'];
$checking=false;

$OrderId=generate_string(8);

$order['tratamiento']=$tratamiento;
$order['nombre']=$nombre;
$order['apellidos']=$apellidos;
$order['domicilio']=$domicilio;
$order['telefono']=$telefono;
$order['email']=$email;
$order['metodo']=$envio;
$order['tarjeta']=$tarjeta;
$order['cliente']=$cliente;
$order['portes']=$portes;
$order['descuento']=$descuento;
$order['tipo_descuento']=$tipo_descuento;
$order['codigocupon']=$array['codigocupon'];
$order['cupon']=$cupon;
$order['monedero']=$monedero;
$order['importe_fidelizacion']=$importe_fidelizacion;
$order['hora']=$hora;
$order['canal']=$canal;
$order['subtotal']=$subtotal;
$order['canal']=$canal;
$order['comentario']=$comentario;
$order['total']=$total;
$order['pedido']=$OrderId;
$order['publicidad']=$publicidad;




// Buscar impuestos

$sql="SELECT ivaEnvio as iva, idEnvio AS idEnvio FROM opcionescompra WHERE id=1;";


$db = DataBase::getInstance();  
$db->setQuery($sql);  
$ivadeEnvio = $db->loadObjectList();  
$db->freeResults();
$ivaEnvio=$ivadeEnvio[0]->iva;
$idEnvio=$ivadeEnvio[0]->idEnvio;

$order['ivaenvio']=$ivaEnvio;
$order['idenvio']=$idEnvio;

// Buscar impuestos
$sql="SELECT id, nombre, porcentaje FROM impuestos ORDER BY porcentaje;";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    while ($impuestosGenerales = $result->fetch_object()) {
        $porcentajeImpuesto[]=$impuestosGenerales->porcentaje;
        $baseImpuesto[]=0;
        $ivaImpuesto[]=0;
    }
}
$database->freeResults();  

// añadir modificadores al precio
for ($x=0;$x<count($carrito);$x++){
    $carrito[$x]['descuento']=0;
    //$carrito[$x]['precio_sin']=$carrito[$x]['precio'];
    $suma_mod=0;
    if (isset($carrito[$x]['modificadores'])) {
        for($j=0;$j<count($carrito[$x]['modificadores']);$j++){
            $suma_mod+=$carrito[$x]['modificadores'][$j]['precio'];
        }
    }
    if (isset($carrito[$x]['elmentosMenu'])) {
        for($j=0;$j<count($carrito[$x]['elmentosMenu']);$j++){
            $suma_mod+=($carrito[$x]['elmentosMenu'][$j]['precio']*$carrito[$x]['elmentosMenu'][$j]['cantidad']);
        }
    }
    $carrito[$x]['precio']=$carrito[$x]['precio_sin']+$suma_mod;
    
    $carrito[$x]['subtotal']=$carrito[$x]['cantidad']*$carrito[$x]['precio'];
    
}

//logica descuentos
    
   //   1 --> % Dto. Global
   //   2 --> % Dto. Producto
   //   3 --> Envío Gratis
   //   4 --> Importe descuento

if ($descuento > 0 || $monedero > 0) {
    // aplicar descuento
    $adescontar=0;
    if ($tipo_descuento!=""){
        $tipo = explode("#", $tipo_descuento);
        if ($tipo[0]=='3'){ // Envío Gratis
            
            $descuento=$descuento-$portes;
            $portes=0;
        }
        if ($tipo[0]=='4'){ // Importe descuento
            $adescontar=$tipo[1];

        }
        if ($tipo[0]=='1'){ // Dto. Global
            $adescontar=round($subtotal*$tipo[1]/100,2);
        }
        if ($tipo[0]=='2'){ // Dto. Producto
            for ($x=0;$x<count($carrito);$x++){
                if ($carrito[$x]['id']==$tipo[2]){
                    $carrito[$x]['descuento']=round($carrito[$x]['cantidad']*$carrito[$x]['precio']*$tipo[1]/100,2);
                }
                $adescontar=0;
            }
        }
    }
    if($monedero>0) {
        $adescontar+=$monedero;
    }
    if($adescontar>0) {
        // repartir descuento según subtotal
        $suma_descuentos=0;
        /*
         for ($x=0;$x<count($carrito);$x++){
            $carrito[$x]['descuento']=round(calcula_descuento($adescontar,$carrito[$x],$subtotal),2);
            $suma_descuentos+=$carrito[$x]['descuento'];
         }
         */
    }  
}


// calculo de base e iva

$sumadesivass=0;   
for ($x=0;$x<count($carrito);$x++){
    for ($n=0;$n<count($porcentajeImpuesto);$n++){
        if ($carrito[$x]['iva']==$porcentajeImpuesto[$n]) {
            $baseImpuesto[$n]+=calcula_base($carrito[$x]['subtotal']-$carrito[$x]['descuento'],$porcentajeImpuesto[$n]);       $ivaImpuesto[$n]=calcula_iva($baseImpuesto[$n],$porcentajeImpuesto[$n]);
            $sumadesivass+=$ivaImpuesto[$n];
        }
    }
}

if ($portes>0){
    $base_portes=round($portes/(1+($ivaEnvio/100)),2);
    $iva_portes=round($base_portes*$ivaEnvio/100,2);
    for ($h=0;$h<count($porcentajeImpuesto);$h++){ 
        if ($porcentajeImpuesto[$h]==$ivaEnvio){
            $baseImpuesto[$h]+=$base_portes;
            $ivaImpuesto[$h]+=$iva_portes;
        }
    }  
}

$total=$subtotal-$descuento-$monedero+$portes;  
$sumadescuentos=0;
$sumadeivas=0;   

for ($x=0;$x<count($carrito);$x++){
    $sumadescuentos+=$carrito[$x]['descuento'];
    $carrito[$x]['nuevo_subtotal']=$carrito[$x]['subtotal']-$carrito[$x]['descuento'];
    $carrito[$x]['nuevo_precio']=$carrito[$x]['nuevo_subtotal']/$carrito[$x]['cantidad'];
    $carrito[$x]['base']=calcula_base($carrito[$x]['nuevo_subtotal'],$carrito[$x]['iva']);
    $carrito[$x]['iva_calculado']=calcula_iva($carrito[$x]['base'],$carrito[$x]['iva']);
    $sumadeivas+=$carrito[$x]['iva_calculado'];  
}

for ($x=0;$x<count($carrito);$x++){
    //{"id":"107","nombre":"Kusi","precio":"10.00","cantidad":"1","iva":"10.00","img":"https://fitifiti.food2home.es/webapp/img/revo/T81d6HUGio.png","comentario":"","menu":"0","elmentMenu":"0","descuento":0,"subtotal":0,"nuevo_subtotal":0,"nuevo_precio":0,"base":0,"iva_calculado":0}
    $carrito[$x]['iva_calculado']=sprintf('%0.2f',$carrito[$x]['iva_calculado']);
    $carrito[$x]['base']=sprintf('%0.2f',$carrito[$x]['base']);
    $carrito[$x]['nuevo_precio']=sprintf('%0.2f',$carrito[$x]['nuevo_precio']);
    $carrito[$x]['nuevo_subtotal']=sprintf('%0.2f',$carrito[$x]['nuevo_subtotal']);
    $carrito[$x]['subtotal']=sprintf('%0.2f',$carrito[$x]['subtotal']);
    $carrito[$x]['descuento']=sprintf('%0.2f',$carrito[$x]['descuento']);
    $carrito[$x]['precio']=sprintf('%0.2f',$carrito[$x]['precio']);
}

$order['carrito']=$carrito;

$suma_subtotal=0;
$suma_impuestos=0;
$suma_total=0;

$order['fecha']=date('Y-m-d H:i:s');

$sql="INSERT INTO pedidos (numero,numeroRevo,fecha,hora,cliente,subtotal,impuestos,portes,descuento,tipo_descuento,cupon, codigoCupon, monedero, importe_fidelizacion, total,metodoEnvio,metodoPago,estadoPago,canal,comentario) VALUES ('".$OrderId."', '0', CURRENT_TIMESTAMP, '".$hora."',".$cliente.",".$subtotal.",".$sumadeivas.",".$portes.",".$descuento.",'".$tipo_descuento."','".$cupon."','".$array['codigocupon']."',".$monedero.",".$importe_fidelizacion.",".$total.",".$envio.",".$tarjeta.",0,".$canal.",'".$comentario."');";

$order['fecha']=date('Y-m-d H:i:s');

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) { 
    $checking=true;
    $sql="SELECT id FROM pedidos WHERE numero='".$OrderId."';";
    $database->setQuery($sql);
    $resulta = $database->execute();
    $pedido = $resulta->fetch_object();  
    $idPedido=$pedido->id;
}
$database->freeResults();

if ($cliente!=0){   
   if($importe_fidelizacion>0){
       //fwrite($file, "Fidelizacion ". PHP_EOL);
        $importe_fide=0;
        $sql="UPDATE usuarios_app SET monedero=monedero+".($importe_fidelizacion-$monedero)." WHERE id=".$cliente.";";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $database->freeResults();
    }
}
//$idPedido
if (isset($idPedido)) {
    // lineas pedido
    
    for ($x=0;$x<count($carrito);$x++){     
        $articulo= explode ('-',$carrito[$x]['id']);
        $idarticulo=$articulo[0];
        $descripcion=$carrito[$x]['nombre'];
        $modificador="";
        $carrito[$x]['id']=$idarticulo;
        $sql="INSERT INTO pedidos_lineas (idPedido,idArticulo,descripcion,modificadores,canidad,precio,impuesto,menu,comentario) VALUES (".$idPedido.",".$idarticulo.",'".$descripcion."','".$modificador."',".$carrito[$x]['cantidad'].",".$carrito[$x]['precio_sin'].",".$carrito[$x]['iva'].",".$carrito[$x]['menu'].",'".$carrito[$x]['comentario']."');"; 
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
                    $sql="INSERT INTO pedidos_lineas_menu (idLinea,idArticulo,descripcion,cantidad,precio,impuesto) VALUES (".$idLineaPedido.", ".$carrito[$x]['elmentosMenu'][$j]['id'].", '".$carrito[$x]['elmentosMenu'][$j]['nombre']."', ".$carrito[$x]['elmentosMenu'][$j]['cantidad'].", '".$carrito[$x]['elmentosMenu'][$j]['precio']."', '".$carrito[$x]['elmentosMenu'][$j]['iva']."');";
                    
                    $database->setQuery($sql);
                    $resultaEM = $database->execute();
                }
            }
            // modificadores
            if (isset($carrito[$x]['modificadores'])) {
                for($j=0;$j<count($carrito[$x]['modificadores']);$j++){
                    
                    $sql="INSERT INTO pedidos_lineas_modificadores (idLineaPedido,idModificador,descripcion,precio) VALUES (".$idLineaPedido.",'".$carrito[$x]['modificadores'][$j]['id']."','".$carrito[$x]['modificadores'][$j]['nombre']."','".$carrito[$x]['modificadores'][$j]['precio']."');"; 
                    
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
    // domicilio si envio=1

    if ($envio<2){
        //add domicilio
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
    // Datos del cliente si es 0
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

    }
    // cupones
    if ($array['idcupon']>0){
        if (($array['idcupon']==1)||($array['idcupon']==2)){
            $sql='UPDATE cuponesespeciales SET usado="1", importe="'.$array['cupon'].'" WHERE codigo="'.$array['codigocupon'].'" AND usuario="'.$cliente.'";';
        }
        else {
            $sql='INSERT INTO cupones (codigo, usuario, importe) VALUES ("'.$array['codigocupon'].'", "'.$cliente.'", "'.$array['cupon'].'");';
        }
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
    
}
if ($checking){
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
}



$json=array("valid"=>$checking,"order"=>$OrderId,"idpedido"=>$idPedido);

ob_end_clean();
echo json_encode($json); 


function calcula_descuento($totaldescuento,$elemento,$total) {
    $sub=$elemento['subtotal'];
    $suma_mod=0;
    //$elemento['subtotal']
    
    if (isset($elemento['modificadores'])) {
        for($j=0;$j<count($elemento['modificadores']);$j++){
            $suma_mod+=$elemento['modificadores'][$j]['precio'];
        }
    }
    if (isset($elemento['elmentosMenu'])) {
        for($j=0;$j<count($elemento['elmentosMenu']);$j++){
            $suma_mod+=($elemento['elmentosMenu'][$j]['precio']*$elemento['elmentosMenu'][$j]['cantidad']);
        }
    }
    
    $descuento=$totaldescuento*($elemento['subtotal']+$suma_mod)/$total;
    return round($descuento,2);
}

function calcula_base($total,$porcentaje) {
    $base=$total/(1+($porcentaje/100));
    return round($base,2);
}


function calcula_iva($base,$porcentaje) {
    $iva=$base*$porcentaje/100;
    return round($iva,2);
}

function generate_string($strength = 8) {
    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[random_int(0, $input_length - 1)];
        // random_int fot php7
        $random_string .= $random_character;
    }
 
    return $random_string;
}
 

?>
