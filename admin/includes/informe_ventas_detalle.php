<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);

include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

$array = json_decode(json_encode($_POST), true);


//$pagina=2;
$rango=$array['rango'];
$tipo=$array['tipo'];

//$rango='18/11/2023 - 18/12/2023';
//$tipo=6;

$fechasjs=explode(' - ',$rango);
// 01/34/6789
$rangofecha="fecha>='".substr($fechasjs[0],6,4)."-".substr($fechasjs[0],3,2)."-".substr($fechasjs[0],0,2)." 00:00:00"."' AND fecha<='".substr($fechasjs[1],6,4)."-".substr($fechasjs[1],3,2)."-".substr($fechasjs[1],0,2)." 23:59:59"."'";

$checking=false;



$sql="SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha  FROM pedidos WHERE estadoPago>=0 AND ".$rangofecha." GROUP BY DATE_FORMAT(fecha, '%Y-%m-%d')".$limit.";";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
//$file = fopen("zz-texto.txt", "w");
//fwrite($file, "sql: ". $sql . PHP_EOL);
if ($result->num_rows>0){
    
    $checking=true;
    $txt="";
    while ($pedidos = $result->fetch_object()) {
        $fecha[]=$pedidos->fecha;
        $txt.="'".$pedidos->fecha."',";
    }
    $txt = substr($txt, 0, -1);
    
    if ($tipo==1 || $tipo==2|| $tipo==3){
        
        $sql="SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha, count(*) as cantidad, SUM(total) AS suma_total, SUM(subtotal) AS suma_subtotal, SUM(subtotal) AS suma_subtotal, SUM(portes) AS suma_portes, SUM(descuento) AS suma_descuento, SUM(monedero) AS suma_monedero, SUM(importe_fidelizacion) AS suma_importe_fidelizacion FROM pedidos WHERE DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") AND estadoPago>=0 AND anulado=0 GROUP BY DATE_FORMAT(fecha, '%Y-%m-%d');";
        //fwrite($file, "sql: ". $sql . PHP_EOL);
        //fclose($file);

        $database->setQuery($sql);
        $resulta = $database->execute();
        if ($resulta){
            while ($dias = $resulta->fetch_object()) {
                $fechas[]=$dias->fecha;
                $cantidad[]=$dias->cantidad;
                $total[]=$dias->suma_total;
                $subtotal[]=$dias->suma_subtotal;
                $portes[]=$dias->suma_portes;
                $descuento[]=$dias->suma_descuento;
                $monedero[]=$dias->suma_monedero;
                $importe_fidelizacion[]=$dias->suma_importe_fidelizacion;
            }
            $json=array("valid"=>true, "fecha"=>$fechas, "cantidad"=>$cantidad, "total"=>$total, "subtotal"=>$subtotal, "portes"=>$portes, "descuento"=>$descuento, "monedero"=>$monedero, "importe_fidelizacion"=>$importe_fidelizacion);
        }
        else {
            $json=array("valid"=>false);
        }

    }
    
    if ($tipo==4){
        $sql="SELECT pedidos_lineas.idArticulo, productos.nombre, SUM(pedidos_lineas.canidad) AS TotalVentas FROM pedidos_lineas LEFT JOIN pedidos on pedidos.id=pedidos_lineas.idPedido LEFT JOIN productos on productos.id=pedidos_lineas.idArticulo where DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") AND pedidos.anulado=0 GROUP BY pedidos_lineas.idArticulo ORDER BY SUM(pedidos_lineas.canidad) DESC LIMIT 12;";

        $database->setQuery($sql);
        $resulta = $database->execute();
        if ($resulta){
            while ($prod = $resulta->fetch_object()) {
                $nombreP[]=$prod->nombre;
                $cantidadP[]=$prod->TotalVentas;

            }
        

        
            $json=array("valid"=>$checking,  "nombreP"=>$nombreP, "cantidadP"=>$cantidadP);
        
        }
        else {
            $json=array("valid"=>false);
        }

    }
    
    if ($tipo==7){
         
        $sql="SELECT COUNT(*) as cantidad, pedidos_lineas_modificadores.idModificador, pedidos_lineas_modificadores.descripcion AS nombre, pedidos_lineas.id FROM pedidos_lineas_modificadores LEFT JOIN pedidos_lineas on pedidos_lineas.id=pedidos_lineas_modificadores.idLineaPedido LEFT JOIN pedidos on pedidos.id=pedidos_lineas.idPedido where DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") AND pedidos.anulado=0 GROUP BY pedidos_lineas_modificadores.idModificador order BY cantidad DESC LIMIT 12;";
        $database->setQuery($sql);

        $resultmod = $database->execute();
        if ($resultmod){
            while ($mod = $resultmod->fetch_object()) {
                $nombreM[]=$mod->nombre;
                $cantidadM[]=$mod->cantidad;

            }
            $json=array("valid"=>$checking,  "nombreM"=>$nombreM, "cantidadM"=>$cantidadM);
        }
        
        else {
            $json=array("valid"=>false);
        }

    }
    
    if ($tipo==6 || $tipo==5){
        
        $sql="SELECT total, DATE_FORMAT(fecha, '%Y-%m-%d') as fecha, metodoEnvio, metodoPago FROM pedidos WHERE DATE_FORMAT(fecha, '%Y-%m-%d') IN (".$txt.") AND estadoPago>=0 AND anulado=0 ORDER BY fecha;";

        $database->setQuery($sql);
        $resulta = $database->execute();
        if ($resulta){
            $n=-1;
            $lafecha='';
            while ($dias = $resulta->fetch_object()) {
                if ($dias->fecha!=$lafecha){
                    $n++;
                    $fechas[$n]=$dias->fecha;
                    $lafecha=$dias->fecha;
                }
                if($dias->metodoEnvio==1){
                    $TotMetodoEnvio[$n]+=1;
                    $TotMetodoRecoger[$n]+=0;
                    $MetodoEnvio[$n]+=$dias->total;
                    $MetodoRecoger[$n]+=0;
                }
                else {
                    $MetodoRecoger[$n]+=$dias->total;
                    $MetodoEnvio[$n]+=0;
                    $TotMetodoEnvio[$n]+=0;
                    $TotMetodoRecoger[$n]+=1;
                }
                if($dias->metodoPago==2){ //efectivo
                    $MetodoEfectivo[$n]+=$dias->total;
                    $MetodoTarjeta[$n]+=0;
                    $TotMetodoEfectivo[$n]+=1;
                    $TotMetodoTarjeta[$n]+=0;
                }
                else {
                    $MetodoTarjeta[$n]+=$dias->total;
                    $MetodoEfectivo[$n]+=0;
                    $TotMetodoEfectivo[$n]+=0;
                    $TotMetodoTarjeta[$n]+=1;
                }
            }

            $json=array("valid"=>true, "fecha"=>$fechas, 'Metodo1'=>$MetodoEnvio, 'Metodo2'=>$MetodoRecoger, 'MetodoEfectivo'=>$MetodoEfectivo, 'MetodoTarjeta'=>$MetodoTarjeta, 'TotMetodoEnvio'=>$TotMetodoEnvio, 'TotMetodoRecoger'=>$TotMetodoRecoger, 'TotMetodoEfectivo'=>$TotMetodoEfectivo, 'TotMetodoTarjeta'=>$TotMetodoTarjeta);
        }
        else {
            $json=array("valid"=>false);
        }
    
        
        //$json=array("valid"=>$checking);
    }
}
else {
    $json=array("valid"=>$checking);
}

$database->freeResults();  



echo json_encode($json); 



?>
