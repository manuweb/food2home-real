<?php
/**
 * Archivo: leeproducto.php
 *
 * Version: 1.0.1
 * Fecha  : 20/01/2023
 * Se usa en :tienda.js ->muestraelproducto()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL-2/DataBase.class.php";
include "../../webapp/config.php";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}
$array = json_decode(json_encode($_POST), true);

//$array['id']=20;
$checking=false;
if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
    $isApp="productos.precio_web";
}
else {
    $isApp="productos.precio_app=";
}



$sql="SELECT MenuItems.orden, MenuItems.precio, MenuItems.producto, MenuItems.modifier_group_id, MenuItems.addPrecioMod, productos.nombre , productos.imagen, productos.imagen_app1 ,productos.alergias, productos.info, ".$isApp." AS precioProducto, impuestos.porcentaje AS impuesto FROM MenuItems LEFT JOIN productos ON MenuItems.producto=productos.id LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE MenuItems.category_id='".$array['id']."' AND MenuItems.activo=1 group by MenuItems.orden";


    
$db = DataBase::getInstance();  
$db->setQuery($sql);  
$MenuItems = $db->loadObjectList();  
$db->freeResults();
if (count($MenuItems)>0) {
    $eleccion='';
    
    if ($array['eleMulti']==0){
        $eleccion='selecciona 1';
    }
    if ($array['eleMulti']==1){
        $eleccion='selecciona varios';
    }
    if ($array['eleMulti']==2){
        $eleccion='selecciona 1';
    }
    if ($array['eleMulti']==3){
        $eleccion='selecciona min '.$array['min'].', max '.$array['max'];
    }
    
    if ($array['eleMulti']==4){
       $eleccion='selecciona min '.$array['min'].', max '.$array['max'];
    }
    
   
    
    $txt_pre=$eleccion;
    $txt='';
    
    $chk='checked';
    $sel=$array['max'];
    for ($n=0;$n<count($MenuItems);$n++) {
        $impuesto=$MenuItems[$n]->impuesto;
        if ($impuesto==null){
            $impuesto='0';
        }
        $imagen=$MenuItems[$n]->imagen;
        if($MenuItems[$n]->imagen!=""){
            $imagen=IMGREVO.$MenuItems[$n]->imagen;
        }
        if ($MenuItems[$n]->imagen_app1!="") {
            $imagen=IMGAPP.$MenuItems[$n]->imagen_app1;
        }
        if($imagen==""){
            $imagen=IMGREVO."no-imagen.jpg";   
        }
        
        $producto[]=$MenuItems[$n]->producto;
        $nombre[]=$MenuItems[$n]->nombre;
        $precio_a_mostrar=0;
        if ($MenuItems[$n]->addPrecioMod >0) {
            $precio[]=$MenuItems[$n]->precio;
            $precio_a_mostrar=$MenuItems[$n]->precio;
        }
        else {
            $precio[]=0;
        }
        $alergias=$MenuItems[$n]->alergias;
        if ($alergias!=""){
        //$porciones = explode(";", $alergias);
        
        //for ($x=0;$x<count($porciones);$x++){
            $sqla="SELECT nombre, imagen FROM alergenos WHERE id IN (".$alergias.")";
            $db = DataBase::getInstance();  
            $db->setQuery($sqla);  
            $res = $db->loadObjectList();  
            $db->freeResults();
            for ($j=0;$j<count($res);$j++){
                //$alergenos[$j]['nombre']=$res[$j]->nombre;
                //$alergenos[$j]['imagen']=$res[$j]->imagen;
                $alergenos[$j]=array('nombre' => $res[$j]->nombre, 'imagen' => $res[$j]->imagen);
            }
        //}
        if (count($alergenos)<1){
            $alergi='';
        }
        else {
            $alergi='<div>';
            for ($h=0;$h<count($alergenos);$h++){
                $alergi.='<div style="float:left;margin:5px;" class="text-align-center">'.
                            '<img src="'.IMGALE.$alergenos[$h]['imagen'].'" width="22px" height="auto"><br><span style="font-size:.6em;">'.$alergenos[$h]['nombre'].'</span></div>';


            }

            $alergi.='</div>';
        }
        
    }
        $txt.='<div class="swiper-slide" style="border: solid 1px;border-radius: 10px;">
            <div class="list media-list" style="margin-top: -5px;bottom: -10px;">
      <ul>
        <li>
            <div class="item-content">
              <div class="item-media"><img style="border-radius: 8px"
                  src="'.$imagen.'" width="70" />
              </div>
              <div class="item-inner">
                <div class="item-title-row">
                  <div class="item-title" style="font-size: .9em;">'.$MenuItems[$n]->nombre.'</div><br>
                  <div class="item-after" style="font-size: .7em;">Precio '.$precio_a_mostrar.' €</div>
                </div>
                <div class="item-subtitle">'.$alergi.'</div>
                <div class="item-text" style="font-size:.6em;">'.$MenuItems[$n]->info.'</div>
              </div>
            </div>

        </li>
        </ul>
         </div>';
        
        if (($array['eleMulti']==0)||($array['eleMulti']==2)){
            $txt.='<div class="text-align-center"style="margin-top: -20px;"><p>Seleccionar: <label class="radio"><input type="radio" name="chk-ele-multi-'.$array['id'].'" value="'.$MenuItems[$n]->nombre.'"  onclick="cambiaSeleccionMenu(this.value,'.$MenuItems[$n]->producto.','.$array['id'].',\''.$imagen.'\','.$precio_a_mostrar.','.$impuesto.')"/><i class="icon-radio"></i></label></p>  <br> <br> <br></div>';
            $chk='';
        }
        
        if (($array['eleMulti']==3)||($array['eleMulti']==4)){
            
            $txt.='<div class="text-align-center"><div class="stepper stepper-fill stepper-round" style="margin-top: -20px;">
              <div class="stepper-button-minus" onclick="RestaUnoMenu('.$MenuItems[$n]->producto.',0,'.$array['max'].','.$array['id'].');"></div>
              <div class="stepper-input-wrap">
                <input type="text" value="0" min="0" max="'.$array['max'].'" step="1" readonly id="prod-menu-'.$MenuItems[$n]->producto.'" class="prod-menu-'.$array['id'].'" data-id="'.$MenuItems[$n]->producto.'" data-nombre="'.$MenuItems[$n]->nombre.'" data-precio="'.$precio_a_mostrar.'" data-imagen="'.$imagen.'" data-iva="'.$impuesto.'"/>
              </div>
              <div class="stepper-button-plus" onclick="SumaUnoMenu('.$MenuItems[$n]->producto.',0,'.$array['max'].','.$array['id'].');"></div>
            </div><br> <br> <br></div>';
            $sel=0;
            
        }
        
         $txt.='</div> ';
        
        
    }
    
    $checking=true;
    
}





$json=array("valid"=>$checking,"producto"=>$producto,"nombre"=>$nombre,"precio"=>$precio,"modifier_group_id"=>$modifier_group_id,"addPrecioMod"=>$addPrecioMod,"txt"=>$txt,"txt_pre"=>$txt_pre);
ob_end_clean();
echo json_encode($json); 

/*
$file = fopen("Menu.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);

fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fwrite($file, "isapp: ". $isApp. PHP_EOL);
fclose($file);
*/

?>
