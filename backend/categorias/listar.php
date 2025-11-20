<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require("../conexion.php");

// Consulta SQL para obtener todas las categorías
$sql = "SELECT id, nombre FROM categoria ORDER BY nombre ASC";
$result = mysqli_query($conexion, $sql);

// Array donde guardamos los resultados
$categorias = [];

// Convertimos cada fila en un objeto dentro del array
while ($fila = mysqli_fetch_assoc($result)) {
    $categorias[] = $fila;
}

// Devolvemos la respuesta en JSON
echo json_encode([
    "status" => 200,
    "data"   => $categorias
]);