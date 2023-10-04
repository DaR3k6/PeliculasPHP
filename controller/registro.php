<?php
session_start();
if (isset($_POST["user"]) && isset($_POST["password"])) {
    $usuario = strtoupper($_POST["user"]);
    $pass = base64_encode($_POST["password"]);

    // Validar campos no vacíos
    if (empty($usuario) || empty($pass)) {
        $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
        header("Location:../login.php");
    } else {
        require_once '../model/mysql.php';
        $mysql = new Mysql();
        $mysql->conectar();

        $registro = $mysql->generarConsulta("INSERT INTO peliculas.usuarios 
        (peliculas.usuarios.user,peliculas.usuarios.pass)VALUES
        ( '" . $usuario . "','" . $pass . "')");

        $mysql->desconectar();

        if ($registro) {
            $_SESSION["mensajeExitoso"] = "Usuario registrado exitosamente";
            header("Location:../login.php");
        } else {
            $_SESSION["mensajeError"]  = "Error del registro ";
            header("Location:../login.php");
        }
    }
} else {
    $_SESSION["mensajeError"] = "Los campos están vacíos, por favor ingresa los datos correctamente.";
    header("Location:../login.php");
}
