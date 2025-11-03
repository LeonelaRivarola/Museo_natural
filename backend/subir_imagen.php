<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200);
  exit;
}

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
  mkdir($targetDir, 0777, true);
}

// ✅ Caso 1: FormData (archivo enviado desde app física)
if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
  $extension = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
  $nombreUnico = uniqid("img_") . "." . $extension;
  $targetFile = $targetDir . $nombreUnico;

  if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $targetFile)) {
    echo json_encode([
      "status" => 200,
      "nombre" => $nombreUnico,
      "url" => "http://" . $_SERVER["HTTP_HOST"] . "/ProyectoFinal/backend/" . $targetFile
    ]);
  } else {
    echo json_encode(["status" => 500, "message" => "Error al mover archivo"]);
  }
  exit;
}

// ✅ Caso 2: base64 (imagen enviada como texto)
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data["imagen"]) && strpos($data["imagen"], "data:image/") === 0) {
  $extension = explode("/", explode(";", $data["imagen"])[0])[1];
  $base64 = explode(",", $data["imagen"])[1];
  $nombreUnico = uniqid("img_") . "." . $extension;
  $targetFile = $targetDir . $nombreUnico;

  if (file_put_contents($targetFile, base64_decode($base64))) {
    echo json_encode([
      "status" => 200,
      "nombre" => $nombreUnico,
      "url" => "http://" . $_SERVER["HTTP_HOST"] . "/ProyectoFinal/backend/" . $targetFile
    ]);
  } else {
    echo json_encode(["status" => 500, "message" => "Error al guardar imagen"]);
  }
  exit;
}

echo json_encode(["status" => 400, "message" => "No se envió archivo"]);
?>
