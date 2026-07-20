<?php
session_start();
// Conectamos a la base de datos (subiendo un nivel a /general)
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden gestionar categorías.'); window.location.href='listar.php';</script>";
    exit();
}

$error_msg = "";
$success_msg = "";
$codigo_negocio = $_SESSION['codigo_negocio']; // Aislamiento Multi-Tenant

// 2. LÓGICA PARA GUARDAR LA CATEGORÍA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_categoria = trim($_POST['nombre_categoria']);

    // Verificar si la categoría ya existe en ESTE NEGOCIO en específico
    $sql_verificar = "SELECT id_categoria FROM categoria WHERE nombre_categoria = '$nombre_categoria' AND codigo_negocio = '$codigo_negocio'";
    $resultado_verificacion = $conn->query($sql_verificar);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        $error_msg = "Error: Ya existe una categoría con el nombre '" . $nombre_categoria . "' en tu inventario.";
    } else {
        // Insertar nueva categoría amarrada al código del negocio
        $sql_insert = "INSERT INTO categoria (nombre_categoria, codigo_negocio) VALUES ('$nombre_categoria', '$codigo_negocio')";

        if ($conn->query($sql_insert) === TRUE) {
            $success_msg = "¡Categoría '" . $nombre_categoria . "' registrada con éxito!";
        } else {
            $error_msg = "Hubo un error al registrar en la base de datos: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoría | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css"> </head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; background-color: var(--bg-color); padding: 20px;">

    <div class="auth-container single-panel">
        <div class="auth-card" style="max-width: 450px;">
            <div class="auth-header">
                <h2 style="color: var(--primary-color); margin-bottom: 5px; font-size: 1.8rem; font-weight: 700;">Nueva Categoría</h2>
                <p class="auth-subtitle">Crea grupos (Ej. Papelería, Snacks) para organizar mejor tus artículos.</p>
            </div>

            <?php if(!empty($error_msg)): ?>
                <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error_msg; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($success_msg)): ?>
                <div style="background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center;">
                    <i class="fa-solid fa-circle-check"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <form action="crear_categoria.php" method="POST" class="auth-form">
                
                <div class="input-group">
                    <label for="nombre_categoria">Nombre de la Categoría</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-folder-open input-icon" style="color: #eab308;"></i>
                        <input type="text" id="nombre_categoria" name="nombre_categoria" placeholder="Ej. Bebidas, Limpieza, Ferretería..." required autocomplete="off">
                    </div>
                    <span class="code-help" style="margin-top: 6px; font-size: 0.8rem; color: var(--text-muted);">Usa nombres claros que tus trabajadores identifiquen rápidamente al surtir.</span>
                </div>
                
                <button type="submit" class="btn-submit w-100" style="margin-top: 10px;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Categoría
                </button>
            </form>

            <div style="text-align: center; margin-top: 25px;">
                <a href="listar.php" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--primary-color)'" onmouseout="this.style.color='var(--text-muted)'">
                    <i class="fa-solid fa-arrow-left"></i> Volver al Inventario
                </a>
            </div>
        </div>
    </div>

    <script>
        // Sincronización del tema oscuro o claro en el navegador
        if(localStorage.getItem('tema') === 'claro') { 
            document.documentElement.setAttribute('data-theme', 'light'); 
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</body>
</html>