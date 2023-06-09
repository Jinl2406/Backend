<?php
$host = 'localhost';
$port = '5435';
$dbname = 'usuarios';
$user = 'postgres';
$password = '113355';

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conexion) {
    echo "Error al conectar a la base de datos.";
    exit;
}

$email = $_POST['emailInput'];
$contraseña = $_POST['passwordInput'];



// Verificar que el email y la contraseña cumplan los requisitos
$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailRegex, $email)) {
    echo "Email inválido.";
    exit;
}

$contraseñaRegex = '/^(?=.*[a-z])(?=.*[A-Z]).{6,}$/';
if (!preg_match($contraseñaRegex, $contraseña)) {
    echo "La contraseña debe tener al menos 6 caracteres y contener al menos una letra minúscula y una letra mayúscula.";
    exit;
}
$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
if (!preg_match($emailRegex, $email)) {
    header('HTTP/1.1 400 Bad Request');
    echo "Email inválido.";
    exit;
}

$contraseñaRegex = '/^(?=.*[a-z])(?=.*[A-Z]).{6,}$/';
if (!preg_match($contraseñaRegex, $contraseña)) {
    header('HTTP/1.1 400 Bad Request');
    echo "La contraseña debe tener al menos 6 caracteres y contener al menos una letra minúscula y una letra mayúscula.";
    exit;
}

// Verificar en la base de datos
$query = "SELECT COUNT(*) FROM usuarios WHERE email = $1";
$resultado = pg_prepare($conexion, "select_query", $query);
if (!$resultado) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Error al preparar la consulta.";
    exit;
}

$resultado = pg_execute($conexion, "select_query", array($email));
if (!$resultado) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "Error al ejecutar la consulta.";
    exit;
}

$row = pg_fetch_row($resultado);
if ($row[0] > 0) {
    header('HTTP/1.1 400 Bad Request');
    echo "El usuario ya está registrado.";
    exit;
}

$query = "INSERT INTO usuarios (email, contraseña) VALUES ($1, $2)";
$resultado = pg_prepare($conexion, "insert_query", $query);
if (!$resultado) {
    echo "Error al preparar la consulta.";
    exit;
}

$resultado = pg_execute($conexion, "insert_query", array($email, $contraseña));
if (!$resultado) {
    echo "Error al ejecutar la consulta.";
    exit;
}

echo "Usuario registrado exitosamente.";

pg_close($conexion);

?>
