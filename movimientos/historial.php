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
        <h2>🔍 Historial de Trazabilidad</h2>
        <p style="color: var(--text-muted);">Registro inmutable de entradas y salidas del sistema.</p>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: var(--surface-color);">
                <tr>
                    <th>ID Mov.</th>
                    <th>Fecha y Hora</th>
                    <th>Artículo Afectado</th>
                    <th>Acción</th>
                    <th>Cantidad</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_movimiento']; ?></td>
                    <td><?php echo $fila['fecha_hora']; ?></td>
                    <td><?php echo $fila['producto']; ?></td>
                    <td>
                        <span style="color: <?php echo ($fila['tipo_accion'] == 'Entrada') ? '#28a745' : '#dc3545'; ?>; font-weight: bold;">
                            <?php echo $fila['tipo_accion']; ?>
                        </span>
                    </td>
                    <td><?php echo $fila['cantidad_afectada']; ?></td>
                    <td><?php echo $fila['usuario']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <br>
        <a href="../general/panel.php" class="btn-submit" style="text-decoration: none; display: inline-block;">Volver al Panel</a>
    </div>
</body>
</html>