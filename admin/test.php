<?php

$texto='Porterillo "ana valle"';
echo eliminaComillas($texto);
echo "<br>";
$texto="Porterillo 'ana valle'";
echo eliminaComillas($texto);
function eliminaComillas($texto){
    $texto=str_replace('"', '*', $texto);  
    $texto=str_replace("'", "*", $texto);  
    return $texto;
}
?>