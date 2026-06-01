<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | NetStock</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh;">

    <div class="card" style="max-width: 400px; width: 100%; text-align: center;">
        <h2 style="color: var(--primary-color); margin-bottom: 5px;">Bienvenido a NetStock</h2>
        <p style="color: var(--text-muted); margin-top: 0;">Ingresa tus credenciales</p>

        <form action="general/seguridad.php" method="POST">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required placeholder="usuario@ciber.com">

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">

            <button type="submit" class="btn-submit">Ingresar al Sistema</button>
        </form>

        <p style="margin-top: 20px; font-size: 0.9rem;">
            ¿No tienes cuenta? <a href="registro.php" style="color: var(--primary-color);">Regístrate aquí</a>
        </p>
        <a href="index.php" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none;">← Volver al inicio</a>
    </div>

</body>
</html>