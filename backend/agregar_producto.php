<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["nombre"]) || empty($data["precio"])) {
  echo json_encode(["error" => "Faltan datos obligatorios"]);
  exit;
}

$nombre = mysqli_real_escape_string($conexion, $data["nombre"]);
$descripcion = mysqli_real_escape_string($conexion, $data["descripcion"] ?? "");
$precio = floatval($data["precio"]);
$imagen = mysqli_real_escape_string($conexion, $data["imagen"] ?? "");

$sql = "INSERT INTO producto (nombre, descripcion, precio, imagen)
        VALUES ('$nombre', '$descripcion', $precio, '$imagen')";

if (mysqli_query($conexion, $sql)) {
  echo json_encode(["success" => true, "id" => mysqli_insert_id($conexion)]);
} else {
  echo json_encode(["error" => "Error al agregar el producto"]);
}
?>
