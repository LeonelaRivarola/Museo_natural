<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require("conexion.php");

$baseUrl = "http://192.168.0.104/ProyectoFinal/backend/uploads/";

// Consulta todos los productos
$sql = "SELECT id_producto, nombre, precio, imagen FROM producto";
$result = mysqli_query($conexion, $sql);

$productos = [];

while ($row = mysqli_fetch_assoc($result)) {
  if (!empty($row["imagen"])) {
    $row["imagen"] = $baseUrl . basename($row["imagen"]);
  } else {
    $row["imagen"] = null;
  }
  $productos[] = $row;
}

echo json_encode($productos, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
