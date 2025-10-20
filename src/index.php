<?php
$usuario = $contrasena = "";
$recordarme = false;

if (isset($_COOKIE["c_recordarme"]) && $_COOKIE["c_recordarme"]){
    $recordarme = true;
    $usuario = $_COOKIE["c_usuario"];
    $contrasena = $_COOKIE["c_contrasena"];
}else{
    if(isset($_COOKIE)){
        foreach($_COOKIE as $name => $value){
            setcookie($name, "", 1);
        }
    }
}
session_start();
session_destroy();
?>



<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de productos</title>
</head>
<body>
    <h1>Tienda Virtual</h1>
    <fieldset>
        <h2>LOGIN</h2>
        <form action="acceso.php" method="post">
            Usuario:
            <input type="text" name="usuario" required notnull placeholder="Ingrese su usuario" value="<?php echo $usuario?>">
            <br>
            Contraseña*:
            <input type="password" name="contrasena" required notnull placeholder="Ingrese su contraseña" value="<?php echo $contrasena?>">
            <br>
            <input type="checkbox" name="recordarme" <?php if($recordarme) echo "checked"; ?>> Recordarme
            <br>
            <input type="submit" value="Enviar">
        </form>
    </fieldset>
</body>
</html>