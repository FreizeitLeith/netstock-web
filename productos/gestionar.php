<?php
session_start();
require_once '../general/conexion.php';

if (!isset($_SESSION['rol'])) {
    header("Location: ../login.php");
    exit();
}

$id_producto = $_GET['id'];
$sql = "SELECT p.*, c.nombre_categoria FROM producto p 
        LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
        WHERE p.id_producto = $id_producto";
$resultado = $conn->query($sql);
$producto = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar <?php echo $producto['nombre_articulo']; ?> | NetStock</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/acceso.css">
</head>
<body style="background-color: var(--bg-color); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">

    <div class="auth-card" style="max-width: 450px; width: 100%;">
        <div class="auth-header">
            <h2 style="color: var(--primary-color); margin-bottom: 5px; font-size: 1.6rem;"><?php echo $producto['nombre_articulo']; ?></h2>
            <p class="auth-subtitle">Gestiona las existencias de este producto</p>
        </div>

        <div style="background: var(--bg-color); padding: 15px; border-radius: 8px; margin-bottom: 25px; text-align: center;">
            <span style="color: var(--text-muted); font-size: 0.85rem; display: block;">Stock Actual</span>
            <span style="font-size: 2.5rem; font-weight: 700; color: var(--text-main);"><?php echo $producto['cantidad_stock']; ?></span>
        </div>

        <form action="modificar_stock.php" method="POST">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

            <div class="input-group">
                <label for="cantidad_afectada">Cantidad a modificar</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-calculator input-icon"></i>
                    <input type="number" name="cantidad_afectada" min="1" required placeholder="Ej. 5">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                <button type="submit" name="accion" value="Entrada" style="background-color: #10b981; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fa-solid fa-arrow-down"></i> Entrada
                </button>
                <button type="submit" name="accion" value="Salida" style="background-color: #ef4444; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    <i class="fa-solid fa-arrow-up"></i> Salida
                </button>
            </div>
        </form>

        <?php if ($_SESSION['rol'] == 'Jefe' || $_SESSION['rol'] == 'Administrador'): ?>
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-around;">
            <a href="editar.php?id=<?php echo $producto['id_producto']; ?>" style="color: var(--primary-color); text-decoration: none; font-size: 0.9rem;"><i class="fa-solid fa-pen"></i> Editar</a>
            <a href="eliminar.php?id=<?php echo $producto['id_producto']; ?>" style="color: #ef4444; text-decoration: none; font-size: 0.9rem;" onclick="return confirm('¿Seguro?');"><i class="fa-solid fa-trash"></i> Eliminar</a>
        </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 25px;">
            <a href="listar.php" style="color: var(--text-muted); font-size: 0.9rem;"><i class="fa-solid fa-arrow-left"></i> Volver al inventario</a>
        </div>
    </div>

</body>
</html>