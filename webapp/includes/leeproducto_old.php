<?php
ini_set('display_startup_errors',0);
ini_set('display_errors',0);
/**
 * Archivo: leeproducto.php
 *
 * Version: 1.0.2
 * Fecha  : 08/10/2023
 * Se usa en :tienda.js ->muestraelproducto()
 *
 * © Manuel Sánchez (www.elmaestroweb.es)
 *
*/
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
include "../../webapp/config.php";

$array = json_decode(json_encode($_POST), true);


$array['tienda']=0;
$array['id']=978;
$array['idgrupo']=24;
$array['nombregrupo']='CARTA';
$array['idcategoria']=89;
$array['nombrecategoria']='BURGERS';


$array['id']=3196;
$array['idgrupo']=1022;
$array['nombregrupo']='PLATOS MENÚS';
$array['idcategoria']=186;
$array['nombrecategoria']='PRIMEROS';


$array['isapp']='false';



$checking=false;
$texto="";
$texto.=
'<div class="navbar" style="position:fixed;">
    <div class="navbar-inner">
      <div class="left" style="height: 44px;width: 44px;">
            <a href="javascript:app.tab.show(\'#view-catalog\');muestraproductos(\''.$array['idgrupo'].'\',\''.$array['nombregrupo'].'\',\''.$array['idcategoria'].'\',\''.$array['nombrecategoria'].'\');" id="link-volver" style="width: 44px;">
              <i class="icon icon-back" id="icon-back" style="color:white;text-shadow: #ce132e 0.1em 0.1em 0.2em;"></i>
            </a>               
        </div>
        <div class="title" id="titulo-menus"></div>
          <div class="right">
                <a href="#" class="link icon-only popover-open" data-popover=".popover-cart">
                  <i class="icon material-icons"><span class="badge color-red badage-cart"  style="display:none;"></span>shopping_cart</i>
                </a>               
          </div>
    </div>
</div>';
/*
$texto.='<div style="background: rgba(255,255,255,1);
    background: -moz-linear-gradient(top, rgba(255,255,255,1) 55%, rgba(247,247,247,1) 76%, rgba(237,237,237,1) 100%);
    background: -webkit-gradient(left top, left bottom, color-stop(55%, rgba(255,255,255,1)), color-stop(76%, rgba(247,247,247,1)), color-stop(100%, rgba(237,237,237,1)));
    background: -webkit-linear-gradient(top, rgba(255,255,255,1) 55%, rgba(247,247,247,1) 76%, rgba(237,237,237,1) 100%);
    background: -o-linear-gradient(top, rgba(255,255,255,1) 55%, rgba(247,247,247,1) 76%, rgba(237,237,237,1) 100%);
    background: -ms-linear-gradient(top, rgba(255,255,255,1) 55%, rgba(247,247,247,1) 76%, rgba(237,237,237,1) 100%);
    background: linear-gradient(to bottom, rgba(255,255,255,1) 55%, rgba(247,247,247,1) 76%, rgba(237,237,237,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=#ffffff, endColorstr=#ededed, GradientType=0 );
    border-radius:25px 25px 0 0;">';  
*/

//$texto.='<div style="background-color:white;border-radius:25px 25px 0 0;">';  
    
$sql="SELECT productos.id, productos.nombre, productos.imagen, productos.info, productos.alergias, productos.activo, productos.activo_web, productos.activo_app,  productos.modificadores, productos.imagen_app1, productos.imagen_app2, productos.imagen_app3, productos.precio, productos.precio_web, productos.precio_app, productos.modifier_category_id, productos.modifier_group_id, productos.esMenu, categorias.modifier_category_id as modcatcat, categorias.modifier_group_id as modgrucat, impuestos.porcentaje AS porImpuesto FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE productos.id='".$array['id']."' AND productos.tienda='".$array['tienda']."';";

//echo $sql."<br>";
//echo '<hr>';

$database = DataBase::getInstance();
$database->setQuery($sql);
$result_prod = $database->execute();

$alergenos=array();

