<?php
    session_start();
    require_once '../config/functions.php';
    
    //validar sesion
    $_SESSION["nombre"] = "usuario1";
    $_SESSION["clave"] = "clave1";

    if(!isset($_SESSION["nombre"]) && !isset($_SESSION["clave"])){
        header("Location: index.php");
        exit();
    }
    
    // Procesar cambio de idioma
    if(isset($_GET["lang"])){
        establecerIdiomaUsuario($_GET["lang"]);
        header("Location: panelprincipal.php");
        exit();
    }
    
    // Obtener idioma actual
    $idioma = obtenerIdiomaUsuario();
    
    // Cargar productos y textos
    $productos = obtenerTodosLosProductos($idioma);
    $t = cargarTextos('panelprincipal', $idioma);
?>


<html lang="<?php echo $idioma; ?>">
<head>
    <title><?php echo $t["panel"]; ?></title>
</head>
<body>
    <div>
        <div>
            <h1><?php echo $t["panel"]; ?></h1>
            <p> <?php echo $t["bienvenido"] . ": " . $_SESSION["nombre"]; ?> </p>
            <div> <?php echo $t["idioma"]; ?>
            <br>
            <a href="?lang=es">ES (ESPANOL)</a>
            /
            <a href="?lang=en">EN (ENGLISH)</a>
            </div>
        </div>
        <br>
        <a href="cerrarsesion.php"> <?php echo $t["cerrar_sesion"]; ?></a>
        <br>
        <h2> <?php echo($t["productos"]) ?></h2>
        <?php if(count($productos)>0): ?>
            <ul>
                <?php foreach($productos as $producto): ?>
                <li>
                    <a href="producto.php?id=<?php echo urlencode($producto["id"]); ?>">
                        <?php echo htmlspecialchars($producto["nombre"]); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p><?php echo($t["no_productos"]); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>