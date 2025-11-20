<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require("../conexion.php");

// Consulta SQL para obtener los usuarios.
// NOTA: no se incluye la contraseña por seguridad.
$sql = "SELECT id, nombre, apellido, user, rol_id FROM usuarios";
$result = mysqli_query($conexion, $sql);

// Array donde se guardarán los usuarios obtenidos de la BD.
$usuarios = [];

// Recorremos todos los registros devueltos por la consulta
// y los agregamos al array.
while ($fila = mysqli_fetch_assoc($result)) {
    $usuarios[] = $fila;
}

// Devolvemos la respuesta en formato JSON.
echo json_encode([
    "status" => 200,
    "data"   => $usuarios
]);