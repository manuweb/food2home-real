<?php
/*
 *
 * Archivo: mirapedidoshoy.php
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


$horarioreparto=$array['horario'];
$horariococina=$array['horariococina'];
$lafecha=$array['fecha'];
// 01/34/6789
$fecha=date_create(substr($lafecha,6,4)."-".substr($lafecha,3,2)."-".substr($lafecha,0,2));

$checking=false;
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
$sql1='SELECT count(id) AS contado,hora,metodoEnvio from pedidos WHERE dia="'.date_format($fecha,'Y-m-d').'" AND estadoPago>=0 GROUP by hora, metodoEnvio;';

$database = DataBase::getInstance();
$database->setQuery($sql1);
$result = $database->execute();



/*
$db = DataBase::getInstance();  
$db->setQuery($sql);  
$horas = $db->loadObjectList();  
*/
$pilladosEnvio=[];
$pilladosRecoger=[];
if ($result->num_rows>0) {
    $checking=true;
    
    while ($horas = $result->fetch_object()) {
        $hora=$horas->hora;
        $metodoEnvio=$horas->metodoEnvio;
        $contado=$horas->contado;
        if ($metodoEnvio==1){
            // enviar
            if (is_array($horarioreparto)){
                $y=array_search($hora, $horarioreparto);
                if ($y>=0) {
                    if($contado>=$maximo_pedidosportramoenvio){
                        $pilladosEnvio[]=$hora;
                        array_splice($horarioreparto, $y, 1);
                        //unset($horarioreparto[$y]);
                    }
                }
            }
        }
        else {
            //recoger
            if (is_array($horariococina)){
                $z=array_search($hora, $horariococina);
                if ($z>=0) {
                    if($contado>=$maximo_pedidosportramococina){
                        $pilladosRecoger[]=$hora;
                        array_splice($horariococina, $z, 1);
                        //unset($horariococina[$z]);
                    }
                }
            }
        }
        
    }
    if (is_array($horariococina)){
        if (count($horariococina)==0){
            $horariococina=null;
        }
    }
    if (is_array($horarioreparto)){
        if (count($horarioreparto)==0){
            $horarioreparto=null;
        }
    }
    
}


if (count($pilladosEnvio)>0){
    $libreE=array_diff($array['horario'], $pilladosEnvio);
    $libreED=$array['horario'];
    if (is_array($libreE)){
        unset($libreED);
        foreach($libreE as $key => $value) { 
            $libreED[] =$value ; 
        }
    }
}
else {
    $libreED=$array['horario'];
}

if (count($pilladosRecoger)>0){
    $libreR=array_diff($array['horariococina'], $pilladosRecoger);
    $libreRD=$array['horariococina'];
    if (is_array($libreR)){
        unset($libreRD);
        foreach($libreR as $key => $value) { 
            $libreRD[] =$value ; 
        }
    }
    
}
else {
    $libreRD=$array['horariococina'];
}



$database->freeResults(); 





$checking=true;

$json=array("valid"=>$checking, "horario"=>$libreED, "horariococina"=>$libreRD);
//$json=array("valid"=>$checking, "horario"=>$horarioreparto, "horariococina"=>$horariococina);

ob_end_clean();
echo json_encode($json);

/*
$file = fopen("zz-mirapedidoshoy.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "maximo_pedidosportramococina: ". $maximo_pedidosportramococina . PHP_EOL);
fwrite($file, "maximo_pedidosportramoenvio: ". $maximo_pedidosportramoenvio . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fwrite($file, "ENV: ". json_encode($array['horario']) . PHP_EOL);
fwrite($file, "REC: ". json_encode($array['horariococina']) . PHP_EOL);

fwrite($file, "p-ENV: ". json_encode($pilladosEnvio) . PHP_EOL);
fwrite($file, "p-REC: ". json_encode($pilladosRecoger) . PHP_EOL);
fwrite($file, "d-ENV: ". json_encode($libreED) . PHP_EOL);
fwrite($file, "d-REC: ". json_encode($libreRD) . PHP_EOL);

fclose($file);
*/

?>