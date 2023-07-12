<?php
$host = 'localhost';
$port = '5435';
$dbname = 'db_calendar';
$user = 'postgres';
$password = '113355';

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conexion) {
    echo "Error al conectar a la base de datos.";
    exit;
}
date_default_timezone_set('America/Buenos_Aires');


// Obtener los datos del evento enviado por AJAX
$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'];
$start = $data['start'];
$end = $data['end'];
$allDay = $data['allDay'];  // Obtener el valor de allDay

// Escapar los valores para evitar inyección SQL
$title = pg_escape_string($conexion, $title);

$startDateTime = new DateTime($start);
$startDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));
$startFormatted = $startDateTime->format('Y-m-d H:i:s');

$endFormatted = null;
if ($end !== null) {
    $endDateTime = new DateTime($end);
    $endDateTime->setTimezone(new DateTimeZone('America/Buenos_Aires'));
    $endFormatted = $endDateTime->format('Y-m-d H:i:s');
}

// Convertir el valor de allDay a un valor booleano
$allDayValue = $allDay ? 'TRUE' : 'FALSE';


// Crear la consulta INSERT
$query = "INSERT INTO eventos (events, fecha_hora_inicio, fecha_hora_final, es_dia_completo) VALUES ('$title', '$startFormatted', '$endFormatted', $allDayValue)";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo "Evento guardado exitosamente.";
} else {
    echo "Error al guardar el evento.";
}

pg_close($conexion);
?>
