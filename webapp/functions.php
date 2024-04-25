<?php
/*
 *
 * Archivo: functions.php
 *
 * Version: 1.0.4
 * Fecha  : 14/02/2024
 * 
 * ALGUNAS FUNCIONES
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
include_once "../config.php";
include_once "../config.mail.php";
include_once "../PHPMailer/class.phpmailer.php";
include_once "../PHPMailer/class.smtp.php";

function logicacupon($cupon){
    $sql="SELECT desde, hasta, tipo,logica FROM promos WHERE codigo='".$cupon."'";

    $db = DataBase::getInstance();  
    $db->setQuery($sql);  
    $promos = $db->loadObjectList();  
    $db->freeResults();

    $tipo=$promos[0]->tipo;  
    $desde=$promos[0]->desde;
    $hasta=$promos[0]->hasta;

    $logica=$promos[0]->logica; 

    $hoy = date("d-m-Y h:m:s");
    //Incrementando x dias
    $textoDescuento='';


    $porciones = explode("##", $logica);

    if ($tipo==1){
        $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento comprando un mínimo de <b>'.$porciones[1].'</b> &euro;.';
    }
    if ($tipo==2){
        $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
        $db = DataBase::getInstance();  
        $db->setQuery($sql);  
        $prod = $db->loadObjectList();  
        $db->freeResults();
        $producto=$prod[0]->nombre;
        $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento al comprar <b><i>'.$producto.'</i></b>.';
    }
    if ($tipo==3){
        $textoDescuento='Tienes Envío GRATIS';
        if($porciones[0]==''){
            $textoDescuento.='.';
        }
        else {
            $textoDescuento.=' comprando un mínimo de <b>'.$porciones[0].'</b> &euro;.';
        }
    }
    if ($tipo==4){
        $textoDescuento='Tienes <b>'.$porciones[0].'</b> &euro; de descuento al comprar un mínimo de <b>'.$porciones[0].'</b> &euro;.';
    }
        //0123-56-89 12:45
    $desdetxt=substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' ('.substr($desde,11,5).')';
    $hastatxt=substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).' ('.substr($hasta,11,5).')';
    $textoCupon='<h3 style="margin:0;"><img src="'.URLServidor.'webapp/img/cupon.png" width="64" height="64" alt="f" style="vertical-align: middle;padding: 10px;"><span style="border:solid 1px;border-radius:5px;">&nbsp;'.$cupon.'&nbsp;</span></h3><p>'.$textoDescuento.'<br><i>Usalo   desde el '.$desdetxt.' hasta el '.$hastatxt.'</i>.</p>';   
        
    return $textoCupon;
}

function productoemail($id) {
    $sql="SELECT id, nombre, precio_web, info, imagen,imagen_app1  FROM productos WHERE id='".$id."'";
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    $prod = $result->fetch_object();
    
    
    $producto=$prod->nombre;
    $precio=$prod->precio_web;
    $info=$prod->info;
    if ($prod->imagen==''){
        $imagen=URLServidor."webapp/img/productos/".$prod[0]->imagen_app1;
    }
    else {
        $imagen=IMGREVO.$prod->imagen;
    }
    $txt='<div style="background-color: #d3d3d3 !important;;border-radius: 40px;"><br><h3 style="padding: 20px;padding-top: 0px;padding-bottom: 0;">'.$producto.'</h3><table style="width:100%;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;"><tr><td style="padding:10px;text-align:center;width:40%;" valign="top"><img src="'.$imagen.'" style="width:100%;height:auto;border-radius:50%;"></td><td style="padding:10px;text-align:left;width:60%;"><p>'.$info.'</p><h3 style="text-align: center;color:#ff0000;">'.$precio.' &euro;</h3></td></tr></table></div>';

    return $txt;
}

function headmail(){
	$head='<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="ISO-8859-1"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #555555; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#d3d3d3;text-align: center;width: 100%;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#d3d3d3 ;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]-->';
	return $head;
}

function footmail(){
	$foot='<!--[if mso]> </td></tr></table><![endif]--> </td></tr></table></div></body></html>';
	return $foot;
}

function calcula_descuento($totaldescuento,$subtotal,$total) {
    $descuento=$totaldescuento*$subtotal/$total;
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

function eliminar_acentos($cadena){
	//$cadena = utf8_encode($cadena);	
    //Reemplazamos la A y a
    $cadena = str_replace(
    array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
    $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
    array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
    $cadena );

    //Reemplazamos la I y i
    $cadena = str_replace(
    array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
    $cadena );

    //Reemplazamos la O y o
    $cadena = str_replace(
    array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
    $cadena );

    //Reemplazamos la U y u
    $cadena = str_replace(
    array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
    $cadena );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
    array('Ñ', 'ñ', 'Ç', 'ç'),
    array('N', 'n', 'C', 'c'),
    $cadena
    );

    return $cadena;
}

function redimensionar($src, $ancho_forzado){

   //if (file_exists($src)) {
      // echo $src."<hr>";
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
   //} else {
   //   return -1;
   //}

   return array($max_width, $height_dyn);
}

class RecomponePedido 
{
    //public $ctrUsuRevo;
    public $http;
    public $url;
    public $urlImgRevo;
    public $urlImgProducto;
    
    public function __construct(){
        $this->http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $this->url=$this->http.'://' . $_SERVER["HTTP_HOST"] ;
        $this->urlImgRevo=$this->url.'/webapp/img/revo/';
        $this->urlImgProducto=$this->url.'/webapp/img/productos/';
    }
    
    public function BuscaImg($id){
        $sql="SELECT imagen, imagen_app1 FROM productos WHERE id=".$id.";";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $img='';
        if ($result->num_rows > 0) {
            $imagen = $result->fetch_object();
            $img=$this->urlImgRevo.$imagen->imagen;
            if ($imagen->imagen_app1!=''){
               $img=$this->urlImgProducto.$imagen->imagen_app1;
            }
        }
        $database->freeResults();  
        return $img;  
    }
    
    public function BuscaDomicilio($idPedido){
        $sql="SELECT direccion,  complementario, cod_postal, poblacion, provincia FROM pedidos_domicilios WHERE idPedido=".$idPedido.";";
        $domicilio=[];
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $resultado = $database->execute();
        if ($resultado->num_rows > 0) {
            $datos = $resultado->fetch_object();
            $domicilio=[
                "direccion"=>$datos->direccion,
                "complementario"=>$datos->complementario,
                "cod_postal"=>$datos->cod_postal,
                "poblacion"=>$datos->poblacion,
                "provincia"=>$datos->provincia
            ];

        }
        else {
            $domicilio=[
                "direccion"=>"",
                "complementario"=>"",
                "cod_postal"=>"",
                "poblacion"=>"",
                "provincia"=>""
            ]; 
        }
        
        $database->freeResults();  
        return $domicilio;
        
    }
    
    public function BuscaLineasMenu($idLinea){
        $sql="SELECT idArticulo,  descripcion, cantidad, precio, impuesto FROM pedidos_lineas_menu WHERE idLinea=".$idLinea.";";
        $elmentosMenu=[];
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $resultadoMenu = $database->execute();
        if ($resultadoMenu->num_rows > 0) {
            while ($lineasMenu = $resultadoMenu->fetch_object()) {
                $elmentosMenu[]=[
                    "id" =>$lineasMenu->idArticulo,
                    "nombre" =>$lineasMenu->descripcion,
                    "cantidad" =>$lineasMenu->cantidad,
                    "precio" =>$lineasMenu->precio,
                    "iva" =>$lineasMenu->impuesto,
                    "precio" =>$lineasMenu->precio,
                    "img" =>$this->BuscaImg($lineasMenu->idArticulo),
                    "mod" =>""
                ];
            }
        }
        $database->freeResults();  
        return $elmentosMenu;
        
    }
    
    public function BuscaLineasModificadores($idLinea){
        $sql="SELECT idModificador,  descripcion, precio FROM pedidos_lineas_modificadores WHERE idLineaPedido=".$idLinea.";";
        $modificadores=[];
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $resultadoModificadores = $database->execute();
        if ($resultadoModificadores->num_rows > 0) {
            while ($lineasModificadores = $resultadoModificadores->fetch_object()) {
                $modificadores[]=[
                    "id" =>$lineasModificadores->idModificador,
                    "nombre" =>$lineasModificadores->descripcion,
                    "precio" =>$lineasModificadores->precio
                ];
            }
        }
        $database->freeResults();  
        return $modificadores;
        
    }
    
    public function DatosPortes() {
        $sql="SELECT ivaEnvio as iva, idEnvio AS idEnvio FROM opcionescompra WHERE id=1;";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        
        if ($result->num_rows > 0) {
            $ivas = $result->fetch_object();
            $ivaEnvio=$ivas->iva;
            $idEnvio=$ivas->idEnvio;         
        }
        $database->freeResults();  
        $devuelve=[
            'ivaenvio'=>$ivaEnvio,
            'idenvio'=>$idEnvio
            ];
        return $devuelve;

    } 
    
    function DatosGlobalesPedido($IdPedido)     
    {
        
        $sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.numeroRevo as numeroRevo, pedidos.canal as canal, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha,pedidos.dia as dia,pedidos.hora as hora, pedidos.cliente as cliente, usuarios_app.id AS idcliente, usuarios_app.tratamiento as tratamiento, usuarios_app.username as email, usuarios_app.telefono as telefono, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, pedidos.subtotal as subtotal, pedidos.impuestos as impuestos, pedidos.portes as portes, pedidos.descuento AS descuento, pedidos.monedero as monedero, pedidos.importe_fidelizacion as importe_fidelizacion, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodoPago, pedidos.codigoCupon as codigoCupon, pedidos.cupon as cupon, pedidos.tipo_descuento as tipo_descuento, pedidos.comentario as comentario, pedidos_clientes.tratamiento AS tra_otro, pedidos_clientes.email AS ema_otro, pedidos_clientes.telefono AS tel_otro, pedidos_clientes.apellidos AS ape_otro, pedidos_clientes.nombre as nom_otro FROM pedidos LEFT JOIN usuarios_app ON usuarios_app.id=pedidos.cliente LEFT JOIN pedidos_clientes ON pedidos_clientes.idPedido = pedidos.id  WHERE pedidos.id=".$IdPedido.";";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $resultado = $database->execute();
        if ($resultado->num_rows > 0) {
            $pedido = $resultado->fetch_object();

            $numero=$pedido->numero;
            $numeroRevo=$pedido->numeroRevo;
            $fecha=$pedido->fecha;
            $dia=$pedido->dia;
            $hora=$pedido->hora;
            $cliente=$pedido->cliente;
            if ($cliente!=0){
                $apellidos=$pedido->apellidos;
                $nombre=$pedido->nombre;
                $tratamiento=$pedido->tratamiento;
                $email=$pedido->email;
                $telefono=$pedido->telefono;
            }
            else {
                $apellidos=$pedido->ape_otro;
                $nombre=$pedido->nom_otro;
                $tratamiento=$pedido->tra_otro;
                $email=$pedido->ema_otro;
                $telefono=$pedido->tel_otro;
            }
            $subtotal=$pedido->subtotal;
            $impuestos=$pedido->impuestos;
            $portes=$pedido->portes;
            $descuento=$pedido->descuento;
            $monedero=$pedido->monedero;
            $importe_fidelizacion=$pedido->importe_fidelizacion;
            $total=$pedido->total;
            $envio=$pedido->envio;// 2 recoger 1 domi
            $metodoPago=$pedido->metodoPago;
            //$canal=$pedido->canal;
            $codigocupon=$pedido->codigoCupon;
            $tipo_descuento=$pedido->tipo_descuento;
            $cupon=$pedido->cupon;
            $estadoPago=$pedido->estadoPago;
            $comentario=$pedido->comentario;
            
            $DatosdelPortes=$this->DatosPortes();

            $devuelve=[
                "pedido" =>$numero,
                "numeroRevo" =>$numeroRevo,
                "cliente" =>$cliente,
                "tratamiento" =>$tratamiento,
                "nombre" =>$nombre,
                "apellidos" =>$apellidos,
                "telefono" =>$telefono,
                "email" =>$email,
                "metodo" =>$envio,
                "tarjeta" =>$metodoPago,
                "cliente" =>$cliente,
                "fecha" =>$fecha,
                "dia" =>$dia,
                "hora" =>$hora,
                "portes" =>$portes,
                "descuento" =>$descuento,
                "monedero" =>$monedero,
                "importe_fidelizacion" =>$importe_fidelizacion,
                "tipo_descuento" =>$tipo_descuento,
                "subtotal" =>$subtotal,
                "impuestos" =>$impuestos,
                "total" =>$total,
                "comentario" =>$comentario,
                "estadoPago" =>$estadoPago,
                "codigocupon" =>$codigocupon,
                "tipo_descuento" =>$tipo_descuento,
                "cupon" =>$cupon,
                "domicilio" => $this->BuscaDomicilio($IdPedido),
                "ivaenvio"=>$DatosdelPortes['ivaenvio'],
                "idenvio"=>$DatosdelPortes['idenvio']
                
            ];

        }
        else {          
            $devuelve=[];
        }
        $database->freeResults();  
        return $devuelve;
    }
     
    function LineasPedido($IdPedido)     
    {
        $devuelve=[];
        $sql="SELECT id, idArticulo,  descripcion, canidad, precio, impuesto, menu, comentario FROM pedidos_lineas WHERE idPedido=".$IdPedido.";";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $resultado = $database->execute();
        if ($resultado->num_rows > 0) {
            
            while ($lineas = $resultado->fetch_object()) {
                $textoMenu='';
                $campoMenu=[];
                if ($lineas->menu==1){
                    $elmentosMenu=$this->BuscaLineasMenu($lineas->id);
                    $textoMenu='elmentosMenu';
                    $campoMenu=$elmentosMenu;
                }
                else {
                    $textoMenu='elmentMenu';
                   $campoMenu='0';
                }
                $modificadores=$this->BuscaLineasModificadores($lineas->id);
                
                if (count($modificadores)>0){
                    
                    $precio_sin=$lineas->precio;
                    $precio=$lineas->precio;
                    for ($x=0;$x<count($modificadores);$x++){
                        $precio+=$modificadores[$x]['precio'];
                    }
                    array_push($devuelve, [
                        "id" =>$lineas->idArticulo,
                        "nombre" =>$lineas->descripcion,
                        "img" =>$this->BuscaImg($lineas->idArticulo),
                        "precio" =>$precio,
                        "iva"=>$lineas->impuesto,
                        "menu"=>$lineas->menu,
                        "precio_sin" =>$precio_sin,
                        "cantidad" =>$lineas->canidad,
                        "subtotal" =>($lineas->canidad*$precio),
                        "comentario" =>$lineas->comentario,
                        $textoMenu =>$campoMenu,
                        "modificadores" =>$modificadores
                    ]);
                }
                else {
                    $precio=$lineas->precio;
                    $precio_sin=$precio;
                    if ($textoMenu=='elmentosMenu') {
                        for ($x=0;$x<count($campoMenu);$x++){
                            $precio+=$campoMenu[$x]['precio'];
                        }
                    }
                    array_push($devuelve, [
                        "id" =>$lineas->idArticulo,
                        "nombre" =>$lineas->descripcion,
                        "img" =>$this->BuscaImg($lineas->idArticulo),
                        "iva"=>$lineas->impuesto,
                        "menu"=>$lineas->menu,
                        "precio" =>$precio,
                        "precio_sin" =>$precio_sin,
                        "comentario" =>$lineas->comentario,
                        "cantidad" =>$lineas->canidad,
                        "subtotal" =>($lineas->canidad*$precio),
                        $textoMenu => $campoMenu
                    ]);
                }

                
            }    
        }
        else {          
            $devuelve=[];
        }
        
        $database->freeResults();  
        return $devuelve;
    }
    
    
}

class RedSysMio
{
    public $http;
    public $url;

    
    public function __construct(){
        $this->http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $this->url=$this->http.'://' . $_SERVER["HTTP_HOST"];
    }  
    
    public function DatosRedsys($tienda=1){
        $tien=1;
        if ($tienda==0){
            $nombre='empresa';
        }
        else {
            $tien=$tienda;
            $nombre='tienda';
        }
        $sql="SELECT ".$nombre.".nombre_comercial,  redsys.MerchantCode, redsys.MerchantKey, redsys.terminal  FROM ".$nombre." LEFT JOIN redsys on redsys.id=".$tien." Where ".$nombre.".id=".$tien;
        //echo $sql."<hr>";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        $devuelve=[
            'NombreComercial'=>'Nombre empresa',
            'MerchantCode'=>'999008881',
            'MerchantKey'=>'sq7HjrUOBfKmC576ILgskD5srU870gJ7',
            'terminal'=>'1',
            'modo'=>'test',
            'url'=>$this->url
        ]; 
        if ($result->num_rows > 0) {
            $redsysMio = $result->fetch_object();
            $NombreComercial=$redsysMio->nombre_comercial;
            $MerchantCode=$redsysMio->MerchantCode;
            $MerchantKey=$redsysMio->MerchantKey;
            $terminal=$redsysMio->terminal;
            $modo='live';
            if($MerchantCode==''){
                $MerchantCode='999008881';
            }
            if($MerchantKey==''){
                $MerchantKey='  sq7HjrUOBfKmC576ILgskD5srU870gJ7';
            }
            if($MerchantKey=='sq7HjrUOBfKmC576ILgskD5srU870gJ7'){
                $modo='test';
            }
            $devuelve['NombreComercial']=$NombreComercial;
            $devuelve['MerchantCode']=$MerchantCode;
            $devuelve['MerchantKey']=$MerchantKey;
            $devuelve['terminal']=$terminal;
            $devuelve['modo']=$modo;
            $devuelve['url']=$this->url;
            
        }
        $database->freeResults();  
        return $devuelve;  
    }
    
    
}

class PedidosRevo 
{

    public $clienttoken;
    public $url;
    
    public function __construct(){
        $this->clienttoken=CLIENTTOKEN;
        $this->url=URLREVO.'api/loyalty/orders';
    }
    
    public function BuscaDatos($tienda=1){
        $sql="SELECT multi FROM empresa WHERE id=1;";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $devuelve=[
            'usuario'=>'',
            'token'=>''
        ];
        if ($result->num_rows > 0) {
            $empresa = $result->fetch_object();
            $multi=$empresa->multi;
            if ($multi==1){
                $sql="SELECT usuario, token FROM integracion WHERE id=1;";
                
                $database->setQuery($sql);
                $resultado = $database->execute();
                $datos = $resultado->fetch_object();
                $usuario=$datos->usuario;
                $token=$datos->token;
               
            }
            else {
                $sql="SELECT usuario, token FROM tiendas WHERE id=".$tienda.";";
                $database->setQuery($sql);
                $resultado = $database->execute();
                $datos = $resultado->fetch_object();
                $usuario=$datos->usuario;
                $token=$datos->token;
                
            }
        }
        $devuelve['usuario']=$usuario;
        $devuelve['token']=$token;
        $database->freeResults();  
        return $devuelve;  
    }
    
    function addPedidoRevo($order,$idRedsys, $tienda=1){
        $carrito=$order['carrito'];
        $ivaenvio=$order['ivaenvio'];
        $idenvio=$order['idenvio'];
        $userytoken=$this->BuscaDatos($tienda);
        $user=$userytoken['usuario'];
        $token=$userytoken['token'];
        
        for ($x=0;$x<count($carrito);$x++){


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

            $productos[$x]['itemPrice']=$carrito[$x]['precio_sin'];

            if (isset($carrito[$x]['modificadores'] )){

                $mod=[];
                for ($j=0;$j<count($carrito[$x]['modificadores']);$j++){
                    $mod[$j]['id'] = $carrito[$x]['modificadores'][$j]['id'];
                    $mod[$j]['price'] = $carrito[$x]['modificadores'][$j]['precio'];

                    $mod[$j]['name'] = $carrito[$x]['modificadores'][$j]['nombre'];
                }
                $productos[$x]['modifiers']=$mod;
            }


            $productos[$x]['notes']=eliminar_acentos($carrito[$x]['comentario']);
            // mirar si portes

        }

        if ($order['portes']!=0){
                $productos[$x]['item_id']=$idenvio;
                $productos[$x]['quantity']=1;

                
                $productos[$x]['itemPrice']=$order['portes'];
                
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
            $pago['amount']=$orders['total'];
        
            //$pago['tip']=0;
            $pago['payment_reference']=substr($order['fecha'],0,4).$order['pedido'];
            $pago['payment_method_id']=$idRedsys; 
                        
            $orders['payment']=$pago;
            
        }


        $customer['name']=$order['nombre']." ".$order['apellidos'];
        $customer['email']=$order['email'];
        $customer['address']=$order['domicilio']['direccion'];
        $customer['city']=$order['domicilio']['poblacion'];
        $customer['state']=$order['domicilio']['provincia'];
        $customer['postalCode']=$order['domicilio']['cod_postal'];
        $customer['phone']=$order['telefono'];

        //$mifecha= date(substr($order['fecha'],0,10)." ". $order['hora'].":00"); 
        $mifecha= date($order['dia']." ". $order['hora'].":00"); 
        $NuevaFecha = strtotime('-2 hour',strtotime ($mifecha)); 
        $NuevaFecha = date ( 'Y-m-d H:i:s' , $NuevaFecha); 

        //$delivery['channel']=22;

        if ($order['metodo']==1) { //1=enviar, 2=recoger
            $delivery['address']=$order['domicilio']['direccion'];
            $delivery['city']=$order['domicilio']['poblacion']; 
        }

        $delivery['phone']=$order['telefono'];
        $delivery['date']=$NuevaFecha;
        $clienttoken=$this->clienttoken;
        $url=$this->url;
        
        $file = fopen("zz-leepedidos.txt", "w");
        fwrite($file, "Fecha: ". $delivery['date'] . PHP_EOL);
        fclose($file);
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
        
        return $revoid;

    }

    
    function deletePedidoRevo($id, $tienda=1){
        $userytoken=$this->BuscaDatos($tienda);
        $user=$userytoken['usuario'];
        $token=$userytoken['token'];
        $clienttoken=$this->clienttoken;
        
        $url=$this->url."/".$id;
        
        echo "user: ".$user."<br>";
        echo "token: ".$token."<br>";
        echo "clienttoken: ".$clienttoken."<br>";
        echo "url: ".$url."<br>";
        
        $topost="reason=Cancelacion";
        $header=array(
                'tenant: ' . $user,"Authorization: Bearer ". $token, "client-token: ".$clienttoken
                );


        $opts = array('http' =>
            array(
                'method'  => 'DELETE',
                'header'  => $header,
                'content' => $topost
            )
        );

        
        $context = stream_context_create($opts);
        $result = file_get_contents($url, true, $context);
        $resultado=json_decode($result);
        return json_encode($resultado);
        
    }
    
}

class MisMails 
{

    public $http;
    public $url;
    
    public function __construct(){
        $this->http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $this->url=$this->http.'://' . $_SERVER["HTTP_HOST"];
    }
    
    public function BuscaDatosMail(){
        $devuelve=[
            'Username'=>USUARIOMAIL,
            'Password'=>CLAVEMAIL,
            'SMTPSecure'=>SMTPSecure,
            'Host'=>HOSTMAIL,
            'Port'=>PUERTOMAIL,
            'Sender'=>MAILsender,
            'NombreEmpresa'=>NOMBREEmpresa,
            'cco'=>CCO,
            'cco_pedidos'=>CCOPEDIDOS,
            'cco_registro'=>CCOREGISTRO,
            'cco_contacto'=>CCOCONTACTO,
            'url'=>$this->url
        ]; 
        return $devuelve;  
    }
    
    public function CreaPieMail(){
        $foot='<!--[if mso]> </td></tr></table><![endif]--> </td></tr></table></div></body></html>';
        $pie=PIECORREO;
        $pie=str_replace('[txt-pie]',NOMBRECOMERCIAL." ".date("Y"),$pie);
        $pie.=$foot;
        //
        return $pie;
    }
    
    public function CreaHeadMail(){
        $head='<!DOCTYPE html><html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"><head><meta charset="ISO-8859-1"> <meta name="viewport" content="width=device-width,initial-scale=1"> <meta name="x-apple-disable-message-reformatting"> <title></title><!--[if mso]> <style>table{border-collapse:collapse;border-spacing:0;border:none;margin:0;}div, td{padding:0;}div{margin:0 !important;}</style> <noscript> <xml> <o:OfficeDocumentSettings> <o:PixelsPerInch>96</o:PixelsPerInch> </o:OfficeDocumentSettings> </xml> </noscript><![endif]--> <style>table, td, div, h1, p{font-family: Arial, sans-serif;}@media screen and (max-width: 530px){.unsub{display: block; padding: 8px; margin-top: 14px; border-radius: 6px; background-color: #555555; text-decoration: none !important; font-weight: bold;}.col-lge{max-width: 100% !important;}}@media screen and (min-width: 531px){.col-sml{max-width: 27% !important;}.col-lge{max-width: 73% !important;}}</style></head><body style="margin:0;padding:0;word-spacing:normal;background-color:#d3d3d3;text-align: center;width: 100%;"> <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#d3d3d3 ;"> <table role="presentation" style="width:100%;border:none;border-spacing:0;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td align="center" style="padding:0;"><!--[if mso]> <table role="presentation" align="center" style="width:600px;"> <tr> <td><![endif]-->';
        
        $textomail='<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;"><tr><td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;background-color:#d3d3d3"><a href="'.$this->url.'" style="width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;">[logo]</a></td></tr><tr><td style="padding:30px;background-color:#ffffff;text-align:left">';
        $textomail=str_replace('[logo]',"<img src='".$this->url."/webapp/img/empresa/".LOGOEMPRESA."' style='width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;'>",$textomail);
        return $head.$textomail;
    }
    
    public function CreaBodyCampaign($datos){
        $devuelve=[
            'textomail'=>$datos['textomail'],
            'subject'=>$datos['subject']
        ]; 
        return $devuelve;
    }
        
    public function CreaBodyTextoContacto($datos){
        $subject='Formulario de contacto';
        $textomail= "<p>Ha sido contactado por:</p>";
        $textomail.= "<p>Nombre: <b>".$datos['nombre']."</b></p>";
        $textomail.= "<p>Email: <b>".$datos['email']."</b></p>";
        $textomail.= "<p>Teléfono: <b>".$datos['telefono']."</b></p>";
        $textomail.= "<p>comentario:<br>".$datos['comentario']."</p>";
        $textomail.='</td></tr></table>';
        $devuelve=[
            'textomail'=>$textomail,
            'subject'=>$subject
        ]; 
        return $devuelve;
    }
    
    public function CreaBodyTextoDevolución($numero,$tarjeta,$importe){
        $subject='Anulación de pedido';
        
        //$numero=$order['pedido'];
        
        $textomail= "<p>Su pedido número: <b>".$numero."</b> de importe <b>".$importe."</b> €, se ha anulado correctamente.</p>";
        if ($tarjeta){
            $textomail.= "<p>Se ha procedido a la devolución de <b>".$importe."</b> € a su tarjeta con la que realizó el pedido.</p>";
        }
        $textomail.="<p>Gracias por confiar en <b>".NOMBRECOMERCIAL ."</b></p>"; 
        $textomail.='</td></tr></table>';
        $devuelve=[
            'textomail'=>$textomail,
            'subject'=>$subject
        ]; 
        return $devuelve;
    }
    
    public function TextoCupon($cupon){
        $devuelve='';
        $sql="SELECT desde, hasta, tipo,logica FROM promos WHERE codigo='".$cupon."'";
        
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows>0) {
            $promos = $result->fetch_object();

            $tipo=$promos->tipo;  
            $desde=$promos->desde;
            $hasta=$promos->hasta;

            $logica=$promos->logica; 
        }

        $hoy = date("d-m-Y h:m:s");
        //Incrementando x dias
        $textoDescuento='';


        $porciones = explode("##", $logica);

        if ($tipo==1){
            $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento comprando un mínimo de <b>'.$porciones[1].'</b> &euro;.';
        }
        if ($tipo==2){
            $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
            $database->setQuery($sql);
            $result = $database->execute();
            if ($result->num_rows>0) {
                $prod = $result->fetch_object();
            
                $db->freeResults();
                $producto=$prod->nombre;
                $textoDescuento='Tienes un <b>'.$porciones[0].'%</b> de descuento al comprar <b><i>'.$producto.'</i></b>.';
            }
        }
        if ($tipo==3){
            $textoDescuento='Tienes Envío GRATIS';
            if($porciones[0]==''){
                $textoDescuento.='.';
            }
            else {
                $textoDescuento.=' comprando un mínimo de <b>'.$porciones[0].'</b> &euro;.';
            }
        }
        if ($tipo==4){
            $textoDescuento='Tienes <b>'.$porciones[0].'</b> &euro; de descuento al comprar un mínimo de <b>'.$porciones[0].'</b> &euro;.';
        }
        $database->freeResults();
            //0123-56-89 12:45
        $desdetxt=substr($desde,8,2).'/'.substr($desde,5,2).'/'.substr($desde,0,4).' ('.substr($desde,11,5).')';
        $hastatxt=substr($hasta,8,2).'/'.substr($hasta,5,2).'/'.substr($hasta,0,4).' ('.substr($hasta,11,5).')';
        $devuelve='<h3 style="margin:0;"><img src="'.URLServidor.'webapp/img/cupon.png" width="64" height="64" alt="f" style="vertical-align: middle;padding: 10px;"><span style="border:solid 1px;border-radius:5px;">&nbsp;'.$cupon.'&nbsp;</span></h3><p>'.$textoDescuento.'<br><i>Usalo   desde el '.$desdetxt.' hasta el '.$hastatxt.'</i>.</p>';   

        return $devuelve;
    }
    
    public function CreaBodyTextoRecupera($email){
        $devuelve=[];
        $textomail='';
        $subject='Recuperar Clave de Acceso';

        $sql = "SELECT username FROM usuarios_app where username='".$email."'";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) { 
            $seguridad = uniqid('', true);
            $sql = "UPDATE usuarios_app SET seguridad='".$seguridad."' WHERE username='".$email."'";
            $database->setQuery($sql);
            $result = $database->execute();
            if ($result) {
                
                $textomail.= "<p>Ha dedidido recuperar su contrase&ntilde;a. Si no lo ha hecho por favor ignore este mail.</p>";
                $textomail.= "<p>Para crear una nueva contrase&ntilde;a haga <a href='".$this->url."/restablecerclave.php?seguridad=".$seguridad."'>click aqui</a>.</p>";
                $textomail .= "<p>Rellene los datos con su nueva contrase&ntilde;a y vuela a iniciar sesi&oacute;n en la app.</p>";
                $textomail.= "<p>Gracias por usar nuestra app.</p>";
                $textomail.='</td></tr></table>';
            }

            

        }
        $database->freeResults();
        
        $devuelve=[
            'textomail'=>$textomail,
            'subject'=>$subject
        ]; 
        return $devuelve;     
    }
    
    public function CreaBodyTextoNuevoUsuario($email){
        $devuelve=[];
        $textomail='';
        $subject='Registro Cliente';

        $sql = "SELECT id,nombre, apellidos, telefono, publicidad FROM usuarios_app WHERE username='".$email."'";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        // Verificar si se obtuvieron resultados
        if ($result) { 
            $usuario = $result->fetch_object();
            $sql="SELECT textomail FROM tiposcorreos WHERE nombre='bienvenida';";
            $database->setQuery($sql);
            $result = $database->execute();
            if ($result) {
                $mail = $result->fetch_object();
                $textomail.=$mail->textomail;
                $textomail=str_replace('[usuarioNombre]',$usuario->nombre,$textomail);
                $textomail=str_replace('[usuarioApellidos]',$usuario->apellidos,$textomail);
                $textomail=str_replace('[usuarioEmail]',$email,$textomail);
                $textomail=str_replace('[usuarioTelefono]',$usuario->telefono,$textomail);
                
                
                
                $sql="SELECT dias,tipo,logica FROM promos WHERE id=1 AND activo=1;";
    

                $database->setQuery($sql);
                $result = $database->execute();
                $textoDescuento='';
                $textoCupon='';
                if ($result->num_rows>0) {
                    $promos = $result->fetch_object();

                    $tipo=$promos->tipo;  
                    $dias=$promos->dias;
                    $logica=$promos->logica; 

                    $hoy = date("d-m-Y h:m:s");
                    //Incrementando x dias


                    $fecha = date("d-m-Y h:m:s", strtotime($hoy."+ ".$dias." days"));
                    $porciones = explode("##", $logica);
                    $textoDescuento.='<ul>';
                    if ($tipo==1){
                        $textoDescuento='<li>Tienes un <b>'.$porciones[0].'%</b> de descuento comprando un mínimo de <b>'.$porciones[1].'</b> &euro;.</li>';
                    }
                    if ($tipo==2){
                        $sql="SELECT id, nombre FROM productos WHERE id='".$porciones[1]."'";
                        $database->setQuery($sql);
                        $result = $database->execute();

                        $prod = $result->fetch_object();

                        $producto=$prod->nombre;
                        $textoDescuento='<li>Tienes un <b>'.$porciones[0].'%</b> de descuento al comprar <b><i>'.$producto.'</i></b>.</li>';
                    }
                    if ($tipo==3){
                        $textoDescuento='<li>Tienes Envío GRATIS';
                        if($porciones[0]==''){
                            $textoDescuento.='.</li>';
                        }
                        else {
                            $textoDescuento.=' comprando un mínimo de <b>'.$porciones[0].'</b> &euro;.</li>';
                        }
                    }
                    if ($tipo==4){
                        $textoDescuento='<li>Tienes <b>'.$porciones[0].'</b> &euro; de descuento al comprar un mínimo de <b>'.$porciones[0].'</b> &euro;.</li>';
                    }
                    $fechaphp=substr($fecha,6,4).'-'.substr($fecha,3,2).'-'.substr($fecha,0,2).' '.substr($fecha,11,5).':00';

                    $sql='INSERT INTO cuponesespeciales (codigo, usuario,limite,usado,importe ) VALUES ("BIENVENIDA", "'.$usuario->id.'", "'.$fechaphp.'","0",0);';
                    //echo $sql."<br>";
                    // 01-34-6789 12:56
                    // 0123-56-89 12:45
                    $database->setQuery($sql);
                    $result = $database->execute();
                    $fechaJS=substr($fechaphp,8,2).'/'.substr($fechaphp,5,2).'/'.substr($fechaphp,0,4).' ('.substr($fechaphp,11,5).')';

                    $textoCupon='<p>Usa el código:<b>BIENVENIDA</b><br>'.$textoDescuento.'<li>Tienes hasta el '.$fechaJS.' para usarlo</i>.</li></ul></p>';

                    $textomail=str_replace('[cuponBienvenida]',$textoCupon,$textomail);
                    $textomail.='</td></tr></table>';
                }
                
            }
        }
        
        $database->freeResults();
        $devuelve=[
            'textomail'=>$textomail,
            'subject'=>$subject
        ]; 
        return $devuelve;     
    }
    
    public function CreaBodyTextoPedido($order){
        $devuelve=[];
        $textomail='';

        $sql="SELECT estilo.primario, estilo.secundario, opcionescompra.cortesia, empresa.movil FROM estilo LEFT JOIN opcionescompra ON opcionescompra.id=estilo.id LEFT JOIN empresa ON empresa.id=estilo.id WHERE estilo.id=1";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result_empresa = $database->execute();
        $opciones= $result_empresa->fetch_object();
        $colorprimario=$opciones->primario;
        $colorsecundario=$opciones->secundario;
        $cortesia=$opciones->cortesia;
        $movil=$opciones->movil;
        $numero=$order['pedido'];
        if (TIPOINTEGRACION==1){
            if (USARNUMEROREVO==1){
                $numero=$order['numeroRevo'];
            }
        }
        
        
        $subject='Su pedido '.$numero;
        $fecha=$order['fecha'];
        $cssmail=''.
            '<style></style>';
        
        $textomail.= $cssmail."<p>Estos son los datos de su pedido:</p>";
        $textomail.= "<p>Número: <b>".$numero."</b></p>";
        $textomail.= "<p>Fecha: <b>".substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4)."</b> Hora: <b> ".substr($fecha,11,5)."</b></p>";
        
        if ($order['tarjeta']!=2) {
            $textomail .="<h3>Pago: <b>Tarjeta</b></h3>";
        }
        else {
            $textomail .="<h3>Pago: <b>Pago al Repartidor</b></h3>";
        }
        if ($order['tratamiento']==1) {
            $trata="Sr.";
        }
        else {
            $trata="Sra.";
        }

        $dia=substr($order['dia'],8,2)."/".substr($order['dia'],5,2)."/".substr($order['dia'],0,4);
        $hora=$order['hora'];
        $txt_cortesia='';
        if ($cortesia>0){
            $hh=substr($hora,0,2);
            $mm=substr($hora,3,2);
            $mm+=$cortesia;
            if ($mm>60){
                $mm-=$cortesia;
                $hh+=1;
            }
            if ($hh>24){
                $hh=0;
            }
            if ($mm<9){
                $mm='0'.$mm;
            }
            if ($hh<9){
                $hh='0'.$hh;
            }
            $txt_cortesia='-'.$hh.':'.$mm;
        }


        $textomail .="<p>Teléfono: <b>".$order['telefono']."</b></p>";
        $textomail .="<p><b>".$trata." ".$order['nombre']." ".$order['apellidos']."</b></p>";
        if ($order['metodo']==1) {// metodo envio
            $textomail.= "<h3>Entrega domicilio fecha: <b>".$dia."</b>, hora: <b>".$hora.$txt_cortesia."</b> en:</h3>";
            $textomail.= "<p>".$order['domicilio']['direccion']."<br>";
            if ($order['domicilio']['complementario']!=""){
                $textomail.= $order['domicilio']['complementario']."<br>";
            }
            $textomail .= $order['domicilio']['cod_postal']." - ".$order['domicilio']['poblacion']." (".$order['domicilio']['provincia'].")</p>";
        }
        else {
            $textomail.= "<h3>Recoger en local fecha:<b>".$dia."</b>, hora: <b>".$hora.$txt_cortesia."</b></h3>";
        }
        if ($order['comentario']!=''){
            $textomail.= "<p>Comentario:<br><i>".$order['comentario']."</i></p>";
        }


        // pedido:
        $carrito=$order['carrito'];

        $textomail .='<div style="overflow-x:auto;"><table style="border-collapse: collapse;border-spacing: 0;width: 100%;font-size:.8em;" class=""><tr style="background:'.$colorprimario.';color:#fff;padding:5px;"><th colspan="2" style="padding: 4px;">Concepto</th><th align ="right" style="padding: 4px;">Cantidad</th><th align ="right" style="padding: 4px;">Precio</th><th align ="right" style="padding: 4px;">Subtotal</th></tr>';
        for ($n=0;$n<count($carrito);$n++){
            $textomail.='<tr><td align ="center" valign="top" style="padding: 4px;"><img src="'.$carrito[$n]['img'].'" width=40 height=auto></td><td  align ="left" valign="top" style="padding: 4px;">'.$carrito[$n]['nombre'];

            if (isset($carrito[$n]['modificadores'])){
                $txt_mod="<br>";
                for ($j=0;$j<count($carrito[$n]['modificadores']);$j++){
                    $txt_mod.=$carrito[$n]['modificadores'][$j]['nombre'].", ";
                }
                $txt_mod=trim($txt_mod, ', ');
                $textomail .=$txt_mod;
            }
            if ($carrito[$n]['comentario']!=''){
                $textomail.='<br><i>'.$carrito[$n]['comentario'].'</i>';
            }
            $textomail .="</td><td align ='right' valign='top' style=padding: 4px;'>".$carrito[$n]['cantidad']."</td><td align ='right' valign='top' style=padding: 4px;'>".$carrito[$n]['precio']."</td><td align ='right' valign='top' style=padding: 4px;'>".number_format(($carrito[$n]['subtotal']),2)."</td></tr>";


            if (isset($carrito[$n]['elmentosMenu'])){
                for ($j=0;$j<count($carrito[$n]['elmentosMenu']);$j++){
                    $textomail.='<tr><td align ="center" valign="top"><img src="'.$carrito[$n]['elmentosMenu'][$j]['img'].'" width=30 height=auto></td><td  align ="left" valign="top">'.$carrito[$n]['elmentosMenu'][$j]['nombre'];
                    $textomail .="</td><td align ='right' valign='top'>".$carrito[$n]['elmentosMenu'][$j]['cantidad']."</td><td ></td><td></td></tr>";

                }
            }            
        }
        $textomail.="</table></div>";

        $textomail.="<p style='text-align:right;'>Subtotal: ".number_format($order['subtotal'],2)." &euro;</p>"; 
        if ($order['portes']>0){
            $textomail.="<p style='text-align:right;'>Envío: ".number_format($order['portes'],2)." &euro;</p>"; 
        }
        if ($order['descuento']>0){
            $textomail.="<p style='text-align:right;'>Descuento: ".number_format($order['descuento'],2)." &euro;</p>"; 
        }
        if ($order['monedero']>0){
            $textomail.="<p style='text-align:right;'>Monedero usado: ".number_format($order['monedero'],2)." &euro;</p>"; 
        }
        $textomail.="<p style='text-align:right;'>Total*: <b>".number_format($order['total'],2)." &euro;</b></p>"; 
        if ($order['importe_fidelizacion']>0){
            $textomail.="<p>Has acumulado <b>".number_format($order['importe_fidelizacion'],2)." &euro;</b> en tu tarjeta monedero.</p>"; 
        }
        if ($movil==''){
            $textomail.="<p><i>Ante cualquier incidencia con su pedido utilize el formulario de contacto en la app.</i></p>"; 
        }
        else {
            $urlwhatapp="https://api.whatsapp.com/send?phone=".$movil."&text=Hola, soy ".$order['nombre']." ".$order['apellidos']." y tengo dudas con respecto el pedido ".$order['pedido']." de fecha ".substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
            
            $textomail.="<p><i>Ante cualquier incidencia con su pedido utilize el formulario de contacto en la app o bien contactar por <a href='".$urlwhatapp."'><img src='".$this->url."/img/whatsapp-icon.png' height='24' width='24' style='vertical-align: middle;'></a></p>"; 
        }
        
        
        $textomail.="<p>Gracias por confiar en <b>".NOMBRECOMERCIAL ."</b></p>"; 
        $textomail.="<p style='font-size:0.9em;'>* <i>Impuestos incluidos.</i></p>"; 
        $textomail.='</td></tr></table>';

        $devuelve=[
            'textomail'=>$textomail,
            'subject'=>$subject
        ]; 
        return $devuelve;     
    }
}

class ImprimeTicket 
{
    //public $ctrUsuRevo;
    public $http;
    public $url;
    public $telefono;
    public $nombre_comercial;
    
    
    public function __construct(){
        $this->http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $this->url=$this->http.'://' . $_SERVER["HTTP_HOST"] ;
        $sql="SELECT nombre_comercial,telefono FROM empresa WHERE id=1;";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $datos = $result->fetch_object();
        $this->telefono=$datos->telefono;
        $this->nombre_comercial=$datos->nombre_comercial;
        $database->freeResults();  
        
    }
    
    public function cuentaPedidos($order){
        if ($order['cliente']>0){
            $sql="SELECT COUNT(*) AS cantidad FROM pedidos WHERE cliente='".$order['cliente']."';"; 
        }
        else {
            $sql="SELECT COUNT(*) AS cantidad FROM pedidos_clientes WHERE email='".$order['email']."';"; 
        }
        $total=0;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result){
            $pedidos = $result->fetch_object();
            $total=$pedidos->cantidad;
        }
        $database->freeResults();  
        //echo 'Total pedidos:'.$total.'<hr>';
        return $total;         
    }
    
    public function generaTicket($idpedido){
        $Pedido = new RecomponePedido;
        $order=$Pedido->DatosGlobalesPedido($idpedido);
        $order['carrito']=$Pedido->LineasPedido($idpedido);
        $carrito=$order['carrito'];
        $numero=$order['pedido'];
        /*
        $font_path =$this->url_font.'Arial.ttf';
        $font_path_b = $this->url_font.'Arial_Bold.ttf';
        $font_path_i = $this->url_font.'ariali.ttf';
        $font_path_bi = $this->url_font.'Arial_Bold_Italic.ttf';
        */
        
        
        $font_path = __DIR__.'/includes/fonts/Arial.ttf';
        $font_path_b = __DIR__.'/includes/fonts/Arial_Bold.ttf';
        $font_path_i = __DIR__.'/includes/fonts/ariali.ttf';
        $font_path_bi = __DIR__.'/includes/fonts/Arial_Bold_Italic.ttf';

        
        $numero_pedidos=$this->cuentaPedidos($order);
        
        $logo_src=$this->url."/webapp/img/empresa/logo-impresora.png";

        //echo "logo_src:".$logo_src."<hr>";
        $logo=imagecreatefrompng($logo_src);
        list($logo_w, $logo_h, $type, $attr) = getimagesize($logo_src);

        $largo_pedido=0;
        for ($x=0;$x<count($carrito);$x++){
            $largo_pedido+=30;
            $lines = explode("\n", wordwrap($carrito[$x]['nombre'], 25, "\n"));
            for ($h=0;$h<count($lines);$h++){
                $largo_pedido+=30;
            }
            $largo_pedido-=30;
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
        
        $dimensions = imagettfbbox(14, 0, $font_path_b, $this->nombre_comercial);
        $textWidth = abs($dimensions[4] - $dimensions[0]);
        $x = round(($ancho-$textWidth)/2); //centrado
        imagettftext($ticket, 14, 0, $x, $y, $negro, $font_path, $this->nombre_comercial);
        $y+=25;
        $dimensions = imagettfbbox(14, 0, $font_path_b, $this->telefono);
        $textWidth = abs($dimensions[4] - $dimensions[0]);
        $x = round(($ancho-$textWidth)/2); //centrado
        imagettftext($ticket, 14, 0, $x, $y, $negro, $font_path, $this->telefono);
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
        $fecha=$order['dia'];
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
                

            }
            else {
                $txt=$carrito[$n]['nombre'];
                
            }
            
            //imagettftext($ticket, 20, 0, $margen+50, $y, $negro, $font_path_b, $txt);
            $lines = explode("\n", wordwrap($txt, 25, "\n"));
            for ($h=0;$h<count($lines);$h++){
                imagettftext($ticket, 20, 0, $margen+50, $y, $negro, $font_path_b, $lines[$h]);
                $y+=30;
            }
            $y-=30;
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
        imagepng($ticket, __DIR__.'/printer/tickets/ticket-'.$numero.'.png',9);

        $sql="INSERT INTO tickets (impreso,ticket) VALUES (0,'".$numero."');"; 
        $checking=false;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result){
            $checking=true;
        }
        $database->freeResults();

        

        imagedestroy($ticket);
        imagedestroy($logo); 
        return $checking;
    }
    
}

