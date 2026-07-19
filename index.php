<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetStock | Control de Inventario Simple y Seguro</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header>
        <div class="logo">NetStock</div>
        <nav class="nav-links">
            <a href="#inicio">Inicio</a>
            <a href="#funciones">Funciones</a>
            <a href="#roles">Roles</a>
            <a href="#faq">Preguntas frecuentes</a>
            <a href="login.php" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
            <a href="registro.php" class="btn-registro"><i class="fa-solid fa-user-plus"></i> Crear cuenta</a>
        </nav>
    </header>

    <section id="inicio" class="hero" style="display: flex; align-items: center; justify-content: center; gap: 50px; text-align: left; max-width: 1200px; margin: 0 auto;">
        <div style="flex: 1;">
            <h1 style="color: var(--primary-color);">NetStock</h1>
            <h1>Controla el inventario de tu negocio<br>de forma simple y segura.</h1>
            <p style="margin: 0 0 20px 0;">Registra productos, administra existencias y consulta el historial de movimientos desde cualquier dispositivo. NetStock ayuda a mantener organizado el inventario de pequeños negocios, permitiendo que jefes y trabajadores colaboren de forma segura.</p>
            
            <div class="hero-list">
                <p><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Inventario actualizado</p>
                <p><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Acceso por roles</p>
                <p><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Historial de movimientos</p>
                <p><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Sin instalaciones</p>
            </div>

            <div class="hero-buttons" style="margin-top: 30px;">
                <a href="login.php" class="btn-login" style="font-size: 1.1rem;">Acceder al sistema</a>
                <a href="registro.php" class="btn-submit" style="font-size: 1.1rem; background-color: transparent; border: 1px solid var(--primary-color); color: var(--primary-color) !important;">Crear cuenta</a>
            </div>
        </div>

        <div style="flex: 1; text-align: right;">
            <img src="img/dashboard-preview.png" alt="Vista previa de NetStock" style="max-width: 100%; width: 550px; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); transform: perspective(1000px) rotateY(-10deg); transition: transform 0.5s ease;">
        </div>
    </section>

    <section id="funciones">
        <h2>¿Qué puedes hacer con NetStock?</h2>
        <div class="grid-4">
            <div class="card text-center">
                <h3 style="justify-content: center;"><i class="fa-solid fa-box" style="color: var(--primary-color); margin-right: 10px;"></i> Registrar productos</h3>
                <p>Crea y organiza el inventario de tu negocio de manera rápida y sin complicaciones.</p>
            </div>
            <div class="card text-center">
                <h3 style="justify-content: center;"><i class="fa-solid fa-chart-line" style="color: var(--primary-color); margin-right: 10px;"></i> Controlar existencias</h3>
                <p>Actualiza fácilmente las entradas y salidas de mercancía para evitar faltantes.</p>
            </div>
            <div class="card text-center">
                <h3 style="justify-content: center;"><i class="fa-solid fa-users" style="color: var(--primary-color); margin-right: 10px;"></i> Trabajar en equipo</h3>
                <p>Los jefes administran el inventario base y los trabajadores actualizan el stock diario.</p>
            </div>
            <div class="card text-center">
                <h3 style="justify-content: center;"><i class="fa-solid fa-clock-rotate-left" style="color: var(--primary-color); margin-right: 10px;"></i> Consultar historial</h3>
                <p>Cada movimiento queda registrado para mantener la trazabilidad operativa.</p>
            </div>
        </div>
    </section>

    <section id="como-funciona" style="background-color: var(--surface-color);">
        <h2>¿Cómo funciona?</h2>
        <div class="grid-4 steps-container">
            <div class="step-card">
                <div class="step-number">1</div>
                <p>Crea una cuenta gratuita como <strong>Jefe</strong>.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <p>Registra tus primeros <strong>productos</strong> en el catálogo.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <p>Comparte tu <strong>código de vinculación</strong> con tus empleados.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <p>Tus <strong>trabajadores</strong> ya pueden actualizar el inventario.</p>
            </div>
        </div>
    </section>

    <section id="roles">
        <h2>Roles del Sistema</h2>
        <p style="color: var(--text-muted); margin-bottom: 40px;">Seguridad basada en permisos. Cada quien ve solo lo que necesita.</p>
        <div class="grid-2">
            <div class="card role-card">
                <h3 style="font-size: 1.8rem;"><i class="fa-solid fa-user-tie" style="color: var(--primary-color); margin-right: 15px;"></i> Jefe</h3>
                <ul class="role-list">
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Registrar nuevos productos</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Editar detalles de productos</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Eliminar productos obsoletos</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Consultar el historial inmutable</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Generar código de vinculación</li>
                </ul>
            </div>
            <div class="card role-card">
                <h3 style="font-size: 1.8rem;"><i class="fa-solid fa-helmet-safety" style="color: #f59e0b; margin-right: 15px;"></i> Trabajador</h3>
                <ul class="role-list">
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Consultar el inventario actual</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Modificar existencias (Entrada/Salida)</li>
                    <li><i class="fa-solid fa-check" style="color: #10b981;"></i> Enviar tickets de soporte</li>
                    <li class="disabled"><i class="fa-solid fa-xmark" style="color: #ef4444;"></i> No puede eliminar productos</li>
                    <li class="disabled"><i class="fa-solid fa-xmark" style="color: #ef4444;"></i> No puede administrar el historial</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="faq" style="background-color: var(--surface-color);">
        <h2>Transparencia Total</h2>
        <p style="color: var(--text-muted); margin-bottom: 40px;">Para que no te lleves sorpresas, esto es exactamente lo que somos.</p>
        <div class="grid-2">
            <div class="card text-left">
                <h3 style="color: #10b981; margin-bottom: 20px;">Lo que NetStock SÍ hace</h3>
                <ul class="role-list">
                    <li><i class="fa-solid fa-check-circle" style="color: #10b981;"></i> Gestión de Inventario</li>
                    <li><i class="fa-solid fa-check-circle" style="color: #10b981;"></i> Control de Stock de alerta</li>
                    <li><i class="fa-solid fa-check-circle" style="color: #10b981;"></i> Historial de Movimientos</li>
                    <li><i class="fa-solid fa-check-circle" style="color: #10b981;"></i> Seguridad por Roles (RBAC)</li>
                    <li><i class="fa-solid fa-check-circle" style="color: #10b981;"></i> Trazabilidad auditable</li>
                </ul>
            </div>
            <div class="card text-left">
                <h3 style="color: #ef4444; margin-bottom: 20px;">Lo que NetStock NO hace</h3>
                <ul class="role-list">
                    <li class="disabled"><i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i> No es un Punto de Venta (POS)</li>
                    <li class="disabled"><i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i> No emite Facturación electrónica</li>
                    <li class="disabled"><i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i> No gestiona Contabilidad</li>
                    <li class="disabled"><i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i> No realiza cálculos financieros</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="equipo">
        <h2>Desarrollado Por</h2>
        <p style="color: var(--text-muted); max-width: 600px; margin: 0 auto;">Diseñado e implementado aplicando los principios de la Teoría de Sistemas.</p>
        <div class="card" style="max-width: 400px; margin: 30px auto; text-align: center;">
            <h3 style="margin-bottom: 5px; color: var(--primary-color); justify-content: center;">FreizeitLeith</h3>
            <p style="color: var(--text-muted); margin-top:0;"><i class="fa-solid fa-code"></i> Ingeniería de Computación</p>
            <p>"La tecnología como herramienta para el orden."</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2026 NetStock. Todos los derechos reservados. | Proyecto realizado por Frank Pérez</p>
    </footer>

    <script>
        // Lectura del tema guardado
        if(localStorage.getItem('tema') === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
</body>
</html>