if ($result_prod->num_rows>0) {
    $producto = $result_prod->fetch_object();
    $checking=true;
    $nombre=$producto->nombre;


    if ($producto->imagen!=''){
            $imagen=IMGREVO.$producto->imagen;
    }
    else {
        $imagen=IMGREVO."no-imagen.jpg";
    }
    if ($producto->imagen_app1!=''){
        $imagen=IMGAPP.$producto->imagen_app1;;
    }
    
    
    $imagen_app1=IMGAPP.$producto->imagen_app1;
    $imagen_app2=IMGAPP.$producto->imagen_app2;
    $imagen_app3=IMGAPP.$producto->imagen_app3;
    
    $activo=$producto->activo;
    $activo_web=$producto->activo_web;
    $activo_app=$producto->activo_app;
    $modifi=$producto->modificadores;
    $impuesto=$producto->porImpuesto;
    if ($impuesto==null){
        $impuesto='0';
    }
    
    
    $info=$producto->info;
    $alergias=$producto->alergias;
    $imagen_app1=$producto->imagen_app1;
    $imagen_app2=$producto->imagen_app2;
    $imagen_app3=$producto->imagen_app3;
    $precio=$producto->precio;
    $precio_web=$producto->precio_web;
    $precio_app=$producto->precio_app;
    $modifier_category_id= $producto->modifier_category_id;
    $modifier_group_id= $producto->modifier_group_id;
    $esMenu= $producto->esMenu;
    
    $modcatcat= $producto->modcatcat;
    if ($modcatcat!="") {
        $modifier_category_id=$modcatcat;
    }
    $modgrucat= $producto->modgrucat;
    if ($modgrucat!="") {
        $modifier_group_id=$modgrucat;
    }
    $precio=0;
        if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
            $precio=$producto->precio_web;
        }
        else {
            $precio=$producto->precio_app;
        }
    //$precio=number_format($precio, 2, ",", ".");
        $decimales = explode(".",$precio);
        $decimales[1]=substr($decimales[1].'00',0,2);
    if ($alergias!=""){
        //$porciones = explode(";", $alergias);
        
        //for ($x=0;$x<count($porciones);$x++){
            $sqla="SELECT nombre, imagen FROM alergenos WHERE id IN (".$alergias.")";
        
        
            $database->setQuery($sql);
            $result = $database->execute();
            
        
            while ($res = $result->fetch_object()) {

            
                //$alergenos[$j]['nombre']=$res[$j]->nombre;
                //$alergenos[$j]['imagen']=$res[$j]->imagen;
                $alergenos[]=array('nombre' => $res->nombre, 'imagen' => $res->imagen);
            }
        //}
        
    }

    
    $modifierCategories=[];
    if ($modifier_category_id!="") {
            
        // leer modificadores
        $sql1="SELECT id, nombre, forzoso, opciones, modificadores FROM modifierCategories WHERE id=".$modifier_category_id." AND activo='1';";
        
        
        $database->setQuery($sql1);
        $result = $database->execute();
        $modifierCategories = $result->fetch_object();
 
        echo "<hr>";
        echo "modifier_category_id:".$modifier_category_id."<br>";
        echo $sql1."<br>";
        print_r($modifierCategories);
      
        
        $sql2="SELECT id, nombre, precio, autoseleccionado FROM modifiers WHERE id IN(".$modifierCategories->modificadores.") ORDER BY FIELD(id, ".$modifierCategories->modificadores.");";
        
        $database->setQuery($sql2);
        $result = $database->execute();
        //$modificadores = $result->fetch_object();
        
        
        $tot=$result->num_rows;
        //$modifierCategoriesGru = $result->fetch_object();
        $modificadores=[];
        while ($modifi = $result->fetch_object()) {
        
     
            
                array_push($modificadores,array ('id'=>$modifi->id,'nombre'=>$modifi->nombre,'precio'=>$modifi->precio,'autoseleccionado'=>$modifi->autoseleccionado) ); 
        }
            
        print_r($modificadores);
   
        echo "<hr>";
        echo "modifier_category_id:".$modifier_category_id."<br>";
        echo $sql1."<br>";
        print_r($modifierCategories);
        echo $sql2."<br>";
        print_r($modificadores);

        
        
        
        
    }

    
    $modifierCategoriesGru=[];
    
    if ($modifier_group_id!=""){
        
        $sql1="SELECT id, nombre, modifierCategories_id FROM modifierGroups WHERE id=".$modifier_group_id;
        
        $database->setQuery($sql1);
        $result = $database->execute();
        $modifierGroups = $result->fetch_object();
        
        
        
        $sql2="SELECT id, nombre, forzoso, opciones, modificadores FROM modifierCategories WHERE id IN(".$modifierGroups->modifierCategories_id.") ORDER BY FIELD(id, ".$modifierGroups->modifierCategories_id.");";
        
        $database->setQuery($sql2);
        $result = $database->execute();
        //$modifierCategoriesGru = $result->fetch_object();
        //$modificadoresGru=[];
        
        echo "<hr>";
        echo "modifier_group_id:".$modifier_group_id."<br>";
        echo $sql1."<br>";
        echo "modifierGroups<br>";
        print_r($modifierGroups);
        echo $sql2."<br>";
        echo "modifierCategoriesGru<br>";
        print_r($modifierCategoriesGru);
       
        
        $tot=$result->num_rows;
        //$modifierCategoriesGru = $result->fetch_object();
        $modificadoresGru=[];
        while ($modifierCategoriesGru = $result->fetch_object()) {
        //for ($j=0;$j<count($modifierCategoriesGru);$j++){
        
        //for ($j=0;$j<$tot;$j++){
            $sql3="SELECT id, nombre, precio, autoseleccionado FROM modifiers WHERE id IN(".$modifierCategoriesGru->modificadores.");";
            
            $database->setQuery($sql3);
            $result_m = $database->execute();
            

            while ($modifiersCatGru = $result_m->fetch_object()) {
            
                array_push($modificadoresGru,array ('id'=>$modifiersCatGru->id,'nombre'=>$modifiersCatGru->nombre,'precio'=>$modifiersCatGru->precio,'autoseleccionado'=>$modifiersCatGru->autoseleccionado) ); 
            }
            
            print_r($modificadoresGru);
            

            //[{"id":"1408","nombre":"GUACAMOLE APARTE","precio":"0.00","autoseleccionado":"0"}

            //array_push($modificadoresGru,$modifiersCatGru ); 
            
            
        }

    }

    $texto.='<div class="block" id="bloque-producto" style="margin-bottom:10px;padding-top:10px;" >
    <img src="'.$imagen.'" width="100%" height="auto" id="img-prod"><br>
                    
    <p><span style="font-size:20px;">'.$nombre.' </span><span style="float:right;font-size:20px;font-weight: bold;">'.$decimales[0].'<span style="font-size:16px">,'.$decimales[1].'</span> €</span></p>
    <p style="font-size:16px;font-weight: bold;color:var(--primario);">Descripción</p>
    <p>'.$info.'</p></div>';
    
    if (count($alergenos)<1){
        $alergi='<div style="float:left;margin:5px;" class="text-align-center"><img src="'.IMGALE.'sin.png" width="48px" height="auto"><br><span style="font-size:.8em;">Libre de alergenos</span></div>';
    }
    else {
        $alergi='<div>';
        for ($h=0;$h<count($alergenos);$h++){
            $alergi.='<div style="float:left;margin:5px;" class="text-align-center">'.
                        '<img src="'.IMGALE.$alergenos[$h]['imagen'].'" width="32px" height="auto"><br><span style="font-size:.8em;">'.$alergenos[$h]['nombre'].'</span></div>';
            
                    
        }

        $alergi.='</div>';
    }
    $texto.='<div class="block" style="margin-top:15    px;margin-bottom:5px;">
        <p style="font-size:16px;font-weight: bold;margin-bottom: 5px;color:'.$array['colorprimario'].';">Alergenos <i style="font-size: 20px" class="icon f7-icons tip-alergenos color-primary">info_circle_fill</i></p><br style="clear:both;"></div>';
    /*
    if (is_array($modifierCategories)){
        if (is_array($modifierCategoriesGru)){
            array_push($modifierCategories,$modifierCategoriesGru ); 
            array_push($modificadores,$modificadoresGru ); 
            
        }
        
    }
    */
	

	
	if($modifi=='0'){



    
		
        if ((is_array($modifierCategories))||(is_array($modifierCategoriesGru))){
            
             $texto.='<div class="block" style="margin-top:5px;margin-bottom:5px;">';
             if (count($modifierCategories)>0||count($modifierCategories)>0){
                $texto.='<p style="font-size:16px;font-weight: bold;margin-bottom: -5px;margin-top: 10px;color:'.$array['colorprimario'].';"><i>Personaliza tu pedido:</i></p>';
             }
            
             $texto.='<div id="opciones-producto" style="margin: 10px;margin-top:25px;">';
                $texto.='<form id="from-producto" >';
            if (count($modifierCategories)>0){
                   $texto.=llenaOpciones($modifierCategories,$modificadores,$precio,$array['id']);
            }

            if (is_array($modifierCategoriesGru)){
                if (count($modifierCategories)>0){
                    array_push($modifierCategories,$modifierCategoriesGru ); 
                    array_push($modificadores,$modificadoresGru ); 

                }
                else {
                    $modifierCategories=$modifierCategoriesGru ; 
                    $modificadores=$modificadoresGru ; 
                }

                $texto.=llenaOpciones($modifierCategoriesGru,$modificadoresGru,$precio,$array['id']);
            }
            $texto.='</from><br></div>
            </div>';

        }
	}
    else {
        $texto.='<form id="from-producto" >';
        $texto.='<input type="hidden" name="forzoso" value="0">';
        $texto.='</from>';
    }

    if ($esMenu=='1'){
        $modifierCategories=null;
        $texto.='<div class="block" style="margin-top:5px;margin-bottom:5px;">
             <p style="font-size:16px;font-weight: bold;margin-bottom: -5px;margin-top: 10px;color:'.$array['colorsecundario'].';"><i>Personaliza tu menú:</i><p>
             <div id="opciones-producto-menu" style="margin: 10px;">';
        $texto.='<form id="from-producto-menu" >'; 
        $texto.=llenaOpcionesMenu($array['id'],$nombre);
        $texto.='</from><br></div>
        </div>';
    }
    
