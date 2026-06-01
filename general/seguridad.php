<?php
// 1. Iniciamos la memoria temporal del servidor
session_start();

// 2. Llamamos al puente de la Base de Datos
require_once 'conexion.php'; 

// 3. Verificamos que los datos vengan realmente del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 4. Capturamos lo que el usuario escribió
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // 5. Le preguntamos a la Base de Datos si existe alguien con esos datos
    // Nota de ingeniero: Cambia 'usuarios' si tu tabla tiene otro nombre
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND password = '$password'";
    $resultado = $conn->query($sql);

    // 6. Si el resultado es mayor a 0, significa que encontró al usuario
    if ($resultado->num_rows > 0) {
        
        // Extraemos todos los datos de esa fila de la base de datos
        $usuario_db = $resultado->fetch_assoc();

        // 7. Guardamos los datos importantes en la sesión (el carnet del usuario)
        $_SESSION['usuario_id'] = $usuario_db['id_usuario']; // Asegúrate que el campo ID se llame así
        $_SESSION['nombre'] = $usuario_db['nombre'];
        $_SESSION['rol'] = $usuario_db['rol'];

        // 8. ¡Redirección exitosa al Panel!
        header("Location: panel.php");
        exit();

    } else {
        // 9. Si no existe, lo devolvemos al login con una alerta
        echo "<script>
                alert('Acceso Denegado: Correo o contraseña incorrectos.');
                window.location.href='../login.php';
              </script>";
    }
} else {
    // Si alguien intenta entrar a este archivo directamente por la URL, lo echamos
    header("Location: ../login.php");
    exit();
}
?>