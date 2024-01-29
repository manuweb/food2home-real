<?php
/*
 *
 * Archivo: leeunpedidodatos.php
 *
 * Version: 1.0.1
 * Fecha  : 15/10/2023
 * Se usa en :masdatos.js ->volverapedir()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST) ) {
    $array = json_decode(json_encode($_POST), true);
}


//$numero='958QYIYL';
$idpedido=$array['idpedido'];
$isApp=$array['isApp'];

$checking=false;

if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $precio="precio_web";
}
else {
    $precio="precio_app";
}

$sql="SELECT orders.datos, pedidos.numero, pedidos.estadoPago FROM orders LEFT JOIN pedidos ON pedidos.id=orders.idPedido WHERE orders.idPedido='".$idpedido."';";



$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();


if ($result) {
    $pedido = $result->fetch_object();
    $database->freeResults();
    $datos=$pedido->datos;  
    
    $order = json_decode($datos,JSON_UNESCAPED_UNICODE);
    $checking=true; 
    $carrito=$order['carrito'];


    for ($n=0;$n<count($carrito);$n++){
        $sql1="SELECT productos.id, productos.nombre,productos.imagen, productos.imagen_app1, productos.".$precio." as precio FROM productos WHERE productos.id=".$carrito[$n]['id'].";";

                 
        $database = DataBase::getInstance();
        $database->setQuery($sql1);
        $result = $database->execute(); 
        $producto = $result->fetch_object();
        $carrito[$n]['precio_sin']=$producto->precio;
        $carrito[$n]['precio']=$carrito[$n]['precio_sin'];

        if (isset($carrito[$n]['modificadores'])){
            $aSumar=0;

            
             for ($j=0;$j<count($carrito[$n]['modificadores']);$j++){
                $sqlM="SELECT precio FROM modifiers WHERE id=".$carrito[$n]['modificadores'][$j]['id'].";";
                 
                 
                 
                $database = DataBase::getInstance();
                $database->setQuery($sqlM);
                $result = $database->execute(); 
                $modificador = $result->fetch_object();
                 
                $carrito[$n]['modificadores'][$j]['precio']=$modificador->precio;
                 $aSumar+=$carrito[$n]['modificadores'][$j]['precio'];

             }
            $carrito[$n]['precio']=$carrito[$n]['precio_sin']+$aSumar;

        }
        
        
        if (isset($carrito[$n]['elmentosMenu'])){
            $aSumar=0;
             for ($j=0;$j<count($carrito[$n]['elmentosMenu']);$j++){
                 
                $sqlM="SELECT precio FROM MenuItems WHERE producto=".$carrito[$n]['elmentosMenu'][$j]['id']." AND activo=1;";

                $database = DataBase::getInstance();
                $database->setQuery($sqlM);
                $result = $database->execute(); 
                if ($result) {
                    $modificador = $result->fetch_object();

                    $carrito[$n]['elmentosMenu'][$j]['precio']=$modificador->precio;
                     $aSumar+=($carrito[$n]['elmentosMenu'][$j]['precio']*$carrito[$n]['elmentosMenu'][$j]['cantidad']);
                }
                else {
                    $carrito[$n]['elmentosMenu'][$j]['precio']=0;
                    $carrito[$n]['elmentosMenu'][$j]['cantidad']=0;
                }
             }
            $carrito[$n]['precio']=$carrito[$n]['precio_sin']+$aSumar;
        }
        

    }   

}
$database->freeResults();  


$json=array("valid"=>$checking, "carrito"=>$carrito);

ob_end_clean();

echo json_encode($json); 
/*
$file = fopen("leeunpedido.txt", "w");
fwrite($file, "Pedido: ". $numero. PHP_EOL);
fwrite($file, "SQL: ". $sql. PHP_EOL);
fwrite($file, "Pedido: ". $numero. PHP_EOL);
fwrite($file, "Email: ". $email. PHP_EOL);
fwrite($file,  PHP_EOL);
fwrite($file, "Subtotal: ". $subtotal . PHP_EOL);
    fwrite($file, "Subtotal cal: ". $subtotal_calculado . PHP_EOL);
fwrite($file, "Descuento: ". $descuento . PHP_EOL);
fwrite($file, "A descontar: ". $adescontar . PHP_EOL);
fwrite($file, "Tipo Descuento: ". $tipo_descuento . PHP_EOL);
fwrite($file, "Monedero: ". $monedero . PHP_EOL);
fwrite($file, "Portes: ". $portes . PHP_EOL);
fwrite($file, "Cupon: ". $cupon . PHP_EOL);
fwrite($file, "TOTAL: ". $total . PHP_EOL);
fwrite($file, "IVA envio: ". $ivaEnvio . PHP_EOL);
fwrite($file, "id envio: ". $idEnvio . PHP_EOL);
fwrite($file, "-------------------------". PHP_EOL); 
fwrite($file, "Dto. calculado: ". $sumadescuentos . PHP_EOL);
    fwrite($file, "Dto. Sumas: ". $suma_descuentos . PHP_EOL);
for ($n=0;$n<count($porcentajeImpuesto);$n++){
    fwrite($file, "Base [".$porcentajeImpuesto[$n]."]: ". $baseImpuesto[$n]." --->Iva: ".$ivaImpuesto[$n] . PHP_EOL);
}         

fwrite($file, "-------------------------". PHP_EOL);     
fwrite($file, print_r($lineasP, true));
fwrite($file, "-------------------------". PHP_EOL);  
*/

?>
