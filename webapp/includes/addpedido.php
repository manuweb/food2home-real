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
include "../../webapp/MySQL-2/DataBase.class.php";
/*
TRUNCATE `pedidos`;
TRUNCATE `orders`; 
TRUNCATE `tickets`; 
TRUNCATE `pedidos_clientes`;
TRUNCATE `pedidos_domicilios`;
TRUNCATE `pedidos_lineas`;
TRUNCATE `pedidos_lineas_menu`;
TRUNCATE `pedidos_lineas_modificadores`;
*/
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);
//file_put_contents('carrito_detalle.txt', print_r($array, true));

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
//01/34/6789
$dia=$array['dia'];
$dia=substr($dia,6,4).'-'.substr($dia,3,2).'-'.substr($dia,0,2);
$canal=$array['canal'];

$subtotal=$array['subtotal'];
$total=$array['total'];
$cupon=$array['cupon'];

$comentario=eliminaComillas($array['comentario']);
$comentario=eliminaIntros($comentario);
$checking=false;

$llevabolsa=$array['llevabolsa'];

$OrderId=generate_string(8);
//$order['numero']=$OrderId;
//$order['numero']=$OrderId;
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
$order['cupon']=$array['cupon'];
$order['monedero']=$monedero;
$order['importe_fidelizacion']=$importe_fidelizacion;
$order['hora']=$hora;
$order['hora']=$dia;
$order['canal']=$canal;
$order['subtotal']=$subtotal;
$order['canal']=$canal;
$order['comentario']=eliminaIntros($comentario);
$order['total']=$total;
$order['pedido']=$OrderId;
$order['publicidad']=$publicidad;

$domicilio['direccion']=eliminaComillas($domicilio['direccion']);
$domicilio['complementario']=eliminaComillas($domicilio['complementario']);



// idRedsys
$sql="SELECT idrevo FROM metodospago WHERE esRedsys=1;";
$db = DataBase::getInstance();  
$db->setQuery($sql);  
$iesRedsys = $db->loadObjectList();  
$db->freeResults();
$esRedsys=$iesRedsys[0]->idrevo;


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

$subtotal=0;
for ($n=0;$n<count($impuestosGenerales);$n++){
    $porcentajeImpuesto[$n]=$impuestosGenerales[$n]->porcentaje;  
    $baseImpuesto[$n]=0;
    $ivaImpuesto[$n]=0;
}    

$tarjetasRegalo=[];
$j=0;
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
    $subtotal+=$carrito[$x]['subtotal'];
    if($carrito[$x]['menu']==5){
        $nombreT=$carrito[$x]['nombreT'];
        $emailT=$carrito[$x]['emailT'];
        if ($emailT==''){
            $emailT=$order['email'];
        }
        if ($nombreT==''){
            $nombreT=$order['nombre']. ' '.$order['apellidos'];
        }

        for ($h=0;$h<$carrito[$x]['cantidad'];$h++){
            $tarjetasRegalo[]=[
                'uuid'=> generate_string(6)."-".generate_string(6),
                'idPedido'=>0,
                'idRevo'=>0,
                'idProducto'=>$carrito[$x]['id'],
                'nombre'=>$nombreT,
                'email'=>$emailT,
                'precio'=>$carrito[$x]['precio']   
            ];
        }
    }
    $j=$x;
}


if ($llevabolsa=='si'){
    $x=$j+1;
    $carrito[$x]['descuento']=0;
    $carrito[$x]['id']=$array['idBolsa'];
    $carrito[$x]['nombre']=$array['productoBolsa'];
    $carrito[$x]['precio']=$array['precioBolsa'];
    $carrito[$x]['precio_sin']=$array['precioBolsa'];
    $carrito[$x]['subtotal']=$array['precioBolsa'];
    $subtotal+=$carrito[$x]['subtotal'];
    $carrito[$x]['cantidad']=1;
    $carrito[$x]['iva']=0;
    $carrito[$x]['menu']=0;
    $carrito[$x]['comentario']=''; 
}
$order['subtotal']=$subtotal;
$order['total']=$subtotal;

