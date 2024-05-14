<?php
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




// Ejemplo de uso:
$host = DB_SERVER;
$username = DB_USER;
$password = DB_PASS;
$database = DB_DATABASE;

/*
$databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);

// Ejemplo de consulta para obtener un objeto
$queryForObject = "SELECT * FROM tu_tabla WHERE condicion = 'alguna_condicion'";
$resultObject = $databaseInteraction->queryToObject($queryForObject);
var_dump($resultObject); // Muestra el resultado como objeto

// Ejemplo de consulta para obtener un array
$queryForArray = "SELECT clave_prod FROM tu_tabla WHERE condicion = 'alguna_condicion'";
$resultArray = $databaseInteraction->queryToArray($queryForArray);
var_dump($resultArray); // Muestra el resultado como array
*/


/*
$array['tienda']=0;
$array['id']=229;
$array['idgrupo']=4;
$array['nombregrupo']='Desayunos';
$array['idcategoria']=25;
$array['nombrecategoria']='Tartas';
$array['isapp']='false';
*/

$checking=false;
$texto="";
/*
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
*/
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
    
$sql="SELECT productos.id, productos.nombre, productos.imagen, productos.info, productos.alergias, productos.activo, productos.activo_web, productos.activo_app,  productos.modificadores, productos.imagen_app1, productos.imagen_app2, productos.imagen_app3, productos.precio, productos.precio_web, productos.precio_app, productos.modifier_category_id, productos.modifier_group_id, productos.esMenu, categorias.modifier_category_id as modcatcat, categorias.modifier_group_id as modgrucat, impuestos.porcentaje AS porImpuesto FROM productos LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE productos.id='".$array['id']."' AND productos.tienda='".$array['tienda']."' LIMIT 1;";

/*
$db = DataBase::getInstance();  
$db->setQuery($sql);  
$grupo = $db->loadObjectList();  
$db->freeResults();
*/



$databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);

$grupo = $databaseInteraction->queryToArray($sql);

 


$alergenos=array();

