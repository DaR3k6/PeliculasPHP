<?php
session_start();

if (isset($_GET["idPeliculas"])) {
    require_once '../model/mysql.php';
    $mysql = new MySQL();
    $mysql->conectar();

    // CAPTURA EL ID DE LA PELÍCULA A ELIMINAR
    $idPelicula = $_GET["idPeliculas"];

    // ELIMINAR LAS RELACIONES DE GÉNERO PARA ESTA PELÍCULA
    $idGenero = $mysql->generarConsulta("DELETE FROM peliculas.peliculas_has_generos 
    WHERE peliculas_has_generos.Peliculas_idPeliculas = $idPelicula");

    // ELIMINAR LAS RELACIONES DE IDIOMA PARA ESTA PELÍCULA
    $idIdioma = $mysql->generarConsulta("DELETE FROM peliculas.idiomas_has_peliculas WHERE
    idiomas_has_peliculas.Peliculas_idPeliculas = $idPelicula");

    // ELIMINAR LA PELÍCULA
    $peliculaEliminar = $mysql->generarConsulta("DELETE FROM peliculas.peliculas WHERE idPeliculas = $idPelicula");

    if ($idGenero && $idIdioma && $peliculaEliminar) {
        $_SESSION["mensajeExitoso"] = "Eliminar película fue exitoso";
    } else {
        $_SESSION["mensajeError"] = "Error al eliminar película" . mysqli_error($mysql->getConexion());
    }
    $mysql->desconectar();
} else {
    $_SESSION["mensajeError"] = "No encontramos el idPelicula";
}
header("Location:../index.php");
