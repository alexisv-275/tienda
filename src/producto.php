<?php
    session_start();
    require_once '../config/functions.php';
    
    //Validar sesion
    $_SESSION["nombre"] = "usuario1";
    $_SESSION["clave"] = "clave1";
    
    if(!isset($_SESSION["nombre"]) && !isset($_SESSION["clave"])){
        header("Location:index.php");
        exit();
    }
    
    // Obtener idioma actual
    $idioma = obtenerIdiomaUsuario();
    
    // Cargar textos
    $t = cargarTextos('producto', $idioma);
    
    // Obtener ID del producto desde URL
    $producto_id = isset($_GET["id"]) ? trim($_GET["id"]) : null;
    
    // Validar que se recibió un ID
    if($producto_id === null || $producto_id === ""){
        header("Location: panelprincipal.php");
        exit();
    }

    // Buscar el producto
    $producto_encontrado = obtenerProductoPorId($producto_id, $idioma);

    // Si no se encuentra el producto, redirigir
    if($producto_encontrado === null){
        header("Location: panelprincipal.php");
        exit();
    }

?>

<html lang="<?php echo $idioma; ?>">
<head>
    <title><?php echo htmlspecialchars($producto_encontrado["nombre"]); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($producto_encontrado["nombre"]); ?></h1>

    <!-- Imagen del producto -->
    <?php if(isset($producto_encontrado["imagen"]) && !empty($producto_encontrado["imagen"])): ?>
        <div style="margin: 20px 0;">
            <img src="../assets/img/productos/<?php echo htmlspecialchars($producto_encontrado["imagen"]); ?>" 
                 alt="<?php echo htmlspecialchars($producto_encontrado["nombre"]); ?>"
                 style="width: 400px; height: 400px; object-fit: cover;">
        </div>
    <?php endif; ?>

    <div><strong><?php echo $t["id_producto"]; ?>:</strong> <?php echo htmlspecialchars($producto_encontrado["id"]); ?></div>
    
    <div><strong><?php echo $t["precio"]; ?>:</strong> $<?php echo htmlspecialchars($producto_encontrado["precio"]); ?></div>
    
    <p><strong><?php echo $t["descripcion"]; ?>:</strong><br>
    <?php echo htmlspecialchars($producto_encontrado["descripcion"]); ?></p>

    <form action="carrito.php" method="post">
        <!-- Enviar datos del producto al carrito -->
        <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($producto_encontrado["id"]); ?>">
        <input type="hidden" name="producto_nombre" value="<?php echo htmlspecialchars($producto_encontrado["nombre"]); ?>">
        <input type="hidden" name="producto_precio" value="<?php echo htmlspecialchars($producto_encontrado["precio"]); ?>">
        
        <input type="submit" value="<?php echo $t["agregar_carrito"]; ?>">
    </form>

    <br>
    <a href="panelprincipal.php"><?php echo $t["volver"]; ?></a>
    <br><br>
    <a href="cerrarsesion.php"><?php echo $t["cerrar_sesion"]; ?></a>
</body> 
</html>