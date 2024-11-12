<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header('Access-Control-Allow-Origin: *');
ini_set('display_errors', 1);

error_reporting(E_ALL);

$array = json_decode(json_encode($_POST), true);

define('CLIENTTOKEN', "Iyt02DPB592ilC6wUyk4GM1yjz2yAri83aWC7KkFV6euPUxzy0UFQ29vrH7H");
define('URLREVO', "https://revoxef.works/");
$sql="SELECT integracion.usuario, integracion.token FROM integracion where id=1";
$database = DataBase::getInstance();
$database->setQuery($sql);
$result_empresa = $database->execute();
$empresa= $result_empresa->fetch_object();


$checking=false;
$token = $empresa->token;
$user= $empresa->usuario;
$clienttoken=CLIENTTOKEN;
$database->freeResults();
//$array['tipo']='categorias';
$tipo='categories';
if ($array['tipo']=='grupos'){
    $tipo='groups';
}
if ($array['tipo']=='productos'){
    $tipo='items';
}
if ($array['tipo']=='catmod'){
    $tipo='modifierCategories';
}
if ($array['tipo']=='grumod'){
    $tipo='modifierGroups';
}
if ($array['tipo']=='pivmod'){
    $tipo='modifierPivots';
}
if ($array['tipo']=='modificadores'){
    $tipo='modifiers';
}
if ($array['tipo']=='menuMenuCategories'){
    $tipo='menuMenuCategories';
}
if ($array['tipo']=='menuMenuItemCategoryPivots'){
    $tipo='menuMenuItemCategoryPivots';
}
$url=URLREVO.'api/external/v2/catalog/'.$tipo.'?page=';


$pagina=1;
$finished = false;    
$contador=0;
$datosRevo=[];

while ( ! $finished ):                   // while not finished
    sleep(5);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.$pagina,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        //CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        'tenant: ' . $user,"Authorization: Bearer ". $token, "client-token: ".$clienttoken
          ),
     ));
    
    $response = curl_exec($curl);
    $data = json_decode($response, true);
    $datos=$data['data'];
    $per_page=$data['per_page'];


    for ($n=0;$n<count($datos);$n++){
        
        if ($array['tipo']=='productos'){
            $info=$datos[$n]['info'];
            if ($info!=''){
                $info=substr(quitaComillas($datos[$n]['info']),0,495);
            }
            $datosRevo[]=[
                'id'=>$datos[$n]['id'],
                'categoria'=>$datos[$n]['category_id'],
                'nombre'=>quitaComillas($datos[$n]['name']),
                'esMenu'=>$datos[$n]['type'],
                'imagen'=>$datos[$n]['photo'],
                'impuesto'=>$datos[$n]['tax_id'],
                'orden'=>$datos[$n]['order'],
                'activo'=>$datos[$n]['active'],
                'precio'=>$datos[$n]['price'],
                'info'=>$info,
                'alergias'=>$datos[$n]['allergies'],
                'modifier_group_id'=>$datos[$n]['modifier_group_id'],
                'modifier_category_id'=>$datos[$n]['modifier_category_id']
            ]; 

        }
        if ($array['tipo']=='categorias'){
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'grupo'=>$datos[$n]['group_id'],
            'nombre'=>quitaComillas($datos[$n]['name']),
            'imagen'=>$datos[$n]['photo'],
            'impuesto'=>$datos[$n]['tax_id'],
            'orden'=>$datos[$n]['order'],
            'activo'=>$datos[$n]['active'],
            'modifier_group_id'=>$datos[$n]['modifier_group_id'],
            'modifier_category_id'=>$datos[$n]['modifier_category_id']
            ];
            
            
            
        }
        if ($array['tipo']=='modificadores'){
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'nombre'=>quitaComillas($datos[$n]['name']),
            'precio'=>$datos[$n]['price'],
            'imagen'=>$datos[$n]['photo'],
            'activo'=>$datos[$n]['active'],
            'category_id'=>$datos[$n]['category_id'],
            'autoSelected'=>$datos[$n]['autoSelected']
            ];
            
                
        }
        if ($array['tipo']=='catmod'){
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'nombre'=>quitaComillas($datos[$n]['name']),
            'orden'=>$datos[$n]['order'],
            'activo'=>$datos[$n]['active'],
            'opciones'=>$datos[$n]['isChoice'],
            'forzoso'=>$datos[$n]['isOptional'],
            'shouldHide'=>$datos[$n]['shouldHide'],
            'min'=>$datos[$n]['min'],
            'max'=>$datos[$n]['max']
            ];
        }
        if ($array['tipo']=='menuMenuCategories'){   
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'nombre'=>quitaComillas($datos[$n]['name']),
            'orden'=>$datos[$n]['order'],
            'eleMulti'=>$datos[$n]['isMultipleChoice'],
            'min'=>$datos[$n]['min'],
            'max'=>$datos[$n]['max'],
            'producto'=>$datos[$n]['item_id']
            ];
        }
        if ($array['tipo']=='menuMenuItemCategoryPivots'){   
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'orden'=>$datos[$n]['order'],
            'activo'=>$datos[$n]['active'],
            'precio'=>$datos[$n]['price'],
            'producto'=>$datos[$n]['item_id'],
            'category_id'=>$datos[$n]['category_id'],
            'modifier_group_id'=>$datos[$n]['modifier_group_id'],
            'addPrecioMod'=>$datos[$n]['addModifiersPrice']
            ];
        }
        if ($array['tipo']=='grumod'){
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'nombre'=>$datos[$n]['name']
            ];
        }
        if ($array['tipo']=='pivmod'){
            $datosRevo[]=[
            'id'=>$datos[$n]['id'],
            'group_id'=>$datos[$n]['group_id'],
            'category_id'=>$datos[$n]['category_id'] 
            ];
        }
        if ($tipo=='groups'){
            
            $datosRevo[]=[
                'id'=>$datos[$n]['id'],
                'nombre'=>$datos[$n]['name'],
                'imagen'=>$datos[$n]['photo'],
                'orden'=>$datos[$n]['order'],
                'impuesto'=>$datos[$n]['tax_id'],
                'activo'=>$datos[$n]['active']
            ];
        }
    }

    $contador=$contador+count($datos);

    $pagina++;
    if ( $contador>=$data['total'] ):        // última página
        $finished = true;                    // ...we are finished
    endif;
    $checking=true;

