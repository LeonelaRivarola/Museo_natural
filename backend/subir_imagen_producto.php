<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=utf-8");

// Carpeta donde se suben las imágenes
$carpetaDestino = "uploads/";

// Verificar que llegó un archivo
if (!isset($_FILES["archivo"])) {
    echo json_encode([
        "success" => false,
        "error" => "No llegó ningún archivo."
    ]);
    exit;
}

$archivo = $_FILES["archivo"];

// Verificar error de PHP en la subida
if ($archivo["error"] !== UPLOAD_ERR_OK) {
    echo json_encode([
        "success" => false,
        "error" => "Error al subir archivo: " . $archivo["error"]
    ]);
    exit;
}

// Crear carpeta uploads si no existe
if (!is_dir($carpetaDestino)) {
    mkdir($carpetaDestino, 0777, true);
}

// Extensión
$extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));

// Extensiones permitidas
$extPermitidas = ["jpg", "jpeg", "png"];
if (!in_array($extension, $extPermitidas)) {
    echo json_encode([
        "success" => false,
        "error" => "Formato no permitido. Solo JPG, JPEG o PNG."
    ]);
    exit;
}

// Crear un nombre único para la imagen
$nombreArchivo = "prod_" . uniqid() . "." . $extension;

// Ruta final
$rutaFinal = $carpetaDestino . $nombreArchivo;

// Mover archivo al servidor
if (!move_uploaded_file($archivo["tmp_name"], $rutaFinal)) {
    echo json_encode([
        "success" => false,
        "error" => "No se pudo guardar el archivo en el servidor."
    ]);
    exit;
}

// Respuesta correcta
echo json_encode([
    "success" => true,
    "nombreArchivo" => $nombreArchivo
]);
?>
