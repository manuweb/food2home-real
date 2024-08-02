<?php
include "../conexion.php";
//include "../MySQL/DataBase.class.php";

function conecta(){
    $con = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);
    return $con;
}
function desconecta($con){
    $con->close();
}
//include "../webapp/config.php";




/*
    Solicitud de trabajo
*/
function handleCloudPRNTGetJob() {
    
    $content_type = $_GET['type'];  
    
    /*
    determinar el tipo de medio que solicita el dispositivo cloudPRNT, configúrelo como el tipo de contenido para esta respuesta GET
    crea archivos temporales para almacenar el trabajo de impresión de origen y la versión convertida al formato solicitado por el dispositivo cloudprnt
    /NOTA: el uso de archivos temporales suele ser muy rápido, ya que se generarán en /tmp, que generalmente es un sistema de archivos basado en RAM.
    pero esto depende del sistema operativo y la distribución. Si estos archivos se escriben en medios físicos, puede perjudicar el rendimiento y provocar escrituras innecesarias en el disco.
    
    //$basefile = tempnam(sys_get_temp_dir(), "markup");
    //$markupfile = $basefile.".stm"; 
    $basefile = tempnam('tmp', "markup");
    $markupfile = $basefile.".stm"; 
    
    // cputil used the filename to determing the format of the job that it is to convert
    */
    

    
    //$outputfile = tempnam('tmp', "output");
    

    list($position, $queue, $width) = getDevicePrintingRequired( $_GET['mac']);    
    // imprimiendo ticket y ancho
    
    // vamos a una imagen, almacenada en integración (ticket)
    // imprimiendo >1 (1, ticket, 2 factura, etc)
    // tenemos que buscar el ticket y sacarlo como imagen.
    
    
    //$im = imagecreatefrompng('ticket-'.$queue.'.png');
    $ticket = imagecreatefrompng('tickets/ticket-'.$queue.'.png');
    $file='tickets/ticket-'.$queue.'.png';
    ob_end_clean();
    header('Content-Type: image/png');

    imagepng($ticket);
    
    imagedestroy($ticket);
    
    //unlink($file);
    
    

}


/*
	Determine si se requiere impresión para una impresora en particular, marcando su campo "Impresión" que se establecerá en el número de posición para ser impresora cuando se requiera un trabajo.
    Devuelve la posición, el ID de la cola y el ancho de impresión del dispositivo si se requiere impresión
*/

function getDevicePrintingRequired($mac) {
    //$sql="SELECT imprimiendo, ticket, DotWidth FROM integracion WHERE impresora = '".$mac."'";
    //$archivo = fopen("cloud.txt", "a+");
    $sql="SELECT DotWidth FROM integracion WHERE impresora = '".$mac."'";
    $mysqli=conecta();
    if ($resultado = $mysqli->query($sql)) {
        $row = $resultado->fetch_assoc();
        $sql="SELECT ticket FROM tickets WHERE impreso=0 LIMIT 1;";
        $mysqli=conecta();
        if ($resultadot = $mysqli->query($sql)) {
            if ($resultadot->num_rows > 0) {
                $rowt = $resultadot->fetch_assoc();

                return array(1, $rowt['ticket'], $row['DotWidth']);
            }
            else {
                return array(NULL, NULL, NULL);
            }
        }
        else {
        
            return array(NULL, NULL, NULL);
        }

    } else {
        
        return array(NULL, NULL, NULL);
    }
    desconecta($mysqli);
}

                         
/*
	Consulta la base de datos y devuelve el ancho de impresión almacenado para el dispositivo.
    CPUtil lo utilizará para formatear correctamente el trabajo de impresión    
*/
                         
function getDeviceOutputWidth($mac) {
	$sql="SELECT DotWidth FROM integracion WHERE impresora = '".$mac."'";
    
    $mysqli=conecta();
    if ($resultado = $mysqli->query($sql)) {
        $row = $resultado->fetch_assoc();
        $width = $row['DotWidth'];

        if (!isset($width)) {
            $width = 0;
        }
        return $width;
   
    } else {
        return 0;
    }
    desconecta($mysqli);
}
                        
