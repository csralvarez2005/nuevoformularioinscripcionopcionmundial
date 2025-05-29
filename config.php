<?php
$host = 'localhost';
$user = 'root'; // o tu usuario de MySQL
$pass = '';     // tu contraseña si tienes una
$db   = 'opcionmundial';

// Crear conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>








