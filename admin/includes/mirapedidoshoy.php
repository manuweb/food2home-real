<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
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

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


$array = json_decode(json_encode($_POST), true);

$checking=false;




//$horarioreparto=$array['horario'];
//$horariococina=$array['horariococina'];


$modo=$array['modo'];
$lafecha=$array['fecha'];

//$modo=2;
//$lafecha='20/03/2024';

// 01/34/6789
$fecha=date_create(substr($lafecha,6,4)."-".substr($lafecha,3,2)."-".substr($lafecha,0,2));

$checking=false;
$hoy=date_format($fecha,'w');
$busco='lpp';//pedidos por tramo
$buscod1='ld1';
$buscod2='ld2';
$buscoh1='lh1';
$buscoh2='lh2';
$buscop='lp';
$buscodia='lunes';

switch ($hoy) {
    case "0":
        $busco='dpp';
        $buscod1='dd1';
        $buscod2='dd2';
        $buscoh1='dh1';
        $buscoh2='dh2';
        $buscop='dp';
        $buscodia='domingo';
        break;
    case "1":
        $busco='lpp';
        $buscod1='ld1';
        $buscod2='ld2';
        $buscoh1='lh1';
        $buscoh2='lh2';
        $buscop='lp';
        $buscodia='lunes';
        break;
    case "2":
        $busco='mpp';
        $buscod1='md1';
        $buscod2='md2';
        $buscoh1='mh1';
        $buscoh2='mh2';
        $buscop='mp';
        $buscodia='martes';
        break;
    case "3":
        $busco='xpp';
        $buscod1='xd1';
        $buscod2='xd2';
        $buscoh1='xh1';
        $buscoh2='xh2';
        $buscop='xp';
        $buscodia='miercoles';
        break;
    case "4":
        $busco='jpp';
        $buscod1='jd1';
        $buscod2='jd2';
        $buscoh1='jh1';
        $buscoh2='jh2';
        $buscop='jp';
        $buscodia='jueves';
        break;
    case "5":
        $busco='vpp';
        $buscod1='vd1';
        $buscod2='vd2';
        $buscoh1='vh1';
        $buscoh2='vh2';
        $buscop='vp';
        $buscodia='viernes';
        break;
    case "6":
        $busco='spp';
        $buscod1='sd1';
        $buscod2='sd2';
        $buscoh1='sh1';
        $buscoh2='sh2';
        $buscop='sp';
        $buscodia='sabado';
        break;
}
$db='horas_cocina';
if ($modo==1) {
    $db='horas_repartos';
}

$sql='SELECT horas_cocina.'.$busco.' AS maximo_pedidosportramococina, horas_repartos.'.$busco.' AS maximo_pedidosportramoenvio, opcionescompra.tiempoenvio AS tiempoenvio  FROM horas_cocina LEFT JOIN horas_repartos ON horas_repartos.id=horas_cocina.id LEFT JOIN opcionescompra ON opcionescompra.id=horas_cocina.id  WHERE horas_cocina.id=1;';

//echo $sql;
//echo "<hr>";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$maximo = $result->fetch_object();

$maximo_pedidosportramococina=$maximo->maximo_pedidosportramococina;
$maximo_pedidosportramoenvio=$maximo->maximo_pedidosportramoenvio;
$tiempoenvio=$maximo->tiempoenvio;
$maximo_pedidosportramo=$maximo_pedidosportramococina;
if ($modo==1) {
    $maximo_pedidosportramo=$maximo_pedidosportramoenvio;
}

$database->freeResults(); 


$sql="select intervalo, ".$buscod1." as desde1, ".$buscoh1." as hasta1, ".$buscod2." as desde2, ".$buscoh2." as hasta2, ".$buscodia." as dia, ".$buscop." as partido FROM ".$db." Where id=1";




$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
$horarios = $result->fetch_object();

$intervalo=$horarios->intervalo;
$desde1=$horarios->desde1;
$hasta1=$horarios->hasta1;
$desde2=$horarios->desde2;
$hasta2=$horarios->hasta2;
$dia=$horarios->dia;
$partido=$horarios->partido;

$database->freeResults(); 

/*
echo "Modo:".$modo;
echo "<hr>";
echo "tabla:".$db;
echo "<hr>";
echo $sql;
echo "<hr>";
echo "busco:".$buscodia;
echo "<hr>";
echo "dia:".$dia;
echo "<hr>";
*/
$hoy1 = getdate();
echo "<pre>";
/*
Array
(
    [seconds] => 15
    [minutes] => 27
    [hours] => 10
    [mday] => 20
    [wday] => 3
    [mon] => 3
    [year] => 2024
    [yday] => 79
    [weekday] => Wednesday
    [month] => March
    [0] => 1710926835
)
*/
print_r($hoy1);
echo "</pre>";
$horario=[];
$ahora=date("Y-m-d H:".$hoy1['minutes'].":s");
echo "ahora1:".$ahora."<br>";

$NuevaFecha = strtotime ( '+'.$tiempoenvio.' minute' , strtotime($ahora ) ); 
$ahora = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
//$tiempoenvio

echo "tiempoenvio:".$tiempoenvio."<br>";
echo "ahora2:".$ahora."<br>";

