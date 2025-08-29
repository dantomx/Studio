<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$nome = $data["nome"] ?? '';

if ($nome !== '') {
    $stmt = $pdo->prepare("INSERT INTO nomi (nome) VALUES (:nome)");
    $stmt->execute(['nome' => $nome]);
    echo json_encode(["success" => true, "message" => "Nome inserito con successo!"]);
} else {
    echo json_encode(["success" => false, "message" => "Il nome è obbligatorio."]);
}

