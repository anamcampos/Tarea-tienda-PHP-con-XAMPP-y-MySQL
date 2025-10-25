# Tarea-tienda-PHP-con-XAMPP-y-MySQL

# Una vez que se configuro XAMPP se crea una carpeta llamada "tienda" en htdocs de XAMPP
# Se crea un archivo llamado index.php con el siguiente codigo:
<?php 
$host = "127.0.0.1"; 
$user = "root"; $pass = ""; $db = "tienda"; 
$mysqli = new mysqli($host, $user, $pass, $db); 
if ($mysqli->connect_errno) { die("Error de conexión: " . $mysqli->connect_error); } 
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
  $nombre = trim($_POST['nombre'] ?? ''); $precio = floatval($_POST['precio'] ?? 0); 
  if ($nombre !== '' && $precio > 0) { 
    $stmt = $mysqli->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)"); 
    $stmt->bind_param("sd", $nombre, $precio); $stmt->execute(); $stmt->close(); 
  } 
  header("Location: index.php"); exit; 
} 
if (isset($_GET['toggle'])) { 
  $id = intval($_GET['toggle']); $mysqli->query("UPDATE productos SET adquirido = 1 - adquirido 
WHERE id = $id"); 
  header("Location: index.php"); exit; 
} 
$res = $mysqli->query("SELECT * FROM productos ORDER BY id DESC"); $items = $res->fetch_all(MYSQLI_ASSOC);; ?><!doctype html><html lang="es"><head><meta charset="utf-8"><title>Tienda (mini)</title> 
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<style>body{font-family:Arial; max-width:720px; margin:24px auto;} table{width:100%; border
:collapse;} 
td,th{border:1px solid #ddd; padding:8px;} form{margin:16px 0;} .ok{color:green; font-weight:bold;} 
.btn{padding:6px 10px; text-decoration:none; border:1px solid #555; border-radius:6px;}</style></head> 
 <body> 
   <h1>Inventario simple</h1> 
   <form method="post"> 
   <label>Nombre: <input name="nombre" required></label> 
   <label>Precio: <input name="precio" type="number" step="0.01" required></label> 
   <button>Agregar</button></form> 
   <table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th> 
     <th>Adquirido</th>                            <th>Acción</th></tr></thead><tbody> 
        <?php foreach($items as $p): ?><tr> 
          <td><?= htmlspecialchars($p['id']) ?></td> 
          <td><?= htmlspecialchars($p['nombre']) ?></td> 
          <td><?= number_format($p['precio'], 2) ?></td> 
          <td><?= $p['adquirido'] ? '<span class="ok">Sí</span>' : 'No' ?></td> 
          <td><a class="btn" href="?toggle=<?= intval($p['id']) ?>">Toggle</a></td> 
    </tr><?php endforeach; ?></tbody></table> 
  </body> 
</html>

 # Se crea la base de datos en PHPMYADMIN llamada tienda con el siguiente formato:
 CREATE DATABASE tienda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; 
USE tienda; 
CREATE TABLE productos ( 
   id INT AUTO_INCREMENT PRIMARY KEY, 
   nombre VARCHAR(120) NOT NULL, 
   precio DECIMAL(10,2) NOT NULL, 
   adquirido TINYINT(1) NOT NULL DEFAULT 0, 
   creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

# Se abre el explorador con http://localhost:8080/tienda/index.php