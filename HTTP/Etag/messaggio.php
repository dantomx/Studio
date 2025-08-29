<?php
$contenuto = "Benvenuto nel a  d  cd aaa on ETag!";

// Genera ETag basato sul contenuto
$etag = '"' . md5($contenuto) . '"';

// Recupera l'ETag del client (se presente)
$clientEtag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';

// Confronta gli ETag
if ($etag === $clientEtag) {
    http_response_code(304); // Not Modified
    exit;
}

// Altrimenti invia i dati
header("ETag: $etag");
header("Content-Type: text/plain");
echo $contenuto;


