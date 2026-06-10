<?php
session_start();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | NetStock</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css"> 
    
    <style>
        /* Estilos específicos para la vista estilo Holded */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .search-bar-container {
            background-color: var(--surface-color);
            padding: 15px 20px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .search-input {
            flex-grow: 1;
            border: none;
            background: transparent;
            color: var(--text-main);
            font-size: 1rem;
            outline: none;
        }

        .table-row-clickable:hover {
            background-color: var(--sidebar-active);
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        /* ESTILOS DEL MODAL FLOTANTE */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background-color: var(--surface-color);
            width: 100%;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            padding: 30px;
            position: relative;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.2rem;
            cursor: pointer;
        }

        .modal-header-badge {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        /* Botones de acción en el Modal */
        .btn-action-group {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-add { background-color: #10b981; color: white; flex: 1; }
        .btn-sub { background-color: #ef4444; color: white; flex: 1; }
        .btn-add:hover { background-color: #059669; }
        .btn-sub:hover { background-color: #dc2626; }

        .modal-footer-actions {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
        }

        .mobile-header {
            display: none;
            background-color: var(--sidebar-bg);
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            align-items: center;
            justify-content: space-between;
        }
        .menu-toggle-btn {
            background: none;
            border: none;
            color: var(--text-main);
            font-size: 1.5rem;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .mobile-header { display: flex; }
            .sidebar { box-shadow: 5px 0 15px rgba(0,0,0,0.5); }
        }
    </style>
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
                <?php endif; ?>

                <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
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
                    <i class="fa-solid fa-circle-info" style="color: var(--text-muted); cursor: pointer;" title="Gestión de inventario centralizada"></i>
                </div>
                
                <?php if ($rol == 'Jefe' || $rol == 'Administrador'): ?>
                    <a href="crear.php" class="btn-submit"><i class="fa-solid fa-plus"></i> Nuevo producto</a>
                <?php endif; ?>
            </div>

            <form method="GET" action="listar.php" class="search-bar-container">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                <input type="text" name="buscar" class="search-input" placeholder="Buscar por nombre o stock..." value="<?php echo htmlspecialchars($busqueda); ?>">
                <?php if(!empty($busqueda)): ?>
                    <a href="listar.php" style="color: var(--text-muted); text-decoration: none;"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
                <button type="submit" style="display: none;">Buscar</button> </form>

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
                            <td data-label="ID" style="color: var(--text-muted); font-weight: bold;">
                                <?php echo $fila['id_producto']; ?>
                            </td>
                            <td data-label="Artículo" style="font-weight: 500;">
                                <?php echo $fila['nombre_articulo']; ?>
                            </td>
                            <td data-label="Categoría">
                                <span style="background-color: var(--bg-color); padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; border: 1px solid var(--border-color);">
                                    <?php echo $fila['nombre_categoria']; ?>
                                </span>
                            </td>
                            <td data-label="Stock" style="text-align: right; font-weight: 600; font-size: 1.1rem;">
                                <?php echo $fila['cantidad_stock']; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                No se encontraron productos.
                            </td>
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
                    <button type="submit" name="accion" value="Entrada" class="btn-submit btn-add">
                        <i class="fa-solid fa-arrow-turn-down fa-rotate-90"></i> Agregar
                    </button>
                    <button type="submit" name="accion" value="Salida" class="btn-submit btn-sub">
                        <i class="fa-solid fa-arrow-turn-up fa-rotate-90"></i> Disminuir
                    </button>
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
        // Tema
        if(localStorage.getItem('tema') === 'claro') {
            document.documentElement.setAttribute('data-theme', 'light');
        }

        // Lógica del Menú Móvil
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-menu');
        const closeBtn = document.getElementById('close-menu');

        openBtn.addEventListener('click', () => sidebar.classList.add('active'));
        closeBtn.addEventListener('click', () => sidebar.classList.remove('active'));

        // ==========================================
        // LÓGICA DEL MODAL FLOTANTE
        // ==========================================
        const modal = document.getElementById('productModal');
        
        function openModal(id, nombre, stock) {
            // 1. Rellenar los textos visuales
            document.getElementById('modal-title').innerText = nombre;
            document.getElementById('modal-stock').innerText = stock;
            document.getElementById('modal-initial').innerText = nombre.charAt(0).toUpperCase();

            // 2. Rellenar el input oculto para el formulario de stock
            document.getElementById('modal-id').value = id;

            // 3. Actualizar los enlaces de Editar y Eliminar (Solo si existen en el DOM)
            const editLink = document.getElementById('modal-edit-link');
            const deleteLink = document.getElementById('modal-delete-link');
            
            if(editLink && deleteLink) {
                editLink.href = 'editar.php?id=' + id;
                deleteLink.href = 'eliminar.php?id=' + id;
            }

            // 4. Mostrar el Modal
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        // Cerrar el modal si se hace clic afuera de la caja
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>