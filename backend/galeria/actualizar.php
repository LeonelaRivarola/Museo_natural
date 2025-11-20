<?php
require("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $categoria_id = $_POST['categoria_id'];

    // Primero obtener archivo actual
    $sql = "SELECT archivo FROM imagen WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
    $row = mysqli_fetch_assoc($res);
    $archivoActual = $row['archivo'];

    $nuevoArchivo = $archivoActual;

    // Si se sube archivo nuevo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {

        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($_FILES['archivo']['type'], $permitidos)) {
            die(json_encode(["error" => "Formato no permitido"]));
        }

        $nombreFinal = uniqid() . "_" . $_FILES['archivo']['name'];
        $ruta = "../uploads/" . $nombreFinal;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
            // eliminar archivo viejo
            if ($archivoActual && file_exists("../uploads/" . $archivoActual)) {
                unlink("../uploads/" . $archivoActual);
            }
            $nuevoArchivo = $nombreFinal;
        }
    }

    // Actualizar BD
    $stmt = $conexion->prepare("
        UPDATE imagen SET
        titulo = ?, autor = ?, descripcion = ?, fecha = ?, archivo = ?, categoria_id = ?
        WHERE id = ?
    ");

    $stmt->bind_param("ssssssi", 
        $titulo, $autor, $descripcion, $fecha, $nuevoArchivo, $categoria_id, $id
    );

    echo $stmt->execute()
        ? json_encode(["success" => true])
        : json_encode(["error" => $stmt->error]);

    $stmt->close();
}
?>