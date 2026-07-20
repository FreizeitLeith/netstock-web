<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. CONEXIÓN A LA BASE DE DATOS (Recuperado de tu primer código)
require_once 'general/conexion.php';

// PROCESAR EN LA MISMA PÁGINA PARA EVITAR ERROR 404
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $password = $_POST['password'];
    $codigo_ingresado = isset($_POST['codigo_negocio']) ? trim($_POST['codigo_negocio']) : '';

    $codigo_final = "";

    // 2. LÓGICA DE VINCULACIÓN SAAS (Recuperado de tu primer código)
    if ($rol == 'Jefe' || $rol == 'Administrador') {
        // Genera un código aleatorio único de 6 caracteres
        $codigo_final = 'NS-' . strtoupper(substr(md5(uniqid()), 0, 6));
    } else {
        // Es Trabajador, verificamos que el código de su jefe exista en la BD
        $sql_verificar = "SELECT id_usuario FROM usuario WHERE codigo_negocio = '$codigo_ingresado' AND (rol = 'Jefe' OR rol = 'Administrador')";
        $resultado = $conn->query($sql_verificar);
        
        if ($resultado && $resultado->num_rows > 0) {
            $codigo_final = $codigo_ingresado; // Vinculación exitosa
        } else {
            echo "<script>alert('Error: El código de vinculación no es válido o no pertenece a ningún negocio activo.'); window.history.back();</script>";
            exit();
        }
    }

    // 3. INSERTAR EN LA BASE DE DATOS REAL
    $sql = "INSERT INTO usuario (nombre, correo, rol, contrasena, codigo_negocio) VALUES ('$nombre', '$correo', '$rol', '$password', '$codigo_final')";

   // 3. INSERTAR EN LA BASE DE DATOS REAL
    $sql = "INSERT INTO usuario (nombre, correo, rol, contrasena, codigo_negocio) VALUES ('$nombre', '$correo', '$rol', '$password', '$codigo_final')";

    if ($conn->query($sql) === TRUE) {
        // Redirigir al LOGIN para que inicie sesión manualmente
        if($rol == 'Jefe' || $rol == 'Administrador'){
            echo "<script>alert('¡Registrado! Tu Código de Vinculación para tus trabajadores es: $codigo_final'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('¡Cuenta de Trabajador enlazada con éxito!'); window.location.href = 'login.php';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Hubo un error al registrar: " . $conn->error . "'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetStock | Crear Cuenta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/acceso.css">
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" class="auth-logo">NetStock</a>
                <p class="auth-subtitle">Crea tu cuenta para administrar el inventario de tu negocio de forma simple.</p>
            </div>

            <form action="" method="POST" class="auth-form">
                
                <div class="input-group">
                    <label for="nombre">Nombre Completo</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="correo">Correo electrónico</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope input-icon"></i>
                        <input type="email" id="correo" name="correo" placeholder="correo@ejemplo.com" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" id="password" name="password" placeholder="********" required>
                        <i class="fa-solid fa-eye toggle-password" id="btnTogglePassword"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="rol">¿Cuál es tu rol en el negocio?</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user-gear input-icon"></i>
                        <select id="rol" name="rol" required>
                            <option value="Jefe" selected>👔 Jefe / Dueño de negocio</option>
                            <option value="Trabajador">👷 Trabajador / Empleado</option>
                        </select>
                    </div>
                </div>

                <div class="input-group" id="grupo-codigo-vinculacion" style="display: none;">
                    <label for="codigo_negocio">Código de Vinculación (Obligatorio)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-key input-icon"></i>
                        <input type="text" id="codigo_negocio" name="codigo_negocio" placeholder="Ej. NS-A1B2C3">
                    </div>
                    <span class="code-help">Este código debe proporcionártelo el jefe de tu negocio para vincularte a su inventario.</span>
                    <span class="code-warning">
                        <i class="fa-solid fa-triangle-exclamation"></i> Este código lo genera automáticamente el jefe.
                    </span>
                </div>

                <button type="submit" class="btn-submit w-100">Comenzar ahora</button>

                <p class="auth-footer-text">
                    ¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a>
                </p>
            </form>
        </div>

        <div class="auth-features-panel" id="features-box">
            </div>
    </div>

    <script>
        const btnTogglePassword = document.getElementById('btnTogglePassword');
        const inputPassword = document.getElementById('password');

        btnTogglePassword.addEventListener('click', function () {
            const type = inputPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            inputPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        const selectorRol = document.getElementById('rol');
        const grupoCodigo = document.getElementById('grupo-codigo-vinculacion');
        const inputCodigo = document.getElementById('codigo_negocio');
        const cajaVentajas = document.getElementById('features-box');

        const ventajasJefe = `
            <h3>Como jefe podrás:</h3>
            <ul class="features-list-box">
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Crear productos:</strong> Configura tu catálogo inicial.</li>
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Eliminar productos:</strong> Mantén limpio tu espacio de trabajo.</li>
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Administrar trabajadores:</strong> Controla quién entra a tu sistema.</li>
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Consultar historial:</strong> Auditoría completa de movimientos.</li>
            </ul>`;

        const ventajasTrabajador = `
            <h3>Como trabajador podrás:</h3>
            <ul class="features-list-box">
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Consultar inventario:</strong> Revisa el stock disponible en tiempo real.</li>
                <li><i class="fa-solid fa-circle-check text-green"></i> <strong>Actualizar existencias:</strong> Suma entradas y restas salidas.</li>
                <li class="forbidden"><i class="fa-solid fa-circle-xmark text-red"></i> <strong>No podrás</strong> eliminar productos del catálogo.</li>
            </ul>`;

        function actualizarFormulario() {
            if (selectorRol.value === 'Trabajador') { 
                grupoCodigo.style.display = 'block';
                inputCodigo.required = true;
                cajaVentajas.innerHTML = ventajasTrabajador;
            } else {
                grupoCodigo.style.display = 'none';
                inputCodigo.required = false;
                inputCodigo.value = '';
                cajaVentajas.innerHTML = ventajasJefe;
            }
        }

        selectorRol.addEventListener('change', actualizarFormulario);
        actualizarFormulario();
    </script>
</body>
</html>