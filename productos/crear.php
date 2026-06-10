<?php
session_start();
// Conectamos a la base de datos (subiendo un nivel a /general)
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden agregar productos.'); window.location.href='listar.php';</script>";
    exit();
}

// 2. LÓGICA PARA GUARDAR EL PRODUCTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$nombre_articulo = $_POST['nombre_articulo'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad_stock = $_POST['cantidad_stock'];
    $stock_alerta = $_POST['stock_alerta']; // NUEVO CAMPO

    // --- VALIDACIÓN: Verificar si el producto ya existe ---
    $sql_verificar = "SELECT id_producto FROM producto WHERE nombre_articulo = '$nombre_articulo'";
    $resultado_verificacion = $conn->query($sql_verificar);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        $error_msg = "Error: Ya existe un artículo registrado con el nombre '" . $nombre_articulo . "'.";
    } else {
        // Insertamos en tu tabla "producto" (incluyendo el stock_alerta)
        $sql_insert = "INSERT INTO producto (nombre_articulo, cantidad_stock, id_categoria, stock_alerta) 
                       VALUES ('$nombre_articulo', $cantidad_stock, $id_categoria, $stock_alerta)";

        if ($conn->query($sql_insert) === TRUE) {
            echo "<script>
                    alert('¡Artículo registrado con éxito en el inventario!');
                    window.location.href = 'listar.php';
                  </script>";
            exit();
        } else {
            $error_msg = "Error al guardar el producto: " . $conn->error;
        }
    }
}

// 3. OBTENER CATEGORÍAS DINÁMICAS PARA EL FORMULARIO
// Buscamos las categorías reales en la BD para que el Jefe elija de una lista
$sql_categorias = "SELECT * FROM categoria";
$resultado_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="card" style="max-width: 450px; width: 100%; text-align: left;">
        <h2 style="color: var(--primary-color); margin-bottom: 5px; text-align: center;">Alta de Artículo</h2>
        <p style="color: var(--text-muted); margin-top: 0; text-align: center;">Registra un nuevo producto en el catálogo</p>

        <?php if(isset($error_msg)): ?>
            <div style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="crear.php" method="POST">
            
            <label for="nombre_articulo">Nombre del Artículo</label>
            <input type="text" id="nombre_articulo" name="nombre_articulo" required placeholder="Ej. Resma de Papel Carta">

            <label for="id_categoria">Categoría</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php 
                // Imprimimos dinámicamente las categorías que existen en la BD
                if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
                    while ($cat = $resultado_categorias->fetch_assoc()) {
                        echo '<option value="' . $cat['id_categoria'] . '">' . $cat['nombre_categoria'] . '</option>';
                    }
                } else {
                    echo '<option value="">Error: No hay categorías creadas en la BD</option>';
                }
                ?>
            </select>

            <label for="cantidad_stock">Stock Inicial (Cantidad)</label>
            <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" required placeholder="Ej. 10" value="0">

            <div class="form-group">
                <label for="cantidad_stock">Stock Inicial (Cantidad)</label>
                <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" required placeholder="Ej. 10" value="0">
            </div>

            <div class="form-group">
                <label for="stock_alerta">Stock de Alerta (Punto de Reorden) <i class="fa-solid fa-bell" style="color: #f59e0b;"></i></label>
                <p style="margin-top: -5px; font-size: 0.8rem; color: var(--text-muted);">Te avisaremos cuando el stock llegue a esta cantidad.</p>
                <input type="number" id="stock_alerta" name="stock_alerta" min="0" required placeholder="Ej. 5" value="5">
            </div>
            
            <button type="submit" class="btn-submit" style="margin-top: 15px;">Guardar Producto</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="listar.php" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">← Cancelar y volver al Inventario</a>
        </div>
    </div>

</body>
</html>