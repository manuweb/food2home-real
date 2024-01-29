<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
//*************************************************************************************************
//
//  syncmodificadores.php
//
//  lee modificadores para sincronizarlos  
//
//*************************************************************************************************
$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;
$token = TOKENREVO;
$user= USUARIOREVO;
$clienttoken=CLIENTTOKEN;
$url=URLREVO.'api/external/v2/catalog/modifiers?page=';
$pagina=1;
$finished = false;    
$contador=0;
while ( ! $finished ):                   // while not finished

    //sleep(5);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.$pagina,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        'tenant: ' . $user,"Authorization: Bearer ". $token, "client-token: ".$clienttoken
          ),
     ));
    
    $response = curl_exec($curl);
    $data = json_decode($response, true);
    $datos=$data['data'];
    $per_page=$data['per_page'];
    
    for ($n=0;$n<count($datos);$n++){

        $id[]=$datos[$n]['id'];
        $nombre[]=$datos[$n]['name'];
        $precio[]=$datos[$n]['price'];
        $activo[]=$datos[$n]['active'];
        $category_id[]=$datos[$n]['category_id'];
        $autoseleccionado[]=$datos[$n]['autoSelected'];

    }

    $contador=$contador+count($datos);

    $pagina++;
    if ( $contador>=$data['total'] ):        // última página
        $finished = true;                    // ...we are finished
    endif;
    $checking=true;

endwhile;

curl_close($curl); 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"precio"=>$precio,"activo"=>$activo,"autoseleccionado"=>$autoseleccionado,"category_id"=>$category_id);

echo json_encode($json); 


?>