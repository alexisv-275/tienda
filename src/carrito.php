<?php
session_start();
require_once '../config/functions.php';
// Restringe acceso 
if (!isset($_SESSION['usuario']) && !isset($_SESSION['contrasena'])) {
    header('Location:index.php');
    exit();
}

$idioma = obtenerIdiomaUsuario();
$t = cargarTextos('carrito', $idioma);
$labelVaciar = $idioma === 'en' ? 'Empty Cart' : 'Vaciar Carrito';
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
//procesar acciones del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar desde producto.php
    if (isset($_POST['producto_id'])) {
        $id = preg_replace('/\D/', '', (string)$_POST['producto_id']);
        $nombre = isset($_POST['producto_nombre']) ? trim((string)$_POST['producto_nombre']) : '';
        $precio = isset($_POST['producto_precio']) ? (float)preg_replace('/[^\d.\-]/', '', (string)$_POST['producto_precio']) : 0.0;
        $p = $id !== '' ? obtenerProductoPorId($id, $idioma) : null;
        if ($p) {
            $nombre = $p['nombre'] ?? $nombre;
            $precio = (float)($p['precio'] ? (float)$p['precio'] : $precio);
        }
        if ($id !== '' && $nombre !== '' && $precio >= 0) {
            $_SESSION['carrito'][$id] = [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio
            ];
        }
    }
      if (isset($_POST['vaciar'])) {
        $_SESSION['carrito'] = [];
    }
     header('Location: carrito.php');
    exit();
}

$items = array_values($_SESSION['carrito']);
//añadir imagenes a los items
foreach ($items as $k => $it) {
    $prod = obtenerProductoPorId($it['id'], $idioma);
    $items[$k]['imagen'] = $prod['imagen'] ?? '';
}
$total = 0.0;
foreach ($items as $it) {
    $total += (float)$it['precio'];
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($idioma); ?>">
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($t['titulo']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($t['titulo']); ?></h1>
    <p><?php echo $t['bienvenido_carrito']. "" . $_SESSION["usuario"]; ?></p>

    <?php if (empty($items)): ?>
        <p><?php echo htmlspecialchars($t['vacio']); ?></p>
    <?php else: ?>
        <!-- Cambiado: de UL a tabla con imagen a la derecha -->
        <table border="1" cellpadding="6" cellspacing="0">
            <tbody>
                <?php foreach ($items as $it): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($it['nombre']); ?>
                        - $<?php echo number_format((float)$it['precio'], 2); ?>
                    </td>
                    <td>
                        <?php if (!empty($it['imagen'])): ?>
                            <img
                                src="../assets/img/productos/<?php echo htmlspecialchars($it['imagen']); ?>"
                                alt="<?php echo htmlspecialchars($it['nombre']); ?>"
                                width="120" height="120">
                        <?php endif; ?>
                     </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p><?php echo htmlspecialchars($t['total']); ?>: $<?php echo number_format($total, 2); ?></p>

        <form method="post">
            <input type="hidden" name="vaciar" value="1">
            <button type="submit"><?php echo htmlspecialchars($labelVaciar); ?></button>
        </form>
        <p><button type="button" disabled><?php echo htmlspecialchars($t['finalizar']); ?></button></p>
    <?php endif; ?>

    <p><a href="panelprincipal.php"><?php echo htmlspecialchars($t['continuar']); ?></a></p>
    <p><a href="cerrarsesion.php"><?php echo htmlspecialchars($t['cerrar_sesion']); ?></a></p>
</body>
</html>