<?php
session_start();
require_once '../general/conexion.php';

// Validar que el usuario esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];

// Verificar que se haya enviado un ID válido por la URL
if (!isset($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$id_producto = $_GET['id'];

// Obtener los datos actuales del producto
$sql = "SELECT p.*, c.nombre_categoria 
        FROM producto p
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.id_producto = $id_producto";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $producto = $resultado->fetch_assoc();
} else {
    echo "<script>alert('Error: Producto no encontrado.'); window.location.href='listar.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
    <style>
        .btn-action-group { display: flex; gap: 10px; margin-top: 20px; }
        .btn-add { background-color: #10b981; color: white; flex: 1; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-sub { background-color: #ef4444; color: white; flex: 1; border: none; padding: 12px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-add:hover { background-color: #059669; }
        .btn-sub:hover { background-color: #dc2626; }
        .modal-footer-actions { margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; }
        .modal-header-badge { display: inline-block; background-color: var(--primary-color); color: white; width: 50px; height: 50px; border-radius: 50%; text-align: center; line-height: 50px; font-weight: bold; margin-right: 15px; font-size: 1.5rem; }
    </style>
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="card-form" style="max-width: 500px; width: 100%;">
        
        <div style="display: flex; align-items: center; margin-bottom: 25px;">
            <div class="modal-header-badge">
                <?php echo strtoupper(substr($producto['nombre_articulo'], 0, 1)); ?>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.5rem; color: var(--text-main);"><?php echo $producto['nombre_articulo']; ?></h3>
                <p style="margin: 0; color: var(--text-muted); font-size: 0.95rem;">Categoría: <?php echo $producto['nombre_categoria']; ?></p>
            </div>
        </div>

        <div style="background-color: var(--bg-color); padding: 15px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border-color); text-align: center;">
            <span style="color: var(--text-muted); font-size: 0.9rem; display: block;">Stock Actual</span>
            <span style="font-size: 2.5rem; font-weight: bold; color: var(--primary-color);"><?php echo $producto['cantidad_stock']; ?></span>
        </div>

        <form action="modificar_stock.php" method="POST">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
            
            <label style="display: block; margin-bottom: 8px; font-size: 0.95rem; color: var(--text-muted);">Cantidad a mover:</label>
            <input type="number" name="cantidad_afectada" min="1" required style="width: 100%; padding: 12px; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 1rem; margin-bottom: 5px;" placeholder="Ej. 5">
            
            <div class="btn-action-group">
                <button type="submit" name="accion" value="Entrada" class="btn-add"><i class="fa-solid fa-arrow-turn-down fa-rotate-90"></i> Agregar</button>
                <button type="submit" name="accion" value="Salida" class="btn-sub"><i class="fa-solid fa-arrow-turn-up fa-rotate-90"></i> Disminuir</button>
            </div>
        </form>

        <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
        <div class="modal-footer-actions">
            <a href="editar.php?id=<?php echo $producto['id_producto']; ?>" style="color: var(--primary-color); font-weight: 500; text-decoration: none;"><i class="fa-solid fa-pen-to-square"></i> Editar Producto</a>
            <a href="eliminar.php?id=<?php echo $producto['id_producto']; ?>" style="color: #ef4444; font-weight: 500; text-decoration: none;" onclick="return confirm('¿Seguro que deseas eliminar este artículo?');"><i class="fa-solid fa-trash-can"></i> Eliminar</a>
        </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px;">
            <a href="listar.php" style="color: var(--text-muted); font-size: 0.95rem; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i> Volver al Inventario</a>
        </div>

    </div>

    <script>
        if(localStorage.getItem('tema') === 'claro') { document.documentElement.setAttribute('data-theme', 'light'); }
    </script>
</body>
</html>