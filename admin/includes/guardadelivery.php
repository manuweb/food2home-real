<?php 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

$array = json_decode(json_encode($_POST), true);


$checking=false;

$valores=$array['valores'];

$sql="SELECT logica FROM delivery WHERE id=".$array['id'].';';

//file_put_contents('zz-delivery.txt', print_r($array, true));

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

if ($result) {
    $integra = $result->fetch_object();
    $logica=$integra ->logica;
    
    $sql="UPDATE integracion SET delivery=".$array['id']." WHERE id=1";
    
    //file_put_contents('zz-delivery.txt', print_r($sql,true));
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $checking=true;
 
    $lineas_logica=explode("**||**", $logica);
    
    $txt='';

    for ($x=0;$x<count($lineas_logica);$x++){

        $la_logica=explode("#||#", $lineas_logica[$x]);

        $la_linea=preg_replace('/{/i','',$la_logica[0]);
        $la_linea=preg_replace('/}/i','',$la_linea);
        $variables=explode("|", $la_linea);
        $nuevo='{'.$variables[0].'|'.$variables[1].'|'.$variables[2].'}'."#||#";
       
        for ($j=0;$j<count($valores);$j++){
            if ($valores[$j]['variable']==$variables[0]){
                $nuevo.=$valores[$j]['valor'];
                if ($x<(count($lineas_logica)-1)){
                    $nuevo.="**||**";
                }
            }
        }
        $txt.=$nuevo;
    }
    
    $sql="UPDATE delivery SET logica='".$txt."' WHERE id=".$array['id'].";";
    
    
    $database->setQuery($sql);
    $result = $database->execute();
    
    
}
   
    

   

$json=array("valid"=>$checking);
$database->freeResults();


ob_end_clean();
echo json_encode($json);  



?>
