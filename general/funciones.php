<?php
require 'conexion.php'; // Esto conecta este archivo con la base de datos

function obtenerProductos() {
    global $conn;
    $sql = "SELECT * FROM productos"; // Cambia 'productos' por el nombre real de tu tabla
    $resultado = $conn->query($sql);
    return $resultado;
}
?>
<?php
// Función para verificar si hay una sesión activa
function verificarSesion() {
    session_start();
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../general/login.php"); // Redirige al login si no está logueado
        exit();
    }
}
?>