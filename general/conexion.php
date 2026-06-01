<<<<<<< HEAD
=======
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
>>>>>>> 59809a6629f1f9c38de2827b6f5577a2e98c50e2
