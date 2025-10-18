<?php

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
        <form action="panelprincipal.php" method="post">
            Usuario:
            <input type="text" name="usuario" required notnull> 
            <br>
            Contraseña:
            <input type="password" name="contrasena" required notnull>
            <br>
            <input type="checkbox" name="recordarme"> Recordar datos
            <br>
            <input type="submit" value="Enviar">
        </form>
    </fieldset>
</body>
</html>