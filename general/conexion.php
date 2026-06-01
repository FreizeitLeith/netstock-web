<?php
mysqli_report(MYSQLI_REPORT_OFF);

$servidor = "infinityfree.com";
$usuario = "if0_42011820";
$password = "Pollocon12miel";
$base_datos = "if0_42011820_netstock_db";

$conn = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("🚨 ALERTA DE SISTEMA: " . $conn->connect_error);
}
?>