<?php
session_start();
// Si ya tiene una sesión activa, lo mandamos directo al panel
if (isset($_SESSION['rol'])) {
    header("Location: panel.php");
    exit();
}

// PROCESAR EN LA MISMA PÁGINA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sincronizado con seguridad.php: usamos 'correo'
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    if (!empty($correo) && !empty($password)) {
        // [Aquí incluirías tu conexión si quieres validar contra la BD real]
        // Por ahora, iniciamos sesión directo para que pases al panel en la prueba:
        $_SESSION['usuario_id'] = 1;
        $_SESSION['nombre'] = "Jefe de Prueba";
        $_SESSION['rol'] = 'Jefe'; 
        $_SESSION['codigo_negocio'] = "NS-REALE"; 

        header("Location: panel.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetStock | Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/acceso.css">
</head>
<body>

    <div class="auth-container single-panel">
        <div class="auth-card">
            <div class="auth-header">
                <a href="index.php" class="auth-logo">NetStock</a>
                <p class="auth-subtitle">Accede para administrar el inventario de tu negocio.</p>
            </div>

            <form action="" method="POST" class="auth-form">
                
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

                <button type="submit" class="btn-submit w-100">Iniciar sesión</button>

                <p class="auth-footer-text">
                    ¿No tienes una cuenta aún? <a href="registro.php">Crear cuenta</a>
                </p>
            </form>
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
    </script>
</body>
</html>