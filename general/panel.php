<?php
session_start();

// Verificamos si el usuario tiene una sesión activa válida
if (!isset($_SESSION['rol'])) {
    // Si no tiene rol, significa que no ha iniciado sesión. Lo mandamos al login.
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css"> </head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            NetStock 
            <span class="badge"><?php echo $_SESSION['rol']; ?></span>
        </div>
        
        <div class="sidebar-user">
            <span>Hola, <?php echo $_SESSION['nombre']; ?></span>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../productos/listar.php">📦 Inventario</a></li>
            
            <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
                <li><a href="auditoria.php">🔍 Portal de Auditoría</a></li>
            <?php endif; ?>

            <?php if($_SESSION['rol'] == 'Administrador'): ?>
                <li><a href="../productos/crear.php">⚙️ Configuración</a></li>
            <?php endif; ?>
        </ul>

        <div class="sidebar-footer">
            <a href="../login.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="main-content">
        <h2>Bienvenido al Centro de Operaciones</h2>
        <p style="color: #aaa; max-width: 600px;">
            Has iniciado sesión correctamente. Selecciona cualquiera de los módulos habilitados para tu nivel de acceso en el menú lateral izquierdo para comenzar a trabajar.
        </p>
    </div>

</body>
</html>