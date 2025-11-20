<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("../conexion.php");

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Validar ID
if (!isset($_GET["id"])) {
    echo json_encode([
        "status" => 400,
        "message" => "Falta el ID de la categoría."
    ]);
    exit;
}

$id = intval($_GET["id"]);

// Leer JSON enviado desde el frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validar contenido
if (!$data || empty($data["nombre"])) {
    echo json_encode([
        "status" => 400,
        "message" => "El nombre es obligatorio."
    ]);
    exit;
}

$nombre = $data["nombre"];

// Actualizar categoría
$sql = "UPDATE categoria SET nombre = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("si", $nombre, $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => 200,
        "message" => "Categoría actualizada correctamente."
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al actualizar la categoría."
    ]);
}