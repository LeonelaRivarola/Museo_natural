<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Content-Type: application/json');

require("conexion.php");

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$usuario = $data['usuario'] ?? null;
$password = $data['password'] ?? null;


$conexion = mysqli_connect($server_db, $usuario_db, $password_db, $base_db)
    or die("No se puede conectar con el servidor");
    

if ($usuario && $password) {

    $salt = substr($usuario, 0, 2);
    $clave_crypt = crypt($password, $salt);

    $usuario = mysqli_real_escape_string($conexion, $usuario);
    $password = mysqli_real_escape_string($conexion, $password);

    $instruccion = "SELECT * FROM usuarios WHERE user='$usuario' AND password='$clave_crypt'";
    $consulta = mysqli_query($conexion, $instruccion)
        or die("Fallo en la consulta");
    $nfilas = mysqli_num_rows($consulta);

    if ($nfilas > 0) {
        $token = bin2hex(random_bytes(32));
        $row = mysqli_fetch_object($consulta);

        $update = "UPDATE usuarios SET token='$token' WHERE user='$usuario'";
        mysqli_query($conexion, $update);

        echo json_encode([
            'status' => 200,
            'token' => $token,
            'nombre' => $row->nombre,
            'apellido' => $row->apellido,
            'id' => $row->id,
            'rol_id' => $row->rol_id
        ]);
    } else {
        echo json_encode([
            'status' => 500,
            'token' => null,
            'menssage' => 'Usuario o contraseña incorrectos'
        ]);
    }
} else {
    echo json_encode([
        'status' => 500,
        'token' => null,
        'menssage' => 'No se envió usuario o password'
    ]);
}

mysqli_close($conexion);
?>