function setDeviceInfo($mac, $width, $clientType, $clientVer) {
    $sql="UPDATE integracion SET DotWidth = '".$width."', ClientType = '".$clientType."', ClientVersion = '" .$clientVer."' WHERE impresora = '" .$mac."';";
    
    $mysqli=conecta();
    if ($resultado = $mysqli->query($sql)) {
        
    }
    desconecta($mysqli);

    
    
}

/*
	Actualice el estado del dispositivo y LastPoll en la base de datos.
    Devuelve verdadero si el dispositivo existe, o falso si no hay ninguna impresora registrada en la base de datos.
*/
function setDeviceStatus($mac, $status) {
	$tstamp = time();    
    // Tiempo almacenado simplemente como número de segundos desde 1970-01-01 00:00:00+0000 (UTC)
    $sql="UPDATE integracion SET Status = '".$status."', LastPoll = '".$tstamp."' WHERE impresora = '".$mac."';";
    $mysqli=conecta();
    if ($resultado = $mysqli->query($sql)) {
        return true;
    }
    return false;
    desconecta($mysqli);
    
    
}

/*
    Se ha solicitado un tranajo de impresión
*/
function handleCloudPRNTPoll() {
    //obtener el cuerpo de la solicitud, que debe estar en formato json, y analizarlo
    $parsed = json_decode(file_get_contents("php://input"), true);
    $pollResponse = array();

    
    $pollResponse['jobReady'] = false;    
    // establezca jobReady en falso de forma predeterminada, esto es suficiente para proporcionar la respuesta mínima de cloudprnt
    
    //$pollResponse['deleteMethod'] = "GET";    

    $deviceRegistered = setDeviceStatus($parsed['printerMAC'], urldecode($parsed['statusCode']));

    if (!$deviceRegistered) {
        // la solicitud provino de una impresora que actualmente no está registrada en la base de datos.
        // simplemente no hacer nada, permitir que jobReady devuelva falso para que el dispositivo cloudPrnt no realice ninguna acción
        // Nota: este puede ser un buen momento para imprimir un trabajo de "bienvenida" si es necesario
    } elseif (isset($parsed['clientAction'])) {
        // respuestas de acción del cliente recibidas, lo que significa que el dispositivo cloudPRNT ha respondido a una solicitud del servidor. Este servidor solicitará el tamaño de impresión/papel y el tipo/versión del cliente cuando sea necesario
        
        $width = 0;
        $ctype = "";
        $cver = "";

        $client_actions = $parsed['clientAction'];

        for ($i = 0; $i < count($client_actions); $i++) {
            if ($client_actions[$i]['request'] === "PageInfo") {
                $width = intval($client_actions[$i]['result']['printWidth']) * intval($client_actions[$i]['result']['horizontalResolution']);
            } elseif ($client_actions[$i]['request'] === "ClientType") {
                $ctype = strval($client_actions[$i]['result']);
            } elseif ($client_actions[$i]['request'] === "ClientVersion") {
                $cver = strval($client_actions[$i]['result']);
            }
        }

        setDeviceInfo($parsed['printerMAC'], $width, $ctype, $cver);
    } else {
        // obtener información del dispositivo de la impresora, para ver si se ha almacenado en la base de datos
        $printWidth = getDeviceOutputWidth($parsed['printerMAC']);   
        if (intval($printWidth) === 0) {
            // Si el ancho del dispositivo no está almacenado en la base de datos, utilice una acción del cliente para solicitarlo y otra información del dispositivo al mismo tiempo.
            $pollResponse['clientAction'] = array();
            $pollResponse['clientAction'][0] = array("request" => "PageInfo", "options" => "");
            $pollResponse['clientAction'][1] = array("request" => "ClientType", "options" => "");
            $pollResponse['clientAction'][2] = array("request" => "ClientVersion", "options" => "");
        } else {
            // No es necesaria ninguna acción del cliente, así que verifique la base de datos para ver si se ha solicitado un ticket
            // $printing>1 hay que imprimir
            // $queue el ticket
            list($printing, $queue, $dotwidth) = getDevicePrintingRequired( $parsed['printerMAC']);
            // imprimiendo, ticket y ancho

            if (isset($printing) && !empty($printing) && isset($queue)) {
                // Se ha solicitado un ticket, así que seinforma al dispositivo que es necesario imprimir.
                $pollResponse['jobReady'] = true;

                // Esta muestra de cola siempre utilizará Star Markup para definir el trabajo de impresión, así que obtenga una lista deformatos de salida que cputil puede generar a partir de un trabajo de marcado Star y devolverlo.el dispositivo seleccionará un formato de esta lista, según su compatibilidad interna y capacidade
                
                $tipos=['application/vnd.star.line','application/vnd.star.linematrix','application/vnd.star.starprnt','application/vnd.star.starprntcore','text/vnd.star.markup'];
                $pollResponse['mediaTypes'] =["image/png"];
                //$pollResponse['mediaTypes'] =$tipos;
                // funcion getCPSupportedOutputs BORRADA
                //$pollResponse['mediaTypes'] = getCPSupportedOutputs("text/vnd.star.markup");
                // solo voy a imprimir imagenes!!
                
                $pollResponse['jobToken'] =$queue;


            }
        }
    }    
    // $pollResponse['jobReady'] = false;
    // si no hay que imprimir
    // $pollResponse['mediaTypes'] =["image/png"];
    // tipo de impresion
    
    header("Content-Type: application/json");
    print_r(json_encode($pollResponse));
}

