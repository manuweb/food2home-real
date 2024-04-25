<?php
/*
 *
 * Archivo: verificahoralibre.php
 *
 * Version: 1.0.0
 * Fecha  : 18/08/2023
 * Se usa en :caomprar.js ->checkout_3) 
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');


include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


$array = json_decode(json_encode($_POST), true);

$checking=false;


$envio=$array['envio'];
$hora=$array['hora'];

$lafecha=$array['dia'];
// 01/34/6789
$fecha=date_create(substr($lafecha,6,4)."-".substr($lafecha,3,2)."-".substr($lafecha,0,2));

$checking=false;
//$hoy=date('w');
$hoy=date_format($fecha,'w');

$busco='lpp';
switch ($hoy) {
    case "0":
        $busco='dpp';
        break;
    case "1":
        $busco='lpp';
        break;
    case "2":
        $busco='mpp';
        break;
    case "3":
        $busco='xpp';
        break;
    case "4":
        $busco='jpp';
        break;
    case "5":
        $busco='vpp';
        break;
    case "6":
        $busco='spp';
        break;
}


$sql='SELECT horas_cocina.'.$busco.' AS maximo_pedidosportramococina, horas_repartos.'.$busco.' AS maximo_pedidosportramoenvio FROM horas_cocina LEFT JOIN horas_repartos ON horas_repartos.id=horas_cocina.id  WHERE horas_cocina.id=1;';

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$maximo = $result->fetch_object();

$maximo_pedidosportramococina=$maximo->maximo_pedidosportramococina;
$maximo_pedidosportramoenvio=$maximo->maximo_pedidosportramoenvio;

$database->freeResults(); 
/*
$db = DataBase::getInstance();  
$db->setQuery($sql);  
$maximo = $db->loadObjectList();  
*/
//$db->freeResults();

$envio=$array['envio'];
$hora=$array['hora'];

//$sql1='SELECT count(id) AS contado,hora,metodoEnvio from pedidos WHERE fecha LIKE "'.date('Y').'-'.date('m').'-'.date('d').'%" AND estadoPago>=0  AND metodoEnvio='.$envio.' AND hora="'.$hora.'" GROUP by hora, metodoEnvio;';

$sql1='SELECT count(id) AS contado,hora,metodoEnvio from pedidos WHERE dia="'.date_format($fecha,'Y-m-d').'" AND estadoPago>=0 GROUP by hora, metodoEnvio;';


$database = DataBase::getInstance();
$database->setQuery($sql1);
$result = $database->execute();


if ($result->num_rows>0) {
    while ($horas = $result->fetch_object()) {
    
        $horaEnc=$horas->hora;
        $metodoEnvio=$horas->metodoEnvio;
        $contado=$horas->contado;
        if ($hora==$horaEnc){
            if ($metodoEnvio==1){
                if($contado>=$maximo_pedidosportramoenvio){
                    //
                    $checking=false;
                }
                else {
                    $checking=true;
                }
            }
            else {
                if($contado>=$maximo_pedidosportramococina){
                    $checking=false;
                }
                else {
                    $checking=true;
                }
            }
        }
        else {
            $checking=true;
        }
            


    }
}
else {
    $checking=true;
}






$json=array("valid"=>$checking);
//$json=array("valid"=>$checking, "horario"=>$horarioreparto, "horariococina"=>$horariococina);

ob_end_clean();
echo json_encode($json);

/*
$file = fopen("zz-verificahoras.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "maximo_pedidosportramococina: ". $maximo_pedidosportramococina . PHP_EOL);
fwrite($file, "maximo_pedidosportramoenvio: ". $maximo_pedidosportramoenvio . PHP_EOL);
fwrite($file, "Envio: ". $envio . PHP_EOL);
fwrite($file, "hora: ". $hora . PHP_EOL);
fwrite($file, "Contados: ". $contado . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);

*/
?>
