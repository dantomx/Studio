<?php
// Simuliamo una risposta fissa (in realtà potresti caricare dal DB)
$dati = [
    "nome" => "Gamenotes",
    "versione" => "1.0",
    "data" => "2025-08-08"  // dato fisso per testare l'ETag
];

// Codifica in JSON
$json = json_encode($dati);

// Genera un ETag basato sui contenuti
$etag = '"' . md5($json) . '"';

// Recupera l'ETag inviato dal client (se esiste)
$clientEtag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';

// Confronta
if ($clientEtag === $etag) {
    // Nessun cambiamento → risposta 304
    http_response_code(304);
    exit;
}

// Altrimenti rispondi normalmente
header("Content-Type: application/json");
header("ETag: $etag");

echo $json;


