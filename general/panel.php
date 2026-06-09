<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | NetStock</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css"> 
    
    <style>
        /* Estilos específicos para el botón móvil del panel */
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
                <li><a href="../productos/listar.php"><i class="fa-solid fa-box"></i> Inventario</a></li>
                
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
            <h2>Bienvenido al Centro de Operaciones</h2>
            <p style="color: var(--text-muted); max-width: 600px;">
                Has iniciado sesión correctamente. Selecciona cualquiera de los módulos habilitados para tu nivel de acceso en el menú lateral izquierdo para comenzar a trabajar.
            </p>
        </div>

    </div>

    <script>
        // 1. Aplicar el tema (Modo Claro/Oscuro)
        if(localStorage.getItem('tema') === 'oscuro') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }

        // 2. Lógica del Menú Móvil (Hamburguesa)
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-menu');
        const closeBtn = document.getElementById('close-menu');

        // Función para adaptar los botones según el tamaño de pantalla
        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                closeBtn.style.display = 'block';
            } else {
                closeBtn.style.display = 'none';
                sidebar.classList.remove('active'); // Asegura que no se quede pegado al rotar pantalla
            }
        }

        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        // Escuchar cambios en el tamaño de la pantalla
        window.addEventListener('resize', checkScreenSize);
        checkScreenSize(); // Ejecutar al cargar
    </script>

</body>
</html>