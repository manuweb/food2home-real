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
include_once "../config.mail.php";
include_once "../PHPMailer/class.phpmailer.php";
include_once "../PHPMailer/class.smtp.php";
include_once "../functions.php";
//include_once "enviaemailcss.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {
    
    //$_POST = json_decode(file_get_contents('php://input'), true);
    //$array = $_POST;
    $array = json_decode(json_encode($_POST), true);
    // Se están enviando datos a través del método POST

    $llamada='POST';
    $tipo=$array['tipo'];
    $idpedido=$array['idpedido'];
    $numero=$array['numero'];
    
    
}
else {
    $llamada='GET';  
}



$checking=false;

//$tipo='nuevo';
//$array['usuario']='m.sanchez@elmaestroweb.es';

//$tipo='recupera';
//$array['mail']='m.sanchez@elmaestroweb.es';
/*
$array['pedido']=2;
$array['numero']='2H7PSY1M';
$array['colorprimario']='#ce132e';
*/

$phpmailer = new PHPMailer();
$phpmailer->CharSet = 'UTF-8';
$phpmailer->Username = USUARIOMAIL;
$phpmailer->Password = CLAVEMAIL; 
$phpmailer->SMTPSecure = SMTPSecure;
$phpmailer->Host = HOSTMAIL; 
$phpmailer->Port = PUERTOMAIL;
//$phpmailer->IsSMTP(); // use SMTP
$phpmailer->SMTPAuth = true;
$phpmailer->Sender = MAILsender;
$phpmailer->SetFrom(MAILsender, NOMBREEmpresa);
$phpmailer->IsHTML(true);

$textomail='<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
<tr>
<td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;background-color:#d3d3d3">
<a href="https://demorevo.food2home.es" style="width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;">[logo]</a>
</td>
</tr>
<tr>
<td style="padding:30px;background-color:#ffffff;text-align:left">';
$textomail=str_replace('[logo]',"<img src='".URLServidor."img/empresa/".LOGOEMPRESA."' style='width:250px;max-width:80%;height:auto;border:none;text-decoration:none;color:#ffffff;'>",$textomail);

if ($tipo=='contacto'){
    $phpmailer->AddAddress(MAILEMPRESA); // recipients email
    $phpmailer->Subject = 'Formulario de contacto';

    $textomail.= "<p>Ha sido contactado por:</p>";
    $textomail.= "<p>Nombre: <b>".$array['nombre']."</b></p>";
    $textomail.= "<p>Email: <b>".$array['email']."</b></p>";
    $textomail.= "<p>Teléfono: <b>".$array['telefono']."</b></p>";
    $textomail.= "<p>comentario:<br>".$array['comentario']."</p>"; 
}

if ($tipo=='recupera'){
    $email=$array['mail'];

    $sql = "SELECT username FROM usuarios_app where username='".$email."'";


    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    // Verificar si se obtuvieron resultados
    if ($result) { 
        $seguridad = uniqid('', true);

        $sql = "UPDATE usuarios_app SET seguridad='".$seguridad."' WHERE username='".$email."'";

        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        // Verificar si se obtuvieron resultados
        if ($result) {
            $phpmailer->AddAddress($email); 
            $phpmailer->Subject = 'Recuperar Clave de Acceso';
            $new_url=str_replace('/webapp','',URLServidor);
            $textomail.= "<p>Ha dedidido recuperar su contrase&ntilde;a. Si no lo ha hecho por favor ignore este mail.</p>";
            $textomail.= "<p>Para crear una nueva contrase&ntilde;a haga <a href='".$new_url."restablecerclave.php?seguridad=".$seguridad."'>click aqui</a>.</p>";
            $textomail .= "<p>Rellene los datos con su nueva contrase&ntilde;a y vuela a iniciar sesi&oacute;n en la app.</p>";
            $textomail.= "<p>Gracias por usar nuestra app.</p>";
            
        }
    }
}

if ($tipo=='nuevo'){
    $email=$array['usuario'];

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
            $phpmailer->AddAddress($email); // recipients email
            
            $phpmailer->Subject = 'Registro Cliente';
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
            }
            
            
            
        }
    }
    $database->freeResults();
}

