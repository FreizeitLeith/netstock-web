<?php
session_start();
// Validamos que el usuario haya iniciado sesión
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado.'); window.location.href='panel.php';</script>";
    exit();
}

// Simulamos el envío del ticket si se envió el formulario
$mensaje_exito = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_ticket'])) {
    $tipo = $_POST['tipo_ticket'];
    // Aquí a futuro harías un INSERT a una tabla "tickets" en tu base de datos
    $mensaje_exito = "¡Tu $tipo ha sido enviado al equipo de soporte exitosamente! Nos pondremos en contacto pronto.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css">
    <style>
        /* Pequeños estilos adicionales para esta pantalla */
        .config-section {
            background-color: var(--surface-color, #1e1e2f);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #333;
        }
        
        /* Clases que se activarán cuando JavaScript cambie a Modo Claro */
        body.light-mode {
            --bg-color: #f4f4f9;
            --surface-color: #ffffff;
            --text-color: #333333;
            --text-muted: #666666;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        body.light-mode .config-section, 
        body.light-mode table, 
        body.light-mode th, 
        body.light-mode td {
            background-color: var(--surface-color);
            border-color: #ddd;
            color: var(--text-color);
        }
        body.light-mode input, body.light-mode select, body.light-mode textarea {
            background-color: #fff;
            color: #333;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="main-content" style="margin-left: 260px; padding: 20px;">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
            <h2 style="margin: 0;">⚙️ Configuración del Sistema</h2>
            <a href="panel.php" class="btn-submit" style="background-color: #6c757d; color: white; text-decoration: none; padding: 8px 15px; width: auto;">← Volver al Panel</a>
        </div>

        <div class="config-section">
            <h3 style="color: var(--primary-color, #8b5cf6); margin-top: 0;">Apariencia Visual</h3>
            <p style="color: var(--text-muted);">NetStock utiliza un "Dark Mode" por defecto para proteger tu vista. Sin embargo, puedes alternar el modo de visualización aquí.</p>
            <button id="btn-theme" class="btn-submit" style="width: auto; padding: 10px 20px; background-color: #ffc107; color: #000;">
                ☀️ Cambiar a Modo Claro
            </button>
        </div>

        <?php if($mensaje_exito != ""): ?>
            <div style="background-color: #28a745; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-weight: bold;">
                ✅ <?php echo $mensaje_exito; ?>
            </div>
        <?php endif; ?>

        <div class="config-section">
            <h3 style="color: var(--primary-color, #8b5cf6); margin-top: 0;">Soporte Técnico (Tickets)</h3>
            <p style="color: var(--text-muted);">¿Encontraste un error o tienes una sugerencia para mejorar NetStock? Envíanos un ticket.</p>
            
            <form action="configuracion.php" method="POST" style="max-width: 500px;">
                <label for="tipo_ticket" style="display: block; margin-bottom: 5px; color: var(--text-color);">Tipo de Reporte</label>
                <select name="tipo_ticket" id="tipo_ticket" required style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #333; color: white; border: 1px solid #555;">
                    <option value="Sugerencia">💡 Sugerencia de Mejora</option>
                    <option value="Error">🐛 Reporte de Error (Bug)</option>
                    <option value="Duda">❓ Duda del Sistema</option>
                </select>

                <label for="asunto" style="display: block; margin-bottom: 5px; color: var(--text-color);">Asunto</label>
                <input type="text" name="asunto" id="asunto" required placeholder="Ej. El inventario no descuenta la salida" style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #333; color: white; border: 1px solid #555;">

                <label for="mensaje" style="display: block; margin-bottom: 5px; color: var(--text-color);">Descripción Detallada</label>
                <textarea name="mensaje" id="mensaje" rows="4" required placeholder="Explica detalladamente la situación..." style="width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #333; color: white; border: 1px solid #555; resize: vertical;"></textarea>

                <button type="submit" name="enviar_ticket" class="btn-submit" style="background-color: var(--primary-color, #8b5cf6); color: white;">
                    📩 Enviar Ticket
                </button>
            </form>
        </div>

    </div>

    <script>
        const btnTheme = document.getElementById('btn-theme');
        const body = document.body;

        // Revisamos si el usuario ya había elegido el modo claro anteriormente
        if(localStorage.getItem('tema') === 'claro') {
            body.classList.add('light-mode');
            btnTheme.innerHTML = '🌙 Cambiar a Modo Oscuro';
        }

        btnTheme.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            
            if(body.classList.contains('light-mode')) {
                localStorage.setItem('tema', 'claro');
                btnTheme.innerHTML = '🌙 Cambiar a Modo Oscuro';
            } else {
                localStorage.setItem('tema', 'oscuro');
                btnTheme.innerHTML = '☀️ Cambiar a Modo Claro';
            }
        });
    </script>
</body>
</html>