$texto.='<div style="margin: 15px;margin-top: -5px;"><p style="font-size:16px;font-weight: bold;margin-bottom: -5px;margin-top: 10px;color:'.$array['colorprimario'].';"><i>¿Algún comentario?</i><span id="caracteres" style="float: right; color: var(--f7-theme-color);font-size:.9em;">136</span></p><div class="list" style="margin-top: 15px;">
        <ul>
          <li class="item-content" style="background-color: transparent;border: white solid 1px;border-radius: 5px;  ">
            <div class="item-inner">
              <div class="item-input-wrap">
                <input type="text" placeholder="Comentario" id="comentario-prod" onkeyup="evaluatextomsgprod();"/>
                <span class="input-clear-button"></span>
              </div>
            </div>
          </li>
          </ul>
          </div></div>';
    
    $posicion_coincidencia = strpos($texto, 'Obligatorio');
    
    $steep_no_visible='';
    if ($posicion_coincidencia != false) {

        $steep_no_visible='display:none;';
    }
    $txt_cantidad='<div class="block" style="margin-top:5px;margin-bottom:5px;'.$steep_no_visible.'" id="stepper-producto">
                <div class="" style="display: flex;">
                    <div class="" style="font-size:16px;font-weight: bold;width: 60%;">Número de unidades
                    </div>
                    <div class="" style="width: 40%;text-align: right;">
                        <div class="stepper stepper-fill stepper-round stepper-init">
                            <div class="stepper-button-minus" onclick="RestaUno();"></div>
                            <div class="stepper-input-wrap">
                                <input type="text" value="1" id="cantidadProducto" readonly />
                            </div>
                            <div class="stepper-button-plus" onclick="SumaUno();"></div>
                        </div>
                    </div>
                </div>
     </div>';

    
    /*
     $texto.='<div class="block" style="margin-top:5px;margin-bottom:5px;">
                <div class="row">
                    <div class="col-60" style="font-size:16px;font-weight: bold;">Número de unidades
                    </div>
                    <div class="col-40">
                        <div class="stepper stepper-fill stepper-round stepper-init">
                            <div class="stepper-button-minus" onclick="RestaUno();"></div>
                            <div class="stepper-input-wrap">
                                <input type="text" value="1" id="cantidadProducto" readonly />
                            </div>
                            <div class="stepper-button-plus" onclick="SumaUno();"></div>
                        </div>
                    </div>
                </div>
     </div>';
    
    

     $texto.='<div style="background-color:'.$array['colorprimario'].';width:126px;height:34px;border-radius: 0 34px 0 0;margin-left: -16px;" ></div>'  ;
    
    $texto.='
            <div style="background: linear-gradient(to right, '.$array['colorprimario'].' 126px, transparent 0);"margin-left: -16px;" >
              <div class="item-inner" style="background-color: white;border-radius: 44px 15px 15px 44px;margin-left: 60px;margin-right: 30px;padding-left: 15px;padding-top: 10px;padding-bottom: 10px;box-shadow: 0 0 6px 1px lightgrey;">
                
                <div class="item-text" style="text-align:center;line-height: 5px;">
                <p>Precio total</<p>
                <span style="color: #ce132e!important;width: 32px!important;height: 26px!important;border-radius: 15px!important;border: 1px solid lightgrey!important;padding-top: 6px!important;box-shadow: 2px 2px 5px lightgrey!important;background-color: white;margin-right: -15px;margin-top: 15px!important;float: right;"><i class="icon material-icons">shopping_cart</i></span>
                <p id="precio-producto" style="font-size:22px;font-weight: bold;">'.$precio.' €</<p>';
                if ($esMenu=='1'){
                    $texto.='<div class="row"><div class="col"><button id="add-to-cart" class="button button-fill button-round button-outline add-to-cart" style="width:70%;margin:auto;font-size:12px;" data-id="'.$array['id'].'"data-nombre="'.$nombre.'" data-precio="'.$precio.'" data-iva="'.$impuesto.'" data-img="'.$imagen.'" onclick="addCarritoMenu(this);"  data-esmenu="si"><i class="icon f7-icons if-not-md">cart_fill_badge_plus</i><i class="icon material-icons if-md">add_shopping_cart</i>&nbsp;&nbsp;Añadir a carrito</button></div></div>';
                }
                else {
                    $texto.='<div class="row"><div class="col"><button id="add-to-cart" class="button button-fill button-round button-outline add-to-cart" style="width:70%;margin:auto;font-size:12px;" data-id="'.$array['id'].'"data-nombre="'.$nombre.'" data-precio="'.$precio.'" data-iva="'.$impuesto.'" data-img="'.$imagen.'" onclick="addCarritodesdeproducto(this);" data-esmenu="no"><i class="icon f7-icons if-not-md">cart_fill_badge_plus</i><i class="icon material-icons if-md">add_shopping_cart</i>&nbsp;&nbsp;Añadir a carrito</button></div></div>';
                }
                  
                  
              $texto.='</div><br></div></div>';
            
   
     $texto.='<div style="background-color:'.$array['colorprimario'].';width:126px;height:34px;border-radius: 0 0 34px 0;margin-left: -16px;" ></div>' ;
         
    $texto.='</div>';   

    */
    
 
    
    $class_forsoso='button-fill';
    if ($posicion_coincidencia != false) {
        $class_forsoso='disabled ';
    }
    $txt_add='
          <div class="button button-outline button-round '.$class_forsoso.'" id="add-to-cart"  data-id="'.$array['id'].'"data-nombre="'.$nombre.'" data-precio="'.$precio.'" data-sin="'.$precio.'" data-iva="'.$impuesto.'" data-img="'.$imagen.'" onclick="addCarritodesdeproducto(this);" style="width: 95%; margin: auto;display: block;height: 40px;line-height: 35px;"><span style="float:left;">Añadir al pedido</span> <span id="precio-producto" style="font-size:22px;font-weight: bold;float:right;">'.$precio.' €</span><span ></span></div>
        ';
    $texto.='<br><br><div class="block-outline" style="position: fixed;bottom: 0;z-index: 5;
    padding-bottom: 25px;background-color:rgba(255, 255, 255, 0.8);; width:100%;">'.$txt_cantidad.$txt_add.'</div>';

}	
$database->freeResults(); 

