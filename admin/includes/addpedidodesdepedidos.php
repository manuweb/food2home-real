<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");



$array = json_decode(json_encode($_POST), true);

//file_put_contents('zz-pedido_detalle_post.txt', print_r($array, true));

$checking=false;

$nombre=$array['nombre'];
$apellidos=$array['apellidos'];
$telefono=$array['telefono'];
$email=$array['email'];
$idcliente=$array['idcliente'];
$domicilio=[
    'direccion'=>$array['direccion'],
    'cod_postal'=>$array['cod_postal'],
    'poblacion'=>$array['poblacion'],
    'provincia'=>$array['provincia'] 
];

$cliente=$array['idcliente'];

if ($idcliente=='N'){
    $cliente=0;
}
if ($idcliente=='Nuevo'){
    $cliente=0;
}




$OrderId=generate_string(8);

$order['pedido']=$OrderId;    
$order['tratamiento']='1';
$order['nombre']=$nombre;
$order['apellidos']=$apellidos;
$order['domicilio']=$domicilio;
$order['telefono']=$telefono;
if (isset($array['email'])){
    $order['email']=$array['email'];
}
else {
    $order['email']='';
}
$order['metodo']=$array['modo'];
$order['tarjeta']=2;
$order['cliente']=$cliente;
$order['portes']=$array['portes'];
$order['descuento']=0;
$order['tipo_descuento']='';
$order['codigocupon']='';
$order['cupon']=0;
$order['monedero']=0;
$order['importe_fidelizacion']=0;
$order['hora']=$array['hora_pedido'];
$fecha=str_replace('/', '-', $array['fecha_pedido']);
$order['dia']=date('Y-m-d', strtotime($fecha));
$order['fecha']=date('Y-m-d H:i:s');
$order['subtotal']=$array['subtotal'];
$order['comentario']=$array['comentario'];
$order['total']=$order['subtotal']+$order['portes'];
$order['publicidad']=0;

$carrito=$array['carrito'];

for ($x=0;$x<count($carrito);$x++){
    $carrito[$x]['comentario']=eliminaIntros($carrito[$x]['comentario']);
    $carrito[$x]['comentario']=eliminaComillas($carrito[$x]['comentario']);
}

$order['carrito']=$carrito;

//file_put_contents('zz-pedido_detalle.txt', print_r($order, true));



//$file = fopen("zz-pedido.txt", "w");


$sql="INSERT INTO pedidos (numero,numeroRevo,fecha,dia,hora,cliente,subtotal,impuestos,portes,descuento,tipo_descuento,cupon, monedero,importe_fidelizacion,total,metodoEnvio,metodoPago,estadoPago,canal,comentario,anulado) VALUES ('".$OrderId."', '0', '".$order['fecha']."', '".$order['dia']."', '".$order['hora']."', ".$cliente.", ".$order['subtotal'].", 0, ".$order['portes'].",".$order['descuento'].", '".$order['tipo_descuento']."', '".$order['cupon']."', ".$order['monedero'].", ".$order['importe_fidelizacion'].", ".$order['total'].", ".$order['metodo'].", ".$order['tarjeta'].", 0, 1, '".$order['comentario']."', 0);";

//fwrite($file, "sql: ". $sql . PHP_EOL);

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
 //fwrite($file, "sql: ". $sql . PHP_EOL);
// Verificar si se obtuvieron resultados
if ($result) { 
    $checking=true;
}
else {
    $checking=false;
}
if ($checking) {
    
    $sql="SELECT id FROM pedidos WHERE numero='".$OrderId."';";
    
    $database->setQuery($sql);
    $resultid = $database->execute();
  
    if ($resultid) { 
        $checking=true;
        $pedido = $resultid->fetch_object() ;
        $idPedido=$pedido->id;
    }
    else {
        $checking=false;
    }
    
}


//fwrite($file, "sql: ". $sql . PHP_EOL);
//fwrite($file, "idPedido: ". $idPedido . PHP_EOL);
//fclose($file);


if (isset($idPedido)) {
    
    
    
    // lineas pedido
    for ($x=0;$x<count($carrito);$x++){
        
        $sql="INSERT INTO pedidos_lineas (idPedido,idArticulo,descripcion,modificadores,canidad,precio,impuesto,menu,comentario) VALUES (".$idPedido.",".$carrito[$x]['id'].",'".$carrito[$x]['nombre']."','',".$carrito[$x]['cantidad'].",".$carrito[$x]['precio_sin'].",0,".$carrito[$x]['menu'].",'".$carrito[$x]['comentario']."');";
        
        //fwrite($file, "sql: ". $sql . PHP_EOL);
        
        $database->setQuery($sql);
        $resultcarrito = $database->execute();
     
        if ($resultcarrito){
            $checking=true;
            $sql="SELECT MAX(id) AS idLineaPedido FROM pedidos_lineas WHERE idPedido='".$idPedido."';";
            $database->setQuery($sql);
            $resultmax = $database->execute();
            $linea = $resultmax->fetch_object() ;  
            $idLineaPedido=$linea->idLineaPedido;
            
            //fwrite($file, "sql: ". $sql . PHP_EOL);
            //fwrite($file, "idLineaPedido: ". $idLineaPedido . PHP_EOL);
            
            if (isset($carrito[$x]['modificadores'])) {
                for($j=0;$j<count($carrito[$x]['modificadores']);$j++){            
                    $sql="INSERT INTO pedidos_lineas_modificadores (idLineaPedido,idModificador,descripcion,precio) VALUES (".$idLineaPedido.",'".$carrito[$x]['modificadores'][$j]['id']."','".$carrito[$x]['modificadores'][$j]['nombre']."','".$carrito[$x]['modificadores'][$j]['precio']."');"; 
                    $database->setQuery($sql);
                    $resulmod = $database->execute();
                    
                    //fwrite($file, "sql: ". $sql . PHP_EOL);
                }
             }
        }
        else {
            $checking=false;
        }
        
        
    }

    if ($order['metodo']==1){
        //add domicilio
        $sql="INSERT INTO pedidos_domicilios (idPedido,direccion,complementario,cod_postal,poblacion,provincia) VALUES (".$idPedido.",'".$order['domicilio']['direccion']."','','".$order['domicilio']['cod_postal']."','".$order['domicilio']['poblacion']."','".$order['domicilio']['provincia']."');"; 
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result) { 
            $checking=true;    
        }
        else {
            $checking=false;
        }
    }
    
    if ($cliente<1){
        $sql="INSERT INTO pedidos_clientes (idPedido,tratamiento,nombre,apellidos,telefono,email,publicidad) VALUES (".$idPedido.",'".$order['tratamiento']."','".$order['nombre']."','".$order['apellidos']."','".$order['telefono']."','".$order['email']."','".$order['publicidad']."');"; 
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result) { 
            $checking=true;    
        }
        else {
            $checking=false;
        }
    }
    

}


//fclose($file);


$database->freeResults();  




$json=array("valid"=>$checking,"idPedido"=>$idPedido);

ob_end_clean();
echo json_encode($json); 


//file_put_contents('zz-pedido_detalle.txt', print_r($order, true));


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
