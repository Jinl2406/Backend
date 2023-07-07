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

date_default_timezone_set('America/Buenos_Aires');


// Obtener los parámetros del evento enviados por POST
$eventId = isset($_POST['eventId']) ? $_POST['eventId'] : null;
$newStart = isset($_POST['newStart']) ? $_POST['newStart'] : null;
$newEnd = isset($_POST['newEnd']) ? $_POST['newEnd'] : null;

// Validar los parámetros
if (!$eventId || !$newStart || !$newEnd) {
    echo json_encode(array('message' => 'Faltan parámetros requeridos.'));
    exit;
}

// Escapar los valores para evitar inyección SQL
$eventId = pg_escape_string($conexion, $eventId);

// Convertir las fechas a objetos DateTime y establecer la zona horaria
$newStartDateTime = new DateTime($newStart, new DateTimeZone('UTC'));
$newEndDateTime = new DateTime($newEnd, new DateTimeZone('UTC'));

// Convertir las fechas a la zona horaria deseada
$newStartDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));
$newEndDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));

// Formatear las fechas en el formato adecuado para la consulta SQL
$newStartFormatted = $newStartDateTime->format('Y-m-d H:i:s');
$newEndFormatted = $newEndDateTime->format('Y-m-d H:i:s');

// Crear la consulta UPDATE para actualizar la fecha del evento
$query = "UPDATE eventos SET fecha_hora_inicio = '$newStartFormatted', fecha_hora_final = '$newEndFormatted' WHERE id = $eventId";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo json_encode(array('message' => 'Fecha del evento actualizada exitosamente.'));
} else {
    echo json_encode(array('message' => 'Error al actualizar la fecha del evento.'));
}

pg_close($conexion);
?>
