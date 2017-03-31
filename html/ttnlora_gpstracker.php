<?php

// include the global vars
include './ttnlora_gpstracker_vars.php';

// Write to log file
    $entityBody = file_get_contents('php://input');
    $content = "$entityBody\r\n";
    file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
?>
