<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if (!isset($_GET['id'])) {
  echo json_encode(["error" => "Falta el parámetro id"]);
  exit;
}

$id = intval($_GET['id']);
$sql = "DELETE FROM producto WHERE id_producto = $id";

if (mysqli_query($conexion, $sql)) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "No se pudo eliminar el producto"]);
}
?>
