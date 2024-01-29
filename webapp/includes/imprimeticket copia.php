<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
/*
 *
 * Archivo: envioemail.php
 *
 * Version: 1.0.1
 * Fecha  : 29/09/2023
 * Se usa en :varios sitios
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include_once "../conexion.php";
include_once "../MySQL/DataBase.class.php";
include_once "../config.php";


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {
    // Se están enviando datos a través del método POST
    //$_POST = json_decode(file_get_contents('php://input'), true);
    $array = json_decode(json_encode($_POST), true);
    $llamada='POST';
    $idpedido=$array['idpedido'];
    $numero=$array['numero'];
    
}
else {
    $llamada='GET';  
}

$checking=false;



/*
$array['idpedido']=9;
$array['numero']='CAS7VAOL';
*/


//$idpedido=$array['idpedido'];
//$numero=$array['numero'];

$sql="SELECT datos FROM orders WHERE idPedido='".$idpedido."';"; 

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$pedido = $result->fetch_object();
$database->freeResults();
$datos=$pedido->datos;
$order = json_decode($datos,JSON_UNESCAPED_UNICODE);
if ($order['cliente']>0){
    $sql="SELECT COUNT(*) AS cantidad FROM pedidos WHERE cliente='".$order['cliente']."';"; 
}
else {
    $sql="SELECT COUNT(*) AS cantidad FROM pedidos_clientes WHERE email='".$order['email']."';"; 
}

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$pedidos = $result->fetch_object();
$numero_pedidos=$pedidos->cantidad;
    //echo "<pre>";
//print_r($order);
    //echo "</pre>";
$carrito=$order['carrito'];
$font_path = __DIR__.'/fonts/Arial.ttf';
$font_path_b = __DIR__.'/fonts/Arial_Bold.ttf';
$font_path_i = __DIR__.'/fonts/ariali.ttf';
$font_path_bi = __DIR__.'/fonts/Arial_Bold_Italic.ttf';


$logo_src="../img/empresa/logo-impresora.png";
//$logo_src="logo-impresora.png";
$logo=imagecreatefrompng($logo_src);
list($logo_w, $logo_h, $type, $attr) = getimagesize($logo_src);

$largo_pedido=0;
for ($x=0;$x<count($carrito);$x++){
    $largo_pedido+=30;
   
    if ($carrito[$x]['comentario'] !=""){
        $largo_pedido+=30;
    }

    if (isset($carrito[$x]['modificadores'])){
        //echo count($carrito[$x]['modificadores']);
        //echo "<br>";
        
        for ($j=0;$j<count($carrito[$x]['modificadores']);$j++){
            $largo_pedido+=30;
        }
       
    }
    if (isset($carrito[$x]['elmentosMenu'])){
        //echo count($carrito[$x]['elmentosMenu'] );
        echo "<br>";
        
        for ($j=0;$j<count($carrito[$x]['elmentosMenu']);$j++){
            $largo_pedido+=30;
        }
       
    }
}
if($order['descuento']>0){
    $largo_pedido+=30;
}
if($order['portes']>0){
    $largo_pedido+=30;
}
if ($order['metodo']==1){
    $lines = explode("\n", wordwrap($order['nombre']." ".$order['apellidos'], 25, "\n"));
    for ($x=0;$x<count($lines);$x++){
        $largo_pedido+=35;
    }
    $lines = explode("\n", wordwrap($order['domicilio']['direccion'], 25, "\n"));
    for ($x=0;$x<count($lines);$x++){
        $largo_pedido+=35;
    }
    if ($order['domicilio']['complementario']!=""){
        $lines = explode("\n", wordwrap($order['domicilio']['complementario'], 25, "\n"));
        for ($x=0;$x<count($lines);$x++){
            $largo_pedido+=35;
        }
    }
}
if($order['monedero']>0){
    $largo_pedido+=25;
}
if($order['cupon']>0){
    $largo_pedido+=25;
}
$margen=10;
$ancho=576;
$alto=950;
$porcentaje_redu=0.7;

$alto+=$largo_pedido;

