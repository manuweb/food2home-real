<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
//*************************************************************************************************
//
//  syncmenucategories.php
//
//  lee menuMenuCategories para sincronizarlos  
//
//*************************************************************************************************
$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;
$token = TOKENREVO;
$user= USUARIOREVO;
$clienttoken=CLIENTTOKEN;
$url=URLREVO.'api/external/v2/catalog/menuMenuCategories?page=';
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
        $orden[]=$datos[$n]['order'];
        $eleMulti[]=$datos[$n]['isMultipleChoice'];
        $min[]=$datos[$n]['min'];
        $max[]=$datos[$n]['max'];
        $producto[]=$datos[$n]['item_id'];
        

    }

    $contador=$contador+count($datos);

    $pagina++;
    if ( $contador>=$data['total'] ):        // última página
        $finished = true;                    // ...we are finished
    endif;
    $checking=true;

endwhile;

curl_close($curl); 

$json=array("valid"=>$checking,"id"=>$id,"nombre"=>$nombre,"orden"=>$orden,"eleMulti"=>$eleMulti,"min"=>$min,"max"=>$max,"producto"=>$producto);

echo json_encode($json); 


?>