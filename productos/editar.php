<?php
session_start();
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes pueden editar.'); window.location.href='listar.php';</script>";
    exit();
}

$id_producto = $_GET['id'];
$error_msg = "";

// 2. LÓGICA PARA ACTUALIZAR EL PRODUCTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_articulo = $_POST['nombre_articulo'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad_stock = $_POST['cantidad_stock'];
    $stock_alerta = $_POST['stock_alerta'];

    $sql_update = "UPDATE producto SET 
                   nombre_articulo = '$nombre_articulo', 
                   id_categoria = $id_categoria, 
                   cantidad_stock = $cantidad_stock, 
                   stock_alerta = $stock_alerta 
                   WHERE id_producto = $id_producto";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Producto actualizado correctamente'); window.location.href='listar.php';</script>";
        exit();
    } else {
        $error_msg = "Error al actualizar: " . $conn->error;
    }
}

// 3. OBTENER DATOS ACTUALES
$sql_prod = "SELECT * FROM producto WHERE id_producto = $id_producto";
$producto_actual = $conn->query($sql_prod)->fetch_assoc();

$sql_categorias = "SELECT * FROM categoria WHERE codigo_negocio = '{$_SESSION['codigo_negocio']}'";
$resultado_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
</head>
<body style="background-color: var(--bg-color); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">

    <div class="auth-container single-panel">
        <div class="auth-card" style="max-width: 450px;">
            <div class="auth-header">
                <h2 style="color: var(--primary-color); margin-bottom: 5px; font-size: 1.8rem;">Editar Producto</h2>
                <p class="auth-subtitle">Modifica la información del artículo seleccionado.</p>
            </div>

            <?php if(!empty($error_msg)): ?>
                <div style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center;">
                    <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <form action="editar.php?id=<?php echo $id_producto; ?>" method="POST" class="auth-form">
                <input type="hidden" name="id_producto" value="<?php echo $producto_actual['id_producto']; ?>">

                <div class="input-group">
                    <label>Nombre del Artículo</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-tag input-icon"></i>
                        <input type="text" name="nombre_articulo" value="<?php echo $producto_actual['nombre_articulo']; ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Categoría</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-folder input-icon"></i>
                        <select name="id_categoria" required>
                            <?php while ($cat = $resultado_categorias->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id_categoria']; ?>" <?php echo ($cat['id_categoria'] == $producto_actual['id_categoria']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['nombre_categoria']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Stock Actual</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-boxes-stacked input-icon"></i>
                        <input type="number" name="cantidad_stock" value="<?php echo $producto_actual['cantidad_stock']; ?>" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Stock de Alerta <i class="fa-solid fa-bell" style="color: #eab308;"></i></label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-bell input-icon" style="color: #eab308;"></i>
                        <input type="number" name="stock_alerta" value="<?php echo $producto_actual['stock_alerta']; ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit w-100">Guardar Cambios</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="listar.php" style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Cancelar</a>
            </div>
        </div>
    </div>
</body>
</html>