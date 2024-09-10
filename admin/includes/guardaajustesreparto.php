<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="UPDATE opcionescompra SET tipo_repartos='".$array['tipo_repartos']."', minimo='".$array['minimo']."', tiempoenvio='".$array['tiempoenvio']."', cortesia='".$array['cortesia']."', maximocarrito='".$array['maximocarrito']."', maximoproducto='".$array['maximoproducto']."', pedidosportramoenvio='".$array['pedidosportramoenvio']."', pedidosportramococina='".$array['pedidosportramococina']."', idBolsa='".$array['bolsa']."', 
idEnvio='".$array['portes']."', 
tarifa='".$array['tarifa']."', 
portesgratis='".$array['portesgratis']."', 
importeportesgratis='".$array['importeportesgratis']."', 
portesgratismensaje='".$array['portesgratismensaje']."', norepartomensaje='".$array['norepartomensaje']."', tipo_seleccion_horas='".$array['tipo_seleccion_horas']."', dias_vista='".$array['dias_vista']."' WHERE id=1";

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
$file = fopen("texto.txt", "w");
fwrite($file, "coor: ".count($zona). PHP_EOL);

fclose($file);

*/
?>
