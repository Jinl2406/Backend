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

$query = "SELECT id, email, contraseña FROM usuarios";
$resultado = pg_query($conexion, $query);
if (!$resultado) {
  echo "Error al ejecutar la consulta SELECT.";
  exit;
}

$datos = array();
while ($fila = pg_fetch_assoc($resultado)) {
  $datos[] = array(
    'id' => $fila['id'],
    'email' => $fila['email'],
    'contraseña' => $fila['contraseña']
  );
}

pg_close($conexion);
echo json_encode($datos);
?>
