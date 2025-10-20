<?php
/**
 * Obtiene el idioma del usuario desde cookies o establece el predeterminado
 * @return string El código del idioma ('es' o 'en')
 */
function obtenerIdiomaUsuario() {
    if(isset($_COOKIE["idioma_usuario"]) && in_array($_COOKIE["idioma_usuario"], ["es", "en"])){
        return $_COOKIE["idioma_usuario"];
    }
    
    // Idioma por defecto
    $idioma_default = "es";
    setcookie("idioma_usuario", $idioma_default, time() + (30*24*60*60), "/");
    return $idioma_default;
}

/**
 * Establece el idioma del usuario
 * @param string $idioma Código del idioma ('es' o 'en')
 * @return bool True si se estableció correctamente
 */
function establecerIdiomaUsuario($idioma) {
    if(in_array($idioma, ["es", "en"])){
        setcookie("idioma_usuario", $idioma, time() + (30*24*60*60), "/");
        return true;
    }
    return false;
}

/**
 * Obtiene la ruta del archivo de productos según el idioma
 * @param string $idioma Código del idioma
 * @return string Ruta al archivo de productos
 */
function obtenerArchivoProductos($idioma) {
    $archivos = [
        "es" => "../assets/products/categorias_es.txt",
        "en" => "../assets/products/categorias_en.txt"
    ];
    
    return $archivos[$idioma] ?? $archivos["es"];
}

/**
 * Lee y parsea todos los productos desde el archivo
 * @param string $idioma Código del idioma
 * @return array Array de productos con su información
 */
function obtenerTodosLosProductos($idioma) {
    $archivo = obtenerArchivoProductos($idioma);
    $productos = [];
    
    if(file_exists($archivo)){
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach($lineas as $linea){
            $partes = explode("|", $linea);
            if(count($partes) >= 5){
                $productos[] = [
                    "id" => trim($partes[0]),
                    "nombre" => trim($partes[1]),
                    "precio" => trim($partes[2]),
                    "descripcion" => trim($partes[3]),
                    "imagen" => trim($partes[4])
                ];
            }
        }
    }
    
    return $productos;
}

/**
 * Busca un producto específico por ID
 * @param string $id ID del producto a buscar
 * @param string $idioma Código del idioma
 * @return array|null Información del producto o null si no se encuentra
 */
function obtenerProductoPorId($id, $idioma) {
    $productos = obtenerTodosLosProductos($idioma);
    
    foreach($productos as $producto){
        if($producto["id"] === $id){
            return $producto;
        }
    }
    
    return null;
}

/**
 * Carga los textos de traducción para una página específica
 * @param string $pagina Nombre de la página ('panelprincipal', 'producto', 'carrito')
 * @param string $idioma Código del idioma
 * @return array Array con los textos traducidos
 */
function cargarTextos($pagina, $idioma) {
    $traducciones = [
        'panelprincipal' => [
            "es" => [
                "bienvenido" => "Bienvenido",
                "panel" => "Panel principal",
                "idioma" => "Configurar Idioma",
                'productos' => 'Lista de productos',
                "cerrar_sesion" => "Cerrar Sesión",
                "no_productos" => "No hay productos disponibles",
                'Ir_al_carrito' => "Ir al carrito"
            ],
            "en" => [
                'bienvenido' => 'Welcome',
                'panel' => 'Main Panel',
                'idioma' => 'Set Up Language',
                'productos' => 'Product List',
                'cerrar_sesion' => 'Log Out',
                'no_productos' => 'No products available',
                'Ir_al_carrito' => 'Go to Cart'
            ]
        ],
        'producto' => [
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
            ]
        ],
        'carrito' => [
            "es" => [
                "titulo" => "Carrito de Compras",
                "vacio" => "El carrito está vacío",
                "total" => "Total",
                "continuar" => "Continuar comprando",
                "finalizar" => "Finalizar compra",
                "cerrar_sesion" => "Cerrar sesión",
                "bienvenido_carrito" => "Bienvenido al carrito "
            ],
            "en" => [
                "titulo" => "Shopping Cart",
                "vacio" => "Cart is empty",
                "total" => "Total",
                "continuar" => "Continue shopping",
                "finalizar" => "Checkout",
                "cerrar_sesion" => "Log out",
                "bienvenido_carrito" => "Welcome to the cart "
            ]
        ]
    ];
    
    return $traducciones[$pagina][$idioma] ?? $traducciones[$pagina]["es"];
}
?>
