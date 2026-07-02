<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recibir los datos del formulario
    $titulo = $_POST['titulo'];
    $genero = $_POST['genero'];
    $fecha = $_POST['fecha_lanzamiento'];
    
    // Validar que el título no esté vacío
    if (empty($titulo)) {
        die("❌ Error: El título es obligatorio");
    }
    
    try {
        // Preparar la consulta SQL para insertar el juego
        $sql = "INSERT INTO videojuegos (titulo, genero, fecha_lanzamiento) 
                VALUES (:titulo, :genero, :fecha)";
        
        $stmt = $pdo->prepare($sql);
        
        // Ejecutar la consulta con los datos
        $stmt->execute([
            ':titulo' => $titulo,
            ':genero' => $genero,
            ':fecha' => $fecha
        ]);
        
        // Mensaje de éxito
        echo "✅ ¡Juego registrado exitosamente!";
        echo "<br><a href='index.php'>⬅ Volver al menú</a>";
        
    } catch(PDOException $e) {
        echo "❌ Error al guardar: " . $e->getMessage();
    }
} else {
    // Si alguien entra directamente a este archivo sin enviar el formulario
    header("Location: registrar_juego.php");
}
?>