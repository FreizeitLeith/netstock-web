<?php
session_start();
// Conectamos a la base de datos (subiendo un nivel a /general)
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden editar productos.'); window.location.href='listar.php';</script>";
    exit();
}

$error_msg = "";

// 2. LÓGICA PARA ACTUALIZAR EL PRODUCTO (Cuando se le da a Guardar)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $nombre_articulo = $_POST['nombre_articulo'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad_stock = $_POST['cantidad_stock'];

    // Validar que no exista OTRO producto distinto con el mismo nombre
    $sql_verificar = "SELECT id_producto FROM producto WHERE nombre_articulo = '$nombre_articulo' AND id_producto != $id_producto";
    $resultado_verificacion = $conn->query($sql_verificar);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        $error_msg = "Error: Ya existe otro artículo registrado con el nombre '" . $nombre_articulo . "'.";
        
        // Mantenemos los datos temporales para que no se borre lo que el usuario escribió si hay error
        $producto_actual = [
            'id_producto' => $id_producto,
            'nombre_articulo' => $nombre_articulo,
            'id_categoria' => $id_categoria,
            'cantidad_stock' => $cantidad_stock
        ];
    } else {
        // Actualizamos los datos en la tabla
        $sql_update = "UPDATE producto SET 
                        nombre_articulo = '$nombre_articulo', 
                        id_categoria = $id_categoria, 
                        cantidad_stock = $cantidad_stock 
                       WHERE id_producto = $id_producto";

        if ($conn->query($sql_update) === TRUE) {
            echo "<script>
                    alert('¡Artículo actualizado con éxito!');
                    window.location.href = 'listar.php';
                  </script>";
            exit();
        } else {
            $error_msg = "Error al actualizar el producto: " . $conn->error;
        }
    }
} 
// 3. LÓGICA PARA CARGAR LOS DATOS (Cuando el usuario entra por primera vez)
else {
    if (isset($_GET['id'])) {
        $id_producto = $_GET['id'];
        
        // Buscamos los datos actuales del producto
        $sql_producto = "SELECT * FROM producto WHERE id_producto = $id_producto";
        $resultado_producto = $conn->query($sql_producto);

        if ($resultado_producto && $resultado_producto->num_rows > 0) {
            $producto_actual = $resultado_producto->fetch_assoc();
        } else {
            echo "<script>alert('Error: Producto no encontrado.'); window.location.href='listar.php';</script>";
            exit();
        }
    } else {
        header("Location: listar.php");
        exit();
    }
}

// 4. OBTENER CATEGORÍAS DINÁMICAS PARA EL SELECT
$sql_categorias = "SELECT * FROM categoria";
$resultado_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="card" style="max-width: 450px; width: 100%; text-align: left;">
        <h2 style="color: #ffc107; margin-bottom: 5px; text-align: center;">Editar Artículo</h2>
        <p style="color: var(--text-muted); margin-top: 0; text-align: center;">Modifica la información del catálogo</p>

        <?php if(!empty($error_msg)): ?>
            <div style="background-color: #dc3545; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form action="editar.php" method="POST">
            
            <input type="hidden" name="id_producto" value="<?php echo $producto_actual['id_producto']; ?>">

            <label for="nombre_articulo">Nombre del Artículo</label>
            <input type="text" id="nombre_articulo" name="nombre_articulo" required 
                   value="<?php echo $producto_actual['nombre_articulo']; ?>">

            <label for="id_categoria">Categoría</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">-- Selecciona una categoría --</option>
                <?php 
                if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
                    while ($cat = $resultado_categorias->fetch_assoc()) {
                        // Verificamos si esta es la categoría que ya tiene el producto para marcarla
                        $selected = ($cat['id_categoria'] == $producto_actual['id_categoria']) ? 'selected' : '';
                        
                        echo '<option value="' . $cat['id_categoria'] . '" ' . $selected . '>' . $cat['nombre_categoria'] . '</option>';
                    }
                }
                ?>
            </select>

            <label for="cantidad_stock">Stock Actual (Ajuste Manual)</label>
            <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" required 
                   value="<?php echo $producto_actual['cantidad_stock']; ?>">

            <button type="submit" class="btn-submit" style="margin-top: 15px; background-color: #ffc107; color: #000;">Guardar Cambios</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="listar.php" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none;">← Cancelar y volver al Inventario</a>
        </div>
    </div>

</body>
</html>