$json=array("valid"=>$checking,"nombre"=>$nombre,"imagen"=>$imagen,"imagen_app1"=>$imagen_app1,"imagen_app2"=>$imagen_app2,"imagen_app3"=>$imagen_app3,"precio"=>$precio,"precio_web"=>$precio_web,"precio_app"=>$precio_app,"activo"=>$activo,"activo_web"=>$activo_web,"activo_app"=>$activo_app,"impuesto"=>$impuesto,"info"=>$info, "alergenos"=>$alergenos, "modifierCategories"=>$modifierCategories, "modifierGroups"=>$modifierGroups, "modifiers"=>$modificadores,"modifi"=>$modifi,"texto"=>$texto,"background"=>'si',"txt_aler"=>$alergi,"esMenu"=>$esMenu);

ob_end_clean();

echo json_encode($json); 




/*
$file = fopen("texto.txt", "w");
fwrite($file, "sql: ". $sql . PHP_EOL);
fwrite($file, "sql2: ". $sql2 . PHP_EOL);
fwrite($file, "sql3: ". $sql3 . PHP_EOL);
//fwrite($file, "sql1: ". $sql1 . PHP_EOL);
fwrite($file, "modifierCategories: ". $modifier_category_id . PHP_EOL);
fwrite($file, print_r($modifierCategories, true));
fwrite($file, "modifierGroups: ". $modifier_group_id . PHP_EOL);
fwrite($file, print_r($modifierGroups, true));
fwrite($file, "modificadores: ". PHP_EOL);
fwrite($file, print_r($modificadores, true));
fwrite($file, "DATOS: ". json_encode($json) . PHP_EOL);
fwrite($file, "isapp: ". $isApp. PHP_EOL);
fclose($file);
*/