if (count($grupo)>0) {
    $checking=true;
    $nombre=$grupo[0]->nombre;

    
    if ($grupo[0]->imagen!=''){
            $imagen=IMGREVO.$grupo[0]->imagen;
    }
    else {
        $imagen=IMGREVO."no-imagen.jpg";
    }
    if ($grupo[0]->imagen_app1!=''){
        $imagen=IMGAPP.$grupo[0]->imagen_app1;;
    }
    
    
    $imagen_app1=IMGAPP.$grupo[0]->imagen_app1;
    $imagen_app2=IMGAPP.$grupo[0]->imagen_app2;
    $imagen_app3=IMGAPP.$grupo[0]->imagen_app3;
    
    $activo=$grupo[0]->activo;
    $activo_web=$grupo[0]->activo_web;
    $activo_app=$grupo[0]->activo_app;
    $modifi=$grupo[0]->modificadores;
    $impuesto=$grupo[0]->porImpuesto;
    if ($impuesto==null){
        $impuesto='0';
    }
    
    
    $info=$grupo[0]->info;
    $alergias=$grupo[0]->alergias;
    $imagen_app1=$grupo[0]->imagen_app1;
    $imagen_app2=$grupo[0]->imagen_app2;
    $imagen_app3=$grupo[0]->imagen_app3;
    $precio=$grupo[0]->precio;
    $precio_web=$grupo[0]->precio_web;
    $precio_app=$grupo[0]->precio_app;
    $modifier_category_id= $grupo[0]->modifier_category_id;
    $modifier_group_id= $grupo[0]->modifier_group_id;
    $esMenu= $grupo[0]->esMenu;
    
    
   /*
    echo 'sql: ' . $sql . "<br>";
    print_r($grupo);
    echo "<hr>";

    echo $grupo[0]->modifier_group_id."<br>";
    echo "<hr>";
    echo 'modifier_category_id: ' . $modifier_category_id . "<br>";
    echo 'modifier_group_id: ' . $modifier_group_id . "<br>";
      */  
       // die();
    
    
    $modcatcat= $grupo[0]->modcatcat;
    if ($modcatcat!="") {
        $modifier_category_id=$modcatcat;
    }
    $modgrucat= $grupo[0]->modgrucat;
    if ($modgrucat!="") {
        $modifier_group_id=$modgrucat;
    }
    
    //echo "<hr>";
    //echo 'modifier_category_id: ' . $modifier_category_id . "<br>";
    //echo 'modifier_group_id: ' . $modifier_group_id . "<br>";
        
        //die();
    
    $precio=0;
        if (($array['isapp']=="")||($array['isapp']==null)||($array['isapp']=='false')) {
            $precio=$grupo[0]->precio_web;
        }
        else {
            $precio=$grupo[0]->precio_app;
        }
    //$precio=number_format($precio, 2, ",", ".");
        $decimales = explode(".",$precio);
        $decimales[1]=substr($decimales[1].'00',0,2);
    
    
    if ($alergias!=""){
        //$porciones = explode(";", $alergias);
        
        //for ($x=0;$x<count($porciones);$x++){
            $sqla="SELECT nombre, imagen FROM alergenos WHERE id IN (".$alergias.")";
        /*
            $db = DataBase::getInstance();  
            $db->setQuery($sqla);  
            $res = $db->loadObjectList();  
            $db->freeResults();
        */
        $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);

        $res = $databaseInteraction->queryToArray($sqla);
        
        
            for ($j=0;$j<count($res);$j++){
                //$alergenos[$j]['nombre']=$res[$j]->nombre;
                //$alergenos[$j]['imagen']=$res[$j]->imagen;
                $alergenos[$j]=array('nombre' => $res[$j]->nombre, 'imagen' => $res[$j]->imagen);
            }
        //}
        
    }

    
    $modifierCategories=[];
    if ($modifier_category_id!="") {
            
            // leer modificadores
            $sql1="SELECT id, nombre, forzoso, opciones, maximo, modificadores FROM modifierCategories WHERE id=".$modifier_category_id." AND activo='1';";

            /*
            $db = DataBase::getInstance();  
            $db->setQuery($sql1);  
            $modifierCategories = $db->loadObjectList(); 
            $db->freeResults(); 
            */
        
            $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);
            $modifierCategories = $databaseInteraction->queryToArray($sql1);
        
             
            $sql2="SELECT id, nombre, precio, autoseleccionado FROM modifiers WHERE id IN(".$modifierCategories[0]->modificadores.") ORDER BY FIELD(id, ".$modifierCategories[0]->modificadores.");";
        
             $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);
            $modificadores = $databaseInteraction->queryToArray($sql2);
            //array_push($modificadoresGru,$modifiersCatGru ); 
            /*
            $db = DataBase::getInstance();  
            $db->setQuery($sql2);  
            $modificadores = $db->loadObjectList();  
            $db->freeResults();
            */
        //array_push($modifierCategories,$modificadores ); 
    }

    
    $modifierCategoriesGru=[];
    
    if ($modifier_group_id!=""){
        
        $sql1="SELECT id, nombre, modifierCategories_id FROM modifierGroups WHERE id=".$modifier_group_id;
        
        /*
        $db = DataBase::getInstance();  
        $db->setQuery($sql1);  
        $modifierGroups = $db->loadObjectList();  
        $db->freeResults();  
        */
         
        
        $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);


        $modifierGroups = $databaseInteraction->queryToArray($sql1);

        
        $modificadoresGru=[];
        
        $sql3="SELECT id, nombre, forzoso, opciones,maximo, modificadores FROM modifierCategories WHERE id IN(".$modifierGroups[0]->modifierCategories_id.") ORDER BY FIELD(id, ".$modifierGroups[0]->modifierCategories_id.");";
        /*
        $db = DataBase::getInstance();  
        $db->setQuery($sql3);  
        $modifierCategoriesGru = $db->loadObjectList();  
        //$db->freeResults();
        */
        $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);
        
        
        $modifierCategoriesGru = $databaseInteraction->queryToArray($sql3);

        

        for ($j=0;$j<count($modifierCategoriesGru);$j++){
            $sql3="SELECT id, nombre, precio, autoseleccionado FROM modifiers WHERE id IN(".$modifierCategoriesGru[$j]->modificadores.") AND activo='1';";
            
            $databaseInteraction = new DatabaseInteraction($host, $username, $password, $database);
            
            $modifiersCatGru = $databaseInteraction->queryToArray($sql3);
            
            /*
            $db = DataBase::getInstance();  
            $db->setQuery($sql3);  
            $modifiersCatGru = $db->loadObjectList();
            $db->freeResults();
            */
            array_push($modificadoresGru,$modifiersCatGru ); 

            
        }
        $modificadores=$modificadoresGru;
        //die();
    }
    
    
    $texto='<div id="img-producto"><img src="'.$imagen.'" width="100%" height="auto" id="img-prod"></div>';
    /*
    $texto.=
'<div class="navbar" style="position:fixed;background-color: rgba(255, 255, 255, 0.3);">
    <div class="navbar-inner">
      <div class="left"  onclick="javascript:app.tab.show(\'#view-catalog\');muestraproductos(\''.$array['idgrupo'].'\',\''.$array['nombregrupo'].'\',\''.$array['idcategoria'].'\',\''.$array['nombrecategoria'].'\');">
              <i class="icon icon-back color-white"></i>
                          
        </div>
        <div class="title"><img src="img/logo-white.png" height="40" width="auto"></div>
          <div class="right">
                              
          </div>
    </div>
</div>';
*/
    $texto.='<div class="block" id="bloque-producto" style="margin-bottom:10px;margin-top:-20px;" >
    <br>
                    
    <p><span style="font-size:20px;">'.$nombre.' </span><span style="float:right;font-size:20px;font-weight: bold;">'.$decimales[0].'<span style="font-size:16px">,'.$decimales[1].'</span> €</span></p>
    <p style="font-size:16px;font-weight: bold;color:var(--primario);">Descripción</p>
    <p>'.$info.'</p></div>';
    


    if (count($alergenos)<1){
        $alergi='<div style="float:left;margin:5px;" class="text-align-center"><img src="'.IMGALE.'sin.png" width="48px" height="auto"><br><span style="font-size:.8em;">Libre de alergenos</span></div>';
    }
    else {
        $alergi='<div style="margin: 15px;margin-top: 0px;">';
        for ($h=0;$h<count($alergenos);$h++){
            $alergi.='<div style="float:left;margin:5px;" class="text-align-center">'.
                        '<img src="'.IMGALE.$alergenos[$h]['imagen'].'" width="32px" height="auto"><br><span style="font-size:.8em;">'.$alergenos[$h]['nombre'].'</span></div>';
            
                    
        }

        $alergi.='</div>';
    }
    /*
    $texto.='<div class="block" style="margin-top:15    px;margin-bottom:5px;">
        <p style="font-size:16px;font-weight: bold;margin-bottom: 5px;color:'.$array['colorprimario'].';">Alergenos <i style="font-size: 20px" class="icon f7-icons tip-alergenos color-primary">info_circle_fill</i></p></div>';
        */
    $texto.='<div class="block" style="margin-top:15    px;margin-bottom:5px;">
        <p style="font-size:16px;font-weight: bold;margin-bottom: 5px;color:'.$array['colorprimario'].';">Alergenos <i style="font-size: 20px" class="icon f7-icons color-primary">info_circle_fill</i></p></div>';
    $texto.=$alergi.'<br style="clear:both;">';
	
    /*
    if (is_array($modifierCategories)){
        if (is_array($modifierCategoriesGru)){
            array_push($modifierCategories,$modifierCategoriesGru ); 
            array_push($modificadores,$modificadoresGru ); 
            
        }
        
    }
    */

	

    if ($esMenu=='1'){
        $modifierCategories=null;
        $texto.='<div class="block" style="margin-top:5px;margin-bottom:5px;">
             <p style="font-size:16px;font-weight: bold;margin-bottom: -5px;margin-top: -10px; color:'.$array['colorsecundario'].';"><i>Personaliza tu menú:</i></p><form id="from-producto-menu"><div id="opciones-producto-menu" style="margin: 10px;">';
        //$texto.='<form id="from-producto-menu">'; 
        $texto.=llenaOpcionesMenu($array['id'],$nombre,$colorprimario);
        //$texto.='</from><br></div></div>';
        $texto.='<br></div></from></div>';
    }
    else {
        if($modifi=='0'){

        
		
            if ((is_array($modifierCategories))||(is_array($modifierCategoriesGru))){
                //$modificadores=[];
                 $texto.='<div class="block" style="margin-top:-25px;margin-bottom:5px;">';
                 if (count($modifierCategories)>0||count($modifierCategories)>0){
                    $texto.='<p style="font-size:16px;font-weight: bold;margin-bottom: 25px;margin-top: 25px;color:'.$array['colorprimario'].';"><i>Personaliza tu pedido:</i></p>';
                 }

                 $texto.='<div id="opciones-producto" style="margin: 10px;/*margin-top:25px;"*/>';
                    $texto.='<form id="from-producto" style="clear: both;">';
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
    $txt_cantidad='<div class="block" style="margin-top:5px;margin-bottom:-8px;'.$steep_no_visible.'" id="stepper-producto">
                <div class="" style="display: flex;margin-top: 10px;">
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

    
    
 
    
    $class_forsoso='button-fill';
    if ($posicion_coincidencia != false) {
        $class_forsoso='disabled ';
    }
    $txt_add='
          <div class="button button-outline button-round '.$class_forsoso.'" id="add-to-cart"  data-id="'.$array['id'].'"data-nombre="'.$nombre.'" data-precio="'.$precio.'" data-sin="'.$precio.'" data-iva="'.$impuesto.'" data-img="'.$imagen.'" onclick="addCarritodesdeproducto(this);" style="width: 95%; margin: auto;display: block;height: 40px;line-height: 35px;margin-top: 12px;"><span style="float:left;">Añadir al pedido</span> <span id="precio-producto" style="font-size:22px;font-weight: bold;float:right;">'.$precio.' €</span><span ></span></div>
        ';
    $texto.='<div class="block-outline" style="position: fixed;bottom: 0;z-index: 5;
    padding-bottom: 15px;background-color:rgba(255, 255, 255, 1); width:100%;">'.$txt_cantidad.$txt_add.'</div>';

}	

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




function llenaOpcionesMenu($idProd,$nombre,$colorprimario) {
    $sql="SELECT id, nombre, orden, eleMulti, min, max FROM MenuCategories WHERE producto='".$idProd."' ORDER BY orden;";   
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $txt=''; 
    if ($result) {
        $tmp='';
        $txt_exp='';
        // 0  1 opcional
        // 1  varias opciones no obl
        // 2  seleccion 1 obligatorio
        // 3  seleccionar por defecto (min - max)
        // 4  personalizado (min - max) oblig
        $obligatorios=0;
        while ($MenuCategories = $result->fetch_object()){
            
            if ($MenuCategories->eleMulti==0 ){
                $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="0"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Opcional &nbsp;&nbsp;<i class="icon f7-icons text-color-green icon_menu" style="font-size: 10px;">xmark</i></button></span>';
                //$obligatorios++;
                $txt_exp='Seleccione 1';
            }
            if ($MenuCategories->eleMulti==1 ){
                $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="0"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Opcional &nbsp;&nbsp;<i class="icon f7-icons text-color-green icon_menu" style="font-size: 10px;">checkmark_circle_fill</i></button></span>';
                $txt_exp='Seleccione a su gusto';
            }
            if ($MenuCategories->eleMulti==2 ){
                $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="1"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Obligatorio &nbsp;&nbsp;<i class="icon f7-icons text-color-red icon_menu" style="font-size: 10px;">xmark</i></button></span>';
                $txt_exp='Seleccione 1';
                $obligatorios++;
            }
            if ($MenuCategories->eleMulti==3 ){
                $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="1"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Obligatorio &nbsp;&nbsp;<i class="icon f7-icons text-color-red icon_menu" style="font-size: 10px;">xmark</i></button></span>';
                $txt_exp='Seleccione';
                if ($MenuCategories->min>0){
			
                    $txt_exp='Seleccione mínimo '.$MenuCategories->min.' y máximo '.$MenuCategories->max;
                }
                
                $obligatorios++;
                
            }
            if ($MenuCategories->eleMulti==4 ){
                if ($MenuCategories->min==0){
                    $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="0"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Opcional &nbsp;&nbsp;<i class="icon f7-icons text-color-green icon_menu" style="font-size: 10px;">checkmark_circle_fill</i></button></span>';
                }
                else {
                    $forzoso='<span id="but-mod-'.$MenuCategories->id.'" data-forzoso="1"><button class="col button button-small button-fill" style="font-size: 10px;height: 15px;text-transform: initial;width: auto;float: right;margin-top: 3px;" >Obligatorio &nbsp;&nbsp;<i class="icon f7-icons text-color-red icon_menu" style="font-size: 10px;">xmark</i></button></span>';
                    $obligatorios++;
                }
                
                $txt_exp='Seleccione mínimo '.$MenuCategories->min.' y máximo '.$MenuCategories->max;
            }
            
            $tmp.='<div class="grid grid-cols-2 grid-gap" style="margin:-15px;margin-top: 10px;padding: 5px;border: white 2px solid;border-radius: 5px;"><div class="" style="font-size:17px;font-weight: bold;">'.$MenuCategories->nombre.'</div><div class="" style="float: right;" >'.$forzoso.'</div></div>';
            $tmp.='<p style="font-size: 16px;color:'.$colorprimario.';"><i>'.$txt_exp.'</i></p>';
            $tmp.='<div class="list media-list" style="margin: 0;"><ul>';
            // llenar
            
            $tmp.=llenaOpcionesMenuIndividuales($MenuCategories->id,$MenuCategories->eleMulti, $MenuCategories->min, $MenuCategories->max);
            
            
            
            $tmp.='</ul></div>';
            
        }  
    }
    $database->freeResults();  
    
    $txt.=$tmp.'<input type="hidden" name="forzoso" value="'.$obligatorios.'"><input type="hidden" name="prod_menu" value="'.$idProd.'">';
    //$txt.='</form>';
        
    return $txt;
        
}

function llenaOpcionesMenuIndividuales($id,$eleMulti,$min,$max){
    $sql="SELECT MenuItems.orden, MenuItems.precio, MenuItems.producto, MenuItems.modifier_group_id, MenuItems.addPrecioMod, productos.nombre , productos.imagen, productos.imagen_app1 ,productos.alergias, productos.info, impuestos.porcentaje AS impuesto FROM MenuItems LEFT JOIN productos ON MenuItems.producto=productos.id LEFT JOIN categorias ON categorias.id=productos.categoria LEFT JOIN grupos ON grupos.id=categorias.grupo LEFT JOIN impuestos ON if (productos.impuesto='', if (categorias.impuesto='', grupos.impuesto, categorias.impuesto), productos.impuesto)=impuestos.id WHERE MenuItems.category_id='".$id."' AND MenuItems.activo=1 group by MenuItems.orden";
    $txt='';
    
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    
    if ($result) {
        // 1  varias opciones
        // 2  seleccion 1 obligatorio
        // 3  seleccionar por defecto (min - max)
        // 4  personalizado (min - max)
        
        $selected='';
        $x=0;
        while ($Menus = $result->fetch_object()){
            $x++;
            $imagen=$Menus->imagen;
            if($Menus->imagen!=""){
                $imagen=IMGREVO.$Menus->imagen;
            }
            if ($Menus->imagen_app1!="") {
                $imagen=IMGAPP.$Menus->imagen_app1;
            }
            if($imagen==""){
                $imagen=IMGREVO."no-imagen.jpg";   
            }
            
            $txt.='<li>
                <div class="item-content" style="padding: 0">
                    <div class="item-media">
                    <img style="border-radius: 15px" src="'.$imagen.'" width="50" />
                    </div>
                    <div class="item-inner"> 
                        <div class="item-title-row">
                            <div class="item-title">'.$Menus->nombre.'</div>
                        </div>
                       
                            <div class="item-title" style="float: right;margin-right: -20px;">'.poneOpcionSelMenu($id,$Menus->producto,$Menus->nombre,$eleMulti,$min,$max,$Menus->precio,$x,$imagen,$Menus->impuesto).' </div>
                      
                        
                        <div class="item-subtitle">(+'.$Menus->precio.' €)</div>
                        
                        <div class="item-text">'.$Menus->info.'</div>
                       
                    </div>
                </div> 
            </li>';
            /*
            $txt.='<li style="margin-bottom: 10px;">
                      <label class="checkbox" style="padding-right: 10px;">
                        <input type="checkbox" name="tipo_'.$id.'" '.$selected.' value=""  onclick="cambiaSeleccionMenu(this.value);"/>
                        <i class="icon icon-checkbox"></i>
                        
                      </label> '.$Menus->nombre.' (+'.$Menus->precio.' €)
                    </li>';
                    */
        }
    }
    $database->freeResults();  
    return $txt;
}


function poneOpcionSelMenu($id,$producto,$nombre,$eleMulti,$min,$max,$precio,$x,$imagen,$impuesto){
    // 0  1 
    // 1  varias opciones no obl
    // 2  seleccion 1 obligatorio
    // 3  seleccionar por defecto (min - max)
    // 4  personalizado (min - max) oblig 
    
    $opcionSteeper=1;
    $pasa=$x.'#'.$id.'#'.$producto.'#'.$nombre.'#'.$eleMulti.'#'.$min.'#'.$max.'#'.$precio.'#'.$imagen.'#'.$impuesto;
     if ($eleMulti==1 || $eleMulti==3){
         $pasa=$x.'#'.$id.'#'.$producto.'#'.$nombre.'#'.$eleMulti.'#0#999#'.$precio.'#'.$imagen.'#'.$impuesto;
     }
    if ($eleMulti==0){
        //seleccionar 1
        $txt='<label class="checkbox"><input type="checkbox" name="chk-ele-multi-'.$id.'" value="'.$pasa.'" class="elem-menu-opc elem-menu-opc-'.$id.' elem-menu-opc-'.$id.'-'.$producto.'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-checkbox"></i></label>';
        
    }
    if ($eleMulti==2){
        //seleccionar 1
        $txt='<label class="radio"><input type="radio" name="chk-ele-multi-'.$id.'" value="'.$pasa.'" class=" 
        elem-menu-opc elem-menu-opc-'.$id.' elem-menu-opc-'.$id.'-'.$producto.'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-radio icon-radio-menus"></i></label>';
        
    }
    if ($eleMulti==1 || $eleMulti==3){
        $txt='<label class="checkbox"><input type="checkbox" name="chk-ele-multi-'.$id.'" value="'.$pasa.'" class="elem-menu-opc elem-menu-opc-'.$id.' elem-menu-opc-'.$id.'-'.$producto.'"  onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-checkbox"></i></label>';
        
    }
    if ($eleMulti==4){
        if ($opcionSteeper==1){
        $txt='<div class="stepper stepper-raised stepper-small stepper-round stepper-fill stepper-init" data-min="'.$min.'" data-max="'.$max.'" >
            <div class="stepper-button-minus" onclick="resta1Menu(\''.$pasa.'\')"></div>
            <div class="stepper-input-wrap">
                <input type="text" name="chk-ele-multi-'.$id.'" value="0" class="elem-menu-opc menu-stepper-'.$id.' elem-menu-opc-'.$id.'-'.$producto.'" step="1" data-contenido="'.$pasa.'" readonly />
                </div>
                <div class="stepper-button-plus" onclick="suma1Menu(\''.$pasa.'\')"></div>
        </div>';
        }
        else {
            $txt='<label class="checkbox"><input type="checkbox" name="chk-ele-multi-'.$id.'" value="'.$pasa.'" class="elem-menu-opc elem-menu-opc-'.$id.' elem-menu-opc-'.$id.'-'.$producto.'" onclick="cambiaSeleccionOpcionMenu(this)"/><i class="icon-checkbox"></i></label>';
        }
    }
    
    return $txt;
    
}

function poneSteeperMenu ($producto,$id,$nombre,$min,$max,$precio,$imagen,$impuesto) {
    $txt='';
    /*
    
    
    <div class="stepper stepper-raised stepper-small stepper-round stepper-fill stepper-init" >
    <div class="stepper-button-minus"></div>
    <div class="stepper-input-wrap">
        <input type="text" value="0" min="0" max="100" step="1" readonly />
        </div>
        <div class="stepper-button-plus"></div>
</div>
    $txt='<div class="stepper stepper-fill stepper-round" style="margin-top: -20px;">
              <div class="stepper-button-minus" onclick="RestaUnoMenu('.$producto.',0,'.$max.','.$id.');"></div>
              <div class="stepper-input-wrap">
                <input type="text" value="0" min="0" max="'.$max.'" step="1" readonly id="step-prod-menu-'.$producto.'" class="prod-menu-'.$id.'" data-id="'.$id.'" data-nombre="'.$nombre.'" data-precio="'.$precio.'" data-imagen="'.$imagen.'" data-iva="'.$impuesto.'"/>
              </div>
              <div class="stepper-button-plus" onclick="SumaUnoMenu('.$producto.',0,'.$max.','.$id.');"></div>
            </div>';
    */
    return $txt;
}

function llenaOpciones($modifierCategories,$modificadores,$precio,$idProd){

     $txt='';
     $tmp='';
    $totMod=count($modifierCategories);
   $obligatorios=0;
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



class DatabaseInteraction
{
    private $connection;

    public function __construct($host, $username, $password, $database)
    {
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function queryToObject($query)
    {
        $stmt = $this->connection->query($query);
        $result = $stmt->fetchObject();
        return $result;
    }

    public function queryToArray($query)
    {
        $stmt = $this->connection->query($query);
        $arrayTemp = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $arrayTemp[] = $row;
        }

        return json_decode(json_encode($arrayTemp));
        
    }
    
}




?>