class Monedero 
{
    //public $http;
    public function __construct(){
    }
    
    public function leeMonedero($cliente){
        $sql="SELECT monedero FROM usuarios_app WHERE id='".$cliente."';"; 
        $monedero=0;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result){
            $usu = $result->fetch_object();
            $monedero=$usu->monedero;
        }
        $database->freeResults();  
        return $monedero;         
    }
    
    public function guardaMonedero($cliente,$importe){
        $sql="UPDATE usuarios_app SET monedero=".$importe." WHERE id='".$cliente."';"; 
        $monedero=false;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result){
            $monedero=true;
        }
        $database->freeResults();  
        return $monedero;         
    }
    
}


class Producto 
{
    //public $http;
    public function __construct(){
    }
    
    public function leegruposactivos($tienda=0){
        $devuelve=[];
        
        $sql="SELECT grupos.id, grupos.nombre ,COUNT(productos.id) AS cantidad, grupos.imagen, grupos.imagen_app FROM grupos LEFT JOIN categorias ON categorias.grupo=grupos.id LEFT JOIN productos ON productos.categoria=categorias.id where grupos.activo_web=1 AND categorias.activo_web=1 AND productos.activo_web=1 AND grupos.tienda=".$tienda." AND categorias.tienda=".$tienda." AND productos.tienda=".$tienda." GROUP BY grupos.id ORDER BY grupos.orden;";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'cantidad'=>$grupo->cantidad,
                    'imagen'=>$imagen=$grupo->imagen,
                    'imagen_app'=>$grupo->imagen_app
                ];      
            }
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leegrupo($id,$tienda=0){
        $devuelve=[];
        
        $sql="SELECT grupos.id, grupos.nombre, grupos.activo, grupos.activo_web, grupos.imagen, grupos.imagen_app FROM grupos WHERE grupos.id=".$id." AND tienda=".$tienda.";";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_object();
            $devuelve=[
                'id'=>$grupo->id,
                'nombre'=>$grupo->nombre,
                'imagen'=>$grupo->imagen,
                'imagen_app'=>$grupo->imagen,
                'imagen'=>$grupo->imagen_app,
                'activo'=>$grupo->activo,
                'activo_web'=>$grupo->activo_web

            ];      
            
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leecategoriasactivas($grupo=0,$tienda=0){
        $devuelve=[];
        $elgrupo='';
        if ($grupo!=0){
            $elgrupo=' AND categorias.grupo='.$grupo;
        }
        $sql="SELECT categorias.id, categorias.nombre , categorias.grupo ,COUNT(productos.id) AS cantidad, categorias.imagen, categorias.imagen_app, categorias.modifier_category_id, categorias.modifier_group_id FROM categorias LEFT JOIN productos ON productos.categoria=categorias.id WHERE categorias.activo_web=1 AND productos.activo_web=1 AND categorias.tienda=".$tienda.$elgrupo." AND productos.tienda=".$tienda." GROUP BY categorias.id ORDER BY categorias.orden;";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                $imagen=$grupo->imagen_app;
                if ($imagen==''){
                    $imagen=$grupo->imagen;
                }
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'grupo'=>$grupo->grupo,
                    'cantidad'=>$grupo->cantidad,
                    'imagen'=>$grupo->imagen,
	    	    'imagen_app'=>$grupo->imagen_app,
                    'modifier_category_id'=>$grupo->modifier_category_id,
                    'modifier_group_id'=>$grupo->modifier_group_id
                ];      
            }
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leecategoria($id,$tienda=0){
        $devuelve=[];
        
        $sql="SELECT categorias.id, categorias.nombre, categorias.grupo, categorias.activo, categorias.activo_web, categorias.imagen, categorias.imagen_app, categorias.modifier_category_id, categorias.modifier_group_id FROM categorias WHERE categorias.id=".$id." AND tienda=".$tienda.";";
        
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_object();
            $devuelve=[
                'id'=>$grupo->id,
                'nombre'=>$grupo->nombre,
                'grupo'=>$grupo->grupo,
                'imagen'=>$grupo->imagen,
                'imagen_app'=>$grupo->imagen_app,
                //'imagen'=>$grupo->imagen_app,
                'activo'=>$grupo->activo,
                'activo_web'=>$grupo->activo_web,
                'modifier_category_id'=>$grupo->modifier_category_id,
                'modifier_group_id'=>$grupo->modifier_group_id

            ];      
            
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leeproductosactivos($categoria=0,$tienda=0){
        $devuelve=[];
        $lacategoria='';
        if ($categoria!=0){
            $lacategoria=' AND productos.categoria='.$categoria;
        }
        $sql="SELECT productos.id, productos.nombre , productos.categoria, productos.precio_web, productos.imagen, productos.imagen_app1, productos.alergias, productos.modifier_category_id, productos.modifier_group_id, productos.modificadores, productos.esMenu, categorias.modifier_category_id as cat_modifier_category_id, categorias.modifier_group_id AS cat_modifier_group_id FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria WHERE productos.activo_web=1 AND productos.tienda=".$tienda.$lacategoria." ORDER BY productos.orden;";
        
       
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                $imagen=$grupo->imagen_app1;
                if ($imagen==''){
                    $imagen=$grupo->imagen;
                }
                $modifier_category_id='';
                $modifier_group_id='';
                if ($grupo->cat_modifier_category_id!=''){
                    $modifier_category_id=$grupo->cat_modifier_category_id;
                }
                else {
                    $modifier_category_id=$grupo->modifier_category_id;
                }
                if ($grupo->cat_modifier_group_id!=''){
                    $modifier_group_id=$grupo->cat_modifier_group_id;
                }
                else {
                    $modifier_category_id=$grupo->modifier_category_id;
                }
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'categoria'=>$grupo->categoria,
                    'precio'=>$grupo->precio_web,
                    'imagen'=>$grupo->imagen,
	            'imagen_app'=>$grupo->imagen_app1,
                    'alergias'=>$grupo->alergias,
                    'modifier_category_id'=>$modifier_category_id,
                    'modifier_group_id'=>$modifier_group_id,
                    'modificadores'=>$grupo->modificadores,
                    'esmenu'=>$grupo->esMenu
                    
                ];      
            }
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leeproducto($id,$tienda=0){
        $devuelve=[];
        $lacategoria='';
        
        $sql="SELECT productos.id, productos.nombre , productos.categoria, productos.precio_web, productos.info, productos.imagen, productos.imagen_app1, productos.alergias,productos.activo, productos.activo_web, productos.modifier_category_id, productos.modifier_group_id, productos.modificadores, productos.esMenu, categorias.modifier_category_id as cat_modifier_category_id, categorias.modifier_group_id AS cat_modifier_group_id FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria WHERE productos.id=".$id." AND productos.tienda=".$tienda.";";

        //echo $sql;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                $imagen=$grupo->imagen_app1;
                if ($imagen==''){
                    $imagen=$grupo->imagen;
                }
                $modifier_category_id='';
                $modifier_group_id='';
                if ($grupo->cat_modifier_category_id!=''){
                    $modifier_category_id=$grupo->cat_modifier_category_id;
                }
                else {
                    $modifier_category_id=$grupo->modifier_category_id;
                }
                if ($grupo->cat_modifier_group_id!=''){
                    $modifier_group_id=$grupo->cat_modifier_group_id;
                }
                else {
                    $modifier_category_id=$grupo->modifier_category_id;
                }
                $devuelve=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'categoria'=>$grupo->categoria,
                    'precio'=>$grupo->precio_web,
                    'imagen'=>$grupo->imagen,
		            'imagen_app'=>$grupo->imagen_app1,
                    'alergias'=>$grupo->alergias,
                    'info'=>$grupo->info,
                    'activo'=>$grupo->activo,
                    'activo_web'=>$grupo->activo_web,
                    'modifier_category_id'=>$modifier_category_id,
                    'modifier_group_id'=>$modifier_group_id,
                    'modificadores'=>$grupo->modificadores,
                    'esmenu'=>$grupo->esMenu
                ];      
            }
        }
        $database->freeResults(); 
        return $devuelve;
        
    }
    
    public function leemodifierCategories($id){
        $sql='SELECT activo,nombre,opciones,forzoso,maximo, modificadores FROM modifierCategories WHERE id='.$id.';';
        echo $sql;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_object();
            $sql='SELECT id,nombre,precio,autoseleccionado FROM modifiers WHERE id IN ('.$grupo->modificadores.') and activo=1;';
            //echo $grupo->modificadores;
            //die();
            
            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $resulta = $database->execute();
            while ($modi = $resulta->fetch_object()) {
                $modificadores[]=[
                    'id'=>$modi->id,
                    'nombre'=>$modi->nombre,
                    'precio'=>$modi->precio,
                    'autoseleccionado'=>$modi->autoseleccionado
                ];  
            }
            
            $devuelve=[
                    'nombre'=>$grupo->nombre,
                    'activo'=>$grupo->activo,
                    'opciones'=>$grupo->opciones,
                    'forzoso'=>$grupo->forzoso,
                    'maximo'=>$grupo->maximo, 
                    'modificaores'=>$modificadores
                ];  
        }
        $database->freeResults(); 
        return $devuelve;
    }
    
    public function leemodifierGroups($id){
        $sql='SELECT nombre, modifierCategories_id FROM modifierGroups WHERE id='.$id.';';
        echo $sql;
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        $devuelve=[];
        if ($result->num_rows > 0) {
            $grupo = $result->fetch_object();
            //55,76,65
            $sql='SELECT id,activo,nombre,opciones,forzoso,maximo, modificadores FROM modifierCategories WHERE id IN ('.$grupo->modifierCategories_id.') and activo=1;';
            echo $sql;
            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $resulta = $database->execute();
            while ($modi = $resulta->fetch_object()) {
                $categor[]=$this->leemodifierCategories($modi->id);
            }
            $devuelve=$categor;
            
        }
        $database->freeResults(); 
        return $devuelve;    
    }
    
    public function leeMenuCategories($id,$tienda=0){
        $devuelve=[];
        $sql="SELECT id, nombre, orden, eleMulti, min AS minimo, max AS maximo FROM MenuCategories WHERE producto='".$id."' ORDER BY orden;"; 
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'nombre'=>$grupo->nombre,
                    'orden'=>$grupo->orden,
                    'eleMulti'=>$grupo->eleMulti,
                    'min'=>$grupo->minimo,
                    'max'=>$grupo->maximo,
                    'opcionesMenu'=>$this->leeMenuItems($grupo->id,$tienda)
                ];
            }
            
        }
        $database->freeResults(); 
        return $devuelve;
    }
    
    public function leeMenuItems($id,$tienda=0){
        $devuelve=[];
        $sql="SELECT MenuItems.id, MenuItems.orden, MenuItems.precio, MenuItems.producto, MenuItems.modifier_group_id, MenuItems.addPrecioMod, productos.nombre , productos.imagen, productos.imagen_app1 ,productos.alergias, productos.info, impuestos.porcentaje AS impuesto FROM MenuItems LEFT JOIN productos ON MenuItems.producto=productos.id LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE MenuItems.category_id='".$id."' AND MenuItems.activo=1 group by MenuItems.orden;"; 
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();
        if ($result->num_rows > 0) {
            while ($grupo = $result->fetch_object()) {
                $devuelve[]=[
                    'id'=>$grupo->id,
                    'orden'=>$grupo->orden,
                    'nombre'=>$grupo->nombre,
                    'producto'=>$grupo->producto,
                    'precio'=>$grupo->precio,
                    'modifier_group_id'=>$grupo->modifier_group_id,
                    'addPrecioMod'=>$grupo->addPrecioMod,
                    'imagen'=>$grupo->imagen,
                    'imagen_app'=>$grupo->imagen_app1,
                    'alergias'=>$grupo->alergias,
                    'info'=>$grupo->alergias,
                    'alergias'=>$grupo->alergias          
                ];
            }
            
        }
        $database->freeResults(); 
        return $devuelve;
    }
    
}

?>