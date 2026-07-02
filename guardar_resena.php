<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recibir datos del formulario
    $videojuego_id = $_POST['videojuego_id'];
    $usuario = $_POST['usuario'];
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];
    
    // Validaciones
    if (empty($videojuego_id) || empty($usuario) || empty($calificacion)) {
        die("❌ Error: Todos los campos obligatorios deben ser llenados");
    }
    
    try {
        // Guardar la reseña en PostgreSQL
        $sql = "INSERT INTO reseñas (videojuego_id, usuario, calificacion, comentario) 
                VALUES (:videojuego_id, :usuario, :calificacion, :comentario)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':videojuego_id' => $videojuego_id,
            ':usuario' => $usuario,
            ':calificacion' => $calificacion,
            ':comentario' => $comentario
        ]);
        
        echo "✅ ¡Reseña registrada exitosamente!";
        echo "<br><a href='index.php'>⬅ Volver al menú</a>";
        
        // ⚠️ MÁS ADELANTE: Aquí enviaremos los datos a la API de Python
        // Ahora solo guardamos en PostgreSQL
        
    } catch(PDOException $e) {
        echo "❌ Error al guardar: " . $e->getMessage();
    }
} else {
    header("Location: registrar_resena.php");
}
?>