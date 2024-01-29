<?php
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  push_android.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde push.js ->app.request
//
//*************************************************************************************************

define("FB_KEY", "AAAA7YzrNYY:APA91bH9ErjtS9qSPz_zc7CrOPKWUn3zVcAT6zQNUNaMg2fAdVmEH5Bn7LKEdqAwwbR1tmG1r5_5ngQhS71KbvVO3cjso6QTsdOJ3Oi9IqlqqxB7upfdE-P-nn6SIntAhCRye6_SQU9T");


if ($_POST['imagen']!=""){
    $data=array (
            'body'     => $_POST['body'], //mensaje a enviar
            'message'     => $_POST['body'], //mensaje a enviar
            'title'      => $_POST['titulo'],// Titulo de la notificación
            'msgcnt'    => 1,
            'icon'=> 'icono',
            'image'=> 'https://revo2.elmaestroweb.es/webapp/img/upload/'.$_POST['imagen'],
            'key' => $_POST['key']
        );
}
else {
     $data=array (
            'body'     => $_POST['body'], //mensaje a enviar
            'message'     => $_POST['body'], //mensaje a enviar
            'title'      => $_POST['titulo'],// Titulo de la notificación
            'msgcnt'    => 1,
            'icon'=> 'icono',
            'key' => $_POST['key']
        );   
    
    
}





$fcm = new FCM();
$result = $fcm->send_notification(array($_POST['token']), $data);



$resul=json_decode($result,true);

if ($resul['success']==0){
    $checking=false;
}
else {
    $checking=true;
}




$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("android.txt", "w");
fwrite($file, "token: ".$_POST['token']. PHP_EOL);

fwrite($file, "jason: ".json_encode($json). PHP_EOL);
fwrite($file, "payload: ".json_encode($data). PHP_EOL);
fclose($file);

*/


class FCM {
    function __construct() {
    }
   /**
    * Sending Push Notification
   */
  public function send_notification($registatoin_ids, $notification) {
      $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array(
        'registration_ids' => $registatoin_ids,   
         'data' => $notification
    );

     
     
      
      // Firebase API Key
      $headers = array('Authorization:key='.FB_KEY,'Content-Type:application/json');
     // Open connection
      $ch = curl_init();
      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // Disabling SSL Certificate support temporarly
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          //die('Curl failed: ' . curl_error($ch));
          $resul['success']=0;
          return $result;
      }else {
          return $result;
      }
      curl_close($ch);
  }
} 






?>






