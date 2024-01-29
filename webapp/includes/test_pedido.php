<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";

echo 'test<br>';


$carrito=array(
    array(
        'id'=> 107,
        'nombre' =>"Kusi",
        'precio' => 10,
        'precio_sin' => 10,
        'cantidad' => 1,
        'iva'=>'10.00'

        ),
    array(
        'id'=> 110,
        'nombre' =>"Chara",
        'precio' => 11,
        'precio_sin' => 11,
        'cantidad' => 1,
        'iva'=>'10.00'

        )
    
    );





$portes=round(floatval('2'),2);
$descuento=round(floatval(2.1));
$tipo_descuento='1#10';
$monedero=0;

$subtotal=21;
$total=20.9;



$order['portes']=$portes;
$order['descuento']=$descuento;
$order['tipo_descuento']=$tipo_descuento;

$order['monedero']=0;
$order['importe_fidelizacion']=0;

$order['subtotal']=$subtotal;

$order['total']=$total;






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

$sql="SELECT id, nombre, porcentaje FROM impuestos ORDER BY porcentaje;";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$impuestosGenerales = $db->loadObjectList();  
$db->freeResults();


for ($n=0;$n<count($impuestosGenerales);$n++){
    $porcentajeImpuesto[$n]=$impuestosGenerales[$n]->porcentaje;  
    $baseImpuesto[$n]=0;
    $ivaImpuesto[$n]=0;
}    


for ($x=0;$x<count($carrito);$x++){
    $carrito[$x]['descuento']=0;
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
    //$carrito[$x]['precio']+=$suma_mod;
    
    $carrito[$x]['subtotal']=$carrito[$x]['cantidad']*$carrito[$x]['precio_sin'];
    
}


//file_put_contents('carrito_detalle.txt', print_r($carrito, true));



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
echo "A descontar:".$adescontar;
echo "<hr>";    

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

          

         for ($x=0;$x<count($carrito);$x++){
             //fwrite($file, " art: ". $carrito[$x]['nombre'] . " (".$carrito[$x]['descuento'].")".PHP_EOL);

                 $carrito[$x]['descuento']=round(calcula_descuento($adescontar,$carrito[$x],$subtotal),2);
                 //fwrite($file, " art: ". $carrito[$x]['nombre'] . " (".$carrito[$x]['descuento'].")".PHP_EOL); 

echo $carrito[$x]['nombre'].": Descuento-> ".$carrito[$x]['descuento']."<br>";

             $suma_descuentos+=$carrito[$x]['descuento'];


         }


    }
    
}


// calculo de base e iva

$sumadesivass=0;   



for ($x=0;$x<count($carrito);$x++){
    for ($n=0;$n<count($porcentajeImpuesto);$n++){
        if ($carrito[$x]['iva']==$porcentajeImpuesto[$n]) {
            $baseImpuesto[$n]+=calcula_base($carrito[$x]['subtotal']-$carrito[$x]['descuento'],$porcentajeImpuesto[$n]);
            $ivaImpuesto[$n]=calcula_iva($baseImpuesto[$n],$porcentajeImpuesto[$n]);
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
$total=round($subtotal-$descuento-$monedero+$portes,2);
    
    
$sumadescuentos=0;
 $sumadeivas=0;   
for ($x=0;$x<count($carrito);$x++){
    $sumadescuentos+=$carrito[$x]['descuento'];
    echo $carrito[$x]['nombre'].": subtotal-> ".$carrito[$x]['subtotal']."<br>";
    
    $carrito[$x]['nuevo_subtotal']=$carrito[$x]['subtotal']-$carrito[$x]['descuento'];
    echo $carrito[$x]['nombre'].": nuevo_subtotal-> ".$carrito[$x]['nuevo_subtotal']."<br>";
    
    $carrito[$x]['nuevo_precio']=$carrito[$x]['nuevo_subtotal']/$carrito[$x]['cantidad'];
    echo $carrito[$x]['nombre'].": precio-> ".$carrito[$x]['precio']."<br>";
    echo $carrito[$x]['nombre'].": nuevo_precio-> ".$carrito[$x]['nuevo_precio']."<br>";
    
    $carrito[$x]['base']=calcula_base($carrito[$x]['nuevo_subtotal'],$carrito[$x]['iva']);
    echo $carrito[$x]['nombre'].": base-> ".$carrito[$x]['base']."<br>";
    
    $carrito[$x]['iva_calculado']=calcula_iva($carrito[$x]['base'],$carrito[$x]['iva']);
    
    echo $carrito[$x]['nombre'].": iva_calculado-> ".$carrito[$x]['iva_calculado']."<br>";
    
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


echo "<br>";
echo "<pre>";
var_dump($order);
echo "</pre>";


$suma_subtotal=0;
$suma_impuestos=0;
$suma_total=0;

/*
for ($x=0;$x<count($carrito);$x++){
    if ($carrito[$x]['mod']!='') {
        
        //$carrito[$x]['mod']=json_decode($carrito[$x]['mod']);
        $carrito[$x]['mod'] = json_decode(json_encode($carrito[$x]['mod']),true);
    }
    
}

*/

// https://api.whatsapp.com/send?phone=34111223344 phonenumber&text=message

$datos= json_encode($order,JSON_UNESCAPED_UNICODE);

$order = json_decode($datos);

echo "<br>";
echo "<pre>";
var_dump($order);
echo "</pre>";
echo "<hr>";

$datos='{"tratamiento":"1","nombre":"German","apellidos":"Salazar","domicilio":{"direccion":"Calle Mimbron nº4","complementario":"Calle Mimbron nº4 609934835","cod_postal":"14940","poblacion":"Cabra","provincia":"Córdoba","envio":"2.00","minimo":"7.50"},"telefono":"609934835","email":"german@trinomusic.com","metodo":"1","tarjeta":"2","cliente":"118","portes":2,"descuento":2.1,"tipo_descuento":"1#10","codigocupon":"BIENVENIDA","cupon":"2.1","monedero":0,"importe_fidelizacion":"0","hora":"22:30","canal":"1","subtotal":"21","comentario":"","total":"20.9","pedido":"SM8J5KYI","publicidad":"1","ivaenvio":"10.00","idenvio":"127","carrito":[{"id":"107","nombre":"Kusi","precio":"10.00","cantidad":"1","iva":"10.00","img":"https://fitifiti.food2home.es/webapp/img/revo/T81d6HUGio.png","comentario":"","menu":"0","elmentMenu":"0","descuento":0,"subtotal":0,"nuevo_subtotal":0,"nuevo_precio":0,"base":0,"iva_calculado":0},{"id":"110","nombre":"Chara","precio":"11.00","precio_sin":"11.00","cantidad":"1","iva":"10.00","img":"https://fitifiti.food2home.es/webapp/img/revo/h5LINuaATH.png","menu":"0","elmentMenu":"0","comentario":"","descuento":1.1,"subtotal":11,"nuevo_subtotal":9.9,"nuevo_precio":9.9,"base":9,"iva_calculado":0.9}],"fecha":"2023-11-23 19:21:01"}';

$order = json_decode($datos);

echo "<br>";
echo "<pre>";
var_dump($order);
echo "</pre>";

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
