<?php
/*
 *
 * Archivo: leehorarioscocina.php
 *
 * Version: 1.0.0
 * Fecha  : 29/11/2022
 * Se usa en :comprar.js ->leehorariosreparto() devuelve las horeas de reparto 
 *                                              para diascocina[] (para recogida)
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";


$checking=false;


$sql="select intervalo, ld1, lh1, ld2, lh2, md1, mh1, md2, mh2, xd1, xh1, xd2, xh2, jd1, jh1, jd2, jh2, vd1, vh1, vd2, vh2, sd1, sh1, sd2, sh2, dd1, dh1, dd2, dh2, lunes, martes, miercoles, jueves, viernes, sabado, domingo, lp, mp, xp, jp, vp, sp, dp FROM horas_cocina Where id=1";



$db = DataBase::getInstance();  
$db->setQuery($sql);  
$grupo = $db->loadObjectList();  
$db->freeResults();


if (count($grupo)>0) { 
    $ld1=$grupo[0]->ld1;
    $lh1=$grupo[0]->lh1;
    $ld2=$grupo[0]->ld2;
    $lh2=$grupo[0]->lh2;
    $md1=$grupo[0]->md1;
    $mh1=$grupo[0]->mh1;
    $md2=$grupo[0]->md2;
    $mh2=$grupo[0]->mh2; 
    $xd1=$grupo[0]->xd1;
    $xh1=$grupo[0]->xh1;
    $xd2=$grupo[0]->xd2;
    $xh2=$grupo[0]->xh2;
    $jd1=$grupo[0]->jd1;
    $jh1=$grupo[0]->jh1;
    $jd2=$grupo[0]->jd2;
    $jh2=$grupo[0]->jh2; 
    $vd1=$grupo[0]->vd1;
    $vh1=$grupo[0]->vh1;
    $vd2=$grupo[0]->vd2;
    $vh2=$grupo[0]->vh2;
    $sd1=$grupo[0]->sd1;
    $sh1=$grupo[0]->sh1;
    $sd2=$grupo[0]->sd2;
    $sh2=$grupo[0]->sh2; 
    $dd1=$grupo[0]->dd1;
    $dh1=$grupo[0]->dh1;
    $dd2=$grupo[0]->dd2;
    $dh2=$grupo[0]->dh2; 
    
    $intervalo= $grupo[0]->intervalo; 
    $lunes=$grupo[0]->lunes; 
    $martes=$grupo[0]->martes; 
    $miercoles=$grupo[0]->miercoles; 
    $jueves=$grupo[0]->jueves; 
    $viernes=$grupo[0]->viernes; 
    $sabado=$grupo[0]->sabado; 
    $domingo=$grupo[0]->domingo; 
    
    $lp=$grupo[0]->lp; 
    $mp=$grupo[0]->mp;
    $xp=$grupo[0]->xp; 
    $jp=$grupo[0]->jp;
    $vp=$grupo[0]->vp; 
    $sp=$grupo[0]->sp;
    $dp=$grupo[0]->dp;
    
    $checking=true;
}


$json=array("valid"=>$checking, 
            "ld1"=>$ld1, "lh1"=>$lh1, "ld2"=>$ld2, "lh2"=>$lh2, 
            "md1"=>$md1, "mh1"=>$mh1, "md2"=>$md2, "mh2"=>$mh2, 
            "xd1"=>$xd1, "xh1"=>$xh1, "xd2"=>$xd2, "xh2"=>$xh2, 
            "jd1"=>$jd1, "jh1"=>$jh1, "jd2"=>$jd2, "jh2"=>$jh2,  
            "vd1"=>$vd1, "vh1"=>$vh1, "vd2"=>$vd2, "vh2"=>$vh2, 
            "sd1"=>$sd1, "sh1"=>$sh1, "sd2"=>$sd2, "sh2"=>$sh2,
            "dd1"=>$dd1, "dh1"=>$dh1, "dd2"=>$dd2, "dh2"=>$dh2,
            "intervalo"=>$intervalo, "lunes"=>$lunes, "martes"=>$martes, "miercoles"=>$miercoles, "jueves"=>$jueves, "viernes"=>$viernes, "sabado"=>$sabado, "domingo"=>$domingo, "lp"=>$lp, "mp"=>$mp, "xp"=>$xp, "jp"=>$jp, "vp"=>$vp, "sp"=>$sp, "dp"=>$dp);

ob_end_clean();

echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "jason: ".json_encode($json). PHP_EOL);

fclose($file);
*/
?>
