<?php
session_start();
require_once '../general/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $cantidad_afectada = $_POST['cantidad_afectada'];
    $tipo_accion = $_POST['accion']; // Viene como "Entrada" o "Salida" del formulario
    $id_usuario = $_SESSION['usuario_id']; 

            echo "Error al actualizar stock: " . $conn->error;
// 1. Inicializamos la variable vacía para evitar la advertencia de Intelephense
    $sql_update = "";

    // 2. Asignamos la consulta según el tipo de acción
    if ($tipo_accion == 'Entrada') {
        $sql_update = "UPDATE producto SET cantidad_stock = cantidad_stock + $cantidad_afectada WHERE id_producto = $id_producto";
    } else if ($tipo_accion == 'Salida') {
        $sql_update = "UPDATE producto SET cantidad_stock = cantidad_stock - $cantidad_afectada WHERE id_producto = $id_producto AND cantidad_stock >= $cantidad_afectada";
    }

    // 3. Verificamos que la variable tenga texto antes de tocar la base de datos
    if (!empty($sql_update)) {
        
        if ($conn->query($sql_update) === TRUE) {
            // Verifica si realmente se afectaron filas (previene que el stock quede en negativo)
            if ($conn->affected_rows > 0) {
                
                // 2. Registrar en la tabla "movimiento" (La fecha se genera sola con NOW())
                $sql_movimiento = "INSERT INTO movimiento (id_producto, id_usuario, tipo_accion, cantidad_afectada, fecha_hora) 
                                  VALUES ($id_producto, $id_usuario, '$tipo_accion', $cantidad_afectada, NOW())";
                $conn->query($sql_movimiento);

                header("Location: listar.php?msg=exito");
                exit();
            } else {
                echo "<script>alert('Error: Stock insuficiente para realizar esta salida.'); window.location.href='listar.php';</script>";
            }
        } else {
            echo "Error al actualizar stock: " . $conn->error;
        }

    } else {
        // Si alguien intentó hackear el formulario cambiando el valor del botón
        echo "<script>alert('Alerta de Seguridad: Acción no reconocida.'); window.location.href='listar.php';</script>";
    }
}
?>