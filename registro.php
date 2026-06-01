<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Personal | NetStock</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="card" style="max-width: 400px; width: 100%; text-align: center;">
        <h2 style="color: var(--primary-color); margin-bottom: 5px;">Alta de Personal</h2>
        <p style="color: var(--text-muted); margin-top: 0;">Registra un nuevo usuario en el sistema</p>

        <form action="registro.php" method="POST">
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Ej. Carlos Pérez">

            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required placeholder="correo@ejemplo.com">

            <label for="rol">Rol en el Sistema (Nivel de Acceso)</label>
            <select id="rol" name="rol" required>
                <option value="Trabajador">Trabajador (Solo Movimientos)</option>
                <option value="Jefe">Jefe de Turno (Supervisión)</option>
            </select>

            <label for="password">Contraseña Segura</label>
            <input type="password" id="password" name="password" required placeholder="Crea una contraseña">

            <button type="submit" class="btn-submit">Registrar Usuario</button>
        </form>

        <p style="margin-top: 20px; font-size: 0.9rem;">
            ¿Ya perteneces al equipo? <a href="login.php" style="color: var(--primary-color);">Inicia sesión</a>
        </p>
    </div>

</body>
</html>