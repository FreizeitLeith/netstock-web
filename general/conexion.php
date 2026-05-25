<?php
// Configuración de los parámetros de conexión
$servidor = "localhost";
$usuario = "root";       // Usuario por defecto en XAMPP
$contrasena = "";        // Contraseña por defecto en XAMPP (vacía)
$base_datos = "netstock_db"; // ¡Asegúrate de que este nombre coincida con tu BD en phpMyAdmin!

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Fallo en la conexión: " . $conn->connect_error);
}
// Si no hay error, esta línea confirma que el puente está construido
?>