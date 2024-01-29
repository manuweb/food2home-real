<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../conexion.php";
include "../MySQL/DataBase.class.php";
//*************************************************************************************************
//
//  registro_token.php
//
//  actualiza el token en la base de datos
//
//*************************************************************************************************

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;

$usuario=$array['usuario'];
$token=$array['token'];


$sql = "SELECT id FROM telefonos where idtel='".$token."'";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result && $result->num_rows === 0) {
    $sql2="INSERT INTO telefonos ( idtel, push,usuario, plataforma, creado) VALUES ('".$token."','si', '".$usuario."', 'web', CURRENT_TIMESTAMP);";

    // NOexiste
    
    $database = DataBase::getInstance();
    $database->setQuery($sql2);
    $result = $database->execute();
    if ($result) { 
        $checking=true;
    }
}
else {

    $sql2="UPDATE telefonos SET push='si', usuario='".$usuario."', creado= CURRENT_TIMESTAMP WHERE idtel='".$token."'";
    $database = DataBase::getInstance();
    $database->setQuery($sql2);
    $result = $database->execute();

    if ($result) { 
        $checking=true;
    }

}  
$database->freeResults();    

$json=array("valid"=>$checking);
echo json_encode($json);




/*
  $file = fopen("actualizacion_telefono.txt", "w");
    fwrite($file, "Datos: ". json_encode($json) . PHP_EOL);
        fwrite($file, "Sql: ". $sql . PHP_EOL);
        fwrite($file, "Sql2: ". $sql2 . PHP_EOL);
 fwrite($file, "idtel: ". $token . PHP_EOL);
    fclose($file);	

*/

?>