$ancho_forzado=($ancho-(2*$margen))*$porcentaje_redu;
$pos_x=($ancho*$porcentaje_redu/2)-$margen;
$pos_x=round($pos_x/2);
list($ancho_calculado,$alto_calculado)=redimensionar($logo_src, $ancho_forzado);
$ancho_calculado=round($ancho_calculado);
$alto_calculado=round($alto_calculado);

$ticket = imagecreate($ancho, $alto);

$blanco = imagecolorallocate($ticket, 255, 255, 255);// blanco
$negro = imagecolorallocate($ticket, 0, 0, 0);
// Hacer el fondo transparente
//imagecolortransparent($ticket, $blanco);
imagefill($ticket, 0, 0, $blanco);
//$new_logo = imagecreatetruecolor($ancho_calculado, $alto_calculado);
$new_logo = imagecreate($ancho_calculado, $alto_calculado);

imagecopyresampled($logo, $logo, 0, 0, 0, 0, $ancho_calculado, $alto_calculado, $logo_w, $logo_h);

imagecopy($ticket, $logo, $pos_x, $margen, 0, 0, $ancho, $alto);
imagefilledrectangle ($ticket, ($pos_x+$ancho_calculado), 0, $ancho, $alto, $blanco);
imagefilledrectangle ($ticket, 0, ($alto_calculado), $ancho, $alto, $blanco);

$y=$alto_calculado+(2*$margen)+10;

$dimensions = imagettfbbox(14, 0, $font_path_b, 'Food2Home');
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = round(($ancho-$textWidth)/2); //centrado
imagettftext($ticket, 14, 0, $x, $y, $negro, $font_path, 'Food2Home');
$y+=25;
$dimensions = imagettfbbox(14, 0, $font_path_b, '856839083');
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = round(($ancho-$textWidth)/2); //centrado
imagettftext($ticket, 14, 0, $x, $y, $negro, $font_path, '856839083');
$y+=25;

$dimensions = imagettfbbox(30, 0, $font_path_b, $numero);
$textWidth = abs($dimensions[4] - $dimensions[0]);

$x = round(($ancho-$textWidth)/2); //centrado
imagefilledrectangle ($ticket, $x-30, $y, $x+$textWidth+30, $y+50, $negro);
imagettftext($ticket, 30, 0, $x, $y+40, $blanco, $font_path_b, $numero);

