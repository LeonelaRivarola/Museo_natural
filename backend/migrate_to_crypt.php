<?php
// migrate_to_crypt.php
// Ajustá estos datos si tu archivo conexion.php ya define variables parecidas
require 'conexion.php';

// Si tu conexion.php ya define $server_db,$usuario_db,$password_db,$base_db,
// usá esas variables. Si no, poné aquí los datos.
// $server_db = 'localhost'; $usuario_db = 'root'; $password_db = ''; $base_db = 'ProyectoFinal';

$conexion = mysqli_connect($server_db, $usuario_db, $password_db, $base_db);
if (!$conexion) {
    die("No conecta: " . mysqli_connect_error());
}

// Hacemos backup lógico rápido: generar un archivo SQL con los valores actuales
$backupFile = __DIR__ . '/usuarios_backup_' . date('Ymd_His') . '.sql';
$fp = fopen($backupFile, 'w');
fwrite($fp, "-- Backup de la tabla usuarios\n-- Fecha: " . date('Y-m-d H:i:s') . "\n\n");

$res = mysqli_query($conexion, "SELECT id, user, password FROM usuarios");
if (!$res) {
    die("Error al leer usuarios: " . mysqli_error($conexion));
}

while ($row = mysqli_fetch_assoc($res)) {
    $id = (int)$row['id'];
    $user = $row['user'];
    $pass = $row['password'];

    // Guardamos el INSERT original en el backup (escape simples)
    $user_e = mysqli_real_escape_string($conexion, $user);
    $pass_e = mysqli_real_escape_string($conexion, $pass);
    $line = "UPDATE usuarios SET password = '" . $pass_e . "' WHERE id = $id;\n";
    fwrite($fp, $line);
}
fclose($fp);

echo "Backup guardado en: $backupFile\n";

// --- Migración: generar crypt() por cada usuario cuya password parezca en claro ---
// Heurística simple: si la contraseña contiene espacios o tiene solo caracteres alfanuméricos
// (o su longitud < 30) la consideramos en claro. Ajustala si tu caso es distinto.
$res = mysqli_query($conexion, "SELECT id, user, password FROM usuarios");
$updated = 0;
while ($row = mysqli_fetch_assoc($res)) {
    $id = (int)$row['id'];
    $user = $row['user'];
    $pass = $row['password'];

    // Si ya parece un crypt (por ejemplo tiene dos primeros caracteres iguales al user substr OR
    // si contiene caracteres típicos de hash) podemos saltarlo. Aquí una heurística:
    $maybe_hashed = false;
    if (strlen($pass) >= 13 && (strpos($pass, '$') !== false || strpos($pass, '.') !== false || strpos($pass, '/') !== false)) {
        $maybe_hashed = true;
    }
    // Si querés ser más estricto: verificar si crypt($pass, substr(user,0,2)) == $pass (pero eso da falso si pass está en claro)
    if ($maybe_hashed) {
        // saltamos
        continue;
    }

    // Generamos el crypt con el salt que usa tu login (substr 0,2 del user)
    $salt = substr($user, 0, 2);
    $cryptpwd = crypt($pass, $salt);

    // Actualizamos
    $crypt_e = mysqli_real_escape_string($conexion, $cryptpwd);
    $sql = "UPDATE usuarios SET password = '$crypt_e' WHERE id = $id";
    if (mysqli_query($conexion, $sql)) {
        $updated++;
        echo "Actualizado id=$id user=$user -> $cryptpwd\n";
    } else {
        echo "ERROR actualizando id=$id user=$user: " . mysqli_error($conexion) . "\n";
    }
}

echo "Migración completada. Filas actualizadas: $updated\n";

mysqli_close($conexion);
?>
