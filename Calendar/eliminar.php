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

// Obtener el ID del evento enviado por AJAX
$eventId = $_POST['eventId'];

// Escapar el valor para evitar inyección SQL
$eventId = pg_escape_string($conexion, $eventId);

// Crear la consulta DELETE
$query = "DELETE FROM eventos WHERE events = '$eventId'";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

if ($result) {
    echo json_encode(array('message' => 'Evento eliminado exitosamente.'));
} else {
    echo json_encode(array('message' => 'Error al eliminar el evento.'));
}


pg_close($conexion);
?>