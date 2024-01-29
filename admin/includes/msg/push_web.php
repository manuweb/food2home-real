<?php
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  push_web.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde push.js ->app.request
//
//*************************************************************************************************
$urlservidor=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
define("FB_KEY", "AAAANJ1Kn-g:APA91bGpSNep02W_-TPB29fcAUStnASJK1gnZ2IJcyMj_O_NmD2fB4LV0rGJLu_LbpHG9VUFiK6DnxSyD1q3haYsoJYdsebjorrnjyoIm_qnO16gRxpK-ny-07-NlrgTmq13uUTgtzb4");


if ($_POST['imagen']!=""){
    $data = [
        
        'notification' => [
            'title' => $_POST['titulo'],
            'body' => $_POST['body'],
            'icon' => $urlservidor.'img/icon.png',
            'image'=> $urlservidor.'webapp/img/upload/'.$_POST['imagen']
            
        ],
        'data' => [
            'key' => $_POST['key'] // Datos adicionales que quieras enviar
        ]
    ];
    /*
    $data=array (
            'body'     => $_POST['body'], //mensaje a enviar
            'title'      => $_POST['titulo'],// Titulo de la 
            'image'=> 'https://revo2.elmaestroweb.es/webapp/img/upload/'.$_POST['imagen'],
            'data' => array('key' =>'key', 'value' =>$_POST['key'])
        );
        */
}
else {
    
        $data = [
        
        'notification' => [
            'title' => $_POST['titulo'],
            'body' => $_POST['body'],
            'icon' => $urlservidor.'img/icon.png'
            
        ],
        'data' => [
            'key' => $_POST['key'] // Datos adicionales que quieras enviar
        ]
    ];
            /*
     $data=array (
            'body'     => $_POST['body'], //mensaje a enviar
            
            'title'      => $_POST['titulo'],// Titulo de la 
            'data' => array('key' =>'key', 'value' =>$_POST['key'])
        );   
    */
    
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


$file = fopen("web.txt", "w");
fwrite($file, "token: ".$_POST['token']. PHP_EOL);

fwrite($file, "jason: ".json_encode($json). PHP_EOL);
fwrite($file, "payload: ".json_encode($data). PHP_EOL);
fwrite($file, "servidor: ".$urlservidor. PHP_EOL);
fclose($file);




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






