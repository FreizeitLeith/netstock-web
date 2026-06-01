<?php
$host = 'localhost';
$user = 'root';
$password = 'Pollo12frito';
$database = 'if0_42011820_netstock_db';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer charset para evitar problemas con caracteres especiales
$conn->set_charset("utf8mb4");