//file_put_contents('carrito_detalle.txt', print_r($carrito, true));

/*
$file = fopen("carrito.txt", "w");
//fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "desc: ". $suma_descuentos . PHP_EOL);
fclose($file);
*/

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

          

         for ($x=0;$x<count($carrito);$x++){
             //fwrite($file, " art: ". $carrito[$x]['nombre'] . " (".$carrito[$x]['descuento'].")".PHP_EOL);
             //if ($carrito[$x]['descuento']==0){
                 $carrito[$x]['descuento']=round(calcula_descuento($adescontar,$carrito[$x],$subtotal),2);
                 //fwrite($file, " art: ". $carrito[$x]['nombre'] . " (".$carrito[$x]['descuento'].")".PHP_EOL); 
             //}
             /*
             else{
                 $carrito[$x]['descuento']+=calcula_descuento($monedero,$carrito[$x]['subtotal'],$subtotal);
                 //fwrite($file, " art: ". $carrito[$x]['nombre'] . " (".$carrito[$x]['descuento'].")".PHP_EOL);
             }
             */

             $suma_descuentos+=$carrito[$x]['descuento'];


         }
                 //fwrite($file, "Suma desc: ". $suma_descuentos . PHP_EOL);


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
$total=$subtotal-$descuento-$monedero+$portes;
$order['subtotal']=$subtotal;
$order['total']=$total;   
    
$sumadescuentos=0;
 $sumadeivas=0;   
for ($x=0;$x<count($carrito);$x++){
    $sumadescuentos+=$carrito[$x]['descuento'];
    $carrito[$x]['nuevo_subtotal']=$carrito[$x]['subtotal']-$carrito[$x]['descuento'];
    $carrito[$x]['nuevo_precio']=$carrito[$x]['nuevo_subtotal']/$carrito[$x]['cantidad'];
    $carrito[$x]['base']=calcula_base($carrito[$x]['nuevo_subtotal'],$carrito[$x]['iva']);
    $carrito[$x]['iva_calculado']=calcula_iva($carrito[$x]['base'],$carrito[$x]['iva']);
    $sumadeivas+=$carrito[$x]['iva_calculado'];  
    
    $carrito[$x]['comentario']=eliminaIntros($carrito[$x]['comentario']);
    $carrito[$x]['comentario']=eliminaComillas($carrito[$x]['comentario']);
}



$order['carrito']=$carrito;

//file_put_contents('zz-pedido.txt', print_r($order, true));


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


$order['fecha']=date('Y-m-d H:i:s');

$sql="INSERT INTO pedidos (numero,numeroRevo,fecha,dia,hora,cliente,subtotal,impuestos,portes,descuento,tipo_descuento,cupon,codigoCupon,monedero,importe_fidelizacion,total,metodoEnvio,metodoPago,estadoPago,canal,comentario,anulado) VALUES ('".$OrderId."', '0', '".$order['fecha']."', '".$dia."', '".$hora."', ".$cliente.", ".$subtotal.", ".$sumadeivas.", ".$portes.",".$descuento.", '".$tipo_descuento."', '".$cupon."', '".$order['codigocupon']."', ".$monedero.", ".$importe_fidelizacion.", ".$total.", ".$envio.", ".$tarjeta.", 0, ".$canal.", '".$comentario."', 0);";

//$order['fecha']=date('Y-m-d H:i:s');

$db = DataBase::getInstance();  
$db->setQuery($sql);  
//$pedidos = $db->loadObjectList();  

