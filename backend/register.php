<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

// Conexión
require("conexion.php");

// Leer JSON del body
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// Datos recibidos
$usuario   = $data['usuario']   ?? null;
$password  = $data['password']  ?? null;
$nombre    = $data['nombre']    ?? null;
$apellido  = $data['apellido']  ?? null;

// El rol de usuario común es SIEMPRE 2
$rol_id = 2;

// Validación básica
if (!$usuario || !$password || !$nombre || !$apellido) {
    echo json_encode([
        "status" => 500,
        "message" => "Faltan datos obligatorios"
    ]);
    exit;
}

// Sanitizar
$usuario  = mysqli_real_escape_string($conexion, $usuario);
$nombre   = mysqli_real_escape_string($conexion, $nombre);
$apellido = mysqli_real_escape_string($conexion, $apellido);

// Verificar si el usuario ya existe
$check = "SELECT id FROM usuarios WHERE user='$usuario'";
$result = mysqli_query($conexion, $check);

if (mysqli_num_rows($result) > 0) {
    echo json_encode([
        "status" => 500,
        "message" => "El usuario ya existe"
    ]);
    exit;
}

// Encriptar contraseña igual que el login
$salt = substr($usuario, 0, 2);
$clave_crypt = crypt($password, $salt);

// Generar token
$token = bin2hex(random_bytes(32));

// Insertar el nuevo usuario
$insert = "
    INSERT INTO usuarios (user, password, nombre, apellido, rol_id, token)
    VALUES ('$usuario', '$clave_crypt', '$nombre', '$apellido', '$rol_id', '$token')
";

$ok = mysqli_query($conexion, $insert);

if ($ok) {
    echo json_encode([
        "status"  => 200,
        "message" => "Usuario registrado correctamente",
        "token"   => $token,
        "usuario" => $usuario,
        "nombre"  => $nombre,
        "apellido"=> $apellido,
        "rol_id"  => $rol_id
    ]);
} else {
    echo json_encode([
        "status" => 500,
        "message" => "Error al registrar usuario: " . mysqli_error($conexion)
    ]);
}

mysqli_close($conexion);
?>