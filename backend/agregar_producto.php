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

// 📌 Datos recibidos (desde FormData o JSON)
$nombre = $_POST["nombre"] ?? "";
$precio = $_POST["precio"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$imagen_id = $_POST["imagen_id"] ?? null;  // ← NUEVO (viene del CRUD de imágenes)

// 📌 Validaciones básicas
if (!$nombre || !$precio) {
  echo json_encode(["status" => 400, "message" => "Faltan datos obligatorios"]);
  exit;
}

$nombre = mysqli_real_escape_string($conexion, $nombre);
$descripcion = mysqli_real_escape_string($conexion, $descripcion);
$precio = floatval($precio);

// -----------------------------
// 📌 INSERTAR PRODUCTO SIN IMAGEN
// -----------------------------
$sql = "INSERT INTO producto (nombre, descripcion, precio, imagen_id)
        VALUES ('$nombre', '$descripcion', $precio, " . ($imagen_id ? "'$imagen_id'" : "NULL") . ")";

if (mysqli_query($conexion, $sql)) {
  echo json_encode([
    "status" => 200,
    "message" => "Producto agregado correctamente",
    "id" => mysqli_insert_id($conexion)
  ]);
} else {
  echo json_encode(["status" => 500, "message" => "Error al agregar el producto"]);
}
?>