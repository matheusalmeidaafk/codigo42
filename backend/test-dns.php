<?php

$host = "codigo42-grandej2004-0d96.k.aivencloud.com";
$port = 16952;

$socket = @fsockopen(
    $host,
    $port,
    $errno,
    $errstr,
    10
);

if ($socket) {
    echo "TCP OK";
    fclose($socket);
} else {
    echo "TCP ERRO<br>";
    echo "Código: $errno<br>";
    echo "Mensagem: $errstr";
}