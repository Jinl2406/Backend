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

$query = "SELECT * FROM usuarios";
$resultado = pg_query($conexion, $query);
if (!$resultado) {
    echo "Error al ejecutar la consulta SELECT.";
    exit;
}


$datos = pg_fetch_all($resultado);


pg_close($conexion);
?>




<!--/**Mostrar los resultados de la consulta
while ($fila = pg_fetch_assoc($resultado)) {
    foreach ($fila as $columna => $valor) {
        echo "$columna: $valor<br>";
    }
    echo "<br>";
}
**/



 $query = "SELECT * FROM usuarios"; 
$resultado = pg_query($conexion, $query);
if (!$resultado) {
    echo "Error al ejecutar la consulta SELECT.";
    exit;
}

while ($fila = pg_fetch_assoc($resultado)) {
    echo "Email: " . $fila['txtEmail'] . "<br>";
    echo "Contraseña: " . $fila['contraseña'] . "<br>";
    echo "-------------------------". "<br>";
}


pg_close($conexion);--> 

