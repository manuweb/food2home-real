<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
error_reporting(0);
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=informe.xls");
header("Pragma: no-cache");
header("Expires: 0");
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


$array = json_decode(json_encode($_GET), true);


//$pagina=2;
$rango=$array['rango'];
$tipo=$array['tipo'];

//$rango='18/11/2023 - 18/12/2023';
//$tipo=6;

$fechasjs=explode(' - ',$rango);
// 01/34/6789
$rangofecha="fecha>='".substr($fechasjs[0],6,4)."-".substr($fechasjs[0],3,2)."-".substr($fechasjs[0],0,2)." 00:00:00"."' AND fecha<='".substr($fechasjs[1],6,4)."-".substr($fechasjs[1],3,2)."-".substr($fechasjs[1],0,2)." 23:59:59"."'";

$checking=false;





$sql="SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha  FROM pedidos WHERE estadoPago>=0 AND anulado=0 AND ".$rangofecha." GROUP BY DATE_FORMAT(fecha, '%Y-%m-%d')".$limit.";";

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
            
            $output.="<table>";
            $output.="<tr><td style='text-align:left;font-size:20px;' colspan=5><b>Informe de ventas</b></td></tr><tr><td style='text-align:left;font-size:18px;' colspan=5><b>".$rango."</b></td></tr><tr></tr>";
            $output.="<tr><td><b>Fecha</b></td><td><b>Pedidos</b></td><td><b>Subtotal</b></td><td><b>Portes</b></td><td><b>Descuento</b></td><td><b>Monedero</b></td><td><b>Fidelización</b></td><td><b>Total</b></td><td><b>Media</b></td></tr>";
            for ($x=0;$x<count($fechas);$x++){
                $media=$total[$x]/$cantidad[$x];
                $output.="<tr><td>".cambiafecha_php_n($fechas[$x])."</td><td>".$cantidad[$x]."</td><td class='numero'>".number_format($subtotal[$x],2,",","")."</td><td class='numero'>".number_format($portes[$x],2,",","")."</td><td class='numero'>".number_format($descuento[$x],2,",","")."</td><td class='numero'>".number_format($monedero[$x],2,",","")."</td><td class='numero'>".number_format($importe_fidelizacion[$x],2,",","")."</td><td class='numero'>".number_format($total[$x],2,",","")."</td><td class='numero'>".number_format($media,2,",","")."</td></tr>";
            }
            $output.="</table>";
            
        }
        

    }
    
    if ($tipo==4){
        $sql="SELECT pedidos_lineas.idArticulo, productos.nombre, SUM(pedidos_lineas.canidad) AS TotalVentas FROM pedidos_lineas LEFT JOIN pedidos on pedidos.id=pedidos_lineas.idPedido LEFT JOIN productos on productos.id=pedidos_lineas.idArticulo where DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") AND pedidos.anulado=0 GROUP BY pedidos_lineas.idArticulo ORDER BY SUM(pedidos_lineas.canidad) DESC;";

        $database->setQuery($sql);
        $resulta = $database->execute();
        if ($resulta){
            while ($prod = $resulta->fetch_object()) {
                $nombreP[]=$prod->nombre;
                $cantidadP[]=$prod->TotalVentas;

            }
            $output.="<table>";
            $output.="<tr><td style='text-align:left;font-size:20px;' colspan=2><b>Informe Top productos</b></td></tr><tr><td style='text-align:left;font-size:18px;' colspan=2><b>".$rango."</b></td></tr><tr></tr>";
            $output.="<tr><td style='text-align:left;'><b>Nombre</b></td><td><b>Cantidad</b></td></tr>";
            for ($x=0;$x<count($nombreP);$x++){
                $output.="<tr><td style='text-align:left;'>".$nombreP[$x]."</td><td>".$cantidadP[$x]."</td></tr>";
            }
            $output.="</table>";
        

        
        
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
            if ($tipo==5){
                $titulo=" Informe Medios de pago";
            }
            if ($tipo==6){
                $titulo="Informe Medios de envío";
            }
            $output.="<table>";
            $output.="<tr><td style='text-align:left;font-size:20px;' colspan=5><b>".$titulo."</b></td></tr><tr><td style='text-align:left;font-size:18px;' colspan=5><b>".$rango."</b></td></tr><tr></tr>";
            if ($tipo==5){
                $output.="<tr><td><b>Fecha</b></td><td><b>Total Efectivo</b></td><td><b>Nº Efecivos</b></td><td><b>Total Tarjetas</b></td><td><b>Nº Tarjetas</b></td></tr>";
            }
            if ($tipo==6){
                $output.="<tr><td><b>Fecha</b></td><td><b>Total Enviar</b></td><td><b>Nº Envíos</b></td><td><b>Total Recoger</b></td><td><b>Nº Recoger</b></td></tr>";
            }
            
            
            for ($x=0;$x<count($fechas);$x++){
                if ($tipo==5){
                    $output.="<tr><td>".cambiafecha_php_n($fechas[$x])."</td><td class='numero'>".number_format($MetodoEfectivo[$x],2,",","")."</td><td>".$TotMetodoEfectivo[$x]."</td><td class='numero'>".number_format($MetodoTarjeta[$x],2,",","")."</td><td>".$TotMetodoTarjeta[$x]."</td></tr>";
                }
                if ($tipo==6){
                    $output.="<tr><td>".cambiafecha_php_n($fechas[$x])."</td><td class='numero'>".number_format($MetodoEnvio[$x],2,",","")."</td><td>".$TotMetodoEnvio[$x]."</td><td class='numero'>".number_format($MetodoRecoger[$x],2,",","")."</td><td>".$TotMetodoRecoger[$x]."</td></tr>";
                }
            }
            $output.="</table>";

            
        }
        
    
        
        //$json=array("valid"=>$checking);
    }
    
    if ($tipo==7){
        $sql="SELECT COUNT(*) as cantidad, pedidos_lineas_modificadores.idModificador, pedidos_lineas_modificadores.descripcion AS nombre, pedidos_lineas.id FROM pedidos_lineas_modificadores LEFT JOIN pedidos_lineas on pedidos_lineas.id=pedidos_lineas_modificadores.idLineaPedido LEFT JOIN pedidos on pedidos.id=pedidos_lineas.idPedido where DATE_FORMAT(pedidos.fecha, '%Y-%m-%d') IN (".$txt.") AND pedidos.anulado=0 GROUP BY pedidos_lineas_modificadores.idModificador order BY cantidad DESC;";
        $database->setQuery($sql);

        $resultmod = $database->execute();
        if ($resultmod){

            while ($mod = $resultmod->fetch_object()) {
                $nombreM[]=$mod->nombre;
                $cantidadM[]=$mod->cantidad;

            }
            $output.="<table>";
            $output.="<tr><td style='text-align:left;font-size:20px;' colspan=2><b>Informe Top Modificadores</b></td></tr><tr><td style='text-align:left;font-size:18px;' colspan=2><b>".$rango."</b></td></tr><tr></tr>";
            $output.="<tr><td style='text-align:left;'><b>Nombre</b></td><td><b>Cantidad</b></td></tr>";
            for ($x=0;$x<count($nombreM);$x++){
                $output.="<tr><td style='text-align:left;'>".$nombreM[$x]."</td><td>".$cantidadM[$x]."</td></tr>";
            }
            $output.="</table>";
        

        
        
        }
        

    }
}


$database->freeResults();  
ob_end_clean();

echo "<style> .numero {mso-number-format:'#,##0.00';} td {font-size:14px;text-align:right;}</style>"; 

//echo utf8_decode($output);
echo $output;
die();


function cambiafecha_php_n($fecha) {
	// 0123-56-89
	$fecha=substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
	return $fecha;
}






?>
