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


$email= $_POST['txtEmail'];
$contraseña = $_POST['contraseña'];

$query = "INSERT INTO usuarios (email,contraseña) VALUES ('$email','$contraseña')";
$resultado = pg_query($conexion, $query);
if (!$resultado) {
    echo "Error al ejecutar la consulta.";
    exit;
}

echo "Usuario registrado exitosamente. ";


pg_close($conexion);


echo"<script>window.location.href = '../Archivos php/tabla.php';</script>";
?>
