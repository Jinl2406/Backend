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
$allDay = isset($_POST['allDay']) ? $_POST['allDay'] : null;

// Validar los parámetros
if (!$eventId || !$newStart || !$allDay) {
    echo json_encode(array('message' => 'Faltan parámetros requeridos.'));
    exit;
}

// Escapar los valores para evitar inyección SQL
$eventId = pg_escape_string($conexion, $eventId);
$newStart = pg_escape_string($conexion, $newStart);
$newEnd = pg_escape_string($conexion, $newEnd);
$allDay = $allDay ? 1 : 0; // Convertir el valor booleano a entero

// Convertir las fechas a objetos DateTime y establecer la zona horaria
$newStartDateTime = new DateTime($newStart, new DateTimeZone('UTC'));
$newEndDateTime = $newEnd ? new DateTime($newEnd, new DateTimeZone('UTC')) : null;

// Convertir las fechas a la zona horaria deseada
$newStartDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));
if ($newEndDateTime) {
    $newEndDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));
}

// Formatear las fechas en el formato adecuado para la consulta SQL
$newStartFormatted = $newStartDateTime->format('Y-m-d H:i:s');
$newEndFormatted = $newEndDateTime ? $newEndDateTime->format('Y-m-d H:i:s') : null;

// Crear la consulta UPDATE para actualizar la fecha del evento
$query = "UPDATE eventos SET fecha_hora_inicio = '$newStartFormatted', fecha_hora_final = ";
$query .= $newEndFormatted ? "'$newEndFormatted'" : "NULL";
$query .= ", es_dia_completo = CAST($allDay AS BOOLEAN)"; // Utilizar CAST para convertir el valor entero a booleano
$query .= " WHERE id = $eventId";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo json_encode(array('message' => 'Fecha del evento actualizada exitosamente.'));
} else {
    echo json_encode(array('message' => 'Error al actualizar la fecha del evento.'));
}

pg_close($conexion);
?>
