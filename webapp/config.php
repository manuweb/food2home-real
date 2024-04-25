<?php
 
$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$nombre_archivo_me_llama=basename($_SERVER['SCRIPT_NAME']);
    
$url=$http.'://' . $_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"];
$url=str_replace($nombre_archivo_me_llama,'',$url);
$url=str_replace('/includes','',$url);


define('CLIENTTOKEN', "Iyt02DPB592ilC6wUyk4GM1yjz2yAri83aWC7KkFV6euPUxzy0UFQ29vrH7H");

define('IMGREVO', $url.'img/revo/');
//define('IMGREVO', "https://integrations.revoxef.works/storage/cloudtech/images/");

define('URLREVO', "https://revoxef.works/");
define('IMGAPP', $url.'img/productos/');
define('IMGALE', $url.'img/alergenos/');

$sql="SELECT empresa.nombre_comercial,empresa.logo, empresa.email, estilo.primario, estilo.secundario, integracion.tipo, integracion.usuario, integracion.token, integracion.usar_numero_revo, opcionescompra.cortesia FROM empresa LEFT JOIN estilo ON estilo.id=empresa.id LEFT JOIN integracion ON integracion.id=empresa.id LEFT JOIN opcionescompra ON opcionescompra.id=empresa.id WHERE empresa.id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result_empresa = $database->execute();
$empresa= $result_empresa->fetch_object();
define('NOMBRECOMERCIAL', $empresa->nombre_comercial);
define('LOGOEMPRESA', $empresa->logo);
define('USUARIOREVO', $empresa->usuario);
define('TOKENREVO', $empresa->token);
define('MAILEMPRESA', $empresa->email);
define('TIPOINTEGRACION', $empresa->tipo);
define('USARNUMEROREVO', $empresa->usar_numero_revo);

$colorprimario=$empresa->primario;
$colorsecundario=$empresa->secundario;
$cortesia=$empresa->cortesia;
$database->freeResults();
?>