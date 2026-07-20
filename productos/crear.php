<?php
session_start();
// Conectamos a la base de datos (subiendo un nivel a /general)
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden agregar productos.'); window.location.href='listar.php';</script>";
    exit();
}

$error_msg = "";
$success_msg = "";

// 2. LÓGICA PARA GUARDAR EL PRODUCTO
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_articulo = $_POST['nombre_articulo'];
    $id_categoria = $_POST['id_categoria'];
    $cantidad_stock = $_POST['cantidad_stock'];
    $stock_alerta = $_POST['stock_alerta'];
    $codigo_negocio = $_SESSION['codigo_negocio']; // Multi-Tenant: atado al negocio actual

    // --- VALIDACIÓN: Verificar si el producto ya existe en este negocio ---
    $sql_verificar = "SELECT id_producto FROM producto WHERE nombre_articulo = '$nombre_articulo' AND codigo_negocio = '$codigo_negocio'";
    $resultado_verificacion = $conn->query($sql_verificar);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        $error_msg = "Error: Ya existe un artículo registrado con el nombre '" . $nombre_articulo . "' en tu negocio.";
    } else {
        // Insertamos en tu tabla "producto" incluyendo el código de negocio
        $sql = "INSERT INTO producto (nombre_articulo, id_categoria, cantidad_stock, stock_alerta, codigo_negocio) 
                VALUES ('$nombre_articulo', '$id_categoria', $cantidad_stock, $stock_alerta, '$codigo_negocio')";

        if ($conn->query($sql) === TRUE) {
            $success_msg = "¡Producto '" . $nombre_articulo . "' guardado exitosamente en el catálogo!";
        } else {
            $error_msg = "Hubo un error al guardar en la base de datos: " . $conn->error;
        }
    }
}

// Obtener categorías únicamente de ESTE negocio
$sql_categorias = "SELECT * FROM categoria WHERE codigo_negocio = '{$_SESSION['codigo_negocio']}'";
$resultado_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css"> </head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; background-color: var(--bg-color); padding: 20px;">

    <div class="auth-container single-panel">
        <div class="auth-card" style="max-width: 450px;">
            <div class="auth-header">
                <h2 style="color: var(--primary-color); margin-bottom: 5px; font-size: 1.8rem; font-weight: 700;">Alta de Producto</h2>
                <p class="auth-subtitle">Registra un nuevo artículo en tu catálogo de inventario.</p>
            </div>

            <?php if(!empty($error_msg)): ?>
                <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($success_msg)): ?>
                <div style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    <i class="fa-solid fa-circle-check"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <form action="crear.php" method="POST" class="auth-form">
                
                <div class="input-group">
                    <label for="nombre_articulo">Nombre del Artículo</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-tag input-icon"></i>
                        <input type="text" id="nombre_articulo" name="nombre_articulo" placeholder="Ej. Cuaderno Universitario A4" required autocomplete="off">
                    </div>
                </div>

                <div class="input-group">
                    <label for="id_categoria">Categoría del Producto</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-folder input-icon"></i>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="" disabled selected style="color: var(--text-muted);">-- Selecciona una categoría --</option>
                            <?php 
                            if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
                                while ($cat = $resultado_categorias->fetch_assoc()) {
                                    echo '<option value="' . $cat['id_categoria'] . '">' . $cat['nombre_categoria'] . '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>Error: Primero crea categorías</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label for="cantidad_stock">Stock Inicial (Cantidad)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-boxes-stacked input-icon"></i>
                        <input type="number" id="cantidad_stock" name="cantidad_stock" min="0" required placeholder="Ej. 10" value="0">
                    </div>
                </div>

                <div class="input-group">
                    <label for="stock_alerta">Stock de Alerta (Punto de Reorden)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-bell input-icon" style="color: #eab308;"></i>
                        <input type="number" id="stock_alerta" name="stock_alerta" min="0" required placeholder="Ej. 5" value="5">
                    </div>
                    <span class="code-help" style="margin-top: 6px; font-size: 0.8rem; color: var(--text-muted);">Te avisaremos visualmente cuando las existencias bajen de esta cantidad.</span>
                </div>
                
                <button type="submit" class="btn-submit w-100" style="margin-top: 10px;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Producto
                </button>
            </form>

            <div style="text-align: center; margin-top: 25px;">
                <a href="listar.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='var(--text-muted)'">
                    <i class="fa-solid fa-arrow-left"></i> Volver al Inventario
                </a>
            </div>
        </div>
    </div>

    <script>
        // Sincronización del tema claro u oscuro guardado en el navegador
        if(localStorage.getItem('tema') === 'claro') { 
            document.documentElement.setAttribute('data-theme', 'light'); 
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>