<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//*************************************************************************************************
//
//  tpv_ok.php
//
//
//*************************************************************************************************

$idpedido=$_GET['idpedido'];
$importe=$_GET['importe'];
$numero=$_GET['numero'];


$mensaje=$idpedido.'###OK###'.$importe.'###'.$numero;



?>
<script>
window.parent.postMessage('<?php echo $mensaje;?>','*');
    //console.log('<?php echo $mensaje;?>');
</script>