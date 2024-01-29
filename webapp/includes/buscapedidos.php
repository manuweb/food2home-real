<?php

/*
 *
 * Archivo: buscapedidos.php
 *
 * Version: 1.0.1
 * Fecha  : 02/10/2023
 * Se usa en :masdatos.js ->buscapedidos()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

$pagina=$array['pagina'];
$cliente=$array['cliente'];

$checking=false;
$tamPagina=10;

$urlservidor=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";

$sql="SELECT pedidos.id FROM pedidos  WHERE pedidos.cliente=".$cliente." AND estadoPago>=0 ORDER BY pedidos.fecha DESC;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();
if ($result && $result->num_rows != 0) {
    
    $numReg=$result->num_rows;
    $limitInf=($pagina-1)*$tamPagina;

    $sql="SELECT pedidos.id as id, pedidos.numero as numero, pedidos.estadoPago as estadoPago, pedidos.fecha as fecha, pedidos.cliente as cliente, usuarios_app.id AS idusu, usuarios_app.apellidos as apellidos, usuarios_app.nombre as nombre, pedidos.subtotal as subtotal, pedidos.impuestos as impuestos, (pedidos.descuento+pedidos.monedero) as descuentos, pedidos.total as total, pedidos.metodoEnvio as envio, pedidos.metodoPago as metodo, pedidos_clientes.apellidos AS ape_otro, pedidos_clientes.nombre as nom_otro FROM pedidos LEFT JOIN usuarios_app ON usuarios_app.id=pedidos.cliente LEFT JOIN pedidos_clientes ON pedidos_clientes.idPedido = pedidos.id  WHERE pedidos.cliente=".$cliente." AND estadoPago>=0 AND anulado=0 ORDER BY pedidos.fecha DESC LIMIT ".$limitInf.",".$tamPagina.";";

    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    if ($result) {
        $checking=true;
        $n=0;
        while ($pedidos = $result->fetch_object()) {

            $id[$n]=$pedidos->id;
            $numero[$n]=$pedidos->numero;
            $estadoPago[$n]=$pedidos->estadoPago;
            $fecha[$n]=$pedidos->fecha;
            $clientes[$n]=$pedidos->cliente;
            $apellidos[$n]=$pedidos->apellidos;
            $nombre[$n]=$pedidos->nombre;
            $ape_otro[$n]=$pedidos->ape_otro;
            $nom_otro[$n]=$pedidos->nom_otro;
            $subtotal[$n]=$pedidos->subtotal;
            $impuestos[$n]=$pedidos->impuestos;
            $descuentos[$n]=$pedidos->descuentos;
            $total[$n]=$pedidos->total;
            $envio[$n]=$pedidos->envio;
            $metodo[$n]=$pedidos->metodo;
            $n++;
        }
               
    }
    //$numReg=count($id);

    $paginas=$numReg/10;
    $resto=$numReg%10;
    $pagina_actual=$pagina;
    if ($pagina_actual==0){
        $pagina_actual=1;
    }
    if ($resto>0){
        $paginas=floor($paginas)+1;
    }
    //alert (paginas);

    $txt='<div class="card data-table">
    <table>
        <thead>
            <tr style="background-color: var(--f7-theme-color);">
                <th class="numeric-cell" style="color:white;">Numero</th>
                <th class="label-cell" style="color:white;">Fecha</th>
                <th class="numeric-cell" style="color:white;">Importe</th>
                <th class="numeric-cell" style="color:white;">Envio</th>
                <th class="numeric-cell" style="color:white;">Pago</th>
                
            </tr>
        </thead>
    <tbody>';
    


    for ($x=0;$x<count($id);$x++){

        $fecha2[$x]=substr($fecha[$x],8,2).'/'.substr($fecha[$x],5,2).'/'.substr($fecha[$x],0,4);
        
 
        $img_reparto=$urlservidor.'/webapp/img/reparto.png';
        if ($envio[$x]=='2'){
            $img_reparto=$urlservidor.'/webapp/img/recoger.png';
        }
        $img_pago=$urlservidor.'/webapp/img/efectivo.png';
        if ($metodo[$x]=='1'){
            $img_pago=$urlservidor.'/webapp/img/tarjeta.png';
        }
        //$nom_cliente=$apellidos[$x].', '.nombre[$x];
        $color='';
       
        if ($estadoPago[$x]=='-1'){
            //anulado
            $color='color:red;';
        }
   
        $txt.='<tr onclick="verpedido(\''.$id[$x].'\');" style="font-size:12px;'.$color.'">
                <th class="numeric-cell">'.$numero[$x].'</th>
                <th class="label-cell">'.$fecha2[$x].'</th>
                <th class="numeric-cell">'.$total[$x].'</th>
                <th class="numeric-cell"><img src="'.$img_reparto.'" width="24" height="auto"></th>
                <th class="numeric-cell"><img src="'.$img_pago.'" width="24" height="auto"></th>
                
            </tr>';
    
   
    }
    $txt.='</tbody">
        </table>';

    $txt_pie='<div class="data-table-rows-select">&nbsp;&nbsp;Paginas: '.$paginas.'</div>
                <div class="data-table-pagination"><span class="data-table-pagination-label">'.$pagina.' de '.$paginas.'</span>';
    
    $anterior="";
    $siguiente="";

         
    if ($paginas>1){

        if ($pagina>1){
            $txt_pie.='<a href="#" onclick="buscapedidos('.$array['cliente'].','.($pagina-1).');" class="link"><i class="icon icon-prev color-gray"></i></a>';
        }
        else {
            $txt_pie.='<a href="#" class="link disabled"><i class="icon icon-prev color-gray"></i></a>';
        }

        if ($pagina<$paginas){

            $txt_pie.='<a href="#" onclick="buscapedidos('.$array['cliente'].','.($pagina+1).');" class="link"><i class="icon icon-next color-gray"></i></a>';
        }
        else {
            $txt_pie.='<a href="#" class="link disabled"><i class="icon icon-next color-gray"></i></a>';
        }
    }   
                    
    $txt_pie.='</div><br>';
    $txt.=$txt_pie;
}
else {
    $txt='No tiene pedidos.';
}

$database->freeResults();  

$json=array("valid"=>$checking,"id"=>$id,"numero"=>$numero,"fecha"=>$fecha,"cliente"=>$clientes,"apellidos"=>$apellidos,"nombre"=>$nombre,"ape_otro"=>$ape_otro,"nom_otro"=>$nom_otro,"subtotal"=>$subtotal,"impuestos"=>$impuestos,"descuentos"=>$descuentos,"total"=>$total,"envio"=>$envio,"metodo"=>$metodo,"estadoPago"=>$estadoPago,"registros"=>$numReg, "txt"=>$txt);

echo json_encode($json); 


/*
$file = fopen("leeunpedido.txt", "w");

fwrite($file, "JSON: ". json_encode($json) . PHP_EOL); 
fwrite($file, "JSON: ". $sql . PHP_EOL); 
fwrite($file, "pagina: ". $pagina . PHP_EOL); 

fclose($file);


echo('<pre>');
var_dump($json);
echo('</pre><br>');

echo $sql;

*/

?>
