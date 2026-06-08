<?php
session_start();
// Ruta para conectar a la BD desde la carpeta /movimientos/
require_once '../general/conexion.php';

// BLOQUEO DE SEGURIDAD RBAC (Trabajadores no entran)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Nivel de permisos insuficiente.'); window.location.href='../general/panel.php';</script>";
    exit();
}

// JOIN cruzando las 3 tablas basándonos en tu diagrama relacional
$sql = "SELECT m.id_movimiento, p.nombre_articulo AS producto, u.nombre AS usuario, 
               m.tipo_accion, m.cantidad_afectada, m.fecha_hora 
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
    <title>Historial de Movimientos | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css">
</head>
<body>
    <div class="main-content" style="margin-left: 260px; padding: 20px;">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px;">
            <h2 style="margin: 0;">🔍 Portal de Auditoría (Historial)</h2>
            <a href="../general/panel.php" class="btn-submit" style="background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; width: auto;">← Volver al Panel</a>
        </div>
        
        <p style="color: var(--text-muted); margin-top: 0; margin-bottom: 20px;">Registro inmutable de entradas y salidas del sistema.</p>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left; color: white;">
            <thead style="background-color: var(--surface-color);">
                <tr>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">ID Mov.</th>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">Fecha y Hora</th>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">Artículo Afectado</th>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">Acción</th>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">Cantidad</th>
                    <th style="padding: 10px; border-bottom: 2px solid #555;">Usuario Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #444;">
                        <td style="padding: 10px;"><?php echo $fila['id_movimiento']; ?></td>
                        <td style="padding: 10px; color: #aaa;"><?php echo date("d/m/Y H:i A", strtotime($fila['fecha_hora'])); ?></td>
                        <td style="padding: 10px; font-weight: bold;"><?php echo $fila['producto']; ?></td>
                        
                        <td style="padding: 10px;">
                            <?php if ($fila['tipo_accion'] == 'Entrada'): ?>
                                <span style="background-color: rgba(40, 167, 69, 0.2); color: #28a745; padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                                    + Entrada
                                </span>
                            <?php else: ?>
                                <span style="background-color: rgba(220, 53, 69, 0.2); color: #dc3545; padding: 4px 8px; border-radius: 4px; font-weight: bold;">
                                    - Salida
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td style="padding: 10px; font-size: 1.1rem; font-weight: bold;"><?php echo $fila['cantidad_afectada']; ?></td>
                        <td style="padding: 10px; color: #ffc107;"><?php echo $fila['usuario']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="padding: 20px; text-align: center; color: #aaa;">No hay movimientos registrados en el sistema aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>