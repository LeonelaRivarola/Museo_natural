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

// 📌 Validar campos enviados por FormData
$nombre = $_POST["nombre"] ?? "";
$precio = $_POST["precio"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";

if (!$nombre || !$precio) {
  echo json_encode(["status" => 400, "message" => "Faltan datos obligatorios"]);
  exit;
}

$nombre = mysqli_real_escape_string($conexion, $nombre);
$precio = floatval($precio);
$descripcion = mysqli_real_escape_string($conexion, $descripcion);

// -----------------------------
// 📌 PROCESAR IMAGEN SUBIDA
// -----------------------------
$imagenNombre = "";

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {

  $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);

  // Validar imagen
  $extPermitidas = ["jpg", "jpeg", "png", "gif", "webp"];
  if (!in_array(strtolower($extension), $extPermitidas)) {
    echo json_encode(["status" => 400, "message" => "Formato de imagen no permitido"]);
    exit;
  }

  $imagenNombre = uniqid("img_") . "." . $extension;
  $rutaDestino = "uploads/" . $imagenNombre;

  if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino)) {
    echo json_encode(["status" => 500, "message" => "Error al guardar la imagen"]);
    exit;
  }
}

// -----------------------------
// 📌 INSERTAR PRODUCTO
// -----------------------------
$sql = "INSERT INTO producto (nombre, descripcion, precio, imagen)
        VALUES ('$nombre', '$descripcion', $precio, '$imagenNombre')";

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