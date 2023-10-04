<?php
session_start();

if (isset($_POST["genero"])) {
    require_once '../model/mysql.php';
    $genero =  strtoupper(trim($_POST["genero"]));

    if (empty($genero)) {
        $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
    } else {
        $mysql = new Mysql();
        $mysql->conectar();

        // Verificar si el género ya existe en la base de datos
        $consultaExistencia = $mysql->generarConsulta("SELECT idGeneros FROM peliculas.generos WHERE nom_genero = '$genero'");
        $filaExistencia = mysqli_fetch_assoc($consultaExistencia);

        if ($filaExistencia) {
            $_SESSION["mensajeError"] = "Este genero ya existe ";
        } else {
            // Si el género no existe, entonces lo insertamos
            $insertarGenero = $mysql->generarConsulta("INSERT INTO peliculas.generos (nom_genero) VALUES ('$genero')");

            if ($insertarGenero) {
                $_SESSION["mensajeExitoso"] = "Género insertado correctamente.";
            } else {
                $_SESSION["mensajeError"] = "Error en la inserción de género o idioma.";
            }
        }
        $mysql->desconectar();
    }
}

header("Location:../index.php");
