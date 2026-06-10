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

                <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'trabajador'): ?>
                   <li><a href="../general/configuracion.php"><i class="fa-solid fa-gear"></i> Soporte</a></li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">
                <a href="../login.php" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
            </div>
        </div>

        <div class="main-content">
            
            <div style="background-color: var(--surface-color); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 30px;">
                <h2 style="margin-top: 0; color: var(--text-main); font-size: 2.2rem; margin-bottom: 10px; letter-spacing: -0.5px;">
                    Bienvenido al Centro de Operaciones
                </h2>
                <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
            <div style="background-color: rgba(139, 92, 246, 0.1); border: 1px dashed var(--primary-color); padding: 15px; border-radius: 8px; margin-bottom: 30px; display: inline-block;">
                <span style="color: var(--text-muted); font-size: 0.9rem;">Código de Vinculación para tus Trabajadores:</span><br>
                <strong style="color: var(--primary-color); font-size: 1.5rem; letter-spacing: 2px;"><?php echo $_SESSION['codigo_negocio']; ?></strong>
            </div>
            <?php endif; ?>
                <p style="color: var(--text-muted); max-width: 650px; font-size: 1.05rem; margin-bottom: 0;">
                    Has iniciado sesión correctamente. Selecciona cualquiera de los módulos habilitados para tu nivel de acceso en el menú lateral izquierdo para comenzar a trabajar de forma segura y eficiente.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                
                <div style="background-color: var(--surface-color); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                    <h3 style="margin: 0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                        Nivel de Acceso
                    </h3>
                    <p style="font-size: 1.8rem; font-weight: bold; margin: 10px 0 0 0; color: var(--primary-color);">
                        <?php echo $_SESSION['rol']; ?>
                    </p>
                </div>

                <div style="background-color: var(--surface-color); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                    <h3 style="margin: 0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                        Usuario Activo
                    </h3>
                    <p style="font-size: 1.8rem; font-weight: bold; margin: 10px 0 0 0; color: var(--text-main);">
                        <?php echo explode(' ', trim($_SESSION['nombre']))[0]; ?>
                    </p>
                </div>

                <div style="background-color: var(--surface-color); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                    <h3 style="margin: 0; font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                        Estado del Sistema
                    </h3>
                    <p style="font-size: 1.8rem; font-weight: bold; margin: 10px 0 0 0; color: #10b981; display: flex; align-items: center; gap: 10px;">
                        <span style="display: inline-block; width: 12px; height: 12px; background-color: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981;"></span> En línea
                    </p>
                </div>

            </div>

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

        
        // Como el sistema es oscuro por defecto, solo verificamos si el usuario prefirió el claro
        if(localStorage.getItem('tema') === 'claro') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>

</body>
</html>