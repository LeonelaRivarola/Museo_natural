<?php
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../conexion.php");


$baseUrl = "http://".$_SERVER['SERVER_ADDR']."/ProyectoFinal/backend/img/";

$sql = "SELECT * FROM items";
$result = mysqli_query($conexion, $sql);

$items = [];

while ($item = mysqli_fetch_assoc($result)) {

    // imagen principal
    $item["imagen"] = $baseUrl . $item["imagen"];

    // imagenes extras
    $itemId = $item["id"];
    $sqlImg = "SELECT image_url FROM item_images WHERE item_id = $itemId";
    $resImg = mysqli_query($conexion, $sqlImg);

    $images = [];
    while ($img = mysqli_fetch_assoc($resImg)) {
        $images[] = $baseUrl . $img["image_url"];
    }

    $item["images"] = $images;

    $items[] = $item;
}

header("Content-Type: application/json; charset=utf-8");

echo json_encode($items);
?>
