<?php
require("../conexion.php");

$sql = "SELECT * FROM imagen";
$result = mysqli_query($conexion, $sql);

$imagenes = [];

while ($fila = mysqli_fetch_assoc($result)) {
    $imagenes[] = $fila;
}

echo json_encode($imagenes);
?>