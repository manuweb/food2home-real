<?php
/*
 *
 * Archivo: buscadirecciones.php
 *
 * Version: 1.0.1
 * Fecha  : 02/10/2023
 * Se usa en :masdatos.js ->buscadirecciones() y comprar.js->buscadirecciones_compra()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";


$array = json_decode(json_encode($_POST), true);

$checking=false;
//$array['id']=1;


$sql="SELECT id, alias, preferida, direccion, complementario, cod_postal, poblacion, provincia, lat, lng FROM domicilios WHERE usuario='".$array['id']."' ORDER BY preferida DESC
;";

$database = DataBase::getInstance();
$database->setQuery($sql);
$result = $database->execute();

$txt="";

if ($result && $result->num_rows != 0) {
    
    $checking=true;
    $x=0;
    while ($domicilios = $result->fetch_object()) {
    
        $id[]=$domicilios->id;
        $alias[]=$domicilios->alias;
        $preferida[]=$domicilios->preferida;
        $direccion[]=$domicilios->direccion;
        $complementario[]=$domicilios->complementario;
        $cod_postal[]=$domicilios->cod_postal;
        $poblacion[]=$domicilios->poblacion;
        $provincia[]=$domicilios->provincia;
        $lat[]=$domicilios->lat;
        $lng[]=$domicilios->lng;
        
        $ali='';
        $prefe='';

        if ($domicilios->preferida!=0){
            $prefe='checked';
        }
        if ($domicilios->alias!=""){
            $ali=$domicilios->alias;
        }
        else {
            $ali='Mi domicilio '.($x+1);
        }
        if ($domicilios->complementario!=''){
            $compl=$domicilios->complementario.'<br>';
        }
        else {
            $compl="";
        }

        $txt.='<div class="card card-outline card-dividers">
                <div class="card-header">'.$ali.
                    '<label class="radio float-right"><input type="radio" name="dirpreferida" value="1" '.$prefe.' onclick="cambiapreferenciadireccion('.$array['id'].','.$domicilios->id.');"/><i class="icon-radio card"></i>
                    </label> 
                    </div>';

         $txt.='<div class="card-content card-content-padding">'.$domicilios->direccion.'<br>'.$compl.$domicilios->cod_postal.' - '.$domicilios->poblacion.'<br>('.$domicilios->provincia.')
                </div>
                <div class="card-footer"><span style="color:var(--f7-theme-color);"  onclick="editaDomicilio('.$array['id'].',this);" data-id="'.$array['id'].'"  data-alias="'.$ali.'" data-iddomi="'.$domicilios->id.'" data-direccion="'.$domicilios->direccion.'" data-complementario="'.$domicilios->complementario.'" data-cod_postal="'.$domicilios->cod_postal.'" data-poblacion="'.$domicilios->poblacion.'" data-provincia="'.$domicilios->provincia.'"><i class="f7-icons">pencil</i> Editar</span><span onclick="borraDomicilio(this);" data-id="'.$array['id'].'" data-idDomi="'.$domicilios->id.'" data-alias="'.$ali.'"><i class="f7-icons" >trash</i> Borrar</span>
                </div>
            </div>';  
        $x++;
        
    }   
    

    
    
}	
else {
     $txt="<p>Aún no tiene direcciones asociadas.</p>";
}
$database->freeResults();   

 $txt.='<div class="grid grid-cols-1"><button onclick="editaDomicilio('.$array['id'].');" class="button button-fill button-round" style="margin:auto;width: 60%;">+ Añadir domicilio</button></div>';


$json=array("valid"=>$checking,"alias"=>$alias,"preferida"=>$preferida,  "id"=>$id, "direccion"=>$direccion, "complementario"=>$complementario, "cod_postal"=>$cod_postal, "poblacion"=>$poblacion, "provincia"=>$provincia, "lat"=>$lat, "lng"=>$lng, "txt"=>$txt);

ob_end_clean();
echo json_encode($json); 


/*
$file = fopen("buscadirecciones.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);

fclose($file);
*/

?>
