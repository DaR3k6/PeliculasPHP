<?php

require_once './model/mysql.php';

$mysql = new MYSQL();
$mysql->conectar();

$consultaGeneros = $mysql->generarConsulta("SELECT DISTINCT idGeneros, nom_genero FROM peliculas.generos ;");
$consultaIdiomas = $mysql->generarConsulta("SELECT DISTINCT idIdiomas, nom_idioma FROM peliculas.idiomas;");

$consultaTraerId = $mysql->generarConsulta("SELECT idPeliculas,nom_peliculas,descripcion,estado FROM peliculas.peliculas");

$generosDisponibles = array();
$idiomasDisponibles = array();
$mysql->desconectar();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Enlace al archivo CSS de Bootstrap (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="./img/logo.png" />
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="./assets/css/peliculas.css">
</head>

<body>

    <!-- MODAL EDITAR  PELICULA -->
    <?php while ($fila = mysqli_fetch_assoc($consultaTraerId)) { ?>
        <div class="modal fade" id="editar<?php echo $fila["idPeliculas"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-light" id="exampleModalLabel">Editar Pelicula</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="GET" action="./controller/editar.php">

                            <div class="mb-3">
                                <input type="hidden" name="idPeliculas" value="<?php echo $fila["idPeliculas"]  ?>">
                                <label for="recipient-name" class="col-form-label text-light">Nombre:</label>
                                <input type="text" class="form-control" id="recipient-name" value="<?php echo $fila["nom_peliculas"] ?>" name="nombre">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label text-light">Descripcion:</label>
                                <textarea class="form-control" id="message-text" name="descripcion"><?php echo $fila["descripcion"] ?>
                            </textarea>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="col-form-label text-light">Estado:</label>
                                <select class="form-select" aria-label="Default select example" name="estado">
                                    <option selected>Selecciona el estado</option>
                                    <option value="0">Inactivo</option>
                                    <option value="1">Activo</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="estado" class="col-form-label text-light">Genero:</label>
                                <?php


                                while ($rowGenero = mysqli_fetch_array($consultaGeneros)) {
                                    $generosDisponibles[] = $rowGenero;
                                }
                                if (!empty($generosDisponibles)) {
                                    foreach ($generosDisponibles as $genero) {
                                ?>
                                        <div class="form-check text-light">
                                            <input class="form-check-input" type="checkbox" name="generos[]" value="<?php echo $genero['idGeneros']; ?>">
                                            <label class="form-check-label"><?php echo $genero['nom_genero']; ?></label>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No hay géneros disponibles.";
                                }
                                ?>
                            </div>



                            <div class="mb-3">
                                <label for="estado" class="col-form-label text-light">Idioma:</label>
                                <?php

                                while ($rowIdioma = mysqli_fetch_array($consultaIdiomas)) {
                                    $idiomasDisponibles[] = $rowIdioma;
                                }

                                if (!empty($idiomasDisponibles)) {
                                    foreach ($idiomasDisponibles as $idioma) {
                                ?>
                                        <div class="form-check text-light">
                                            <input class="form-check-input" type="checkbox" name="idiomas[]" value="<?php echo $idioma['idIdiomas']; ?>">
                                            <label class="form-check-label"><?php echo $idioma['nom_idioma']; ?></label>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "No hay idiomas disponibles.";
                                }
                                ?>
                            </div>


                            <div class="mb-3">
                                <label for="fechaPublicacion" class="col-form-label text-light">Fecha Publicada:</label>
                                <input class="form-control" type="date" name="fechaPublic" value="<?php echo $row["Fecha_publi"] ?>" />
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Guardar</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    <?php } ?>
    <!-- Enlace al archivo JavaScript de Bootstrap (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!--- MENSAJE EXITOSO -->
    <script>
        <?php
        if (isset($_SESSION["mensajeExitoso"])) {
            echo  "Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '" . $_SESSION["mensajeExitoso"] . "'
        });";
            unset($_SESSION["mensajeExitoso"]);
        }
        ?>
    </script>
    <!--- MENSAJE DE ERROR -->
    <script>
        <?php
        if (isset($_SESSION["mensajeError"])) {
            echo  "Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '" . $_SESSION["mensajeError"] . "'
            });";
            unset($_SESSION["mensajeError"]);
        }
        ?>
    </script>



</body>

</html>