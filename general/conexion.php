<?php
// Esta línea evita el Error 500 y nos obliga a mostrar el texto del error real
mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "localhost";
$usuario = "root";
$password = "Pollocon12miel";
$base_datos = "if0_42011820_netstock_db";

// Creamos la conexion
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificamos la conexion
if ($conn->connect_error) {
    die("🚨 ALERTA DE SISTEMA: " . $conn->connect_error);
}
?>