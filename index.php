<?php
error_reporting( E_ALL & ~E_NOTICE );
header('Content-Type: application/json');

require 'config.php';

// get params
$rvk = $_GET['rvk'] ? $_GET['rvk'] : $_POST['rvk'];
$rvk = strtoupper( urldecode( $rvk ) );

$error = ['error' => null];

if ( preg_match($config['rvkRegex'], $rvk) ) {
    
    $aRvk = explode(' ', $rvk); 
    $filePath = $config['save']['path'] . $aRvk[0].'/'.$aRvk[1] . '.json'; // set save path
    
    if ( is_file( $filePath  ) ) {
        echo file_get_contents($filePath);
    } else {
        $error['error'] = 'unknown rvk: ' . $rvk;
    }
    
} else {
    
    $error['error'] = 'no valid rvk: ' . $rvk;
}

if ( $error['error'] ) {
    echo json_encode($error);
}

?>