<?php
$server_db = "localhost";
$usuario_db = "root";
$password_db = "";
$base_db = "museo_naturales";

$conexion = mysqli_connect($server_db, $usuario_db, $password_db, $base_db);

if (!$conexion) {
    die(json_encode([
        "status" => 500,
        "message" => "Error al conectar con la base de datos: " . mysqli_connect_error()
    ]));
}
?>
