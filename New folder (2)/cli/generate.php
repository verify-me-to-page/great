<?php
// Usage: php generate.php "https://payload.example.com/" 
require __DIR__.'/vendor/autoload.php';
$key = getenv('AES_KEY') ?: random_bytes(32);
// load or generate AES key
if(!getenv('AES_KEY')) echo "Generated AES_KEY: ".bin2hex($key)."\n";
$target = $argv[1] ?? die("Usage: php generate.php <URL>\n");
// encrypt payload
function enc($data,$k){$iv=random_bytes(12);$tag='';$c=openssl_encrypt($data,'aes-256-gcm',$k,OPENSSL_RAW_DATA,$iv,$tag);return bin2hex($iv.$c.$tag);}  
$payload=json_encode(['url'=>$target,'iat'=>time()]);
$q=enc($payload,$key);
// token (just random here; store in Redis if desired)
$t=bin2hex(random_bytes(16));
echo "q=$q\n";
echo "t=$t\n";
