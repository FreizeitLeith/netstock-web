<?php
session_start();
require_once '../general/conexion.php';

// Bloqueo de seguridad: Solo Jefe y Administrador pueden ver esto
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo personal autorizado puede ver el historial.'); window.location.href='../productos/listar.php';</script>";
    exit();
}

$rol = $_SESSION['rol'];

// Consulta que une los movimientos con el producto afectado y quién lo hizo
$sql = "SELECT m.id_movimiento, m.tipo_accion, m.cantidad_afectada, m.fecha_hora, 
               p.nombre_articulo, u.nombre as nombre_usuario
        FROM movimiento m
        INNER JOIN producto p ON m.id_producto = p.id_producto
        INNER JOIN usuario u ON m.id_usuario = u.id_usuario
        ORDER BY m.fecha_hora DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css">
    <style>
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .badge-entrada { background-color: rgba(16, 185, 129, 0.15); color: #10b981; padding: 5px 12px; border-radius: 6px; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: bold; font-size: 0.85rem; }
        .badge-salida { background-color: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 5px 12px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.3); font-weight: bold; font-size: 0.85rem; }
        
        .mobile-header { display: none; background-color: var(--sidebar-bg); padding: 15px 20px; border-bottom: 1px solid var(--border-color); align-items: center; justify-content: space-between; }
        .menu-toggle-btn { background: none; border: none; color: var(--text-main); font-size: 1.5rem; cursor: pointer; }
        @media (max-width: 768px) { .mobile-header { display: flex; } .sidebar { box-shadow: 5px 0 15px rgba(0,0,0,0.5); } }
    </style>
</head>
<body>

    <div class="mobile-header">
        <span style="font-size: 1.25rem; font-weight: bold; color: var(--primary-color);">NetStock</span>
        <button class="menu-toggle-btn" id="open-menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="dashboard-container">
        
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <span><i class="fa-solid fa-layer-group" style="margin-right: 8px;"></i> NetStock</span>
                <button class="menu-toggle-btn" id="close-menu" style="display: none;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div class="sidebar-user">
                <i class="fa-solid fa-circle-user" style="font-size: 2rem; color: var(--primary-color);"></i>
                <div>
                    <div style="font-weight: bold; color: var(--text-main);"><?php echo $_SESSION['nombre']; ?></div>
                    <div class="badge"><?php echo $_SESSION['rol']; ?></div>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li><a href="../productos/listar.php"><i class="fa-solid fa-box"></i> Productos</a></li>
                <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
                   <li><a href="historial.php" style="background-color: var(--sidebar-active); color: var(--primary-color);"><i class="fa-solid fa-clock-rotate-left"></i> Historial</a></li>
                   <li><a href="../general/configuracion.php"><i class="fa-solid fa-gear"></i> Configuración</a></li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">
                <a href="../login.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
            </div>
        </div>

        <div class="main-content">
            
            <div class="page-header">
                <div>
                    <h2 style="margin: 0; font-size: 1.8rem;"><i class="fa-solid fa-magnifying-glass" style="color: var(--primary-color);"></i> Movimientos del Inventario</h2>
                    <p style="color: var(--text-muted); margin-top: 5px; font-size: 0.95rem;">Registro inmutable de entradas y salidas del sistema.</p>
                </div>
                </div>

            <table style="width: 100%; text-align: left; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID MOV.</th>
                        <th>FECHA Y HORA</th>
                        <th>ARTÍCULO AFECTADO</th>
                        <th>ACCIÓN</th>
                        <th style="text-align: center;">CANTIDAD</th>
                        <th>USUARIO RESPONSABLE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='var(--sidebar-active)'" onmouseout="this.style.backgroundColor='transparent'">
                            
                            <td data-label="ID Mov." style="color: var(--text-muted); font-weight: bold;">
                                <?php echo $fila['id_movimiento']; ?>
                            </td>
                            
                            <td data-label="Fecha">
                                <?php echo date("d/m/Y H:i A", strtotime($fila['fecha_hora'])); ?>
                            </td>
                            
                            <td data-label="Artículo" style="font-weight: 600; color: var(--text-main);">
                                <?php echo $fila['nombre_articulo']; ?>
                            </td>
                            
                            <td data-label="Acción">
                                <?php if($fila['tipo_accion'] == 'Entrada'): ?>
                                    <span class="badge-entrada">+ Entrada</span>
                                <?php else: ?>
                                    <span class="badge-salida">- Salida</span>
                                <?php endif; ?>
                            </td>
                            
                            <td data-label="Cantidad" style="text-align: center; font-weight: bold; font-size: 1.1rem; color: var(--text-main);">
                                <?php echo $fila['cantidad_afectada']; ?>
                            </td>
                            
                            <td data-label="Responsable" style="color: #ffc107; font-weight: 500;">
                                <?php echo $fila['nombre_usuario']; ?>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Aún no hay movimientos registrados en el historial.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        if(localStorage.getItem('tema') === 'claro') { document.documentElement.setAttribute('data-theme', 'light'); }
        const sidebar = document.getElementById('sidebar');
        document.getElementById('open-menu').addEventListener('click', () => sidebar.classList.add('active'));
        document.getElementById('close-menu').addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>