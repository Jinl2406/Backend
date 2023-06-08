<?php
session_start();

$host = 'localhost';
$port = '5435';
$dbname = 'usuarios';
$user = 'postgres';
$password = '113355';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "Error al conectar a la base de datos: ";
    exit;
}

// Obtener los valores enviados por la solicitud fetch
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$contraseña = $data['contraseña'];

// Escapar los valores para prevenir inyección SQL
$email = pg_escape_string($conn, $email);
$contraseña = pg_escape_string($conn, $contraseña);

// Consulta SQL para buscar los datos en la base de datos
$query = "SELECT * FROM usuarios WHERE email = '$email' AND contraseña = '$contraseña'";
$result = pg_query($conn, $query);

if (!$result) {
    echo "Error en la consulta: " . pg_last_error($conn);
    exit;
}

// Verificar si se encontraron resultados
if (pg_num_rows($result) > 0) {
    $row = pg_fetch_assoc($result);
    $_SESSION['id'] = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['contraseña'] = $row['contraseña'];

    // Crear un array con los datos a devolver como respuesta
    $response = [
        'success' => true,
        'message' => 'Usuario autenticado exitosamente.',
        'email' => $row['email'],
        'contraseña' => $row['contraseña'],
        'id' => $row['id']
    ];

    // Enviar la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Crear un array con el mensaje de error
    $response = [
        'success' => false,
        'message' => 'El usuario no se encontró en la base de datos.'
    ];

    // Enviar la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Cerrar la conexión a PostgreSQL
pg_close($conn);
?>