<?php
session_start();

if (
    isset($_POST['nombre']) && isset($_POST['descripcion']) &&
    isset($_POST['estado']) && isset($_POST['generos']) &&
    isset($_POST['idiomas'])
) {
    // Verificar si el usuario ha iniciado sesión
    if (isset($_SESSION['idUsuarios'])) {

        require_once '../model/mysql.php';

        // CAPTURO EL ID SI EL USUARIO INICIO SESIÓN
        $idUsuario = $_SESSION['idUsuarios'];

        // OBTENGO LOS DATOS DEL FORMULARIO
        $pelicula = strtoupper($_POST["nombre"]);
        $descripcion = strtoupper($_POST["descripcion"]);
        $estado = $_POST["estado"];
        $publicacion = $_POST["publicacion"];
        $generos = $_POST["generos"];
        $idiomas = $_POST["idiomas"];

        // VERIFICA LOS CAMPOS VACÍOS
        if (
            empty($pelicula) || empty($descripcion) || (!is_numeric($estado) && $estado !== 0)
            || empty($generos) || empty($idiomas)
        ) {
            $_SESSION["mensajeError"] = "Los campos no pueden estar vacíos.";
            header("Location:../index.php");
        } else {
            $mysql = new Mysql();
            $mysql->conectar();

            // INSERTO PELÍCULAS
            $insertoPelicula = $mysql->generarConsulta("INSERT INTO peliculas.peliculas (nom_peliculas, descripcion, estado, Fecha_publi,Usuarios_idUsuarios) VALUES ('$pelicula', '$descripcion', '$estado','$publicacion' ,'$idUsuario')");

            if ($insertoPelicula) {
                // CAPTURO EL ID DE LA PELÍCULA 
                $idPelicula = mysqli_insert_id($mysql->getConexion());

                // INSERTO LAS RELACIONES DE GÉNEROS
                foreach ($generos as $generoId) {
                    $insertHasPeliculas = $mysql->generarConsulta("INSERT INTO peliculas.peliculas_has_generos (Peliculas_idPeliculas, Generos_idGeneros) VALUES ('$idPelicula', '$generoId')");
                }

                // INSERTO LAS RELACIONES DE IDIOMAS
                foreach ($idiomas as $idiomaId) {
                    $insertHasIdiomas = $mysql->generarConsulta("INSERT INTO peliculas.idiomas_has_peliculas (Idiomas_idIdiomas, Peliculas_idPeliculas) VALUES ('$idiomaId', '$idPelicula')");
                }

                if ($insertHasPeliculas && $insertHasIdiomas) {
                    $_SESSION["mensajeExitoso"] = "Insertado correctamente la película";
                } else {
                    $_SESSION["mensajeError"] = "Error en la inserción de relaciones (peliculas_has_generos o idiomas_has_peliculas)";
                }
            } else {
                $_SESSION["mensajeError"] = "Error en la consulta para insertar la película";
            }

            // Desconectar la base de datos
            $mysql->desconectar();
            header("Location:../index.php");
        }
    } else {
        echo "Error en la consulta";
    }
} else {
    $_SESSION["mensajeError"] = "Ingrese bien los campos";
    header("Location:../index.php");
}
