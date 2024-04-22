<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);




$checking=false;


$sql="SELECT 'N' AS id,  pedidos_clientes.apellidos AS apellidos , pedidos_clientes.nombre AS nombre, pedidos_clientes.email AS email,  pedidos_clientes.telefono as telefono FROM pedidos_clientes GROUP BY pedidos_clientes.email  UNION SELECT usuarios_app.id AS id,  usuarios_app.apellidos as apellidos  , usuarios_app.nombre as nombre, usuarios_app.username as email, usuarios_app.telefono as telefono FROM usuarios_app  ORDER BY  apellidos, nombre;";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $checking=true;
    
    while ($clientes = $result->fetch_object()) {
        $elcliente[]=array(
            'id'=>$clientes->id,
            'apellidos'=>$clientes->apellidos,
            'nombre'=>$clientes->nombre,
            'apenom'=>$clientes->apellidos.', '.$clientes->nombre,
            'email'=>$clientes->email,
            'telefono'=>$clientes->telefono
        );
        
    }
    
    
}	

$database->freeResults();  

$json=array("valid"=>$checking,"clientes"=>$elcliente);

echo json_encode($json); 

/*
$file = fopen("zz-leeclientes.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fclose($file);
*/

?>
