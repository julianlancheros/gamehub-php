<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Videojuego</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .formulario { max-width: 400px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>📝 Registrar Nuevo Videojuego</h1>
    
    <div class="formulario">
        <!-- El formulario envía los datos a guardar_juego.php -->
        <form action="guardar_juego.php" method="POST">
            <label>Título del juego:</label>
            <input type="text" name="titulo" required>
            
            <label>Género:</label>
            <input type="text" name="genero">
            
            <label>Fecha de lanzamiento:</label>
            <input type="date" name="fecha_lanzamiento">
            
            <button type="submit">Guardar Juego</button>
        </form>
    </div>
    
    <br>
    <a href="index.php">⬅ Volver al menú</a>
</body>
</html>