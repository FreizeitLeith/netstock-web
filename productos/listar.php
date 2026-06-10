<?php
session_start();
require_once '../general/conexion.php'; 

// Validar que el usuario esté logueado
if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$rol = $_SESSION['rol'];

// Obtener todas las categorías para el filtro
$sql_categorias_filtro = "SELECT * FROM categoria";
$resultado_categorias_filtro = $conn->query($sql_categorias_filtro);

// Lógica del buscador y filtro combinado
$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : '';
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consulta base (1=1 nos permite concatenar condiciones 'AND' fácilmente)
$sql = "SELECT p.id_producto, p.nombre_articulo, p.cantidad_stock, c.nombre_categoria 
        FROM producto p
        INNER JOIN categoria c ON p.id_categoria = c.id_categoria
        WHERE 1=1";

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
                </div>
                
                <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                    <div style="display: flex; gap: 10px;">
                        <a href="crear_categoria.php" class="btn-submit" style="background-color: var(--surface-color); color: var(--text-main); border: 1px solid var(--border-color);"><i class="fa-solid fa-tags"></i> Categorías</a>
                        <a href="crear.php" class="btn-submit"><i class="fa-solid fa-plus"></i> Nuevo producto</a>
                    </div>
                <?php endif; ?>
            </div>

            <form method="GET" action="listar.php" class="search-bar-container">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                <input type="text" name="buscar" class="search-input" placeholder="Buscar por nombre o stock..." value="<?php echo htmlspecialchars($busqueda); ?>">
                
                <select name="categoria" class="filter-select" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php 
                    if ($resultado_categorias_filtro && $resultado_categorias_filtro->num_rows > 0) {
                        while ($cat = $resultado_categorias_filtro->fetch_assoc()) {
                            $selected = ($filtro_categoria == $cat['id_categoria']) ? 'selected' : '';
                            echo '<option value="' . $cat['id_categoria'] . '" ' . $selected . '>' . $cat['nombre_categoria'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <?php if(!empty($busqueda) || !empty($filtro_categoria)): ?>
                    <a href="listar.php" style="color: var(--text-muted); text-decoration: none; margin-left: 10px;" title="Limpiar Filtros"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
                <button type="submit" style="display: none;">Buscar</button>
            </form>

            <table style="width: 100%; text-align: left;">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nombre del Artículo</th>
                        <th>Categoría</th>
                        <th style="text-align: right;">Stock Actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0): ?>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr class="table-row-clickable" onclick="openModal(<?php echo $fila['id_producto']; ?>, '<?php echo addslashes($fila['nombre_articulo']); ?>', <?php echo $fila['cantidad_stock']; ?>)">
                            <td data-label="ID" style="color: var(--text-muted); font-weight: bold;"><?php echo $fila['id_producto']; ?></td>
                            <td data-label="Artículo" style="font-weight: 500;"><?php echo $fila['nombre_articulo']; ?></td>
                            <td data-label="Categoría">
                                <span style="background-color: var(--bg-color); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--border-color);">
                                    <?php echo $fila['nombre_categoria']; ?>
                                </span>
                            </td>
                            <td data-label="Stock" style="text-align: right; font-weight: 600; font-size: 1.1rem;"><?php echo $fila['cantidad_stock']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">No se encontraron productos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            
            <div style="display: flex; align-items: center; margin-bottom: 25px;">
                <div class="modal-header-badge" id="modal-initial">P</div>
                <div>
                    <h3 id="modal-title" style="margin: 0; font-size: 1.5rem; color: var(--text-main);">Nombre Producto</h3>
                    <p style="margin: 0; color: var(--text-muted); font-size: 0.9rem;">Stock Actual: <strong id="modal-stock" style="color: var(--text-main);">0</strong></p>
                </div>
            </div>

            <form action="modificar_stock.php" method="POST">
                <input type="hidden" name="id_producto" id="modal-id">
                <label style="display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-muted);">Cantidad a mover:</label>
                <input type="number" name="cantidad_afectada" min="1" required style="width: 100%; padding: 12px; background: var(--bg-color); border: 1px solid var(--border-color); color: var(--text-main); border-radius: 6px; font-size: 1rem; margin-bottom: 5px;">
                
                <div class="btn-action-group">
                    <button type="submit" name="accion" value="Entrada" class="btn-submit btn-add"><i class="fa-solid fa-arrow-turn-down fa-rotate-90"></i> Agregar</button>
                    <button type="submit" name="accion" value="Salida" class="btn-submit btn-sub"><i class="fa-solid fa-arrow-turn-up fa-rotate-90"></i> Disminuir</button>
                </div>
            </form>

            <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
            <div class="modal-footer-actions">
                <a href="#" id="modal-edit-link" style="color: var(--primary-color); font-weight: 500; text-decoration: none;"><i class="fa-solid fa-pen-to-square"></i> Editar Producto</a>
                <a href="#" id="modal-delete-link" style="color: #ef4444; font-weight: 500; text-decoration: none;" onclick="return confirm('¿Seguro que deseas eliminar este artículo?');"><i class="fa-solid fa-trash-can"></i> Eliminar</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        if(localStorage.getItem('tema') === 'claro') { document.documentElement.setAttribute('data-theme', 'light'); }
        const sidebar = document.getElementById('sidebar');
        document.getElementById('open-menu').addEventListener('click', () => sidebar.classList.add('active'));
        document.getElementById('close-menu').addEventListener('click', () => sidebar.classList.remove('active'));

        const modal = document.getElementById('productModal');
        function openModal(id, nombre, stock) {
            document.getElementById('modal-title').innerText = nombre;
            document.getElementById('modal-stock').innerText = stock;
            document.getElementById('modal-initial').innerText = nombre.charAt(0).toUpperCase();
            document.getElementById('modal-id').value = id;
            
            const editLink = document.getElementById('modal-edit-link');
            const deleteLink = document.getElementById('modal-delete-link');
            if(editLink && deleteLink) {
                editLink.href = 'editar.php?id=' + id;
                deleteLink.href = 'eliminar.php?id=' + id;
            }
            modal.classList.add('active');
        }
        function closeModal() { modal.classList.remove('active'); }
        window.onclick = function(event) { if (event.target == modal) { closeModal(); } }
    </script>
</body>
</html>