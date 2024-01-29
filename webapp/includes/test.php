<?php
 
$http=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$url=$http.'://' . $_SERVER["HTTP_HOST"] ;

echo $url;
?>