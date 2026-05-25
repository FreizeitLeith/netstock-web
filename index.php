<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NetStock - Panel de Control</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f4f9; display: flex; flex-direction: column; align-items: center; padding: 50px; }
        .panel { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center; }
        .botones { margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        a { text-decoration: none; padding: 15px 25px; background: #007bff; color: white; border-radius: 5px; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="panel">
        <h1>Centro de Operaciones NetStock</h1>
        <p>Bienvenido al núcleo del sistema. Selecciona un módulo:</p>
        <div class="botones">
            <a href="productos/listar.php">Inventario</a>
            <a href="movimientos/entrada.php">Registrar Entrada</a>
            <a href="movimientos/salida.php">Registrar Salida</a>
            <a href="general/auditoria.php">Auditoría</a>
        </div>
    </div>
</body>
</html>