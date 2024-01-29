<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$checking=false;


$sql="select intervalo, ld1, lh1, ld2, lh2, md1, mh1, md2, mh2, xd1, xh1, xd2, xh2, jd1, jh1, jd2, jh2, vd1, vh1, vd2, vh2, sd1, sh1, sd2, sh2, dd1, dh1, dd2, dh2, lunes, martes, miercoles, jueves, viernes, sabado, domingo, lp, mp, xp, jp, vp, sp, dp, lpp, mpp, xpp, jpp, vpp, spp, dpp FROM horas_cocina Where id=1";


$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    $grupo = $result->fetch_object();
    $ld1=$grupo->ld1;
    $lh1=$grupo->lh1;
    $ld2=$grupo->ld2;
    $lh2=$grupo->lh2;
    $md1=$grupo->md1;
    $mh1=$grupo->mh1;
    $md2=$grupo->md2;
    $mh2=$grupo->mh2; 
    $xd1=$grupo->xd1;
    $xh1=$grupo->xh1;
    $xd2=$grupo->xd2;
    $xh2=$grupo->xh2;
    $jd1=$grupo->jd1;
    $jh1=$grupo->jh1;
    $jd2=$grupo->jd2;
    $jh2=$grupo->jh2; 
    $vd1=$grupo->vd1;
    $vh1=$grupo->vh1;
    $vd2=$grupo->vd2;
    $vh2=$grupo->vh2;
    $sd1=$grupo->sd1;
    $sh1=$grupo->sh1;
    $sd2=$grupo->sd2;
    $sh2=$grupo->sh2; 
    $dd1=$grupo->dd1;
    $dh1=$grupo->dh1;
    $dd2=$grupo->dd2;
    $dh2=$grupo->dh2; 
    $lpp=$grupo->lpp; 
    $mpp=$grupo->mpp;
    $xpp=$grupo->xpp;
    $jpp=$grupo->jpp;
    $vpp=$grupo->vpp;
    $spp=$grupo->spp;
    $dpp=$grupo->dpp;
    
    $intervalo= $grupo->intervalo; 
    $lunes=$grupo->lunes; 
    $martes=$grupo->martes; 
    $miercoles=$grupo->miercoles; 
    $jueves=$grupo->jueves; 
    $viernes=$grupo->viernes; 
    $sabado=$grupo->sabado; 
    $domingo=$grupo->domingo; 
    
    $lp=$grupo->lp; 
    $mp=$grupo->mp;
    $xp=$grupo->xp; 
    $jp=$grupo->jp;
    $vp=$grupo->vp; 
    $sp=$grupo->sp;
    $dp=$grupo->dp;
    
    $checking=true;
}

$database->freeResults();


$json=array("valid"=>$checking, 
            "ld1"=>$ld1, "lh1"=>$lh1, "ld2"=>$ld2, "lh2"=>$lh2, 
            "md1"=>$md1, "mh1"=>$mh1, "md2"=>$md2, "mh2"=>$mh2, 
            "xd1"=>$xd1, "xh1"=>$xh1, "xd2"=>$xd2, "xh2"=>$xh2, 
            "jd1"=>$jd1, "jh1"=>$jh1, "jd2"=>$xd2, "jh2"=>$xh2,  
            "vd1"=>$vd1, "vh1"=>$vh1, "vd2"=>$vd2, "vh2"=>$vh2, 
            "sd1"=>$sd1, "sh1"=>$sh1, "sd2"=>$sd2, "sh2"=>$sh2,
            "dd1"=>$dd1, "dh1"=>$dh1, "dd2"=>$dd2, "dh2"=>$dh2,
            "intervalo"=>$intervalo, "lunes"=>$lunes, "martes"=>$martes, "miercoles"=>$miercoles, "jueves"=>$jueves, "viernes"=>$viernes, "sabado"=>$sabado, "domingo"=>$domingo, "lp"=>$lp, "mp"=>$mp, "xp"=>$xp, "jp"=>$jp, "vp"=>$vp, "sp"=>$sp, "dp"=>$dp, "lpp"=>$lpp, "mpp"=>$mpp, "xpp"=>$xpp, "jpp"=>$jpp, "vpp"=>$vpp, "spp"=>$spp, "dpp"=>$dpp);


echo json_encode($json); 
/*
$file = fopen("texto.txt", "w");
fwrite($file, "coor: ".count($zona). PHP_EOL);

fclose($file);

*/
?>