if ($db->alter()){
    //fwrite($file, "sql: ".$sql. PHP_EOL);
    $checking=true;
    $sql="SELECT id FROM pedidos WHERE numero='".$OrderId."';";
    
    $db = DataBase::getInstance();  
    $db->setQuery($sql);  
    $pedido = $db->loadObjectList();  
    //$db->freeResults();
    
    //fwrite($file, "sql: ".$sql. PHP_EOL);
    
    $idPedido=$pedido[0]->id;
}
//$db->freeResults();
for ($h=0;$h<count($tarjetasRegalo);$h++){
    $tarjetasRegalo[$h]['idPedido']=$idPedido;
    
    $sql="INSERT INTO tarjetas_regalo (uuid,idPedido,idRevo,idProducto,nombre,precio,email) VALUES ('".$tarjetasRegalo[$h]['uuid']."','".$idPedido."','0','".$tarjetasRegalo[$h]['idProducto']."','".$tarjetasRegalo[$h]['nombre']."','".$tarjetasRegalo[$h]['precio']."','".$tarjetasRegalo[$h]['email']."');";
$file = fopen("zz-pedido-tr.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
    $db = DataBase::getInstance();  
    $db->setQuery($sql);   
    if ($db->alter()){}
    else {}
}

//file_put_contents('zz-pedido-tr.txt', print_r($tarjetasRegalo, true));

if ($cliente!=0){
    //fwrite($file, "Cliente ". PHP_EOL);
    
   if($importe_fidelizacion>0){
       //fwrite($file, "Fidelizacion ". PHP_EOL);
        $importe_fide=0;
       
       //$esRedsys != $tarjeta
       if ($esRedsys != $tarjeta){
            $sql="UPDATE usuarios_app SET monedero=monedero+".($importe_fidelizacion-$monedero)." WHERE id=".$cliente.";";
       
       //fwrite($file, "sql: ".$sql. PHP_EOL);
        
     
            $db = DataBase::getInstance();  
            $db->setQuery($sql); 
       
        
        //$moned = $db->loadObjectList(); 
       
            if ($db->alter()){
                //fwrite($file, "sql OK ". PHP_EOL);
            }
            else {
               //fwrite($file, "sql NO ". PHP_EOL);
           }
        //$db->freeResults();
        
       }
   }
}
//$idPedido
if (isset($idPedido)) {
//if (count($pedidos)>=0) {
    
    
    // lineas pedido

    for ($x=0;$x<count($carrito);$x++){
        
        $articulo= explode ('-',$carrito[$x]['id']);
        $idarticulo=$articulo[0];
        $descripcion=$carrito[$x]['nombre'];
        $modificador="";
        $carrito[$x]['id']=$idarticulo;
        
        

        $sql="INSERT INTO pedidos_lineas (idPedido,idArticulo,descripcion,modificadores,canidad,precio,impuesto,menu,comentario) VALUES (".$idPedido.",".$idarticulo.",'".$descripcion."','".$modificador."',".$carrito[$x]['cantidad'].",".$carrito[$x]['precio_sin'].",".$carrito[$x]['iva'].",".$carrito[$x]['menu'].",'".$carrito[$x]['comentario']."');"; 
        
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        //$lineas = $db->loadObjectList();  
        //$db->freeResults();
         
        //fwrite($file, "sql: ".$sql. PHP_EOL);

        // modificadores
     
        if ($db->alter()){
            $checking=true;
            $sql="SELECT MAX(id) AS idLineaPedido FROM pedidos_lineas WHERE idPedido='".$idPedido."';";
            
            $db = DataBase::getInstance();  
            $db->setQuery($sql);  
            $linea = $db->loadObjectList();
            $db->freeResults();
            
            //fwrite($file, "sql: ".$sql. PHP_EOL);
            
            $idLineaPedido=$linea[0]->idLineaPedido;
            //fwrite($file, "linea: ".$idLineaPedido. PHP_EOL);
            // elementos menu
            if (isset($carrito[$x]['elmentosMenu'])) {
                for($j=0;$j<count($carrito[$x]['elmentosMenu']);$j++){
                    $sql="INSERT INTO pedidos_lineas_menu (idLinea,idArticulo,descripcion,cantidad,precio,impuesto) VALUES (".$idLineaPedido.", ".$carrito[$x]['elmentosMenu'][$j]['id'].", '".$carrito[$x]['elmentosMenu'][$j]['nombre']."', ".$carrito[$x]['elmentosMenu'][$j]['cantidad'].", '".$carrito[$x]['elmentosMenu'][$j]['precio']."', '".$carrito[$x]['elmentosMenu'][$j]['iva']."');";
                    $db = DataBase::getInstance();  
                    $db->setQuery($sql);  
                    //$linea = $db->loadObjectList();
                    //$db->freeResults();
                    //fwrite($file, "sql: ".$sql. PHP_EOL);
                    if ($db->alter()){
                        //fwrite($file, "sql OK ". PHP_EOL);
                    }
                    else {
                        //fwrite($file, "sql NO ". PHP_EOL);
                    }
                    
                }
            }
            // modificadores
            if (isset($carrito[$x]['modificadores'])) {
                for($j=0;$j<count($carrito[$x]['modificadores']);$j++){
                    
                    $sql="INSERT INTO pedidos_lineas_modificadores (idLineaPedido,idModificador,descripcion,precio) VALUES (".$idLineaPedido.",'".$carrito[$x]['modificadores'][$j]['id']."','".$carrito[$x]['modificadores'][$j]['nombre']."','".$carrito[$x]['modificadores'][$j]['precio']."');"; 
                    
                    $db = DataBase::getInstance();  
                    $db->setQuery($sql);  
                    //$modifi = $db->loadObjectList();
                    //$db->freeResults();    
                    if ($db->alter()){
                        //fwrite($file, "sql OK ". PHP_EOL);
                    }
                    else {
                        //fwrite($file, "sql NO ". PHP_EOL);
                    }
                    //fwrite($file, "sql: ".$sql. PHP_EOL);

                }
            }
         }
         else {
             $checking=false;
         }

         
         
    }  
    // domicilio si envio=1

    if ($envio<2){
        //add domicilio
        $sql="INSERT INTO pedidos_domicilios (idPedido,direccion,complementario,cod_postal,poblacion,provincia,lat,lng) VALUES (".$idPedido.",'".$domicilio['direccion']."','".$domicilio['complementario']."','".$domicilio['cod_postal']."','".$domicilio['poblacion']."','".$domicilio['provincia']."',".$domicilio['coordenadas']['lat'].",".$domicilio['coordenadas']['lng'].");"; 
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        //$domi = $db->loadObjectList();
        //$db->freeResults();    
        //fwrite($file, "sql: ".$sql. PHP_EOL);
        


        
        if ($db->alter()){
            $checking=true;
            //fwrite($file, "sql: OK". PHP_EOL);
        }
        else {
            $checking=false;
            //fwrite($file, "sql: NO OK". PHP_EOL);
        }
    }
    // Datos del cliente si es 0
    if ($cliente<1){
        //add cliente       
        
        $sql="INSERT INTO pedidos_clientes (idPedido,tratamiento,nombre,apellidos,telefono,email,publicidad) VALUES (".$idPedido.",'".$tratamiento."','".$nombre."','".$apellidos."','".$telefono."','".$email."','".$publicidad."');"; 
        
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        //$clien = $db->loadObjectList();  
        //$db->freeResults();
        
        //fwrite($file, "sql: ".$sql. PHP_EOL);
        
        if ($db->alter()){
            $checking=true;
            //fwrite($file, "sql: OK". PHP_EOL);
        }
        else {
            $checking=false;
            //fwrite($file, "sql: NO OK". PHP_EOL);
        }
        

    }
    // cupones
    if ($array['idcupon']>0){
        if (($array['idcupon']==1)||($array['idcupon']==2)){
            $sql='UPDATE cuponesespeciales SET usado="1", importe="'.$array['cupon'].'" WHERE codigo="'.$array['codigocupon'].'" AND usuario="'.$cliente.'";';
        }
        else {
            $sql='INSERT INTO cupones (codigo, usuario, importe) VALUES ("'.$array['codigocupon'].'", "'.$cliente.'", "'.$array['cupon'].'");';
        }
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        //$cupones = $db->loadObjectList();  
        //$db->freeResults();
        //fwrite($file, "sql: ".$sql. PHP_EOL);
        
        if ($db->alter()){
            $checking=true;
            //fwrite($file, "sql: OK". PHP_EOL);
        }
        else {
            $checking=false;
            //fwrite($file, "sql: NO OK". PHP_EOL);
        }
        /*
        $file = fopen("cupones.txt", "w");
        fwrite($file, "sql: ". $sql . PHP_EOL);

        fclose($file);
        */
    }
    
}
if ($checking){
   $sql="INSERT INTO orders (idPedido,datos) VALUES (".$idPedido.",'".json_encode($order,JSON_UNESCAPED_UNICODE)."');"; 
    //fwrite($file, "sql: ".$sql. PHP_EOL);  
    $db = DataBase::getInstance();  
    $db->setQuery($sql);   
    if ($db->alter()){
        $checking=true;
        //fwrite($file, "sql: OK". PHP_EOL);
    }
    else {
        $checking=false;
        //fwrite($file, "sql: NO OK". PHP_EOL);
    }
}



$json=array("valid"=>$checking,"order"=>$OrderId,"idpedido"=>$idPedido);
//fwrite($file, "json: ". json_encode($json) . PHP_EOL);

//fclose($file);
ob_end_clean();
echo json_encode($json); 





/*
$file = fopen("carrito.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fclose($file);
*/



//file_put_contents('carrito_detalle.txt', print_r($carrito, true));
//file_put_contents('carrito_domicilio.txt', print_r($carrito_domicilio, true));

/*
function calcula_descuento($totaldescuento,$subtotal,$total) {
    $descuento=$totaldescuento*$subtotal/$total;
    return round($descuento,2);
}
*/

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


function eliminaComillas($texto){
    $texto=str_replace('"', '*', $texto);  
    $texto=str_replace("'", "*", $texto);  
    $texto=remove_emoji($texto);
    return $texto;
}

 function eliminaIntros($texto){
     $sustituye = array("\r\n", "\n\r", "\n", "\r");
    $cadenalimpia = str_replace($sustituye, " - ", $texto);  
     
     return $cadenalimpia;
 }


function remove_emoji(string $string): string
{

    /**
     * @see https://unicode.org/charts/PDF/UFE00.pdf
     */
    $variant_selectors = '[\x{FE00}–\x{FE0F}]?'; // ? - optional

    /**
     * There are many sets of modifiers
     * such as skin color modifiers and etc
     *
     * Not used, because this range already included
     * in 'Match Miscellaneous Symbols and Pictographs' range
     * $skin_modifiers = '[\x{1F3FB}-\x{1F3FF}]';
     *
     * Full list of modifiers:
     * https://unicode.org/emoji/charts/full-emoji-modifiers.html
     */

    // Match Enclosed Alphanumeric Supplement
    $regex_alphanumeric = "/[\x{1F100}-\x{1F1FF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_alphanumeric, '', $string);

    // Match Miscellaneous Symbols and Pictographs
    $regex_symbols = "/[\x{1F300}-\x{1F5FF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_symbols, '', $clear_string);

    // Match Emoticons
    $regex_emoticons = "/[\x{1F600}-\x{1F64F}]$variant_selectors/u";
    $clear_string = preg_replace($regex_emoticons, '', $clear_string);

    // Match Transport And Map Symbols
    $regex_transport = "/[\x{1F680}-\x{1F6FF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_transport, '', $clear_string);

    // Match Supplemental Symbols and Pictographs
    $regex_supplemental = "/[\x{1F900}-\x{1F9FF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_supplemental, '', $clear_string);

    // Match Miscellaneous Symbols
    $regex_misc = "/[\x{2600}-\x{26FF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_misc, '', $clear_string);

    // Match Dingbats
    $regex_dingbats = "/[\x{2700}-\x{27BF}]$variant_selectors/u";
    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

    return $clear_string;
}


?>
