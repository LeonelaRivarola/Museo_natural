<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("../conexion.php");

// Manejo de preflight (para peticiones desde React/React Native)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Leemos el body JSON enviado desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validación básica
if (!$data || empty($data["nombre"])) {
    echo json_encode([
        "status" => 400,
        "message" => "El nombre de la categoría es obligatorio."
    ]);
    exit;
}

$nombre = $data["nombre"];

// Insertar nueva categoría
$sql = "INSERT INTO categoria (nombre) VALUES (?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre);

if ($stmt->execute()) {
    echo json_encode([
        "status" => 201,
        "message" => "Categoría creada correctamente.",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al crear la categoría."
    ]);
}