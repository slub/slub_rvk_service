<?php
error_reporting( E_ALL & ~E_NOTICE );
ini_set('memory_limit', '1024M');
set_time_limit(600);
header("Content-Type: text/plain");

require 'config.php';
require 'SlubRvkXml.php';


echo '@Parser start:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();
$rvk = new SlubRvkXml($config);
$rvk->parseXml();
echo '@Parser end:  ', date('Y-m-d H:i:s'), PHP_EOL; flush();

?>