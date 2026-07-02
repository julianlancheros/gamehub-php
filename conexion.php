<?php
// Datos de conexión a PostgreSQL en Render
$host = "dpg-d92ugeernols73fump20-a.oregon-postgres.render.com";  
$port = "5432";
$dbname = "gamehub_db_351r";                     
$user = "gamehub_db_351r_user";                            
$password = "3PKFJQyOunbOGhlxlKEm18ifHdIeHH0B";                     

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión exitosa a PostgreSQL";
} catch(PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>