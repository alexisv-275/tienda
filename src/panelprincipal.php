<?php
    session_start();
    //validar sesion
    $_SESSION["nombre"] = "usuario1";
    $_SESSION["clave"] = "clave1";

    if(!isset($_SESSION["nombre"]) && !isset($_SESSION["clave"])){
        header("Location: index.php");
    }
    // procesar cambio de idioma
    if(isset($_GET["lang"]) && in_array($_GET["lang"], ["es", "en"])){
        setCookie("idioma_usuario", $_GET["lang"], time()+(30*24*60*60), "/");
        
        $idioma = $_GET["lang"];
        
        header("Location: panelprincipal.php");
    }
    
    if(isset($_COOKIE["idioma_usuario"])){
        $idioma = $_COOKIE["idioma_usuario"];
    } else {
        // default
        $idioma = "es";
        setCookie("idioma_usuario", $idioma, time() + (30*24*60*60), "/");
    }

    $archivos_productos = [
        "es" => "../assets/products/categorias_es.txt",
        "en" => "../assets/products/categorias_en.txt"
    ];

    $titulos_pagina = [
        "es" => "Lista de productos",
        "en" => "Product list"
    ];

    $archivo_producto = $archivos_productos[$idioma] ?? $archivos_productos["es"];
    $titulo_pagina = $titulos_pagina[$idioma] ?? $titulos_pagina["es"];

    $textos = [
        "es" => [
            "bienvenido" => "Bievenido",
            "panel" => "Panel principal",
            "idioma" => "Configurar Idioma",
            'productos' => 'Lista de productos',
            "cerrar_sesion" => "Cerrar Sesion",
            "no_productos" => "No hay productos disponibles"
        ],

        "en" => [
            'bienvenido' => 'Welcome',
            'panel' => 'Main Panel',
            'idioma' => 'Set Up Language',
            'productos' => 'Product List',
            'cerrar_sesion' => 'Log Out',
            'no_productos' => 'No products available'
        ]
        ];

    $productos = [];
    if(file_exists($archivo_producto)){
        $lineas = file($archivo_producto, FILE_IGNORE_NEW_LINES | 
        FILE_SKIP_EMPTY_LINES);
        foreach($lineas as $linea){
            $partes = explode("|", $linea);
            $productos[] = [
                "id" => trim($partes[0]),
                "nombre" => trim($partes[1])
            ];
       }
    }

    $t = $textos[$idioma] ?? $textos["es"];
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