<?php
// Incluir la conexión a la base de datos
include 'conexion.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>GameHub - Inicio</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .menu { background: #f0f0f0; padding: 20px; border-radius: 10px; }
        .menu a { display: block; margin: 10px 0; padding: 10px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; width: 200px; }
        .menu a:hover { background: #45a049; }
    </style>
</head>
<body>
    <h1>🎮 GameHub - Portal de Videojuegos</h1>
    
    <div class="menu">
        <h2>Menú Principal</h2>
        <a href="registrar_juego.php">📝 Registrar Nuevo Juego</a>
        <a href="registrar_resena.php">⭐ Registrar Reseña</a>
        <a href="catalogo.php">📋 Ver Catálogo de Juegos</a>
    </div>
</body>
</html>