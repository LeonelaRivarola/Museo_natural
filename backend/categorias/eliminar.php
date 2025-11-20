<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("../conexion.php");

// Preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Validar si llegó el ID
if (!isset($_GET["id"])) {
    echo json_encode([
        "status" => 400,
        "message" => "Falta el ID de la categoría."
    ]);
    exit;
}

$id = intval($_GET["id"]);

// Borrar categoría
$sql = "DELETE FROM categoria WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => 200,
        "message" => "Categoría eliminada correctamente."
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al eliminar la categoría."
    ]);
}