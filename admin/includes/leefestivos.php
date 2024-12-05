<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");


$array = json_decode(json_encode($_POST), true);
$checking=false;

$hoy=date("Y-m-d");  

if ($array['id']=='foo'){
    $sql="SELECT fecha FROM festivos WHERE fecha>='".$hoy."' ORDER BY fecha;";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result->num_rows>0) {
        $checking=true;
        while ($grupo = $result->fetch_object()) {
            $fecha[]=$grupo->fecha;
        }
    }
    $database->freeResults();
    $json=array("valid"=>$checking,"fecha"=>$fecha);
}
else {
    
    $fecha=$array['fecha'];
    if ($array['id']==0){
        $sql="INSERT INTO festivos (fecha) VALUES ('".$fecha."');";

    }
    else{
        $sql="DELETE FROM festivos WHERE fecha='".$fecha."';";
    }
/*   
$file = fopen("zz-festivos.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fclose($file);
*/   
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {
        $checking=true;
    }
    $database->freeResults();
    $json=array("valid"=>$checking);
}


ob_end_clean();
echo json_encode($json); 


?>
