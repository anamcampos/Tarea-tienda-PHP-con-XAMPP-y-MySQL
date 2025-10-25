<?php 
$host = "127.0.0.1"; //el url del sitio
$user = "root"; $pass = ""; $db = "tienda"; // informacion de acceso a la base de datos
$mysqli = new mysqli($host, $user, $pass, $db); //conexion a la base de datos
if ($mysqli->connect_errno) { die("Error de conexión: " . $mysqli->connect_error); } //validar la conexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //para insertar informacion
  $nombre = trim($_POST['nombre'] ?? ''); $precio = floatval($_POST['precio'] ?? 0); //validar los campos que no esten vacios
  if ($nombre !== '' && $precio > 0) { 
    $stmt = $mysqli->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)"); //se crea el statement para insertar los datos
    $stmt->bind_param("sd", $nombre, $precio); $stmt->execute(); $stmt->close(); //se ejecuta el query y se cierra
  } 
  header("Location: index.php"); exit; //redirige a la misma pagina para actualizar la vista
} 
if (isset($_GET['toggle'])) { //si se obtiene informacion de la bd, carga el "boton toggle"
  $id = intval($_GET['toggle']); $mysqli->query("UPDATE productos SET adquirido = 1 - adquirido
WHERE id = $id"); //se modifica el campo dentro de la base de datos usando UPDATE
  header("Location: index.php"); exit; //redirige a la misma pagina para actualizar la vista
} 
//crear el query para obtener los productos usando un select y ordenando por id de forma descendente para guardar los datos en un array llamado ITEMS
$res = $mysqli->query("SELECT * FROM productos ORDER BY id DESC"); $items = $res->fetch_all(MYSQLI_ASSOC);; ?><!doctype html><html lang="es"><head><meta charset="utf-8"><title>Tienda (mini)</title> 
<meta name="viewport" content="width=device-width, initial-scale=1"> 
<style>body{font-family:Arial; max-width:720px; margin:24px auto;} table{width:100%; border
:collapse;} 
td,th{border:1px solid #ddd; padding:8px;} form{margin:16px 0;} .ok{color:green; font-weight:bold;} 
.btn{padding:6px 10px; text-decoration:none; border:1px solid #555; border-radius:6px;}</style></head> 
 <body> 
  <!-- Se crea un formulario con los campos necesarios -->
   <h1>Inventario simple</h1>
   <form method="post"> 
   <label>Nombre: <input name="nombre" required></label> 
   <label>Precio: <input name="precio" type="number" step="0.01" required></label> 
   <button>Agregar</button></form> 
   <table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th> 
     <th>Adquirido</th>                            <th>Acción</th></tr></thead><tbody> 
        <?php foreach($items as $p): ?><tr> 
          <!-- Con un foreach se recorre el array con los datos obtenidos del select y se guardan en cada row de la tabla -->
          <td><?= htmlspecialchars($p['id']) ?></td> 
          <td><?= htmlspecialchars($p['nombre']) ?></td> 
          <td><?= number_format($p['precio'], 2) ?></td> 
          <td><?= $p['adquirido'] ? '<span class="ok">Sí</span>' : 'No' ?></td> 
          <td><a class="btn" href="?toggle=<?= intval($p['id']) ?>">Toggle</a></td> 
    </tr><?php endforeach; ?></tbody></table> 
  </body> 
</html>