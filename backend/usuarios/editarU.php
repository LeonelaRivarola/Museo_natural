<?php
// CORS: permitimos orígenes, métodos y headers necesarios.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("../conexion.php");

// Si el navegador envía una solicitud OPTIONS (preflight), respondemos 200.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Debe venir el ID del usuario por GET.
if (!isset($_GET["id"])) {
    echo json_encode([
        "status" => 400,
        "message" => "Falta el ID del usuario."
    ]);
    exit;
}

$id = intval($_GET["id"]); // Seguridad: convertir a entero.

// El frontend envía el body en formato JSON.
// file_get_contents("php://input") permite leer el cuerpo de la petición PUT.
// Luego lo transformamos a array con json_decode(..., true)
$data = json_decode(file_get_contents("php://input"), true);

// Si no se envió JSON válido, devolvemos error.
if (!$data) {
    echo json_encode([
        "status" => 400,
        "message" => "Body JSON inválido."
    ]);
    exit;
}

// Extraemos los datos del JSON. 
// El operador ?? evita errores si un campo no viene.
$nombre   = $data["nombre"]   ?? null;
$apellido = $data["apellido"] ?? null;
$user     = $data["user"]     ?? null;
$rol_id   = $data["rol_id"]   ?? null;

// Construimos la consulta SQL para actualizar.
// NOTA: usamos prepared statements por seguridad.
$sql = "UPDATE usuarios 
        SET nombre = ?, apellido = ?, user = ?, rol_id = ?
        WHERE id = ?";

// Preparamos la sentencia para evitar inyecciones SQL.
$stmt = $conexion->prepare($sql);

// bind_param enlaza las variables a los placeholders.
// "sssii" significa: string, string, string, integer, integer
$stmt->bind_param("sssii", $nombre, $apellido, $user, $rol_id, $id);

// Ejecutamos y enviamos respuesta según el resultado.
if ($stmt->execute()) {
    echo json_encode([
        "status" => 200,
        "message" => "Usuario actualizado correctamente."
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al actualizar el usuario."
    ]);
}