if ($tipo=='pedido'){

    
    
    $sql="SELECT datos FROM orders WHERE idPedido='".$idpedido."';"; 

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $pedido = $result->fetch_object();
    $database->freeResults();
    $datos=$pedido->datos;
    $order = json_decode($datos,JSON_UNESCAPED_UNICODE);

    
    //echo "<pre>";
//print_r($order);
    //echo "</pre>";
    

    $phpmailer->AddAddress($order['email']); // recipients email
    $phpmailer->Subject = 'Su pedido '.$numero;
    
    $fecha=$order['fecha'];
    
    

    $cssmail=''.
        '<style>
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                font-size:.8em;
            }
        </style>';
    $textomail.= $cssmail."<p>Estos son los datos de su pedido:</p>";
    $textomail.= "<p>Número: <b>".$numero."</b></p>";
    $textomail.= "<p>Fecha: <b>".substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4)."</b> Hora: <b> ".substr($fecha,11,5)."</b></p>";
    if ($order['tarjeta']==1) {
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
        $textomail.= "<h3>Entrega domicilio <b>hora:".$hora.$txt_cortesia."</b> en:</h3>";
        $textomail.= "<p>".$order['domicilio']['direccion']."<br>";
        if ($order['domicilio']['complementario']!=""){
            $textomail.= $order['domicilio']['complementario']."<br>";
        }
        $textomail .= $order['domicilio']['cod_postal']." - ".$order['domicilio']['poblacion']." (".$order['domicilio']['provincia'].")</p>";
    }
    else {
        $textomail.= "<h3>Recoger en local <b>hora: ".$hora.$txt_cortesia."</b></h3>";
    }
    if ($order['comentario']!=''){
        $textomail.= "<p>Comentario:<br><i>".$order['comentario']."</i></p>";
    }
    
    
    // pedido:
    $carrito=$order['carrito'];

    $textomail .='<table class="table-responsive"><tr style="background:'.$colorprimario.';color:#fff;padding:5px;"><th colspan="2">Concepto</th><th align ="right">Cantidad</th><th align ="right">Precio</th><th align ="right">Subtotal</th></tr>';
    for ($n=0;$n<count($carrito);$n++){
        $textomail.='<tr><td align ="center" valign="top"><img src="'.$carrito[$n]['img'].'" width=40 height=auto></td><td  align ="left" valign="top">'.$carrito[$n]['nombre'];
        
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
        $textomail .="</td><td align ='right' valign='top'>".$carrito[$n]['cantidad']."</td><td align ='right' valign='top'>".$carrito[$n]['precio']."</td><td align ='right' valign='top'>".number_format(($carrito[$n]['subtotal']),2)."</td></tr>";
        
            
        if (isset($carrito[$n]['elmentosMenu'])){
            for ($j=0;$j<count($carrito[$n]['elmentosMenu']);$j++){
                $textomail.='<tr><td align ="center" valign="top"><img src="'.$carrito[$n]['elmentosMenu'][$j]['img'].'" width=30 height=auto></td><td  align ="left" valign="top">'.$carrito[$n]['elmentosMenu'][$j]['nombre'];
                $textomail .="</td><td align ='right' valign='top'>".$carrito[$n]['elmentosMenu'][$j]['cantidad']."</td><td ></td><td></td></tr>";

            }
        }            
    }
    $textomail.="</table>";

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
    
    $textomail.="<p><i>Ante cualquier incidencia con su pedido utilize el formulario de contacto en la app.</i></p>"; 
    $textomail.="<p>Gracias por confiar en <b>".NOMBRECOMERCIAL ."</b></p>"; 
    $textomail.="<p style='font-size:0.9em;'>* <i>Impuestos incluidos.</i></p>"; 
    
}
    

$textomail.='</td></tr></table>';
$pie=PIECORREO;
$pie=str_replace('[txt-pie]',NOMBRECOMERCIAL." ".date("Y"),$pie);

$phpmailer->Body =headmail();
$phpmailer->Body .= iconv("UTF-8", "UTF-8",$textomail);
$phpmailer->Body .= iconv("UTF-8", "UTF-8",$pie.footmail());


if ($phpmailer->send()) {
    $checking=true;
    $msg="Mail enviado correctamente"; 

} else {
    $msg="Error enviando mail"; 
    //echo 'Mailer Error: ' . $mail->ErrorInfo;
}

$json=array("valid"=>$checking, "msg"=>$msg);
ob_end_clean();
if ($llamada=='POST'){
    echo json_encode($json);
}






/*
$file = fopen("zz-email.html", "w");
//fwrite($file, "SQL: ". $sql . PHP_EOL);
//fwrite($file, "head: ". headmail() . PHP_EOL);
//fwrite($file, "pie: ". PIECORREO . PHP_EOL);
//fwrite($file, "Body: ". $phpmailer->Body . PHP_EOL);
fwrite($file,$phpmailer->Body . PHP_EOL);
//fwrite($file, "sender: -". MAILsender .'-'. PHP_EOL);
//fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
fclose($file);
/*
$file = fopen("email.TXT", "w");
fwrite($file, "error: ". $phpmailer->ErrorInfo . PHP_EOL);
fclose($file);
*/
?>
