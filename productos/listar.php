<?php
session_start();
// Ajustamos la ruta para salir de /productos/ y entrar a /general/
require_once '../general/conexion.php'; 

// Validar que el usuario esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];

// Lógica del buscador con los nombres reales de la BD
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    // Busca por nombre del artículo o cantidad exacta
    $sql = "SELECT p.id_producto, p.nombre_articulo, p.cantidad_stock, c.nombre_categoria 
            FROM producto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            WHERE p.nombre_articulo LIKE '%$busqueda%' OR p.cantidad_stock = '$busqueda'";
} else {
    $sql = "SELECT p.id_producto, p.nombre_articulo, p.cantidad_stock, c.nombre_categoria 
            FROM producto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria";
}
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css">
</head>
<body>
    <div class="main-content" style="margin-left: 260px; padding: 20px;">
        <h2>📦 Control de Inventario</h2>
        
        <form method="GET" action="listar.php" style="margin-bottom: 20px;">
            <input type="text" name="buscar" placeholder="Buscar por nombre o stock..." value="<?php echo $busqueda; ?>" style="padding: 8px; width: 300px;">
            <button type="submit" class="btn-submit" style="width: auto; padding: 8px 15px;">Buscar</button>
            <a href="listar.php" style="margin-left: 10px; color: var(--text-muted);">Limpiar</a>
        </form>

        <a href="../general/panel.php" class="btn-submit" style="background-color: #6c757d; color: white; text-decoration: none; display: inline-block; margin-bottom: 20px; margin-right: 10px; padding: 8px 15px;">← Volver al Panel</a>

        <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
           <a href="crear.php" class="btn-login" style="margin-bottom: 20px; display: inline-block; padding: 8px 15px;">+ Nuevo Producto</a>
        <?php endif; ?>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background-color: var(--surface-color);">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Artículo</th>
                    <th>Categoría</th>
                    <th>Stock Actual</th>
                    <th>Acciones (Movimientos)</th>
                    <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                        <th>Gestión</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_producto']; ?></td>
                    <td><?php echo $fila['nombre_articulo']; ?></td>
                    <td><?php echo $fila['nombre_categoria']; ?></td>
                    <td style="font-weight: bold; font-size: 1.1rem;"><?php echo $fila['cantidad_stock']; ?></td>
                    <td>
                        <form action="modificar_stock.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_producto" value="<?php echo $fila['id_producto']; ?>">
                            <input type="number" name="cantidad_afectada" min="1" required style="width: 60px;">
                            <button type="submit" name="accion" value="Entrada" style="background: #28a745; color: white; border: none; padding: 5px;">+ Entrar</button>
                            <button type="submit" name="accion" value="Salida" style="background: #dc3545; color: white; border: none; padding: 5px;">- Salir</button>
                        </form>
                    </td>
                    
                    <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                    <td>
                        <a href="editar.php?id=<?php echo $fila['id_producto']; ?>" style="color: #ffc107;">Editar</a> | 
                        <a href="eliminar.php?id=<?php echo $fila['id_producto']; ?>" style="color: #dc3545;" onclick="return confirm('¿Seguro que deseas eliminar este producto?');">Eliminar</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>