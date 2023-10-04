<?php
session_start();

require_once "./model/mysql.php";
$mysql = new MySQL();
$mysql->conectar();

$consulta = $mysql->generarConsulta("SELECT idPeliculas,nom_peliculas,descripcion,estado,Fecha_publi FROM peliculas.peliculas;");
$consultaGeneros = $mysql->generarConsulta("SELECT DISTINCT idGeneros, nom_genero FROM peliculas.generos;");
$consultaIdiomas = $mysql->generarConsulta("SELECT DISTINCT idIdiomas, nom_idioma FROM peliculas.idiomas;");
//TRAIGO EL ID DEL USUARIO 
$idUsuario = $_SESSION["idUsuarios"];
$consultaUsuario = $mysql->generarConsulta("SELECT user, pass FROM peliculas.usuarios WHERE idUsuarios = $idUsuario;");





$generosDisponibles = array();
$idiomasDisponibles = array();
$mysql->desconectar();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peliculas</title>
    <!-- Enlace al archivo CSS de Bootstrap (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="./img/logo.png" />
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="./assets/css/peliculas.css">
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="./assets/img/logo.svg" alt="Logo" width="50" height="50" class="logo" />
                </a>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link fs-2" href="#">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-2" href="#">Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-2" href="#">Peliculas</a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>



                    <div class="dropdown">
                        <?php
                        if (isset($_SESSION["user"])  != null && $_SESSION["acceso"] == true) {
                            while ($row = mysqli_fetch_assoc($consultaUsuario)) {
                                $_SESSION["user"] = $row["user"];
                                $_SESSION["pass"] = $row["pass"];;
                            }
                        ?>

                            <button class="btn btn-secondary dropdown-toggle btn btn-danger btn-1 mt-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php
                                // Mostrar el nombre de usuario si está autenticado
                                echo $_SESSION["user"];
                                ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#usuario">Editar</a></li>
                                <li><a class="dropdown-item" href="./controller/cerrarSesion.php">Cerrar</a></li>
                            </ul>

                        <?php
                        } else {
                            header("Location: login.php");
                            exit();
                        }
                        ?>
                    </div>

                    <a data-bs-toggle="modal" data-bs-target="#agregar" class="btn btn-danger btn-1 mt-1">Agregar </a>
                    <a href="./reporte.php" class="btn btn-danger btn-1 mt-1">Reporte </a>

                </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 header-1 text-center">
                    <div class="d-flex flex-column align-items-end">
                        <img src="./assets/img/letra.png" alt="" class="img-fluid" />
                        <button class="btn btn-1 mx-auto mt-1" role="button">Ver Ahora </button>
                    </div>
                </div>
                <div class="col-md-6 header-2">
                    <h1 class="mt-5">LAS MEJORES <br />PELICULAS</h1>
                    <img src="./assets/img/play.png" alt="" class="mt-3" />
                </div>
            </div>
        </div>

    </header>

    <section class="movies-container py-5">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h2 class="text-start">Peliculas mas Vistas</h2>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <select class="form-select" aria-label="Default select example" name="idioma" style="width:15%; ">
                        <option selected>Idioma</option>

                    </select>
                </div>
            </div>

            <hr style="width: 50%" />
            <div class="row">
                <?php while ($row = mysqli_fetch_array($consulta)) { ?>
                    <div class="col-4 md-5">
                        <div class="card mt-5" style="width: 20rem;">
                            <img src="./assets/img/netflix.jpg" alt="" class="card-img-top" />
                            <h4 class="card-title ms-3 mt-1 fs-2 text-center"> <?php echo $row['nom_peliculas']  ?> </h4>
                            <div class=" card-body">
                                <p class="card-text  ms-3 fs-2">Descripcion: <?php echo $row['descripcion']  ?></p>
                                <p class="card-text ms-3 fs-2">Estado: <?php echo $row['estado'] ? "Activo" : "Inactivo" ?></p>

                                <h5 class="card-title ms-3 mt-1 fs-2 text-center">Publicacion:
                                    <?php echo $row['Fecha_publi']  ?> </h5>
                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#generoCollapse-<?php echo $row["idPeliculas"]; ?>" aria-expanded="true" aria-controls="generoCollapse-<?php echo $row["idPeliculas"]; ?>">
                                                Genero
                                            </button>
                                        </h2>
                                        <div id="generoCollapse-<?php echo $row["idPeliculas"]; ?>" class="accordion-collapse collapse ">
                                            <div class="accordion-body">
                                                <ul>
                                                    <?php
                                                    $mysql->conectar();
                                                    // Realiza una consulta para obtener los géneros de esta película en particular
                                                    $consultaGenerosPelicula = $mysql->generarConsulta("SELECT generos.nom_genero FROM peliculas.peliculas_has_generos 
                                                    INNER JOIN peliculas.generos ON generos.idGeneros = peliculas_has_generos.Generos_idGeneros
                                                  WHERE peliculas_has_generos.Peliculas_idPeliculas = " . $row['idPeliculas']);
                                                    $mysql->desconectar();

                                                    while ($filaGenero = mysqli_fetch_array($consultaGenerosPelicula)) {
                                                        echo "<li class='list-group-item'>" . $filaGenero["nom_genero"] . "</li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion" id="accordionPanelsStayOpenExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#idiomaCollapse-<?php echo $row["idPeliculas"]; ?>" aria-expanded="true" aria-controls="idiomaCollapse-<?php echo $row["idPeliculas"]; ?>">
                                                Idioma
                                            </button>
                                        </h2>
                                        <div id="idiomaCollapse-<?php echo $row["idPeliculas"]; ?>" class="accordion-collapse collapse">
                                            <div class="accordion-body">
                                                <ul>
                                                    <?php
                                                    $mysql->conectar();
                                                    // Realiza una consulta para obtener los idiomas de esta película en particular
                                                    $consultaIdiomaPelicula = $mysql->generarConsulta("SELECT peliculas.idPeliculas, idiomas.idIdiomas, idiomas.nom_idioma FROM peliculas.peliculas INNER JOIN peliculas.idiomas_has_peliculas ON peliculas.idPeliculas = idiomas_has_peliculas.Peliculas_idPeliculas INNER JOIN peliculas.idiomas ON idiomas.idIdiomas = idiomas_has_peliculas.Idiomas_idIdiomas WHERE peliculas.idPeliculas = " . $row['idPeliculas']);
                                                    $mysql->desconectar();

                                                    while ($filaIdioma = mysqli_fetch_array($consultaIdiomaPelicula)) {
                                                        echo "<li class='list-group-item'>" . $filaIdioma["nom_idioma"] . "</li>";
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="./editar.php?idPeliculas=<?php echo $row["idPeliculas"] ?>" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#editar<?php echo $row["idPeliculas"] ?>">
                                    <i class="bi bi-pen-fill">Editar</i>
                                </a>

                                <a href="./controller/eliminar.php?idPeliculas=<?php echo $row["idPeliculas"] ?>" class="btn btn-danger mt-3">
                                    <i class="bi bi-trash-fill">Borrar</i>
                                </a>

                                <?php include("./editar.php") ?>
                            </div>
                        </div>
                    </div>

                <?php }
                ?>

            </div>
            <div class=" text-center mt-4">
                <button class="btn load-more">Cargar más</button>
            </div>
        </div>
    </section>
    <div class="container">
        <hr class="container-fluid">

        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
            <img src="./assets/img/logo.svg" width="80" alt="">
            <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">

            </a>

            <div class="col-md-5 justify-content-end offset-md-1 mb-3">
                <form method="POST" action="./controller/agregarGenero.php">
                    <h5>Agregar Genero</h5>

                    <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                        <input id="newsletter1" type="text" class="form-control" placeholder="Agrega Genero" name="genero">
                        <input type="text" name="id_User" hidden>
                        <button class="btn btn-outline-danger" type="submit">Agregar</button>
                    </div>
                </form>
                <hr>
                <form method="POST" action="./controller/agregarIdioma.php">
                    <h5>Agregar Idioma</h5>
                    <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                        <input id="newsletter1" type="text" class="form-control" placeholder="Agrega Idioma" name="idioma">
                        <button class="btn btn-outline-info" type="submit">Agregar</button>
                    </div>
                </form>
            </div>

        </footer>
    </div>

    <!-- MODAL AGREGAR  PELICULA -->
    <div class="modal fade" id="agregar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Pelicula</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="./controller/agregarPeliculas.php">
                        <div class="mb-3">
                            <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['idUsuarios']; ?>">
                            <label for="recipient-name" class="col-form-label">Nombre:</label>
                            <input type="text" class="form-control" id="recipient-name" name="nombre">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Descripcion:</label>
                            <textarea class="form-control" id="message-text" name="descripcion"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="col-form-label">Estado:</label>
                            <select class="form-select" aria-label="Default select example" name="estado">
                                <option selected>Selecciona el estado</option>
                                <option value="0">Inactivo</option>
                                <option value="1">Activo</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="generos">Géneros:</label>
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
                            <label for="estado" class="col-form-label">Idioma:</label>
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
                            <label for="publicacion" class="col-form-label">Publicacion:</label>
                            <input type="date" class="form-control" id="recipient-name" name="publicacion">

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


    <!-- MODAL EDITAR USUARIO-->
    <div class="modal fade" id="usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="./controller/editarUsuario.php">
                        <div class="mb-3">
                            <input type="hidden" name="idUsuario" value="<?php echo $_SESSION['idUsuarios']; ?>">
                            <label for="recipient-name" class="col-form-label">Nombre:</label>
                            <input type="text" class="form-control" id="recipient-name" value="<?php echo $_SESSION['user']; ?>" name="usuario">
                        </div>

                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="mostrarContraseña" value="<?php echo base64_decode($_SESSION["pass"]) ?>" name="password">
                                <button class="btn btn-outline-secondary click-password" type="button" onclick="togglePassword()">Mostrar</button>
                            </div>
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


    <script>
        function togglePassword() {
            let mostrarContraseña = document.querySelector("#mostrarContraseña");
            let toggleButton = document.querySelector(".click-password");

            if (mostrarContraseña.type === "password") {
                mostrarContraseña.type = "text";
                toggleButton.textContent = "Ocultar";
            } else {
                mostrarContraseña.type = "password";
                toggleButton.textContent = "Mostrar";
            }
        }
    </script>












</body>

</html>