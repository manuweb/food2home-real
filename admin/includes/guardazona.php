<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

//$_POST = json_decode(file_get_contents('php://input'), true);

//$array = json_decode(json_encode($_POST), true);


$checking=false;

$aux="";


if($_POST['reparto']=='false'){
    $reparto=0;
}
else {
    $reparto=1;
}
if($_POST['precio']==''){
    $precio=0;
}
else {
    $precio=$_POST['precio'];
}
if($_POST['minimo']==''){
    $minimo=0;
}
else {
    $minimo=$_POST['minimo'];
}

if (isset($_FILES)) {
    $directorio = '../archivos/';
   

    $subir_archivo = $directorio.basename($_FILES['archivo']['name']);
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $subir_archivo)) {

        $archivo = fopen($subir_archivo,'r');
        $empieza=false;
        $termina=false;

        while ($linea = fgets($archivo)) {
            //if (trim($linea)=='<coordinates>') {$empieza=true;}
            if (trim($linea)=='</coordinates>') {$termina=true;}
            if ($empieza && !$termina  ){
                $aux= $aux.trim(substr($linea, 0, -3)).PHP_EOL;
            }
            if (trim($linea)=='<coordinates>') {$empieza=true;}
        }
        fclose($archivo);
        $aux = substr($aux, 0, -1);
    }

    
}

if ($_POST['id']=='0') {
    if (isset($_FILES)) {
        $checking=true;  

        $sql="select MAX(Id) AS Max_Id from zona_repartos";
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) {
            $max = $result->fetch_object();
            $maxid=$max->Max_Id;
        }
        
        
        
        $sql="INSERT INTO zona_repartos (nombre, orden, precio, minimo, reparto, zona) VALUES ('".$_POST['nombre']."', '".$maxid."', '".$precio."', '".$minimo."', '".$reparto."', '".$aux."')";
        //insert
        $database = DataBase::getInstance();
        $database->setQuery($sql);
        $result = $database->execute();

        if ($result) {
            $checking=true; 
        }
        $database->freeResults();
    }
        
}

else {
    // update

    if ($aux=="") {
        $sql="UPDATE zona_repartos SET nombre='".$_POST['nombre']."', precio='".$precio."', minimo='".$minimo."', reparto='".$reparto."' WHERE id='".$_POST['id']."'";
    }
    else {
        $sql="UPDATE zona_repartos SET nombre='".$_POST['nombre']."', precio='".$precio."', minimo='".$minimo."', reparto='".$reparto."', zona='".$aux."' WHERE id='".$_POST['id']."'";
    }
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();

    if ($result) {
        $checking=true; 
    }
    $database->freeResults();
}
ob_end_clean();

    
$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("zz-reparto.txt", "w");
fwrite($file, "sql: ".$sql. PHP_EOL);
fwrite($file, "maxid: ".$maxid. PHP_EOL);
fwrite($file, "nombre: ".$_POST['nombre']. PHP_EOL);
fwrite($file, "aux: ".$aux. PHP_EOL);
fwrite($file, "reparto: ".$_POST['reparto']. PHP_EOL);
fwrite($file, "datos: ".json_encode($_POST). PHP_EOL);
fwrite($file, "file: ".$subir_archivo. PHP_EOL);
fclose($file);
*/

?>