$y+=100;
$txt='Entrega programada';
if ($order['metodo']==2){
    $txt='Recogida programada';
}
$fecha=$order['fecha'];
$hora=$order['hora'];
$dimensions = imagettfbbox(24, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = round(($ancho-$textWidth)/2); //centrado
imagettftext($ticket, 24, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=35;
$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
$mes = substr($fecha, 5, 2);

if ($mes <= 12) {
    $mes=$mes-1;
}

$txt="Fecha: ".substr($fecha,8,2)." de ".$meses[$mes]." a las";
$dimensions = imagettfbbox(22, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = round(($ancho-$textWidth)/2); //centrado
imagettftext($ticket, 22, 0, $x, $y, $negro, $font_path, $txt);
$y+=40;
$txt=$hora;
$dimensions = imagettfbbox(32, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = round(($ancho-$textWidth)/2); //centrado
imagettftext($ticket, 32, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=10;


imageline($ticket, $margen,$y,$ancho-$margen, $y, $negro);
$y+=35;
imagettftext($ticket, 18, 0, $margen, $y, $negro, $font_path, 'Comentario');
$y+=25;

imagettftext($ticket, 20, 0, $margen, $y, $negro, $font_path_b, $order['comentario']);
$y+=15;
imageline($ticket, $margen,$y,$ancho-$margen, $y, $negro);
$y+=25;

$txt='EUR';
$dimensions = imagettfbbox(20, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)-$margen; //derecha
imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=30;

/// el pedido
for ($n=0;$n<count($carrito);$n++){
    $txt=$carrito[$n]['cantidad'];
    imagettftext($ticket, 20, 0, $margen, $y, $negro, $font_path_b, $txt);
    $txt='x ';
    imagettftext($ticket, 20, 0, $margen+30, $y, $negro, $font_path_b, $txt);
    if ($carrito[$n]['menu']==1){
        
        $txt='MENU '.$carrito[$n]['nombre'];
        imagettftext($ticket, 20, 0, $margen+50, $y, $negro, $font_path_b, $txt);
        
    }
    else {
        $txt=$carrito[$n]['nombre'];
        imagettftext($ticket, 20, 0, $margen+50, $y, $negro, $font_path_b, $txt);
    }
    

    $txt=number_format($carrito[$n]['cantidad']*$carrito[$n]['precio'], 2, ',', '.');
    $dimensions = imagettfbbox(20, 0, $font_path_b, $txt);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = ($ancho-$textWidth)-$margen; //derecha
    imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
    $y+=30;
    if (isset($carrito[$n]['modificadores'])){
         for ($j=0;$j<count($carrito[$n]['modificadores']);$j++){
             $txt=$carrito[$n]['modificadores'][$j]['nombre'];
             imagettftext($ticket, 18, 0, $margen+45, $y, $negro, $font_path, $txt);
             $y+=30;
         }
    }
    if (isset($carrito[$n]['elmentosMenu'])){
         for ($j=0;$j<count($carrito[$n]['elmentosMenu']);$j++){
             $txt=$carrito[$n]['elmentosMenu'][$j]['cantidad'];
            imagettftext($ticket, 18, 0, $margen+45, $y, $negro, $font_path_b, $txt);
            $txt='x '.$carrito[$n]['elmentosMenu'][$j]['nombre'];
            imagettftext($ticket, 18, 0, $margen+75, $y, $negro, $font_path_b, $txt);
            //$txt=number_format($carrito[$n]['elmentosMenu'][$j]['cantidad']*$carrito[$n]['elmentosMenu'][$j]['precio'], 2, ',', '.');
             $dimensions = imagettfbbox(18, 0, $font_path_b, $txt);
            $textWidth = abs($dimensions[4] - $dimensions[0]);
            $x = ($ancho-$textWidth)-$margen; //derecha
            //imagettftext($ticket, 18, 0, $x, $y, $negro, $font_path_b, $txt);
            $y+=30;
         }
    }
    
    if ($carrito[$n]['comentario'] !=""){
        $txt=$carrito[$n]['comentario'];
        imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path_bi, $txt);
        $y+=30;
    }

    $y+=30;
}
$y+=5;
$txt='Subtotal';
imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path, $txt);
$txt=number_format($order['subtotal'], 2, ',', '.');
$dimensions = imagettfbbox(20, 0, $font_path, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)-$margen; //derecha
imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=30;

// descuento

if($order['portes']>0){
    $txt='Gastos envío';
    imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path, $txt);
    $txt=number_format($order['portes'], 2, ',', '.');
    $dimensions = imagettfbbox(20, 0, $font_path, $txt);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = ($ancho-$textWidth)-$margen; //derecha
    imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
    $y+=30;
}
/*
if($order['cupon']>0){
    $txt='Cupón ['.$order['codigocupon'].']';
    imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path, $txt);
    $txt='-'.number_format($order['cupon'], 2, ',', '.');
    $dimensions = imagettfbbox(20, 0, $font_path, $txt);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = ($ancho-$textWidth)-$margen; //derecha
    imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
    $y+=30;
}
*/
if($order['descuento']>0){
    $txt='Descuento';
    imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path, $txt);
    $txt='-'.number_format($order['descuento'], 2, ',', '.');
    $dimensions = imagettfbbox(20, 0, $font_path, $txt);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = ($ancho-$textWidth)-$margen; //derecha
    imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
    $y+=30;
    if($order['cupon']>0){
        $txt='Cupón ['.$order['codigocupon'].']';
        imagettftext($ticket, 18, 0, $margen+45, $y, $negro, $font_path, $txt);
        
        $y+=30;
    }
}
if($order['monedero']>0){
    $txt='Monedero usado';
    imagettftext($ticket, 20, 0, $margen+45, $y, $negro, $font_path, $txt);
    $txt='-'.number_format($order['monedero'], 2, ',', '.');
    $dimensions = imagettfbbox(20, 0, $font_path, $txt);
    $textWidth = abs($dimensions[4] - $dimensions[0]);
    $x = ($ancho-$textWidth)-$margen; //derecha
    imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
    $y+=30;
}

$txt='--------------';
$dimensions = imagettfbbox(18, 0, $font_path, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)-$margen; //derecha
imagettftext($ticket, 18, 0, $x, $y, $negro, $font_path, $txt);
$y+=30;
$txt='Total';
imagettftext($ticket, 22, 0, $margen+45, $y, $negro, $font_path_b, $txt);
$txt=number_format($order['total'], 2, ',', '.');
$dimensions = imagettfbbox(22, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)-$margen; //derecha
imagettftext($ticket, 22, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=30;
$txt='(Impuestos incluidos)';
imagettftext($ticket, 14, 0, $margen+45, $y, $negro, $font_path, $txt);
$y+=25;
imageline($ticket, $margen,$y,$ancho-$margen, $y, $negro);
$y+=45;
if ($order['tarjeta']==1){
    $txt='PEDIDO PAGADO';
}
else {

    $txt='PENDIENTE DE PAGO';
}

$dimensions = imagettfbbox(32, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)/2; //centrado
imagettftext($ticket, 32, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=20;
imageline($ticket, $margen,$y,$ancho-$margen, $y, $negro);
$y+=40;
$lines = explode("\n", wordwrap($order['nombre']." ".$order['apellidos'], 25, "\n"));

for ($x=0;$x<count($lines);$x++){
    imagettftext($ticket, 26, 0, $margen, $y, $negro, $font_path_b, $lines[$x]);
    $y+=35;
}

if ($order['metodo']==1){
    
    // domicilio
    $lines = explode("\n", wordwrap($order['domicilio']['direccion'], 25, "\n"));

    for ($x=0;$x<count($lines);$x++){
        imagettftext($ticket, 26, 0, $margen, $y, $negro, $font_path_b, $lines[$x]);
        $y+=35;
    }
    if ($order['domicilio']['complementario']!=""){
        $lines = explode("\n", wordwrap($order['domicilio']['complementario'], 25, "\n"));

        for ($x=0;$x<count($lines);$x++){
            imagettftext($ticket, 26, 0, $margen, $y, $negro, $font_path_b, $lines[$x]);
            $y+=35;
        }
    }
    $lines = explode("\n", wordwrap($order['domicilio']['poblacion'], 25, "\n"));

    for ($x=0;$x<count($lines);$x++){
        imagettftext($ticket, 26, 0, $margen, $y, $negro, $font_path_b, $lines[$x]);
        $y+=35;
    }
}
imagettftext($ticket, 18, 0, $margen, $y, $negro, $font_path, 'Teléfono:');
$y+=35;
imagettftext($ticket, 26, 0, $margen, $y, $negro, $font_path_b, $order['telefono']);
$y+=35;

if($numero_pedidos==1){
    
    $txt='(Este es su primer pedido)';
}
else {

    $txt='(Este es su '.$numero_pedidos.'º pedido)';
}

$dimensions = imagettfbbox(20, 0, $font_path_b, $txt);
$textWidth = abs($dimensions[4] - $dimensions[0]);
$x = ($ancho-$textWidth)/2; //centrado
imagettftext($ticket, 20, 0, $x, $y, $negro, $font_path_b, $txt);
$y+=20;
//header('Content-type: image/png');
imagepng($ticket, '../printer/tickets/ticket-'.$numero.'.png',9);

$sql="INSERT INTO tickets (impreso,ticket) VALUES (0,'".$numero."');"; 

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result){
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking);
ob_end_clean();

if ($llamada=='POST'){
    echo json_encode($json);
}

imagedestroy($ticket);
imagedestroy($logo); 



function redimensionar($src, $ancho_forzado){
   if (file_exists($src)) {
      list($width, $height, $type, $attr)= getimagesize($src);

       
      if ($ancho_forzado > $width) {
         $max_width = $width;
      } else {
         $max_width = $ancho_forzado;
      }
      $proporcion = $width / $max_width;
      if ($proporcion == 0) {
         return -1;
      }
      $height_dyn = $height / $proporcion;
   } else {
      return -1;
   }

   return array($max_width, $height_dyn);
}
?>
