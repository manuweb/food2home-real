<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


//$pagina=2;
$pagina=$array['pagina'];

$filtro='1';
if ($array['filtro']!=''){
    $filtro=" apellidos  LIKE '%".$array['filtro']."%'";
}


$checking=false;
$tamPagina=12;


$sql="SELECT 'N' AS tipo, pedidos_clientes.tratamiento AS tratamiento, pedidos_clientes.apellidos AS apellidos , pedidos_clientes.nombre AS nombre, pedidos_clientes.email AS email, pedidos_clientes.publicidad as publicidad, pedidos_clientes.telefono as telefono, '0' AS monedero FROM `pedidos_clientes` WHERE ".$filtro." GROUP BY pedidos_clientes.email  UNION SELECT 'S' AS tipo, usuarios_app.tratamiento AS tratamiento, usuarios_app.apellidos as apellidos  , usuarios_app.nombre as nombre, usuarios_app.username as email, usuarios_app.publicidad as publicidad, usuarios_app.telefono as telefono,usuarios_app.monedero as monedero FROM usuarios_app WHERE ".$filtro." ORDER BY apellidos, nombre;";
$sumamonedero=0;
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

    
$numReg=$result->num_rows;
while ($clientesM = $result->fetch_object()) {
    $sumamonedero+=$clientesM->monedero;
}
$limitInf=($pagina-1)*$tamPagina;

$sql="SELECT 'N' AS tipo, pedidos_clientes.tratamiento AS tratamiento, pedidos_clientes.apellidos AS apellidos , pedidos_clientes.nombre AS nombre, pedidos_clientes.email AS email, pedidos_clientes.publicidad as publicidad, pedidos_clientes.telefono as telefono, '0' AS monedero, '0' AS idCliente FROM `pedidos_clientes` WHERE ".$filtro." GROUP BY pedidos_clientes.email  UNION SELECT 'S' AS tipo, usuarios_app.tratamiento AS tratamiento, usuarios_app.apellidos as apellidos  , usuarios_app.nombre as nombre, usuarios_app.username as email, usuarios_app.publicidad as publicidad, usuarios_app.telefono as telefono,usuarios_app.monedero as monedero, usuarios_app.id AS idCliente FROM usuarios_app WHERE ".$filtro." ORDER BY apellidos, nombre DESC LIMIT ".$limitInf.",".$tamPagina.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    $n=0;
    while ($clientes = $result->fetch_object()) {
        $tipo[$n]=$clientes->tipo;
        $idCliente[$n]=$clientes->idCliente;
        $tratamiento[$n]=$clientes->tratamiento;
        $apellidos[$n]=$clientes->apellidos;
        $nombre[$n]=$clientes->nombre;
        $email[$n]=$clientes->email;
        $telefono[$n]=$clientes->telefono;
        $publicidad[$n]=$clientes->publicidad;
        $monedero[$n]=$clientes->monedero;
        $n++;
    }
  
}	

$database->freeResults();  

$json=array("valid"=>$checking,"tipo"=>$tipo,"idCliente"=>$idCliente,"tratamiento"=>$tratamiento,"apellidos"=>$apellidos,"nombre"=>$nombre,"email"=>$email,"telefono"=>$telefono,"publicidad"=>$publicidad,"monedero"=>$monedero,"registros"=>$numReg, "sumamonedero"=>$sumamonedero);

echo json_encode($json); 

/*
$file = fopen("zz-leeclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
