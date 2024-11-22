<?php


include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);

$checking=false;


if ($array['id']=='0') {
    //insert
    $sql="INSERT INTO promos (nombre, codigo, activo,envio_recoger,usuario, grupo, dias, desde, hasta, maximo, tipo, logica) VALUES ('".$array['nombre']."', '".$array['codigo']."', '1', '".$array['envio_recoger']."', '".$array['usuario']."', '".$array['grupo']."', '".$array['dias']."', '".$array['desde']."', '".$array['hasta']."', '".$array['maximo']."', '".$array['tipo']."', '".$array['logica']."')";
}
else {
    $hasta=$array['hasta'];
    if ($array['id']<3){
        $hasta='NULL';
    }
    $sql="UPDATE promos SET nombre='".$array['nombre']."', codigo='".$array['codigo']."', envio_recoger='".$array['envio_recoger']."', usuario='".$array['usuario']."', grupo='".$array['grupo']."', dias='".$array['dias']."', desde='".$array['desde']."', hasta='".$hasta."', maximo='".$array['maximo']."', tipo='".$array['tipo']."', logica='".$array['logica']."' WHERE id='".$array['id']."'";
}



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result) {
    $checking=true;
}

$database->freeResults(); 

$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("zz-promo.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);

fclose($file);
*/

?>
