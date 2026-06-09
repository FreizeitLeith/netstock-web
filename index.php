<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetStock | Sistema de Control Inteligente</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header>
        <div class="logo">NetStock</div>
        <div class="nav-links">
            <a href="#detalles">Detalles Técnicos</a>
            <a href="#alcances">Alcances</a>
            <a href="login.php" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</a>
            <a href="registro.php" class="btn-registro"><i class="fa-solid fa-user-plus"></i> Crear Cuenta</a>
        </div>
    </header>

    <section class="hero" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 50px; text-align: left; max-width: 1200px; margin: 0 auto;">
        <div style="flex: 1; min-width: 300px;">
            <h1 style="color: var(--primary-color); margin-bottom: 0;">NetStock</h1>
            <h1 style="margin-top: 5px;">Combatiendo la Entropía Operativa</h1>
            <p>Plataforma digital de gestión de inventario y trazabilidad diseñada para optimizar el flujo de mercancía, garantizando seguridad y eficiencia a través de un control de acceso basado en roles.</p>
            <a href="login.php" class="btn-login" style="font-size:1.1rem; margin-top: 20px;">Acceder al Sistema</a>
        </div>
        <div style="flex: 1; text-align: center; min-width: 300px;">
            <div style="background: var(--surface-color); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: var(--shadow-md);">
                <i class="fa-solid fa-chart-line" style="font-size: 5rem; color: var(--primary-color);"></i>
                <h3 style="margin-top: 20px;">Gestión en la Nube</h3>
            </div>
        </div>
    </section>

    <section id="alcances">
        <h2>Alcances del Sistema</h2>
        <div class="grid-3">
            <div class="card">
                <h3><i class="fa-solid fa-box" style="color: var(--primary-color); margin-right: 10px;"></i> Control de Inventario</h3>
                <p>Monitoreo en tiempo real de entradas y salidas de mercancía, diferenciando productos de venta y material de uso interno.</p>
            </div>
            <div class="card">
                <h3><i class="fa-solid fa-shield-halved" style="color: var(--primary-color); margin-right: 10px;"></i> Seguridad por Roles</h3>
                <p>Tres niveles de acceso (Administrador, Jefe, Trabajador) para garantizar que cada usuario solo vea y modifique lo que le corresponde.</p>
            </div>
            <div class="card">
                <h3><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary-color); margin-right: 10px;"></i> Trazabilidad</h3>
                <p>Registro automático de cada movimiento realizado en el sistema, respondiendo a la pregunta: ¿Quién hizo qué y cuándo?</p>
            </div>
        </div>
    </section>

    <section id="equipo" style="background-color: var(--surface-color);">
        <h2>Desarrollado Por</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Diseñado e implementado aplicando los principios de la Teoría de Sistemas.</p>
        <div class="card" style="max-width: 400px; margin: 30px auto;">
            <h3 style="margin-bottom: 5px; color: var(--primary-color);">FreizeitLeith</h3>
            <p style="color: var(--text-muted); margin-top:0;"><i class="fa-solid fa-code"></i> Ingeniería de Computación</p>
            <p>"La tecnología como herramienta para el orden."</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 NetStock. Todos los derechos reservados. | Proyecto realizado por Frank Pérez</p>
    </footer>

    <script>
        if(localStorage.getItem('tema') === 'oscuro') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>