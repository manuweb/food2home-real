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
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";
include "../../webapp/functions.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


//$numero='958QYIYL';
$idpedido=$array['idpedido'];


$checking=false;

$precio="precio_web";

$Pedido = new RecomponePedido;
$order=$Pedido->DatosGlobalesPedido($idpedido);
$order['carrito']=$Pedido->LineasPedido($idpedido);


$carrito=$order['carrito'];


for ($n=0;$n<count($carrito);$n++){
    $checking=true;
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

    $database->freeResults();  
}   





$json=array("valid"=>$checking, "carrito"=>$carrito);

ob_end_clean();

echo json_encode($json); 


?>
