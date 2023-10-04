<?php
session_start();
if (
    isset($_POST["user"]) && !empty($_POST["user"]) &&
    isset($_POST["password"]) && !empty($_POST["password"])
) {
    require_once '../model/mysql.php';
    $usuario = $_POST["user"];
    $pass = base64_encode($_POST["password"]);

    if (empty($usuario) && empty($pass)) {
        $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
        header("Location:../login.php");
    } else {
        $mysql = new Mysql();
        $mysql->conectar();

        $login = $mysql->generarConsulta("SELECT peliculas.usuarios.idUsuarios,
         peliculas.usuarios.user,peliculas.usuarios.pass FROM peliculas.usuarios
        WHERE peliculas.usuarios.user ='" . $usuario . "' && 
         peliculas.usuarios.pass ='" . $pass . "'   ");


        if ($login) {

            if (mysqli_num_rows($login) > 0) {

                $fila = mysqli_fetch_assoc($login);

                require_once("../model/usuario.php");

                //TOMO EL ID DEL USUARIO
                $id = $fila["idUsuarios"];
                $nombre = $fila["user"];

                //CUMPLE CON ESTA CONDICON 
                $_SESSION["idUsuarios"] = $id;
                $_SESSION["user"] = $nombre;

                $_SESSION["acceso"] = true;

                $_SESSION["mensajeExitoso"] = "Inicio Sesion Correctamente";
                header("Location:../index.php");
            } else {
                $_SESSION["mensajeError"] = "Error de Inicio de Sesion";
                header("Location:../login.php");
            }
        } else {
            $_SESSION["mensajeError"] = "Error en la consulta SQL";
            header("Location:../login.php");
        }
    }
    $mysql->desconectar();
} else {
    $_SESSION["mensajeError"] = "Ingrese bien los campos del usuario o la contraseña";
    header("Location:../login.php");
}