function llenaOpcionesMenu($idProd,$nombre) {
    $sql="SELECT id, nombre, orden, eleMulti, min, max FROM MenuCategories WHERE producto='".$idProd."' ORDER BY orden;";   
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();


    

        
    $txt='';
    while ($MenuCategories = $result->fetch_object()) {
    
        
        $txt.='<input type="hidden" class="input-menu-'.$idProd.'" value="'.$MenuCategories->id.'-no" id="input-menu-'.$MenuCategories->id.'" ><div class="list" style="margin:0;"><ul><li><a class="item-link item-content esSelectMenu" ><div class="item-media"><i class="icon f7-icons text-color-red icon_menu" id="icon_menu_'.$MenuCategories->id.'">xmark</i></div><div data-id="'.$MenuCategories->id.'" data-nombre="'.$MenuCategories->nombre.'" class="item-inner" onclick="cambiaMenu('.$MenuCategories->id.','.$MenuCategories->eleMulti.','.$MenuCategories->min.','.$MenuCategories->max.',\''.$MenuCategories->nombre.'\','.$idProd.',\''.$nombre.'\');"><div class="item-title">'.$MenuCategories->nombre.'</div><div class="item-after" id="item-after-'.$MenuCategories->id.'"></div>
            </div>';
        
        
        

        $txt.='</a>
            </li></ul>
        </div>';
        $txt.='<!-- Slider container -->
        <div class="block-title title-swiper" id="title-swiper-menu-'.$MenuCategories->id.'" style="display:none;margin-top: 0;"></div>
        <div class="swiper swiper-init" id="swiper-'.$MenuCategories->id.'" style="display:none;padding-right: 20px;">
            <!-- Slides wrapper -->
            <div class="swiper-wrapper" id="swiper-menu-'.$MenuCategories->id.'"  ></div>
            
            <div class="swiper-pagination"></div>
        </div>';
        
        if (($MenuCategories->eleMulti==3)||($MenuCategories->eleMulti==4)){
            $txt.='<a class="button button-fill button-menu" style="margin: auto;width:40%;display:none;margin-top: 10px;margin-bottom: 5px;" id="button-menu-'.$MenuCategories->id.'" onclick="compruebaMenu('.$MenuCategories->id.','.$MenuCategories->min.','.$MenuCategories->max.');">Ok</a>';
        }
    }
    
    $database->freeResults(); 
    return $txt;
}



