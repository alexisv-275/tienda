<?php
    session_start();
    
    //Validar sesion
    $_SESSION["nombre"] = "usuario1";
    $_SESSION["clave"] = "clave1";
    
    if(!isset($_SESSION["nombre"]) && !isset($_SESSION["clave"])){
        header("Location:index.php");
    }
    
    //Cargar archivos de productos segun idioma
    $archivos_productos = [
        "es" => "../assets/products/categorias_es.txt",
        "en" => "../assets/products/categorias_en.txt"
    ];

    //Agregar textos mulidioma para la pagina de producto
    $textos = [
    "es" => [
        "titulo" => "Detalles del Producto",
        "id_producto" => "ID del Producto",
        "precio" => "Precio",
        "descripcion" => "Descripción",
        "agregar_carrito" => "Agregar al carrito",
        "volver" => "Volver al catálogo",
        "cerrar_sesion" => "Cerrar sesión"
    ],
    "en" => [
        "titulo" => "Product Details",
        "id_producto" => "Product ID",
        "precio" => "Price",
        "descripcion" => "Description",
        "agregar_carrito" => "Add to cart",
        "volver" => "Back to catalog",
        "cerrar_sesion" => "Log out"
    ]];

    
    //1. Obtener preferencia de idioma
    if(isset($_COOKIE["idioma_usuario"])){
        $idioma = $_COOKIE["idioma_usuario"];
    } else {
        // default
        $idioma = "es";
        setCookie("idioma_usuario", $idioma, time() + (30*24*60*60), "/");
    }
    
    $t = $textos[$idioma] ?? $textos["es"];

    // 2. Obtener ID del producto desde URL
    $producto_id = isset($_GET["id"]) ? trim($_GET["id"]) : null;
    
    // Validar que se recibió un ID
    if($producto_id === null || $producto_id === ""){
        // Redirigir al panel si no hay ID
        header("Location: panelprincipal.php");
        // exit();
    }

    //3. Leer archivo de productos
    $archivo_producto = $archivos_productos[$idioma] ?? $archivos_productos["es"];
    $producto_encontrado = null;

    if(file_exists($archivo_producto)){
        $lineas = file($archivo_producto, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach($lineas as $linea){
            $partes = explode("|", $linea);
            
            // Verificar que la línea tenga el formato correcto
            if(count($partes) >= 5){
                $id = trim($partes[0]);
                
                // Buscar el producto por ID
                if($id === $producto_id){
                    $producto_encontrado = [
                        "id" => $id,
                        "nombre" => trim($partes[1]),
                        "precio" => trim($partes[2]),
                        "descripcion" => trim($partes[3]),
                        "imagen" => trim($partes[4])
                    ];
                    break; // Si se encuentra, salir del bucle
                }
            }
        }
    }

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
                 style="max-width: 400px; height: auto; border: 1px solid #ddd; border-radius: 8px;">
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