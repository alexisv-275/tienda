<?php
session_start();
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $recordarme = isset($_POST["recordarme"]) ? $_POST["recordarme"] : false;

    if($recordarme){
        setcookie("c_usuario", $usuario, 0);
        setcookie("c_contrasena", $contrasena, 0);
        setcookie("c_recordarme", $recordarme, 0);
    }
    else{
        if(isset($_COOKIE)){
            foreach($_COOKIE as $name => $value){
                setcookie($name, "", 1);
            }
        }
    }

    $_SESSION["usuario"] = $_POST["usuario"];
    $_SESSION["contrasena"] = $_POST["contrasena"];


    if(!isset($_SESSION["usuario"]) && !isset($_SESSION["contrasena"])){
        header("Location: index.php");
        exit();
    }

    header("Location:panelprincipal.php");
?>
