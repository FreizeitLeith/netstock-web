<?php
session_start();
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden gestionar categorías.'); window.location.href='listar.php';</script>";
    exit();
}

$error_msg = "";
$success_msg = "";
$codigo_negocio = $_SESSION['codigo_negocio']; // El código único del Jefe actual

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
            $success_msg = "¡Categoría '$nombre_categoria' añadida con éxito!";
        } else {
            $error_msg = "Error al guardar la categoría: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Categoría | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
</head>
<body style="justify-content: center; display: flex; align-items: center; min-height: 100vh; padding: 20px;">

    <div class="card-form">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; background-color: rgba(139, 92, 246, 0.1); color: var(--primary-color); width: 60px; height: 60px; border-radius: 50%; line-height: 60px; font-size: 1.5rem; margin-bottom: 15px;">
                <i class="fa-solid fa-tags"></i>
            </div>
            <h2 style="margin: 0; font-size: 1.5rem; color: var(--text-main);">Alta de Categoría</h2>
            <p style="color: var(--text-muted); margin-top: 5px; font-size: 0.9rem;">Organiza mejor tu inventario</p>
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

        <form action="crear_categoria.php" method="POST">
            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría</label>
                <input type="text" id="nombre_categoria" name="nombre_categoria" required placeholder="Ej. Papelería, Snacks, Uso Interno..." autocomplete="off">
            </div>

            <button type="submit" class="btn-submit" style="margin-top: 10px;">
                <i class="fa-solid fa-plus"></i> Guardar Categoría
            </button>
        </form>

        <div style="text-align: center; margin-top: 25px;">
            <a href="listar.php" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none; font-weight: 500; transition: color 0.2s;"><i class="fa-solid fa-arrow-left"></i> Volver al Inventario</a>
        </div>
    </div>

    <script>
        if(localStorage.getItem('tema') === 'claro') { document.documentElement.setAttribute('data-theme', 'light'); }
    </script>
</body>
</html>