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

// Crear la consulta SELECT para obtener todos los eventos
$query = "SELECT * FROM eventos";

// Ejecutar la consulta
$result = pg_query($conexion, $query);

// Crear un array para almacenar los eventos
$eventos = array();

// Recorrer los resultados y guardar los eventos en el array
while ($row = pg_fetch_assoc($result)) {
    $eventos[] = $row;
}

// Devolver los eventos como respuesta JSON
echo json_encode($eventos);
?>