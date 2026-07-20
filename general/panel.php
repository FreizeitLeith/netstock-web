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
                <i class="fa-solid fa-circle-user" style="font-size: 2.2rem; color: var(--primary-color);"></i>
                <div>
                    <div style="font-weight: bold; color: var(--text-main); word-break: break-word;"><?php echo $_SESSION['nombre']; ?></div>
                    <div class="badge"><?php echo $_SESSION['rol']; ?></div>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li><a href="../productos/listar.php"><i class="fa-solid fa-box"></i> Inventario</a></li>
                
                <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
                    <li><a href="../movimientos/historial.php"><i class="fa-solid fa-clock-rotate-left"></i> Historial</a></li>
                <?php endif; ?>

                   <li><a href="../general/configuracion.php"><i class="fa-solid fa-gear"></i> Soporte</a></li>
                
            </ul>

            <div class="sidebar-footer">
                <a href="../login.php" class="btn-logout" style="text-decoration: none; color: var(--text-muted); display: flex; align-items: center; gap: 10px;"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
            </div>
        </div>

        <div class="main-content">
            
<div class="welcome-container" style="display: flex; gap: 30px; background-color: var(--surface-color); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 30px; flex-wrap: wrap;">
    
    <div class="welcome-message" style="flex: 1.4; min-width: 300px;">
        <h2 style="margin-top: 0; color: var(--text-main); font-size: 2.2rem; margin-bottom: 15px; letter-spacing: -0.5px;">
            Bienvenido al Centro de Operaciones
        </h2>
        
        <?php if($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
        <div style="background-color: rgba(139, 92, 246, 0.1); border: 1px dashed var(--primary-color); padding: 15px; border-radius: 8px; margin-bottom: 20px; display: inline-block;">
            <span style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-key" style="margin-right: 5px;"></i> Código de Vinculación para tus Trabajadores:</span><br>
            <strong style="color: var(--primary-color); font-size: 1.5rem; letter-spacing: 2px;"><?php echo $_SESSION['codigo_negocio']; ?></strong>
        </div>
        <?php endif; ?>
        
        <p style="color: var(--text-muted); font-size: 1.05rem; margin-bottom: 0; line-height: 1.5;">
            Has iniciado sesión correctamente. Selecciona cualquiera de los módulos habilitados para tu nivel de acceso en el menú lateral izquierdo para comenzar a trabajar de forma segura y eficiente.
        </p>
    </div>

    <div class="menu-guide" style="flex: 1; min-width: 280px; background-color: rgba(0, 0, 0, 0.15); padding: 25px; border-radius: 10px; border: 1px solid var(--border-color);">
        <h4 style="margin-top: 0; color: var(--primary-color); font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 15px;">
            <i class="fa-solid fa-circle-info"></i> Guía rápida de opciones
        </h4>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; font-size: 0.9rem; color: var(--text-main);">
            <li style="display: flex; align-items: flex-start; gap: 10px;">
                <i class="fa-solid fa-box" style="color: var(--primary-color); margin-top: 3px; width: 14px; text-align: center;"></i>
                <span><strong>Inventario:</strong> Consulta el catálogo completo, gestiona alertas de stock mínimo y registra entradas/salidas de mercancía.</span>
            </li>
            
            <?php if($_SESSION['rol'] == 'Administrador' || $_SESSION['rol'] == 'Jefe'): ?>
            <li style="display: flex; align-items: flex-start; gap: 10px;">
                <i class="fa-solid fa-clock-rotate-left" style="color: var(--primary-color); margin-top: 3px; width: 14px; text-align: center;"></i>
                <span><strong>Historial:</strong> Registro inmutable (Auditoría). Permite revisar quién cargó o retiró cada producto y en qué fecha exacta.</span>
            </li>
            <?php endif; ?>

            <li style="display: flex; align-items: flex-start; gap: 10px;">
                <i class="fa-solid fa-gear" style="color: var(--primary-color); margin-top: 3px; width: 14px; text-align: center;"></i>
                <span><strong>Soporte:</strong> ¿Encontraste algún problema? Envía un ticket directo al desarrollador para reportar fallas del sistema.</span>
            </li>
        </ul>
    </div>

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

        function checkScreenSize() {
            if (window.innerWidth <= 768) {
                closeBtn.style.display = 'block';
            } else {
                closeBtn.style.display = 'none';
                sidebar.classList.remove('active'); 
            }
        }

        openBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        window.addEventListener('resize', checkScreenSize);
        checkScreenSize(); 

        if(localStorage.getItem('tema') === 'claro') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>

</body>
</html>