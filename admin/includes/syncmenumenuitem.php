<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
header('Access-Control-Allow-Origin: *');
//*************************************************************************************************
//
//  syncmenumenuitem.php
//
//  lee menuMenuItemCategoryPivots para sincronizarlos  
//
//*************************************************************************************************
$_POST = json_decode(file_get_contents('php://input'), true);

$array = json_decode(json_encode($_POST), true);

$checking=false;
$token = TOKENREVO;
$user= USUARIOREVO;
$clienttoken=CLIENTTOKEN;
$url=URLREVO.'api/external/v2/catalog/menuMenuItemCategoryPivots?page=';
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
        $activo[]=$datos[$n]['active'];
        $orden[]=$datos[$n]['order'];
        $precio[]=$datos[$n]['price'];
        $producto[]=$datos[$n]['item_id'];
        $category_id[]=$datos[$n]['category_id'];
        $modifier_group_id[]=$datos[$n]['modifier_group_id'];
        $addPrecioMod[]=$datos[$n]['addModifiersPrice'];
        

    }

    $contador=$contador+count($datos);

    $pagina++;
    if ( $contador>=$data['total'] ):        // última página
        $finished = true;                    // ...we are finished
    endif;
    $checking=true;

endwhile;

curl_close($curl); 

$json=array("valid"=>$checking,"id"=>$id,"activo"=>$activo,"orden"=>$orden,"precio"=>$precio,"producto"=>$producto,"category_id"=>$category_id,"modifier_group_id"=>$modifier_group_id,"addPrecioMod"=>$addPrecioMod);

echo json_encode($json); 


?>