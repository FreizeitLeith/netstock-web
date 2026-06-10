<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'general/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];
    $codigo_ingresado = isset($_POST['codigo_negocio']) ? trim($_POST['codigo_negocio']) : '';

    $codigo_final = "";

    // Lógica de Vinculación SaaS
    if ($rol == 'Jefe' || $rol == 'Administrador') {
        // Genera un código aleatorio único de 6 caracteres
        $codigo_final = 'NS-' . strtoupper(substr(md5(uniqid()), 0, 6));
    } else {
        // Es Trabajador, verificamos que el código de su jefe exista
        $sql_verificar = "SELECT id_usuario FROM usuario WHERE codigo_negocio = '$codigo_ingresado' AND (rol = 'Jefe' OR rol = 'Administrador')";
        $resultado = $conn->query($sql_verificar);
        
        if ($resultado && $resultado->num_rows > 0) {
            $codigo_final = $codigo_ingresado; // Vinculación exitosa
        } else {
            echo "<script>alert('Error: El código de vinculación no es válido o no pertenece a ningún negocio activo.'); window.history.back();</script>";
            exit();
        }
    }

    $sql = "INSERT INTO usuario (nombre, correo, rol, contrasena, codigo_negocio) VALUES ('$nombre', '$correo', '$rol', '$password', '$codigo_final')";

    if ($conn->query($sql) === TRUE) {
        if($rol == 'Jefe' || $rol == 'Administrador'){
            echo "<script>alert('¡Registrado! Tu Código de Vinculación para tus trabajadores es: $codigo_final'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('¡Cuenta de Trabajador enlazada con éxito!'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Hubo un error al registrar: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro | NetStock</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh;">

    <div class="card-form">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="color: var(--primary-color); margin-bottom: 5px;">Alta de Personal</h2>
            <p style="color: var(--text-muted); margin-top: 0;">Registra un nuevo usuario en el sistema</p>
        </div>

        <form action="registro.php" method="POST">
            <label>Nombre Completo</label>
            <input type="text" name="nombre" required placeholder="Ej. Carlos Pérez">

            <label>Correo Electrónico</label>
            <input type="email" name="correo" required placeholder="correo@ejemplo.com">

            <label>Rol en el Sistema</label>
            <select name="rol" id="rol" required>
                <option value="Trabajador">Trabajador (Solo Movimientos)</option>
                <option value="Jefe">Jefe de Sucursal</option>
                <option value="Administrador">Administrador del Sistema</option>
            </select>

            <div id="div_codigo">
                <label style="color: #f59e0b;">Código de Vinculación (Obligatorio)</label>
                <input type="text" id="codigo_negocio" name="codigo_negocio" placeholder="Ej. NS-A1B2C3" style="border-color: #f59e0b;">
            </div>

            <label>Contraseña Segura</label>
            <input type="password" name="password" required placeholder="Crea una contraseña">

            <button type="submit" class="btn-submit">Registrar Usuario</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="login.php" class="text-muted">¿Ya perteneces al equipo? Inicia sesión</a>
        </div>
    </div>

    <script>
        const selectRol = document.getElementById('rol');
        const divCodigo = document.getElementById('div_codigo');
        const inputCodigo = document.getElementById('codigo_negocio');

        function toggleCodigo() {
            if(selectRol.value === 'Trabajador') {
                divCodigo.style.display = 'block';
                inputCodigo.required = true;
            } else {
                divCodigo.style.display = 'none';
                inputCodigo.required = false;
                inputCodigo.value = '';
            }
        }
        selectRol.addEventListener('change', toggleCodigo);
        toggleCodigo(); // Ejecutar al cargar
    </script>
</body>
</html>