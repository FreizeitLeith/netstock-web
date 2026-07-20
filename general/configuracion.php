<?php
session_start();
// dejar el acceso totalmente libre sin importar el rol, para que cualquier usuario pueda enviar tickets de soporte o sugerencias
if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
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
    <title>Configuración y Soporte | NetStock</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/panel.css">
    <style>
        /* Diseño Moderno tipo SaaS */
        .modern-container {
            max-width: 600px;
            margin: 40px auto;
        }

        .modern-card {
            background-color: var(--surface-color, #1e1e2f);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .modern-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .modern-header h3 {
            color: #ffffff;
            font-size: 1.5rem;
            margin-bottom: 8px;
            margin-top: 0;
        }

        .modern-header p {
            color: #a0aec0;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Estilos de Formulario Modernos */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #e2e8f0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .modern-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        /* Efecto al hacer clic (Focus) */
        .modern-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25);
            background-color: rgba(255, 255, 255, 0.06);
        }

        .modern-input::placeholder {
            color: #4a5568;
        }

        /* Botón Moderno con Gradiente */
        .btn-modern {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-top: 10px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.4);
        }

        .btn-modern:active {
            transform: translateY(1px);
        }

        /* Alerta de Éxito */
        .alert-success {
            background-color: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #4ade80;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        /* Fuerza el color de fondo y texto para el selector en navegadores oscuros */
        select.modern-input {
            background-color: var(--bg-color) !important;
            color: #ffffff !important;
        }

        /* Cambia el color de fondo de las opciones dentro del desplegable */
        select.modern-input option {
            background-color: var(--surface-color);
            color: #ffffff;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="main-content" style="margin-left: 260px; padding: 20px;">
        
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
            <h2 style="margin: 0;">Centro de Soporte</h2>
            <a href="panel.php" class="btn-submit" style="background-color: transparent; border: 1px solid #4a5568; color: #a0aec0; text-decoration: none; padding: 8px 16px; border-radius: 8px; transition: all 0.2s;">← Volver al Panel</a>
        </div>

        <div class="modern-container">
            <?php if($mensaje_exito != ""): ?>
                <div class="alert-success">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>

            <div class="modern-card">
                <div class="modern-header">
                    <h3>¿En qué podemos ayudarte?</h3>
                    <p>Envía un ticket a nuestro equipo de ingeniería.</p>
                </div>
                
                <form action="configuracion.php" method="POST">
                    
                    <div class="form-group">
                        <label class="form-label" for="tipo_ticket">Tipo de Reporte</label>
                        <select name="tipo_ticket" id="tipo_ticket" class="modern-input" required>
                            <option value="Sugerencia">💡 Sugerencia de Mejora</option>
                            <option value="Error">🐛 Reporte de Error (Bug)</option>
                            <option value="Duda">❓ Duda Operativa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="asunto">Asunto</label>
                        <input type="text" name="asunto" id="asunto" class="modern-input" required placeholder="Resumen breve de la situación...">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mensaje">Descripción Detallada</label>
                        <textarea name="mensaje" id="mensaje" rows="5" class="modern-input" required placeholder="Explica detalladamente qué ocurrió o qué te gustaría mejorar..." style="resize: vertical;"></textarea>
                    </div>

                    <button type="submit" name="enviar_ticket" class="btn-modern">
                        Enviar Ticket de Soporte
                    </button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>