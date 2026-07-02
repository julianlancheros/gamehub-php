<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Reseña</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .formulario { max-width: 400px; }
        input, select, textarea, button { width: 100%; padding: 10px; margin: 5px 0; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <h1>⭐ Registrar Reseña</h1>
    
    <div class="formulario">
        <form action="guardar_resena.php" method="POST">
            <label>Selecciona el juego:</label>
            <select name="videojuego_id" required>
                <option value="">-- Elige un juego --</option>
                <?php
                // Consultar todos los juegos para mostrarlos en el select
                $sql = "SELECT id, titulo FROM videojuegos ORDER BY titulo";
                $stmt = $pdo->query($sql);
                
                while ($juego = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $juego['id'] . "'>" . $juego['titulo'] . "</option>";
                }
                ?>
            </select>
            
            <label>Tu nombre:</label>
            <input type="text" name="usuario" required>
            
            <label>Calificación (1-5):</label>
            <select name="calificacion" required>
                <option value="5">⭐⭐⭐⭐⭐ (Excelente)</option>
                <option value="4">⭐⭐⭐⭐ (Muy Bueno)</option>
                <option value="3">⭐⭐⭐ (Bueno)</option>
                <option value="2">⭐⭐ (Regular)</option>
                <option value="1">⭐ (Malo)</option>
            </select>
            
            <label>Comentario:</label>
            <textarea name="comentario" rows="4"></textarea>
            
            <button type="submit">Guardar Reseña</button>
        </form>
    </div>
    
    <br>
    <a href="index.php">⬅ Volver al menú</a>
</body>
</html>