<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Verificar si el formulario fue enviado
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
        // ============================================
        // PASO 1: Guardar la reseña en PostgreSQL
        // ============================================
        $sql = "INSERT INTO reseñas (videojuego_id, usuario, calificacion, comentario) 
                VALUES (:videojuego_id, :usuario, :calificacion, :comentario)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':videojuego_id' => $videojuego_id,
            ':usuario' => $usuario,
            ':calificacion' => $calificacion,
            ':comentario' => $comentario
        ]);
        
        // ============================================
        // PASO 2: Obtener el título del juego
        // ============================================
        $stmt_titulo = $pdo->prepare("SELECT titulo FROM videojuegos WHERE id = ?");
        $stmt_titulo->execute([$videojuego_id]);
        $juego = $stmt_titulo->fetch(PDO::FETCH_ASSOC);
        $titulo_del_juego = $juego['titulo'] ?? 'Sin título';
        
        // ============================================
        // PASO 3: Enviar datos a la API de Python en Railway
        // ============================================
        
        // La URL de tu API en Railway
        $url_api = 'https://gamehub-api-production-243a.up.railway.app/api/videojuegos';
        
        // Datos que se enviarán a la API
        $datos = [
            'videojuego_id' => $videojuego_id,
            'titulo' => $titulo_del_juego,
            'calificacion' => $calificacion
        ];
        
        // Convertir datos a JSON
        $json_datos = json_encode($datos);
        
        // Inicializar cURL
        $ch = curl_init($url_api);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_datos);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_datos)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Esperar máximo 5 segundos
        
        // Ejecutar la petición a la API
        $respuesta_api = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error_curl = curl_error($ch);
        curl_close($ch);
        
        // ============================================
        // PASO 4: Verificar la respuesta de la API
        // ============================================
        
        $mensaje_api = "";
        
        if ($http_code == 201 || $http_code == 200) {
            // La API recibió los datos correctamente
            $respuesta = json_decode($respuesta_api, true);
            if (isset($respuesta['mensaje'])) {
                $mensaje_api = "✅ " . $respuesta['mensaje'];
            } else {
                $mensaje_api = "✅ Datos enviados a la API correctamente";
            }
        } else {
            // Hubo un error al enviar a la API
            $mensaje_api = "⚠️ La reseña se guardó en PostgreSQL, pero hubo un error al actualizar estadísticas.";
            if ($error_curl) {
                $mensaje_api .= " Error: " . $error_curl;
            } else {
                $mensaje_api .= " Código HTTP: " . $http_code;
            }
        }
        
        // ============================================
        // PASO 5: Mostrar mensaje al usuario
        // ============================================
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Reseña Guardada</title>
            <style>
                body { font-family: Arial; margin: 40px; }
                .exito { background: #d4edda; padding: 20px; border-radius: 10px; border-left: 5px solid #28a745; }
                .exito h2 { color: #155724; }
                .info { background: #e2e3e5; padding: 15px; border-radius: 5px; margin: 10px 0; }
                .info p { margin: 5px 0; }
                .boton { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; }
                .boton:hover { background: #45a049; }
                .advertencia { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 5px solid #ffc107; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="exito">
                <h2>✅ ¡Reseña registrada exitosamente!</h2>
                <div class="info">
                    <p><strong>Videojuego:</strong> <?php echo htmlspecialchars($titulo_del_juego); ?></p>
                    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario); ?></p>
                    <p><strong>Calificación:</strong> <?php echo str_repeat('⭐', $calificacion); ?> (<?php echo $calificacion; ?>/5)</p>
                    <p><strong>Comentario:</strong> <?php echo htmlspecialchars($comentario); ?></p>
                </div>
                
                <?php if ($http_code == 201 || $http_code == 200): ?>
                    <div class="info" style="background: #cce5ff; border-left-color: #007bff;">
                        <p><?php echo $mensaje_api; ?></p>
                        <p><small>📊 Las estadísticas en MongoDB han sido actualizadas.</small></p>
                    </div>
                <?php else: ?>
                    <div class="advertencia">
                        <p><?php echo $mensaje_api; ?></p>
                        <p><small>⚠️ La reseña está guardada en PostgreSQL, pero las estadísticas en MongoDB no se actualizaron.</small></p>
                    </div>
                <?php endif; ?>
                
                <br>
                <a href="index.php" class="boton">⬅ Volver al menú</a>
                <a href="catalogo.php" class="boton" style="background: #17a2b8;">📋 Ver catálogo</a>
            </div>
        </body>
        </html>
        <?php
        
    } catch(PDOException $e) {
        echo "❌ Error al guardar en PostgreSQL: " . $e->getMessage();
        echo "<br><a href='index.php'>⬅ Volver al menú</a>";
    }
} else {
    // Si alguien entra directamente a este archivo sin enviar el formulario
    header("Location: registrar_resena.php");
}
?>
