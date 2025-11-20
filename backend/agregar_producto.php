<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "conexion.php";

// Validar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => 400, "message" => "Método no permitido"]);
    exit;
}

// Obtención segura de campos
$nombre       = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
$precio       = isset($_POST["precio"]) ? trim($_POST["precio"]) : null;
$descripcion  = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : null;
$imagen       = isset($_POST["imagen"]) ? trim($_POST["imagen"]) : null; // nombre ya subido
$id_categoria = isset($_POST["id_categoria"]) && $_POST["id_categoria"] !== "NULL" ? intval($_POST["id_categoria"]) : null;
$stock        = isset($_POST["stock"]) ? intval($_POST["stock"]) : 0;

// Validaciones mínimas
if (!$nombre || !$precio) {
    echo json_encode(["status" => 400, "message" => "Faltan datos obligatorios (nombre, precio)."]);
    exit;
}

// Preparar query
$sql = "INSERT INTO producto (nombre, imagen, descripcion, precio, id_categoria, stock)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    echo json_encode(["status" => 500, "message" => "Error al preparar consulta: " . mysqli_error($conexion)]);
    exit;
}

// Bind: s = string, d = double, i = int
mysqli_stmt_bind_param(
    $stmt,
    "sssddi",
    $nombre,
    $imagen,
    $descripcion,
    $precio,
    $id_categoria,
    $stock
);

// Ejecutar
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["status" => 200, "message" => "Producto agregado correctamente"]);
} else {
    echo json_encode(["status" => 500, "message" => "Error al insertar: " . mysqli_stmt_error($stmt)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
