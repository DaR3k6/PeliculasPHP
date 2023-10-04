<?php
session_start();

if (isset($_POST["idioma"])) {
    require_once '../model/mysql.php';
    $idioma = strtoupper(trim($_POST["idioma"]));

    if (empty($idioma)) {
        $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
        header("Location:../index.php");
    } else {
        $mysql = new Mysql();
        $mysql->conectar();

        // Verificar si el idioma ya existe en la base de datos
        $consultaExistencia = $mysql->generarConsulta("SELECT idIdiomas FROM peliculas.idiomas WHERE nom_idioma = '$idioma'");
        $filaExistencia = mysqli_fetch_assoc($consultaExistencia);

        if ($filaExistencia) {
            $_SESSION["mensajeError"] = "Este idioma ya existe.";
        } else {
            $insertarIdioma = $mysql->generarConsulta("INSERT INTO peliculas.idiomas (nom_idioma)
             VALUES ('" . strtoupper($idioma) . "')");

            if ($insertarIdioma) {
                $_SESSION["mensajeExitoso"] = "Insertado correctamente idioma.";
            } else {
                $_SESSION["mensajeError"] = "Error en la inserción idioma.";
            }
        }

        $mysql->desconectar();
    }
}

header("Location:../index.php");
