<?php
error_reporting( E_ALL & ~E_NOTICE );
ini_set('memory_limit', '1024M');
set_time_limit(600);
header("Content-Type: text/plain");

require 'config.php';
require 'SlubRvkXml.php';
require 'SlubRvkXmlUpdate.php';

echo '@Update start:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();
$update = new SlubRvkXmlUpdate();
$updateState = $update->update();

if ($updateState == TRUE){
    echo '@Parser start:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();
    $rvk = new SlubRvkXml($config);
    $rvk->parseXml();
    echo '@Parser end:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();
}
else{
    echo 'No new cache created because update was not necessary or failed at some point.', PHP_EOL; flush();
}

echo '@Update end:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();

?>
