<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require("conexion.php");

// Fuerza UTF-8 en la conexión
mysqli_set_charset($conexion, "utf8mb4");

$baseUrl = "http://192.168.0.104/ProyectoFinal/backend/uploads/"; 

if (!isset($_GET['id'])) {
  echo json_encode(["error" => "Falta el parámetro id"]);
  exit;
}

$id = intval($_GET['id']);

$sql = "SELECT id_producto, nombre, descripcion, precio, imagen FROM producto WHERE id_producto = $id";

$result = mysqli_query($conexion, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
echo json_encode(["error" => "Producto no encontrado"]);
  exit;
}

$row = mysqli_fetch_assoc($result);

 // Procesar el campo imagen
if (!empty($row["imagen"])) {
  // Si ya viene con "http" no agregamos base URL (por si guardás la URL completa)
  if (!preg_match('/^https?:\/\//', $row["imagen"])) {
    $row["imagen"] = $baseUrl . basename($row["imagen"]);
  }
} else {
  $row["imagen"] = null;
}

// Cerrar conexión
mysqli_close($conexion);

echo json_encode($row, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
