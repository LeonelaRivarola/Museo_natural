<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Content-Type: application/json");

require("../conexion.php");

// Manejo de preflight request (cuando el navegador 
// envía una solicitud OPTIONS antes del DELETE).
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verificamos que venga el ID del usuario a eliminar.
// Se envía por GET: ?id=5
if (!isset($_GET["id"])) {
    echo json_encode([
        "status" => 400,
        "message" => "Falta el ID del usuario."
    ]);
    exit;
}

$id = intval($_GET["id"]); // Convertimos a entero por seguridad.

// Consulta SQL usando prepared statements (más seguro que interpolar variables).
$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
// bind_param permite pasar la variable de forma segura.
// "i" → tipo integer.
$stmt->bind_param("i", $id);

// Ejecutamos y respondemos.
if ($stmt->execute()) {
    echo json_encode([
        "status" => 200,
        "message" => "Usuario eliminado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al eliminar el usuario."
    ]);
}