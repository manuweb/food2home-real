<?php


$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$nombre_archivo_me_llama=basename($_SERVER['SCRIPT_NAME']);
    
$url=$http.'://' . $_SERVER["HTTP_HOST"] .$_SERVER["REQUEST_URI"];
$url=str_replace($nombre_archivo_me_llama,'',$url);
$urlservidor=str_replace('/includes','',$url);

//$urlservidor='http://localhost/apps/f7v8/www/';
$sql="SELECT nombreremitente, mail,usuariomail,clavemail,host,puerto,SMTPSecure,sender,pie FROM mail WHERE id=1";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$configmail= $result->fetch_object();

define('USUARIOMAIL', $configmail->usuariomail);
define('CLAVEMAIL', $configmail->clavemail);
define('HOSTMAIL', $configmail->host);
define('PUERTOMAIL', $configmail->puerto);
define('SMTPSecure', $configmail->SMTPSecure);
define('URLServidor', $urlservidor);
define('NOMBREEmpresa', $configmail->nombreremitente);
define('MAILsender', $configmail->sender);
define('PIECORREO', $configmail->pie);
$database->freeResults();
?>