<?php
// --- SIMULADOR DE SESIÓN (Solo para pruebas de diseño) ---
session_start();
// CAMBIA la siguiente palabra entre 'Administrador', 'Jefe' o 'Trabajador' para probar las vistas:
$_SESSION['rol'] = 'Administrador'; 
$_SESSION['nombre'] = 'FreizeitLeith'; // Tu nombre aquí
// ---------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .badge {
            background-color: var(--primary-color);
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">NetStock <span class="badge"><?php echo $_SESSION['rol']; ?></span></div>
        <div class="nav-links">
            <span style="color: var(--text-muted); margin-right: 20px;">Hola, <?php echo $_SESSION['nombre']; ?></span>
            <a href="../login.php" style="color: #ff4c4c; border: 1px solid #ff4c4c; padding: 5px 10px; border-radius: 5px;">Cerrar Sesión</a>
        </div>
    </header>

    <section>
        <h2>Seleccione una operación</h2>
        
        <div class="grid-3">
            
            <div class="card">
                <h3>📦 Inventario</h3>
                <p>Consulta las existencias actuales de productos.</p>
                <a href="../productos/listar.php" class="btn-login">Ver Productos</a>
            </div>

            <div class="card">
                <h3>🔄 Movimientos</h3>
                <p>Registra entrada o salida de mercancía.</p>
                <a href="../movimientos/entrada.php" class="btn-login" style="margin-bottom: 10px; display: block;">+ Registrar Entrada</a>
                <a href="../movimientos/salida.php" class="btn-registro" style="margin-left: 0; display: block;">- Registrar Salida</a>
            </div>

            <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
            
                <div class="card" style="border-color: var(--primary-color);">
                    <h3>🔍 Portal de Auditoría</h3>
                    <p>Supervisa los movimientos realizados por el personal.</p>
                    <a href="auditoria.php" class="btn-login">Revisar Registros</a>
                </div>

            <?php endif; ?>

            <?php if($_SESSION['rol'] == 'Administrador'): ?>
            
                <div class="card" style="border-color: #ff4c4c;">
                    <h3 style="color: #ff4c4c;">⚙️ Configuración</h3>
                    <p>Gestión del sistema, creación de productos y control de usuarios.</p>
                    <a href="../productos/crear.php" class="btn-registro" style="border-color: #ff4c4c; color: #ff4c4c !important;">Administrar</a>
                </div>

            <?php endif; ?>

        </div>
    </section>

</body>
</html>