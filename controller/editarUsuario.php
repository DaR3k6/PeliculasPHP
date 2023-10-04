    <?php
    session_start();
    if (isset($_POST["usuario"]) &&  isset($_POST["password"])) {
        require_once '../model/mysql.php';

        //CAPTURO EL ID DEL USUARIO
        $idUsuario = $_SESSION["idUsuarios"];
        $nombre = strtoupper($_POST["usuario"]);
        $pass = base64_encode($_POST["password"]);

        if (
            empty($nombre) || empty($pass)
        ) {
            $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
            header("Location:../index.php");
        } else {

            $mysql = new Mysql();
            $mysql->conectar();

            $editarUsuario = $mysql->generarConsulta("UPDATE peliculas.usuarios
            SET user = '$nombre', pass = '$pass' WHERE idUsuarios = $idUsuario");

            if ($editarUsuario) {
                $_SESSION["mensajeExitoso"] = "Usuario editado";
                header("Location:../index.php");
            } else {
                $_SESSION["mensajeError"]  = "Error de editar usuario ";
                header("Location:../index.php");
            }
        }
        $mysql->desconectar();
    } else {
        $_SESSION["mensajeError"] = "Ingrese bien los campos";
        header("Location:../index.php");
    }
