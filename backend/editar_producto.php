<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

require("conexion.php");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || empty($input["id_producto"])) {
  echo json_encode(["status" => 400, "message" => "Faltan datos o ID"]);
  exit;
}

$id = intval($input["id_producto"]);
$nombre = mysqli_real_escape_string($conexion, $input["nombre"]);
$descripcion = mysqli_real_escape_string($conexion, $input["descripcion"]);
$precio = floatval($input["precio"]);
$imagen = mysqli_real_escape_string($conexion, $input["imagen"]);
$id_categoria = isset($input["id_categoria"]) ? intval($input["id_categoria"]) : 1; // por defecto 1
$stock = isset($input["stock"]) ? intval($input["stock"]) : 0;

$sql = "UPDATE producto
        SET nombre='$nombre', descripcion='$descripcion', precio=$precio,
            imagen='$imagen', id_categoria=$id_categoria, stock=$stock
        WHERE id_producto=$id";

if (mysqli_query($conexion, $sql)) {
  echo json_encode(["status" => 200, "message" => "Producto actualizado correctamente"]);
} else {
  echo json_encode(["status" => 500, "message" => mysqli_error($conexion)]);
}
?>
