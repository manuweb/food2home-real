<?php
header("Access-Control-Allow-Origin: *");
//*************************************************************************************************
//
//  push_ios.php
//
//  Lee datos de la tabla inicio 
//
//  Llamado desde push.js ->app.request
//
//*************************************************************************************************



// Step 0: PSR-0 Autoloader
require __DIR__ . '/vendor/autoload.php'; 

// Step 1: Import the library
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Configuration;

// Step 2: declare config variables
$device_token = $_POST['token'];





$apns_topic = 'com.manuelsanchez.test';
$myKey='7A9VHJTJSY';
$teamId="E8EYDZY59U";
$p8file = "AuthKey_".$myKey.".p8";
$url = "https://api.sandbox.push.apple.com/3/device/$device_token"; // call either the sandbox or production API's

$config = Configuration::forUnsecuredSigner();
assert($config instanceof Configuration);


// Step 3: Generate a JWT Token.

$token = (string) $config->builder()
                         ->issuedBy($teamId) // (iss claim) // teamId
                         ->issuedAt(new DateTimeImmutable()) // time the token was issuedAt
                         ->withHeader('kid', $myKey)
                         ->getToken(new Sha256(), new Key\LocalFileReference('file://' . $p8file)); // get 




// Step 4: Generate a payload (PushMessage) to send to the device



$payLoadImage=[
    'aps' => [
        'alert' => [
            'title' => $_POST['titulo'], // title of the notification
            'body' => $_POST['body'], // content/body of the notification
            ],
        'sound' => 'default',
        'badge' => 1,
        'key' => $_POST['key'],
        'mutable-content'=>1
    ],
   'data' => ['attachment-url'=>'https://revo2.elmaestroweb.es/webapp/img/upload/'.$_POST['imagen']]
];   

    
    

$payloadArray['aps'] = [
  'alert' => [
        'title' => $_POST['titulo'], // title of the notification
        'body' => $_POST['body'], // content/body of the notification
  ],
  'sound' => 'default',
  'badge' => 1,
  'key' => $_POST['key'],
];   



// Step 4.1: Encode it as json.
if ($_POST['imagen']!=""){
    $payloadJSON = json_encode($payLoadImage);
}
else {
    $payloadJSON = json_encode($payloadArray);
}




$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJSON);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token","apns-topic: $apns_topic"]);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


if( $httpcode!=200) {
    $checking=false;
}
else {
    $checking=true;
}



$json=array("valid"=>$checking);

echo json_encode($json); 

/*
$file = fopen("ios.txt", "w");
fwrite($file, "token: ".$device_token. PHP_EOL);

fwrite($file, "json: ".json_encode($json). PHP_EOL);

fwrite($file, "payload: ".$payloadJSON. PHP_EOL);
fwrite($file, "payloadArray: ".json_encode($payloadArray). PHP_EOL);
fwrite($file, "payLoadImage: ".json_encode($payLoadImage). PHP_EOL);


fclose($file);
*/

?>