<?php
session_start();
require_once 'conexion.php';

// Verificar que la petición venga del formulario
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: panel.php");
    exit();
}

// Capturar datos
$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

// Buscar el usuario únicamente por el correo
$sql = "SELECT * FROM usuario WHERE correo = '$correo'";
$resultado = $conn->query($sql);

// Verificar si el usuario existe
if ($resultado && $resultado->num_rows == 1) {

    $usuario = $resultado->fetch_assoc();

    // Comparar la contraseña
    if ($password == $usuario['contrasena']) {

        // Crear variables de sesión
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['codigo_negocio'] = $usuario['codigo_negocio'];

        // Redirigir al panel principal
        header("Location: panel.php");
        exit();

    } else {

        header("Location: ../login.php?error=1");
        exit();

    }

} else {

    header("Location: ../login.php?error=1");
    exit();

}
?>