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

// Escapar los valores para evitar inyección SQL
$title = pg_escape_string($conexion, $title);

$startDateTime = date('Y-m-d H:i:s', strtotime($start));
$endDateTime = date('Y-m-d H:i:s', strtotime($end));

// Crear la consulta INSERT
$query = "INSERT INTO eventos (events, fecha_hora_inicio, fecha_hora_final) VALUES ('$title', '$startDateTime', '$endDateTime')";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo "Evento guardado exitosamente.";
} else {
    echo "Error al guardar el evento.";
}

pg_close($conexion);
?>
