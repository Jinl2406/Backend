<?php

$host = 'localhost';
$port = '5435';
$dbname = 'db_calendar';
$user = 'postgres';
$password = '113355';

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conexion) {
    echo json_encode(array('message' => 'Error al conectar a la base de datos.'));
    exit;
}

// Obtener los parámetros del evento enviados por POST
$eventId = isset($_POST['eventId']) ? $_POST['eventId'] : null;
$newTitle = isset($_POST['newTitle']) ? $_POST['newTitle'] : null;

// Validar los parámetros
if (!$eventId || !$newTitle) {
    echo json_encode(array('message' => 'Faltan parámetros requeridos.'));
    exit;
}

// Escapar los valores para evitar inyección SQL
$eventId = pg_escape_string($conexion, $eventId);
$newTitle = pg_escape_string($conexion, $newTitle);

// Crear la consulta UPDATE para modificar el título del evento
$query = "UPDATE eventos SET events = '$newTitle' WHERE id = $eventId";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo json_encode(array('message' => 'Título del evento modificado exitosamente.'));
} else {
    echo json_encode(array('message' => 'Error al modificar el título del evento.'));
}

pg_close($conexion);
?>
