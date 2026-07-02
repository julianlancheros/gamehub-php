<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Catálogo de Juegos</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .juego { background: #f9f9f9; padding: 15px; margin: 15px 0; border-radius: 10px; border-left: 5px solid #4CAF50; }
        .reseña { background: #e8f5e9; padding: 10px; margin: 5px 0; border-radius: 5px; }
        .calificacion { font-weight: bold; }
        .estrellas { color: #FFD700; }
    </style>
</head>
<body>
    <h1>📋 Catálogo de Videojuegos</h1>
    
    <?php
    // Consulta para obtener todos los juegos con sus reseñas
    $sql = "SELECT v.*, 
                   r.id as reseña_id, 
                   r.usuario, 
                   r.calificacion, 
                   r.comentario,
                   r.fecha_registro
            FROM videojuegos v
            LEFT JOIN reseñas r ON v.id = r.videojuego_id
            ORDER BY v.titulo, r.fecha_registro DESC";
    
    $stmt = $pdo->query($sql);
    
    // Variable para controlar el juego actual
    $juego_actual = null;
    $tiene_reseñas = false;
    
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Si es un juego nuevo, mostrarlo
        if ($juego_actual != $fila['id']) {
            if ($juego_actual !== null) {
                // Cerrar el juego anterior si no tenía reseñas
                if (!$tiene_reseñas) {
                    echo "<p style='color: #999;'>📭 Sin reseñas aún</p>";
                }
                echo "</div>";
            }
            
            // Mostrar el nuevo juego
            echo "<div class='juego'>";
            echo "<h2>🎮 " . htmlspecialchars($fila['titulo']) . "</h2>";
            echo "<p><strong>Género:</strong> " . htmlspecialchars($fila['genero']) . "</p>";
            echo "<p><strong>Fecha:</strong> " . $fila['fecha_lanzamiento'] . "</p>";
            echo "<hr>";
            echo "<h3>📝 Reseñas:</h3>";
            
            $juego_actual = $fila['id'];
            $tiene_reseñas = false;
        }
        
        // Si tiene reseña, mostrarla
        if ($fila['reseña_id'] !== null) {
            $tiene_reseñas = true;
            // Convertir calificación a estrellas
            $estrellas = str_repeat('⭐', $fila['calificacion']);
            
            echo "<div class='reseña'>";
            echo "<p><strong>" . htmlspecialchars($fila['usuario']) . "</strong> ";
            echo "<span class='estrellas'>$estrellas</span> ";
            echo "(" . $fila['calificacion'] . "/5)</p>";
            echo "<p>" . htmlspecialchars($fila['comentario']) . "</p>";
            echo "<small>📅 " . $fila['fecha_registro'] . "</small>";
            echo "</div>";
        }
    }
    
    // Cerrar el último juego
    if ($juego_actual !== null) {
        if (!$tiene_reseñas) {
            echo "<p style='color: #999;'>📭 Sin reseñas aún</p>";
        }
        echo "</div>";
    }
    ?>
    
    <br>
    <a href="index.php">⬅ Volver al menú</a>
</body>
</html>