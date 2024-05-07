<?php 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');

$array = json_decode(json_encode($_POST), true);

$database = DataBase::getInstance();
$checking=false;
if ($array['id']=='foo'){
    $sql="UPDATE integracion SET delivery=0 WHERE id=1;";
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $checking=true;
    }
    $json=array("valid"=>$checking);
}
else {
    if ($array['id']==0){
        //guardar
        $sql="SELECT id, nombre, logo, logica  FROM delivery;";
    }
    else {
        $sql="SELECT id, nombre, logo, logica  FROM delivery WHERE id=".$array['id'].';';
    }
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result->num_rows>0) {    
        $checking=true;
        while ($integra = $result->fetch_object()) {

            $id[]=$integra ->id;
            $nombre[]=$integra ->nombre;
            $logo[]=$integra ->logo;
            $logica[]=$integra ->logica;
        }
    }
    $json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"logo"=>$logo,"logica"=>$logica);
}



$database->freeResults();


ob_end_clean();
echo json_encode($json);  



?>
