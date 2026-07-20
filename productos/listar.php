<?php
session_start();
require_once '../general/conexion.php'; 

if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];

$sql_categorias_filtro = "SELECT * FROM categoria";
$resultado_categorias_filtro = $conn->query($sql_categorias_filtro);

$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Modifica el select de las categorías del filtro:
// Modifica el select de las categorías del filtro:
$sql_categorias_filtro = "SELECT * FROM categoria WHERE codigo_negocio = '{$_SESSION['codigo_negocio']}'";

// Modifica la consulta base del inventario (Cambia el WHERE 1=1):
$sql = "SELECT p.id_producto, p.nombre_articulo, p.cantidad_stock, p.stock_alerta, c.nombre_categoria 
        FROM producto p
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE p.codigo_negocio = '{$_SESSION['codigo_negocio']}'";

if ($busqueda != '') {
    $sql .= " AND (p.nombre_articulo LIKE '%$busqueda%' OR p.cantidad_stock = '$busqueda')";
}

if ($filtro_categoria != '') {
    $sql .= " AND p.id_categoria = '$filtro_categoria'";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css"> 
    <link rel="stylesheet" href="../css/inventario.css"> 
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
                <li><a href="listar.php" style="background-color: var(--sidebar-active); color: var(--primary-color);"><i class="fa-solid fa-box"></i> Productos</a></li>
                <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
                   <li><a href="../movimientos/historial.php"><i class="fa-solid fa-clock-rotate-left"></i> Historial</a></li>
                   <li><a href="../general/configuracion.php"><i class="fa-solid fa-gear"></i> Configuración</a></li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">
                <a href="../login.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
            </div>
        </div>

        <div class="main-content">
            
            <div class="page-header">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <h2 style="margin: 0; font-size: 1.8rem;">Productos</h2>
<div class="legend-container" style="background-color: rgba(0, 0, 0, 0.15); padding: 20px; border-radius: 10px; border: 1px solid var(--border-color); margin-bottom: 25px;">
    <h4 style="margin-top: 0; color: var(--primary-color); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px;">
        <i class="fa-solid fa-circle-info"></i> Guía de Botones y Acciones
    </h4>
    <div style="display: flex; gap: 25px; flex-wrap: wrap; font-size: 0.9rem; color: var(--text-main);">
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="border: 1px solid var(--primary-color); color: var(--primary-color); padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                <i class="fa-solid fa-pen-to-square"></i> Gestionar
            </span>
            <span style="color: var(--text-muted);">Sumar o restar existencias (Entradas/Salidas).</span>
        </div>

        <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="background-color: var(--primary-color); color: white; padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                <i class="fa-solid fa-plus"></i> Nuevo
            </span>
            <span style="color: var(--text-muted);">Añadir un artículo al catálogo.</span>
        </div>

        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="background-color: transparent; border: 1px solid #eab308; color: #eab308; padding: 4px 10px; border-radius: 4px; font-weight: bold;">
                <i class="fa-solid fa-folder-plus"></i> Categorías
            </span>
            <span style="color: var(--text-muted);">Agrupar productos para organizar y filtrar tu stock.</span>
        </div>
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #f59e0b; font-weight: bold;">
                <i class="fa-solid fa-pen"></i> Editar
            </span>
            <span style="color: var(--text-muted);">Modificar nombre, alertas o categoría.</span>
        </div>
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="color: #ef4444; font-weight: bold;">
                <i class="fa-solid fa-trash-can"></i> Eliminar
            </span>
            <span style="color: var(--text-muted);">Borrar producto (solo si no tiene historial).</span>
        </div>
        <?php endif; ?>

    </div>
</div>
                <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                    <div style="display: flex; gap: 10px;">
                        <a href="crear_categoria.php" class="btn-submit" style="background-color: var(--surface-color); color: var(--text-main); border: 1px solid var(--border-color); box-shadow: none;"><i class="fa-solid fa-tags"></i> Categorías</a>
                        <a href="crear.php" class="btn-submit"><i class="fa-solid fa-plus"></i> Nuevo producto</a>
                    </div>
                <?php endif; ?>
            </div>

            <form method="GET" action="listar.php" class="filter-search-row">
                <div class="modern-select-container">
                    <select name="categoria" class="modern-select" onchange="this.form.submit()">
                        <option value="">🏷️ Todas las categorías</option>
                        <?php 
                        if ($resultado_categorias_filtro && $resultado_categorias_filtro->num_rows > 0) {
                            while ($cat = $resultado_categorias_filtro->fetch_assoc()) {
                                $selected = ($filtro_categoria == $cat['id_categoria']) ? 'selected' : '';
                                echo '<option value="' . $cat['id_categoria'] . '" ' . $selected . '>' . $cat['nombre_categoria'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <i class="fa-solid fa-chevron-down modern-select-icon"></i>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="modern-search">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                        <input type="text" name="buscar" placeholder="Buscar artículo o stock..." value="<?php echo htmlspecialchars($busqueda); ?>" autocomplete="off">
                    </div>
                    
                    <?php if(!empty($busqueda) || !empty($filtro_categoria)): ?>
                        <a href="listar.php" class="btn-submit" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px 15px; text-decoration: none; box-shadow: none;" title="Limpiar Filtros">
                            <i class="fa-solid fa-eraser"></i>
                        </a>
                    <?php endif; ?>
                    
                    <button type="submit" style="display: none;">Buscar</button>
                </div>
            </form>

            <table style="width: 100%; text-align: left;">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nombre del Artículo</th>
                        <th>Categoría</th>
                        <th style="text-align: right;">Stock Actual</th>
                        <th style="text-align: center; width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                        
                        <tr>
                            <td data-label="ID" style="color: var(--text-muted); font-weight: bold;"><?php echo $fila['id_producto']; ?></td>
                            <td data-label="Artículo" style="font-weight: 500;"><?php echo $fila['nombre_articulo']; ?></td>
                            <td data-label="Categoría">
                                <span style="background-color: var(--bg-color); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--border-color);">
                                    <?php echo $fila['nombre_categoria']; ?>
                                </span>
                            </td>
                            
                            <td data-label="Stock" style="text-align: right; font-weight: 600; font-size: 1.1rem;">
                                <?php 
                                    $stock_actual = $fila['cantidad_stock'];
                                    $alerta = isset($fila['stock_alerta']) ? $fila['stock_alerta'] : 5; 
                                    
                                    if ($stock_actual == 0) {
                                        echo '<span style="background-color: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.3);"><i class="fa-solid fa-triangle-exclamation"></i> Agotado (0)</span>';
                                    } 
                                    else if ($stock_actual <= $alerta) {
                                        echo '<span style="background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(245, 158, 11, 0.3);"><i class="fa-solid fa-bell"></i> Quedan ' . $stock_actual . '</span>';
                                    } 
                                    else {
                                        echo '<span style="color: #10b981;">' . $stock_actual . '</span>';
                                    }
                                ?>
                            </td>

                            <td data-label="Acciones" style="text-align: center;">
                                <a href="gestionar.php?id=<?php echo $fila['id_producto']; ?>" class="btn-submit" style="padding: 6px 12px; font-size: 0.85rem; background-color: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); box-shadow: none; text-decoration: none; display: inline-block;">
                                    <i class="fa-solid fa-pen-to-square"></i> Gestionar
                                </a>
                            </td>

                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">No se encontraron productos.</td>
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