function llenaOpciones($modifierCategories,$modificadores,$precio,$idProd){

     $txt='';
     $tmp='';
    $totMod=count($modifierCategories);
   $obligatorios=0;
    
    $file = fopen("zzz-modtexto.txt", "w");
    fwrite($file, "modifierCategories: ". json_encode($modifierCategories). PHP_EOL);

    fclose($file);
    
    for($x=0;$x<count($modifierCategories);$x++){

        $maximo=$modifierCategories[$x]->maximo;
        
        if ($modifierCategories[$x]->opciones==0){
            $tmp.='<!–– elige uno forzoso:'.$modifierCategories[$x]->forzoso.' maximo:'.$modifierCategories[$x]->maximo.'-->';
            $forzoso='<span id="but-mod-'.$modifierCategories[$x]->id.'" data-forzoso="0"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Opcional</button></span>';
            if($modifierCategories[$x]->forzoso==0){
                $forzoso='<span id="but-mod-'.$modifierCategories[$x]->id.'" data-forzoso="1"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;">Obligatorio</button></span>';
                $obligatorios++;
                
            }
            
            $tmp.='<div class="grid grid-cols-2 grid-gap" style="margin:-15px;padding: 5px;border: white 2px solid;border-radius: 5px;">
                    <div class="" style="font-size:16px;font-weight: bold;">'.$modifierCategories[$x]->nombre.'
                    </div>
                    <div class="" style="float: right;">'.$forzoso.'
                    </div>
                </div>';
            
            $tmp.='<div class="list" style=""><ul>';
            
            if (is_array($modificadores[0])){
                for ($j=0;$j<count($modificadores[$x]);$j++){
                    $selected='';

                    if ($modificadores[$x][$j]->autoseleccionado==1){
                        //$selected='checked';

                    }
                    $tmp.='<li style="margin-bottom: 10px;">
                      <label class="radio" style="padding-right: 10px;">
                        <input type="radio" name="tipo_'.$x.'" '.$selected.' value="'.$modificadores[$x][$j]->id.'#'.$modificadores[$x][$j]->precio.'#'.$modificadores[$x][$j]->nombre.'"  onclick="muestramodificador(this,'.$modifierCategories[$x]->id.');cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');"/>
                        <i class="icon icon-radio"></i>
                        
                      </label> '.$modificadores[$x][$j]->nombre.' (+'.$modificadores[$x][$j]->precio.' €)
                    </li>';
                    
                    

                }
            }
            else {
                
                for ($j=0;$j<count($modificadores);$j++){
                    $selected='';

                    if ($modificadores[$j]->autoseleccionado==1){
                        //$selected='checked';
                        

                    }
                    $tmp.='<li style="margin-bottom: 10px;">
                     <label class="radio" style="padding-right: 10px;">
                        <input type="radio" name="tipo_'.$x.'" '.$selected.' value="'.$modificadores[$j]->id.'#'.$modificadores[$j]->precio.'#'.$modificadores[$j]->nombre.'" onclick="muestramodificador(this,'.$modifierCategories[$x]->id.');cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');" />
                        <i class=" icon-radio"></i>

                      </label> '.$modificadores[$j]->nombre.' (+'.$modificadores[$j]->precio.' €)
                    </li>';
                    
                    
                    
                }
            }
            $tmp.='</ul></div>';
            
            

        }
        
        else {
            //elegir varios
          $tmp.='<!–– elige varios forzoso:'.$modifierCategories[$x]->forzoso.' maximo:'.$modifierCategories[$x]->maximo.' -->';
            
            $forzoso='<span id="but-mod-'.$modifierCategories[$x]->id.'" data-forzoso="0"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;">Opcional</button></span>';
            if($modifierCategories[$x]->forzoso==0){
                $forzoso='<span id="but-mod-'.$modifierCategories[$x]->id.'" data-forzoso="1"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;">Obligatorio</button></span>';
                $obligatorios++;
            }
            
            $tmp.='<div class="grid grid-cols-2 grid-gap" style="margin:-15px;padding: 5px;border: white 2px solid;border-radius: 5px;">
                    <div class="" style="font-size:16px;font-weight: bold;">'.$modifierCategories[$x]->nombre.'
                    </div>
                    <div class="" style="float: right;">'.$forzoso.'
                    </div>
                </div>';
            
            $tmp.='<div class="list" style="margin-top: 15px;"><ul>';
            
            $maximo=$modifierCategories[$x]->maximo;
            //$tmp.='<li><a class="item-link smart-select"  data-open-in="popup"><select name="tipo_'.$x.'"  multiple id="opciones-multiples-'.$x.'" data-maximo="'.$maximo.'" onchange="miraMaximoElegible(this);cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');">';
            
            //var $maximo=$modifierCategories[$x]->maximo;
            //$tmp.='<li><a class="item-link smart-select"  data-open-in="popup" id="smart-multiples-'.$x.'"><select name="tipo_'.$x.'"  multiple id="opciones-multiples-'.$x.'" onchange="miraMaximoElegible('.$x.','.$maximo.');cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');">';
    
            if (is_array($modificadores[0])){
                
                for ($j=0;$j<count($modificadores[$x]);$j++){   
                    $tmp.='<li>
                        <label class="item-checkbox item-content">
                          <input type="checkbox" name="tipo_'.$x.'" value="'.$modificadores[$x][$j]->id.'#'.$modificadores[$x][$j]->precio.'#'.$modificadores[$x][$j]->nombre.'" data-precio="'.$modificadores[$x][$j]->precio.'"  onclick="miraMaximoElegible('.$x.','.$maximo.');cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');muestramodificador(this,'.$modifierCategories[$x]->id.');"/>
                          <i class="icon icon-checkbox"></i>
                          <div class="item-inner">
                            <div class="item-title">'.$modificadores[$x][$j]->nombre.' ('.$modificadores[$x][$j]->precio.' €)</div>
                          </div>
                        </label>
                      </li>';
                    
                    //$tmp.='<option value="'.$modificadores[$x][$j]->id.'#'.$modificadores[$x][$j]->precio.'#'.$modificadores[$x][$j]->nombre.'" data-precio="'.$modificadores[$x][$j]->precio.'">'.$modificadores[$x][$j]->nombre.' ('.$modificadores[$x][$j]->precio.' €)</option>';
                    
                }
            }
           
            else {
                //console.log(modifiers);
             
                
                for ($j=0;$j<count($modificadores);$j++){
                    $tmp.='<li>
                        <label class="item-checkbox item-content">
                          <input type="checkbox" name="tipo_'.$x.'" value="'.$modificadores[$j]->id.'#'.$modificadores[$j]->precio.'#'.$modificadores[$j]->nombre.'" data-precio="'.$modificadores[$j]->precio.'" onclick="miraMaximoElegible('.$x.','.$maximo.');cambiamodificador('.$precio.','.$totMod.',\''.$idProd.'\');muestramodificador(this,'.$modifierCategories[$x]->id.');"/>
                          <i class="icon icon-checkbox"></i>
                          <div class="item-inner">
                            <div class="item-title">'.$modificadores[$j]->nombre.' ('.$modificadores[$j]->precio.' €)</div>
                          </div>
                        </label>
                      </li>';
                    //$tmp.='<option value="'.$modificadores[$j]->id.'#'.$modificadores[$j]->precio.'#'.$modificadores[$j]->nombre.'" data-precio="'.$modificadores[$j]->precio.'" '.$selected.'>'.$modificadores[$j]->nombre.' ('.$modificadores[$j]->precio.' €)</option>';
                }   
                
           }

            /*
            $tmp.='</select>
                <div class="item-content">
                    <div class="item-inner">
                        <div class="item-title" id="item-title-multiples-'.$x.'">'.$modifierCategories[$x]->nombre.'</div>
                        <div class="item-after" id="item-after-multiples-'.$x.'"></div>
                    </div>
                </div>
                </a>
            </li>
            </ul>
            </div>';
            */
            $tmp.='
            </ul>
            </div>';
      
        }   

    }

    
    $txt.=$tmp.'<input type="hidden" name="forzoso" value="'.$obligatorios.'">';
    //$txt.='</form>';
        
    return $txt;
    
}    



?>
