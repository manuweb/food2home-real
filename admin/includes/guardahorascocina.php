<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');


$array = json_decode(json_encode($_POST), true);

$checking=false;


if($array['chklunes']=='false'){
    $array['chklunes']=0;
}
else {
    $array['chklunes']=1;
}

if($array['chkmartes']=='false'){
    $array['chkmartes']=0;
}
else {
    $array['chkmartes']=1;
}

if($array['chkmiercoles']=='false'){
    $array['chkmiercoles']=0;
}
else {
    $array['chkmiercoles']=1;
}

if($array['chkjueves']=='false'){
    $array['chkjueves']=0;
}
else {
    $array['chkjueves']=1;
}

if($array['chkviernes']=='false'){
    $array['chkviernes']=0;
}
else {
    $array['chkviernes']=1;
}

if($array['chksabado']=='false'){
    $array['chksabado']=0;
}
else {
    $array['chksabado']=1;
}

if($array['chkdomingo']=='false'){
    $array['chkdomingo']=0;
}
else {
    $array['chkdomingo']=1;
}

if($array['chklp']=='false'){
    $array['chklp']=0;
}
else {
    $array['chklp']=1;
}

if($array['chkmp']=='false'){
    $array['chkmp']=0;
}
else {
    $array['chkmp']=1;
}

if($array['chkxp']=='false'){
    $array['chkxp']=0;
}
else {
    $array['chkxp']=1;
}

if($array['chkjp']=='false'){
    $array['chkjp']=0;
}
else {
    $array['chkjp']=1;
}

if($array['chkvp']=='false'){
    $array['chkvp']=0;
}
else {
    $array['chkvp']=1;
}
if($array['chksp']=='false'){
    $array['chksp']=0;
}
else {
    $array['chksp']=1;
}
if($array['chkdp']=='false'){
    $array['chkdp']=0;
}
else {
    $array['chkdp']=1;
}


//intervalo:intervalo, chklunes:chklunes, chklp:chklp, ld1:ld1, lh1:lh1, ld2:ld2, lh2:lh2, chkmartes:chkmartes, chkmp:chkmp, md1:ld1, mh1:mh1, md2:md2, mh2:mh2, chkmiercoles:chkmiercoles, chkxp:chkxp, xd1:xd1, xh1:xh1, xd2:ld2, xh2:xh2, chkjueves:chkjueves, chkjp:chkjp, jd1:jd1, jh1:jh1, jd2:jd2, jh2:jh2, chkviernes:chkviernes, chkvp:chkvp, vd1:vd1, vh1:vh1, vd2:vd2, vh2:vh2, chksabado:chksabado, chksp:chksp, sd1:sd1, sh1:sh1, sd2:sd2, sh2:sh2, chkdomingo:chkdomingo, chkdp:chkdp, dd1:dd1, dh1:dh1, dd2:dd2, dh2:dh2


$sql="UPDATE horas_cocina SET intervalo='".$array['intervalo']."', ld1='".$array['ld1']."', lh1='".$array['lh1']."', ld2='".$array['ld2']."', lh2='".$array['lh2']."', md1='".$array['md1']."', mh1='".$array['mh1']."', md2='".$array['md2']."', mh2='".$array['mh2']."', xd1='".$array['xd1']."', xh1='".$array['xh1']."', xd2='".$array['xd2']."', xh2='".$array['xh2']."', jd1='".$array['jd1']."', jh1='".$array['jh1']."', jd2='".$array['jd2']."', jh2='".$array['jh2']."', vd1='".$array['vd1']."', vh1='".$array['vh1']."', vd2='".$array['vd2']."', vh2='".$array['vh2']."', sd1='".$array['sd1']."', sh1='".$array['sh1']."', sd2='".$array['sd2']."', sh2='".$array['sh2']."', dd1='".$array['dd1']."', dh1='".$array['dh1']."', dd2='".$array['dd2']."', dh2='".$array['dh2']."', lunes='".$array['chklunes']."', martes='".$array['chkmartes']."', miercoles='".$array['chkmiercoles']."', jueves='".$array['chkjueves']."', viernes='".$array['chkviernes']."', sabado='".$array['chksabado']."', domingo='".$array['chkdomingo']."', lp='".$array['chklp']."', mp='".$array['chkmp']."', xp='".$array['chkxp']."', jp='".$array['chkjp']."', vp='".$array['chkvp']."', sp='".$array['chksp']."', dp='".$array['chkdp']."', lpp='".$array['lpp']."', mpp='".$array['mpp']."', xpp='".$array['xpp']."', jpp='".$array['jpp']."', vpp='".$array['vpp']."', spp='".$array['spp']."', dpp='".$array['dpp']."'  Where id=1";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result) {
    
    $checking=true;
}
$database->freeResults();

$json=array("valid"=>$checking);

echo json_encode($json); 



?>
