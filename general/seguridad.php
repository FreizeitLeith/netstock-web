<?php
session_start();

require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: panel.php");
    exit();
}

$correo = trim($_POST['correo']);
$password = trim($_POST['password']);

echo "Correo recibido: " . $correo . "<br>";
echo "Contraseña recibida: " . $password . "<br>";
exit();

$sql = "SELECT * FROM usuario
        WHERE correo='$correo'
        AND contrasena='$password'";

$resultado = mysqli_query($conn, $sql);

if ($resultado && mysqli_num_rows($resultado) == 1) {

    $usuario = mysqli_fetch_assoc($resultado);

    $_SESSION['usuario_id'] = $usuario['id_usuario'];
    $_SESSION['nombre'] = $usuario['nombre'];
    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['codigo_negocio'] = $usuario['codigo_negocio'];

    header("Location: panel.php");
    exit();

} else {

    header("Location: ../login.php?error=1");
    exit();

}