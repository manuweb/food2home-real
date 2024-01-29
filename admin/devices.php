<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../webapp/conexion.php";
include "../webapp/MySQL/DataBase.class.php";
include "../webapp/config.php";

$deviceTimeout = 10;   


    
    global $deviceTimeout;
    $sql="SELECT impresora, Status, ClientType, ClientVersion,  LastPoll FROM integracion";
    $database = DataBase::getInstance();
    $database->setQuery($sql);
    $result = $database->execute();
    $rdata = array();

    if ($result) {    

        $now = time();
        
       $row = $database->loadObject(); 


        
        $lpt = 0;    // last polling time

        if ($row->LastPoll > 0) {
            $lpt = $row->LastPoll;
        }

        $secondsElapsed = intval($now) - intval($lpt);

        $rdata['mac'] = $row->impresora;

        if ($secondsElapsed < $deviceTimeout) {
            $rdata['status'] = $row->Status;
        } else {
            $rdata['status'] = "Connection Lost";
        }


        $rdata["clientType"]=$row->ClientType;
        $rdata["clientVersion"]=$row->ClientVersion;
        $rdata["lastConnection"] = $row->LastPoll;
        $rdata["lastPolledTime"] = $secondsElapsed;


    }

    $database->freeResults();
      

    
    echo (json_encode($rdata));


               

?>
