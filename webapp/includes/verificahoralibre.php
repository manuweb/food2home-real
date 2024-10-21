<?php
/*
 *
 * Archivo: verificahoralibre.php
 *
 * Version: 1.0.1
 * Fecha  : 18/09/2024
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

$solotr=$array['solotr'];

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

if ($solotr=='no'){
    $contadoenhora=0;   
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

    //$sql1="SELECT count(id) AS contado,hora,metodoEnvio from pedidos WHERE dia="'.date_format($fecha,'Y-m-d').'" AND estadoPago>=0 GROUP by hora, metodoEnvio;";
    
    $sql1="SELECT count(id) AS contado from pedidos WHERE dia='".date_format($fecha,'Y-m-d')."' AND estadoPago>=0 and hora='".$hora."' and metodoEnvio=".$envio.";";

    $database = DataBase::getInstance();
    $database->setQuery($sql1);
    $result = $database->execute();
    $checking=true;
    if ($result) {
        $horas = $result->fetch_object();
        $contado=$horas->contado;
        if ($envio==1){
            $maximo=$maximo_pedidosportramoenvio;
        }
        else {
            $maximo=$maximo_pedidosportramococina;
        }
        if ($contado>=$maximo){
            $checking=false;
        }
    }
    /*
    if ($result->num_rows>0) {
        
        $checking=true;
        while ($horas = $result->fetch_object()) {
            $horaEnc=$horas->hora;
            $metodoEnvio=$horas->metodoEnvio;
            
            $contado=$horas->contado;
            if ($hora==$horaEnc && $metodoEnvio==$metodo) {
                $contadoenhora=$contado;
                if ($metodoEnvio==1){
                    if ($contado>=$maximo_pedidosportramoenvio){

                    //
                        $checking=false;
                        break;
                    }

                }
                else {
                    if ($contado>=$maximo_pedidosportramococina){
                        $checking=false;
                        break;
                    }
                }

            }

        }

    }
    else {
        $checking=true;
    }
    $txt='';
    */

    $fecha2=date_create(substr($lafecha,6,4)."-".substr($lafecha,3,2)."-".substr($lafecha,0,2));
    $eshoy= date("Y-m-d");  
    $txt='NO Hoy';
    $fechabusco=date_format($fecha2,"Y-m-d");
    /*
    $fechabusco=date_format($fecha2,"Y-m-d");
    if ($fechabusco==$eshoy){
        $txt='Hoy';
        if ($hora<=date("H:i")){
            $txt='Hoy en hora:'.date("H:i");
        }
        else {
            $txt='Hoy pasado de hora:'.date("H:i");
        }
    }
    */
    
    /*
    $disponiblereparto=$array['disponiblereparto'];
    $disponiblecocina=$array['disponiblecocina'];
    //$disponiblecocina=['19:30'];
    if ($fechabusco==$eshoy){
        $txt='ES Hoy';
        if ($envio==1){

            if(is_array($disponiblereparto) ){
                $checking=false;
                $txt='ES Hoy';
                for ($x=0;$x<count($disponiblereparto);$x++){

                    if (date("H:i")<=$disponiblereparto[$x]){
                        $checking=true;
                    }
                }
            }   
        }
        else {
            if(is_array($disponiblecocina) ){
                $checking=false;
                $txt='ES Hoy '.date("H:i");
                for ($x=0;$x<count($disponiblecocina);$x++){

                    if (date("H:i")<=$disponiblecocina[$x]){
                        $checking=true;
                        $txt='ES Hoy '.date("H:i").'<='.$disponiblecocina[$x];
                    }
                }
            } 
        }
    }
    
    if ($envio==1&&($contadoenhora>=$maximo_pedidosportramoenvio)){
        $checking=false;
    }
    if ($envio==2&&($contadoenhora>=$maximo_pedidosportramococina)){
        $checking=false;
    }
    */
    
}
else {
    $checking=true;
    
}


$json=array("valid"=>$checking);
//$json=array("valid"=>$checking, "horario"=>$horarioreparto, "horariococina"=>$horariococina);

ob_end_clean();
echo json_encode($json);



/*
$file = fopen("zz2-verificahoras.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "maximo_pedidosportramococina: ". $maximo_pedidosportramococina . PHP_EOL);
fwrite($file, "maximo_pedidosportramoenvio: ". $maximo_pedidosportramoenvio . PHP_EOL);
fwrite($file, "Envio: ". $envio . PHP_EOL);
fwrite($file, "hora: ". $hora . PHP_EOL);
fwrite($file, "Contados: ". $contado . PHP_EOL);
fwrite($file, "Maximo: ". $maximo . PHP_EOL);
fwrite($file, "TXT: ". $txt . PHP_EOL);
//fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
