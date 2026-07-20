<?php
session_start();
require_once '../general/conexion.php'; 

if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];
$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consultas filtradas por código de negocio
$sql_categorias_filtro = "SELECT * FROM categoria WHERE codigo_negocio = '{$_SESSION['codigo_negocio']}'";
$resultado_categorias_filtro = $conn->query($sql_categorias_filtro);

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
    <style>
        /* Estilos del Modal Flotante */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 2000; opacity: 0; visibility: hidden; transition: opacity 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background-color: var(--surface-color); border: 1px solid var(--border-color); border-radius: 12px; width: 90%; max-width: 480px; padding: 25px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); transform: scale(0.95); transition: transform 0.3s ease; }
        .modal-overlay.active .modal-content { transform: scale(1); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; }
    </style>
</head>
<body>

    <div class="mobile-header">
        <a href="../index.php" style="font-size: 1.25rem; font-weight: bold; color: var(--primary-color); text-decoration: none;">NetStock</a>
        <button class="menu-toggle-btn" id="open-menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <a href="../index.php" style="color: var(--text-main); text-decoration: none; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-layer-group"></i> NetStock
                </a>
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
                    <h2 style="margin: 0; font-size: 1.8rem;">Inventario</h2>
                    <button type="button" id="btnHelp" class="btn-submit" style="background-color: transparent; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 8px 15px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: none;">
                        <i class="fa-solid fa-circle-question"></i> Ayuda
                    </button>
                    <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                        <div style="display: flex; gap: 10px;">
                            <a href="crear_categoria.php" class="btn-submit" style="background-color: var(--surface-color); color: var(--text-main); border: 1px solid var(--border-color); box-shadow: none;"><i class="fa-solid fa-tags"></i> Categorías</a>
                            <a href="crear.php" class="btn-submit"><i class="fa-solid fa-plus"></i> Nuevo producto</a>
                        </div>
                    <?php endif; ?>
                </div>
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
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="modern-search">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                        <input type="text" name="buscar" placeholder="Buscar artículo..." value="<?php echo htmlspecialchars($busqueda); ?>" autocomplete="off">
                    </div>
                </div>
            </form>

            <table style="width: 100%; text-align: left;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Artículo</th>
                        <th>Categoría</th>
                        <th style="text-align: right;">Stock Actual</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td style="color: var(--text-muted); font-weight: bold;"><?php echo $fila['id_producto']; ?></td>
                            <td><?php echo $fila['nombre_articulo']; ?></td>
                            <td><?php echo $fila['nombre_categoria']; ?></td>
                            <td style="text-align: right; font-weight: 600;">
                                <?php 
                                $stock = $fila['cantidad_stock'];
                                $alerta = $fila['stock_alerta'];
                                if ($stock == 0) echo '<span style="color: #ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Agotado</span>';
                                else if ($stock <= $alerta) echo '<span style="color: #f59e0b;"><i class="fa-solid fa-bell"></i> ' . $stock . '</span>';
                                else echo $stock;
                                ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="gestionar.php?id=<?php echo $fila['id_producto']; ?>" class="btn-submit" style="padding: 6px 12px; background: transparent; color: var(--primary-color); border: 1px solid var(--primary-color); box-shadow: none;">
                                    <i class="fa-solid fa-pen-to-square"></i> Gestionar
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center; padding: 40px;">No se encontraron productos.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="helpModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="margin: 0; color: var(--primary-color);">Guía de Acciones</h3>
                <button type="button" id="closeHelp" style="background: none; border: none; color: var(--text-muted); cursor: pointer;"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div><strong>Gestionar:</strong> Ajustar stock (Entrada/Salida).</div>
                <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
                    <div><strong>Nuevo:</strong> Añadir producto al catálogo.</div>
                    <div><strong>Categorías:</strong> Gestionar grupos de productos.</div>
                    <div><strong>Editar:</strong> Modificar datos del producto.</div>
                    <div><strong>Eliminar:</strong> Borrar producto.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        const btnHelp = document.getElementById('btnHelp');
        const helpModal = document.getElementById('helpModal');
        const closeHelp = document.getElementById('closeHelp');
        btnHelp.addEventListener('click', () => helpModal.classList.add('active'));
        closeHelp.addEventListener('click', () => helpModal.classList.remove('active'));
        helpModal.addEventListener('click', (e) => { if(e.target === helpModal) helpModal.classList.remove('active'); });

        const sidebar = document.getElementById('sidebar');
        document.getElementById('open-menu').addEventListener('click', () => sidebar.classList.add('active'));
        document.getElementById('close-menu').addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>