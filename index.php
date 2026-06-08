<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetStock | Sistema de Control Inteligente</title>
    
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header>
        <div class="logo">NetStock</div>
        <div class="nav-links">
            <a href="#detalles">Detalles Técnicos</a>
            <a href="#alcances">Alcances</a>
            <a href="#equipo">Equipo</a>
            <a href="login.php" class="btn-login">Iniciar Sesión</a>
            <a href="registro.php" class="btn-registro">Crear Cuenta</a>
        </div>
    </header>

    <section class="hero">
        <h1 style="color: var(--primary-color);">NetStock</h1>
        <h1>Combatiendo la Entropía Operativa</h1>
        <p>NetStock es una plataforma digital de gestión de inventario y trazabilidad diseñada para optimizar el flujo de mercancía, garantizando seguridad y eficiencia a través de un control de acceso basado en roles.</p>
        <a href="login.php" class="btn-login" style="display:inline-block; margin-top:20px; font-size:1.2rem;">Acceder al Sistema</a>
    </section>

    <section id="alcances">
        <h2>Alcances del Sistema</h2>
        <div class="grid-3">
            <div class="card">
                <h3>📦 Control de Inventario</h3>
                <p>Monitoreo en tiempo real de entradas y salidas de mercancía, diferenciando productos de venta y material de uso interno.</p>
            </div>
            <div class="card">
                <h3>🛡️ Seguridad por Roles</h3>
                <p>Tres niveles de acceso (Jefe, Trabajador) para garantizar que cada usuario solo vea y modifique lo que le corresponde.</p>
            </div>
            <div class="card">
                <h3>🔍 Trazabilidad (Movimientos)</h3>
                <p>Registro automático de cada movimiento realizado en el sistema, respondiendo a la pregunta: ¿Quién hizo qué y cuándo?</p>
            </div>
        </div>
    </section>

    <section id="equipo" style="background-color: var(--surface-color);">
        <h2>Desarrollado Por</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Diseñado e implementado aplicando los principios de la Teoría de Sistemas, buscando soluciones tecnológicas a problemas del mundo real.</p>
        <div class="card" style="max-width: 400px; margin: 30px auto; background-color: var(--bg-color);">
            <h3 style="margin-bottom: 5px; color: #835cd1;">FreizeitLeith</h3>
            <p style="color: var(--text-muted); margin-top:0;">Ingeniería de Computación</p>
            <p>"La tecnología como herramienta para el orden."</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 NetStock. Todos los derechos reservados. | Proyecto realizado por FreizeitLeith</p>
    </footer>

</body>
    </html>