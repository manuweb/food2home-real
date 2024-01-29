<?php
include "../../webapp/conexion.php";
include "../../webapp/MySQL/DataBase.class.php";
header("Access-Control-Allow-Origin: *");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) ) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$checking=false;
if (isset($_FILES)) {

    $uploaddir = '../../webapp/img/empresa/';

    $archivo=$_FILES['imagen']['name'];
    $sep=explode('image/',$_FILES["imagen"]["type"]); 
    $tipo=$sep[1]; 
    if($tipo == "jpg" || $tipo == "jpeg" || $tipo == "png" || $tipo == "gif"){ 
        
        //unlink($uploaddir.$archivo);
        
        $resultado = move_uploaded_file($_FILES["imagen"]["tmp_name"], $uploaddir.$archivo);
        
        if($tipo == "jpg" || $tipo == "jpeg" ) {
            $im = imagecreatefromjpeg($uploaddir.$archivo);
        }
        if($tipo == "png" ) {
            $im = imagecreatefrompng($uploaddir.$archivo);
        }

        $w = imagesx($im);
        $h = imagesy($im);

        // Convert to greyscale
        imagefilter($im,IMG_FILTER_GRAYSCALE);
        //imagepng($im, $uploaddir."grey.png");              // DEBUG only

        // Allocate a new palette image to hold the b&w output
        $out = imagecreate($w,$h);
        // Allocate b&w palette entries
        $black = imagecolorallocate($out,0,0,0);
        $white = imagecolorallocate($out,255,255,255);

        // Iterate over all pixels, thresholding to pure b&w
        for ($x = 0; $x < $w; $x++) {
          for ($y = 0; $y < $h; $y++) {
             // Get current color
             $index  = imagecolorat($im, $x, $y);
             $grey   = imagecolorsforindex($im, $index)['red'];
             // Set pixel white if below threshold - don't bother settting black as image is initially black anyway
             if ($grey <= 190) {
                imagesetpixel($out,$x,$y,$white);
             }
          }
        }
        $logo_impresora='logo-impresora.png';
        imagefilter($out,IMG_FILTER_NEGATE);
        imagepng($out, $uploaddir.$logo_impresora);       
        unlink($uploaddir.$archivo);
        
        if ($resultado){
            $sql="UPDATE integracion SET logo='".$logo_impresora."' WHERE id=1;";

            $database = DataBase::getInstance();
            $database->setQuery($sql);
            $result = $database->execute();

            if ($result) {  

                $checking=true;

            }	
            $database->freeResults();        
        }
        

        
        
        
        
    }
}

$json=array("valid"=>$checking, "logo"=>$logo_impresora);

echo json_encode($json); 

/*
$sql="UPDATE alergenos SET imagen='".$nombre_imagen."' WHERE id='".$array['id']."';";

$db = DataBase::getInstance();  
$db->setQuery($sql);  
$alergeno = $db->loadObjectList();  
$db->freeResults();

if (count($alergeno)==0) {
    $checking=true;
    
}	

$json=array("valid"=>$checking);

echo json_encode($json); 



$file = fopen("texto.txt", "w");
fwrite($file, "Datos: ". $archivo . PHP_EOL);
//fwrite($file, "Sql: ". $sql . PHP_EOL); 

fclose($file);
*/

?>
