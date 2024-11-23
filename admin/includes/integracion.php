<?php 
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);


$checking=false;
if ($array['id']!='foo'){
    //guardar
    if($array['id']==1){
        $sql="UPDATE integracion SET usuario='".$array['usuario']."', token='".$array['token']."', usar_numero_revo='".$array['usar_numero_revo']."',usar_modo_quiosco='".$array['usar_modo_quiosco']."' WHERE id=1";
    }
    else {
        $sql="UPDATE integracion SET usar_modo_quiosco='".$array['usar_modo_quiosco']."',copias='".$array['copias']."',impresora='".$array['impresora']."' WHERE id=1";
    }
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $checking=true; 
        $json=array("valid"=>$checking);
    }
    $database->freeResults();
}
else {
    $sql="SELECT tipo, usuario, token,usar_numero_revo,usar_modo_quiosco, delivery,copias, precio_en_tr, impresora, ClientType  FROM integracion WHERE id=1";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {    
        $checking=true;
        $integra = $result->fetch_object();
        $integracion=$integra ->tipo;
        $usuario=$integra ->usuario;
        $token=$integra ->token;
        $impresora=$integra ->impresora;
        $usar_numero_revo=$integra ->usar_numero_revo;
        $usar_modo_quiosco=$integra->usar_modo_quiosco;
        $delivery=$integra ->delivery;
        $copias_tickets=$integra ->copias;
        $precio_en_tr=$integra ->precio_en_tr;
        $ClientType=$integra ->ClientType;
        $json=array("valid"=>$checking,"integracion"=>$integracion,"usuario"=>$usuario,"token"=>$token,"usar_numero_revo"=>$usar_numero_revo,"usar_modo_quiosco"=>$usar_modo_quiosco,"delivery"=>$delivery,"copias_tickets"=>$copias_tickets,"precio_en_tr"=>$precio_en_tr,"impresora"=>$impresora,"ClientType"=>$ClientType);
    }
    $database->freeResults();
    
}


ob_end_clean();
echo json_encode($json);  



?>
