<?php
session_start();
// Conectamos a la base de datos (subiendo un nivel a /general)
require_once '../general/conexion.php';

// 1. BLOQUEO DE SEGURIDAD (RBAC)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] == 'Trabajador') {
    echo "<script>alert('Acceso Denegado. Solo Jefes o Administradores pueden eliminar productos.'); window.location.href='listar.php';</script>";
    exit();
}

// Verificamos que se haya enviado un ID válido por la URL
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // 2. VALIDACIÓN DE INTEGRIDAD REFERENCIAL (Trazabilidad)
    // Buscamos si este producto ya tiene registros en la tabla "movimiento"
    $sql_check = "SELECT id_movimiento FROM movimiento WHERE id_producto = $id_producto";
    $resultado_check = $conn->query($sql_check);

    if ($resultado_check && $resultado_check->num_rows > 0) {
        // Tiene historial: Bloqueamos el borrado para proteger la auditoría inmutable
        echo "<script>
                alert('🛑 Error de Integridad: No puedes eliminar este artículo porque ya tiene registros en el Historial de Movimientos. Si ya no lo usas, te recomendamos realizar una salida para dejar su stock en 0.');
                window.location.href='listar.php';
              </script>";
    } else {
        // No tiene historial: Es seguro borrarlo de la base de datos
        $sql_delete = "DELETE FROM producto WHERE id_producto = $id_producto";

        if ($conn->query($sql_delete) === TRUE) {
            echo "<script>
                    alert('¡Artículo eliminado exitosamente del catálogo!');
                    window.location.href='listar.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al intentar eliminar el artículo: " . $conn->error . "');
                    window.location.href='listar.php';
                  </script>";
        }
    }
} else {
    // Si alguien entra a eliminar.php directamente sin seleccionar un producto, lo devolvemos
    header("Location: listar.php");
    exit();
}
?>