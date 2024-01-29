<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//*************************************************************************************************
//
//  tpv_ko.php
//
//
//*************************************************************************************************

$idpedido=$_GET['idpedido'];
$importe=$_GET['importe'];
$numero=$_GET['numero'];

$mensaje=$idpedido.'###KO###'.$importe.'###'.$numero;
?>
<script>
window.parent.postMessage('<?php echo $mensaje;?>','*');
</script>