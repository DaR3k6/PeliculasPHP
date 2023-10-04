<?php
session_start();

// Verificamos si se ha proporcionado un idPeliculas en la URL
if (isset($_GET["idPeliculas"])) {
    require_once '../model/mysql.php';
    $mysql = new MySQL();
    $mysql->conectar();

    // Recuperamos el idPeliculas de la URL
    $idPelicula = $_GET["idPeliculas"];

    // Recuperamos los otros valores del formulario usando $_POST, ya que el formulario envía datos mediante POST
    $nombre = strtoupper($_GET["nombre"]);
    $descripcion = strtoupper($_GET["descripcion"]);
    $fechaPublic = $_GET["fechaPublic"];
    $estado = $_GET["estado"];

    // ACTUALIZAR LA PELÍCULA
    $actualizarPelicula = $mysql->generarConsulta("UPDATE peliculas.peliculas
        SET nom_peliculas = '$nombre', descripcion = '$descripcion', estado = '$estado',Fecha_publi = '$fechaPublic'
        WHERE idPeliculas = $idPelicula");

    // ACTUALIZAR LAS RELACIONES DE GÉNERO
    if (isset($_POST["generos"])) {
        $generos = $_POST["generos"];
        // ELIMINAR LAS RELACIONES EXISTENTES
        $eliminarRelacion = $mysql->generarConsulta("DELETE FROM peliculas.peliculas_has_generos WHERE Peliculas_idPeliculas = $idPelicula");

        // INSERTAR LAS NUEVAS RELACIONES
        foreach ($generos as $idGenero) {
            $insertarGenero = $mysql->generarConsulta("INSERT INTO peliculas.peliculas_has_generos (Peliculas_idPeliculas, Generos_idGeneros) VALUES ($idPelicula, $idGenero)");
        }
    }

    // ACTUALIZAR LAS RELACIONES DE IDIOMA
    if (isset($_POST["idiomas"])) {
        $idiomas = $_POST["idiomas"];
        // ELIMINAR LAS RELACIONES EXISTENTES
        $eliminarIdioma = $mysql->generarConsulta("DELETE FROM peliculas.idiomas_has_peliculas WHERE Peliculas_idPeliculas = $idPelicula");

        // INSERTAR LAS NUEVAS RELACIONES
        foreach ($idiomas as $idIdioma) {
            $insertarIdioma = $mysql->generarConsulta("INSERT INTO peliculas.idiomas_has_peliculas (Idiomas_idIdiomas, Peliculas_idPeliculas) VALUES ($idIdioma, $idPelicula)");
        }
    }

    if ($actualizarPelicula) {
        $_SESSION["mensajeExitoso"] = "Película actualizada con éxito.";
    } else {
        $_SESSION["mensajeError"] = "Error al actualizar la película.";
    }
} else {
    $_SESSION["mensajeError"] = "No se proporcionaron datos válidos para actualizar la película.";
}
header("Location:../index.php");
