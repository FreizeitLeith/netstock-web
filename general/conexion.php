<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'netstock_db';

$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset para evitar problemas con caracteres especiales
$conn->set_charset("utf8mb4");
?>