echo date_format($fecha,'Y-m-d');
echo "<br>";
echo substr($ahora,0,10);
echo "<br>";
$eshoy=false;
if (date_format($fecha,'Y-m-d')==substr($ahora,0,10)){
    echo "Es hoy<br>";
    $eshoy=true;
}
echo "<br>";
if ($dia==1){// hay horas

    
    $intSeg=$intervalo*60000;
    if ($hasta1=='00:00:00'){
        $hasta1='23:59:59';
    }
    if ($hasta2=='00:00:00'){
        $hasta2='23:59:59';  
    }
            
    $desde=date("Y-m-d ".$desde1);
    $hasta=date("Y-m-d ".$hasta1);
    
    /*
    echo "Desde:".$desde."<br>";
    echo "Hasta:".$hasta."<br>";
    echo "Intervalo:".$intervalo."<br>";
    echo "<hr>";    
    */
    
    $x=0;
    while($desde <= $hasta){ // 0123-56-89 12:45:78
        //$horario[$x]=substr($desde,11,5);
        if ($eshoy){
        
            if ($desde>=$ahora){
                $horario[$x]=substr($desde,11,5);
                $horacogida[$x]['hora']=$horario[$x];
                $horacogida[$x]['cantidad']=0;
                $horacogida[$x]['disponible']=1;

            //echo "Horario[".$x."]:".$horario[$x]."<br>";
                $x++;
            }
        }
        else {
            $horario[$x]=substr($desde,11,5);
            $horacogida[$x]['hora']=$horario[$x];
            $horacogida[$x]['cantidad']=0;
            $horacogida[$x]['disponible']=1;

        //echo "Horario[".$x."]:".$horario[$x]."<br>";
            $x++;
        }
        
        $NuevaFecha = strtotime ( '+'.$intervalo.' minute' , strtotime($desde ) ); 
        $desde = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
        
        //echo "Desde:".$desde."<br>";
        //break;
    }
    if ($partido==1){
        //horario partido
        $desde=date("Y-m-d ".$desde2);
        $hasta=date("Y-m-d ".$hasta2);
        //echo "Desde:".$desde."<br>";
        //echo "Hasta:".$hasta."<br>";
        
        while($desde <= $hasta){
            //$horario[$x]=substr($desde,11,5);
            if ($eshoy){
        
                if ($desde>=$ahora){
                    $horario[$x]=substr($desde,11,5);
                    $horacogida[$x]['hora']=$horario[$x];
                    $horacogida[$x]['cantidad']=0;
                    $horacogida[$x]['disponible']=1;

                //echo "Horario[".$x."]:".$horario[$x]."<br>";
                    $x++;
                }
            }
            else {
                $horario[$x]=substr($desde,11,5);
                $horacogida[$x]['hora']=$horario[$x];
                $horacogida[$x]['cantidad']=0;
                $horacogida[$x]['disponible']=1;

            //echo "Horario[".$x."]:".$horario[$x]."<br>";
                $x++;
            }
            $NuevaFecha = strtotime ( '+'.$intervalo.' minute' , strtotime($desde ) ); 
            $desde = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
            //echo "Desde:".$desde."<br>";
            //break;
        }
    }
    
}




//echo "máximo:".$maximo_pedidosportramo;
//echo "<hr>";




$sql1='SELECT count(id) AS contado,hora,metodoEnvio from pedidos WHERE dia="'.date_format($fecha,'Y-m-d').'" AND metodoEnvio='.$modo.' AND estadoPago>=0 GROUP by hora, metodoEnvio;';



$database = DataBase::getInstance();
$database->setQuery($sql1);
$result = $database->execute();

echo $sql1;
echo "<hr>";


$pillados=[];

if ($result->num_rows>0) {
    $checking=true;
    
    while ($horas = $result->fetch_object()) {
        $hora=$horas->hora;
        $metodoEnvio=$horas->metodoEnvio;
        $contado=$horas->contado;
        echo "Hora buscada:".$hora."<br>";
        //if (is_array($horario)){
            $y=array_search($hora, $horario,true);
            if ($y>=0) {
                
                echo "encontrada->".$horario[$y]."<br>";
                echo "corresponde->". $horacogida[$y]['hora']."<br>";
                $horacogida[$y]['cantidad']=$contado;
                echo "hay->".$contado."<br>";
                if ($horacogida[$y]['hora']==$hora){
                
                if($contado>=$maximo_pedidosportramo){
                    
                    $horacogida[$y]['disponible']=0;
                    $pillados[]=$hora;
                    //array_splice($horario, $y, 1);
                    //unset($horarioreparto[$y]);
                }
                }
            }
        //}
        
        
    }
    if (is_array($horario)){
        if (count($horario)==0){
            $horario=null;
        }
    }

    
}

echo "<pre>";
print_r ($pillados);
echo "</pre>";


if (count($pillados)>0){
    $libreE=array_diff($horario, $pillados);
    $libreED=$horario;
    if (is_array($libreE)){
        unset($libreED);
        foreach($libreE as $key => $value) { 
            $libreED[] =$value ; 
        }
    }
}
else {
    $libreED=$horario;
}


echo "<pre>";
print_r ($libreED);
echo "</pre>";

//echo "<pre>";
//print_r ($horacogida);
//echo "</pre>";
////die();

$database->freeResults(); 





$checking=true;

//$json=array("valid"=>$checking, "horario"=>$libreED,"pillados"=>$pillados );
$json=array("valid"=>$checking, "horario"=>$horacogida );


ob_end_clean();
echo json_encode($json);

/*
$file = fopen("zz-mirapedidoshoy.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "maximo_pedidosportramo: ". $maximo_pedidosportramo . PHP_EOL);
$maximo_pedidosportramoenvio . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fwrite($file, "ENV: ". json_encode($horario) . PHP_EOL);

fwrite($file, "p-ENV: ". json_encode($pillados) . PHP_EOL);
fwrite($file, "d-ENV: ". json_encode($libreED) . PHP_EOL);


fclose($file);

*/
?>