/*
	Borrar un trabajo de impresión de la base de datos, para la impresora especificada, pero configurando su campo 'Posición' en 'nulo'
*/
function setCompleteJob($mac,$token) {
    // establecer Printing a 0, no hay trabajos pendientes
    $sql="UPDATE tickets SET impreso = 1 WHERE ticket = '".$token."' and impreso=0 limit 1;";
    //$sql="UPDATE integracion SET imprimiendo = 0 WHERE impresora = '".$mac."';";
    $mysqli=conecta();
    if ($resultado = $mysqli->query($sql)) {
        
    }
    desconecta($mysqli);
    
}

/*
	Maneje las solicitudes http DELETE que utiliza el dispositivo CloudPRNT para borrar un trabajo de impresión del servidor.
    Normalmente la solicitud se debe a que el trabajo se ha impreso correctamente, pero también puede deberse a un error.
    como que el tipo de medio del trabajo sea incompatible, demasiado grande o corrupto.
*/
function handleCloudPRNTDelete() {
    // Borrar trabajos pendientes
    $clearJobFromDB = true;
    $headercode = substr($_GET['code'], 0, 1);
    if ($headercode != "2") {
        // El trabajo no se ha impreso debido a un error.
        $fullcode = substr($_GET['code'], 0, 3);
        if ($fullcode === "520") {          
            // tiempo de espera de descarga
            $clearJobFromDB = false;        
            // No borrar el trabajo en este caso, ya que es más probable que la causa sea un problema de red.
        }
        // mensaje error
    }
    if ($clearJobFromDB) {
        setCompleteJob($_GET['mac'], $_GET['token']);
    }
}



    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        //Solicitud GET
        if(strpos($_SERVER['QUERY_STRING'], "&delete") !== false) {    
            // si el servidor configuró "deleteMethod": "GET" en la respuesta POST -> borrar trabajos
            handleCloudPRNTDelete();
        } else {    
            // Solicitar un contenido de trabajo de impresión
            handleCloudPRNTGetJob();    
        }
    } 
    else {
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            //Solicitud POST
            handleCloudPRNTPoll();
            } 
        else {
            if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
                //borrar trabajos 
                handleCloudPRNTDelete();
            } 
            else {
                http_response_code(405);
            }
        }
    }


?>
