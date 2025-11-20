<?php
require("../conexion.php");

if (!isset($_GET['id'])) {
    die(json_encode(["error" => "ID no enviado"]));
}

$id = $_GET['id'];

// obtener archivo
$sql = "SELECT archivo FROM imagen WHERE id = $id";
$res = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($res);

if ($row) {
    $archivo = $row['archivo'];

    // eliminar archivo físico
    if ($archivo && file_exists("../uploads/" . $archivo)) {
        unlink("../uploads/" . $archivo);
    }
}

// eliminar registro de la BD
$sql = "DELETE FROM imagen WHERE id = $id";

if (mysqli_query($conexion, $sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => mysqli_error($conexion)]);
}
?>