endwhile;

curl_close($curl); 

$json=array("valid"=>$checking,"datosRevo"=>$datosRevo);

ob_end_clean();
echo json_encode($json); 

$fichero="synccategorias.txt";
if ($array['tipo']=='productos'){
    $fichero="syncproductos.txt";
}
if ($array['tipo']=='modificadores'){
    $fichero="syncmodificadores.txt";
}
if ($array['tipo']=='catmod'){
    $fichero="synccatmod.txt";
}  
if ($array['tipo']=='grumod'){
    $fichero="syncgrumod.txt";
}
if ($array['tipo']=='pivmod'){
    $fichero="syncpivmod.txt";
    
}
if ($array['tipo']=='menuMenuCategories'){
    $fichero="syncmenuMenuCategories.txt"; 
}
if ($array['tipo']=='menuMenuItemCategoryPivots'){
    $fichero="syncmenuMenuItemCategoryPivots.txt";
}

//file_put_contents('zz-menuMenu.txt', print_r($datosRevo, true));


$file = fopen($fichero, "w");
fwrite($file, "Datos traidos de Revo: ".   PHP_EOL); 
fwrite($file, "---------------------- ".   PHP_EOL); 
fwrite($file, $url.$pagina.   PHP_EOL); 

fclose($file);

file_put_contents($fichero, print_r($datosRevo, true),FILE_APPEND);

function quitaComillas($nombre){
    if ($nombre!=''){
        $text = str_replace("'", "´", $nombre);
        if (str_contains($nombre, "'")) {
            $file = fopen('Reemplazo comillas.txt', "a+");
            fwrite($file, "Reemplazado: ". $nombre. " --> " .$text. PHP_EOL);
            fclose($file);
        }
    }
    else {
        $text=$nombre;
    }
    return $text;
}

function object_sorter($clave,$orden=null) {
    return function ($a, $b) use ($clave,$orden) {
          $result=  ($orden=="DESC") ? strnatcmp($b->$clave, $a->$clave) :  strnatcmp($a->$clave, $b->$clave);
          return $result;
    };
}
?>