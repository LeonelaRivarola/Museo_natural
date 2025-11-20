<?php
require("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $fecha = $_POST['fecha'] ?? null;
    $categoria_id = $_POST['categoria_id'] ?? null;

    // Validar que exista archivo
    if (!isset($_FILES['archivo'])) {
        die(json_encode(["error" => "No se envió ningún archivo"]));
    }

    $file = $_FILES['archivo'];

    // Validar errores del archivo
    if ($file['error'] !== 0) {
        die(json_encode(["error" => "Error al subir archivo"]));
    }

    // Validar tipo de archivo
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $permitidos)) {
        die(json_encode(["error" => "Formato no permitido"]));
    }

    // Guardar archivo
    $nombreFinal = uniqid() . "_" . basename($file['name']);
    $ruta = "../uploads/" . $nombreFinal;

    if (!move_uploaded_file($file['tmp_name'], $ruta)) {
        die(json_encode(["error" => "No se pudo mover el archivo"]));
    }

    // Guardar en BD
    $stmt = $conexion->prepare("
        INSERT INTO imagen (titulo, autor, descripcion, fecha, archivo, categoria_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssssi", $titulo, $autor, $descripcion, $fecha, $nombreFinal, $categoria_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "id" => $stmt->insert_id]);
    } else {
        echo json_encode(["error" => $stmt->error]);
    }

    $stmt->close();
}
?>