<?php
session_start();

if (isset($_SESSION["idUsuarios"])) {
    $idUsuario = $_SESSION["idUsuarios"];
    require_once './model/mysql.php';
    $mysql = new MySQL();
    $mysql->conectar();


    $consultaUsuario = $mysql->generarConsulta("SELECT idUsuarios,user FROM peliculas.usuarios");

    $mysql->desconectar();
} else {
    echo "No lo trae";
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                            <a href="./index.php" class="nav-link fs-2" href="#">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-2" href="#">Nosotros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-2" href="#">Peliculas</a>
                        </li>
                        <li class="nav-item">
                            <!-- Aquí puedes agregar más elementos de navegación si es necesario -->
                        </li>
                    </ul>

                    </a>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 header-1 text-center">
                    <div class="d-flex flex-column align-items-end">
                        <img src="./assets/img/letra.png" alt="" class="img-fluid" />
                        <button class="btn btn-1 mx-auto mt-1" role="button">Ver Ahora</button>
                    </div>
                </div>
                <div class="col-md-6 header-2">
                    <h1 class="mt-5">LAS MEJORES <br />PELICULAS</h1>
                    <img src="./assets/img/play.png" alt="" class="mt-3" />
                </div>
            </div>
        </div>
    </header>

    <section class="movies-container  py-5">
        <div class="container text-center align-items-center h-100">


            <!-- Primer formulario -->
            <form class="row justify-content-center g-4" method="POST" action="./fpdf/PruebaV.php">
                <div class="col-12 text-center">
                    <h1>Generar Reporte de Usuario</h1>
                </div>
                <div class="col-4">
                    <select class="form-select" aria-label="Default select example" name="idUsuario">
                        <option value="" disabled selected>Seleccione un usuario</option>
                        <?php while ($row = mysqli_fetch_array($consultaUsuario)) { ?>
                            <option value="<?php echo $row["idUsuarios"]; ?>"><?php echo $row["user"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary">
                        Generar reporte de usuario
                    </button>
                </div>
            </form>


            <!-- Segundo formulario -->
            <form class="row justify-content-center g-4 mt-5" method="POST" action="./fpdf/reporteFechas.php">
                <div class="col-12 text-center">
                    <h1>Trer peliculas creadas por orden de fechas</h1>
                </div>
                <div class="col-6">
                    <label for="fechaInicio" class="form-label fs-2">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                </div>
                <div class="col-6">
                    <label for="fechaFinal" class="form-label fs-2">Fecha Final</label>
                    <input type="date" class="form-control" id="fechaFinal" name="fechaFinal">
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-secondary fs-2">Generar Reporte</button>
                </div>
            </form>

            <!-- Tercer formulario -->
            <form class="row justify-content-center g-4 mt-5" method="POST">
                <div class="col-12 text-center">
                    <h1>Peliculas</h1>
                </div>
                <div class="col-6">
                    <button type="submit" formaction="./excel/generarExecel.php" class="btn btn-secondary fs-2">Cantidad peliculas</button>
                </div>
                <div class="col-6">
                    <button type="submit" formaction="./excel/generarPromedio.php" class="btn btn-secondary fs-2">Promedio peliculas</button>
                </div>

            </form>
        </div>
    </section>

    <div class="container">
        <hr class="container-fluid">

        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4">
            <img src="./assets/img/logo.svg" width="80" alt="">
            <a href="/" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">

            </a>

            <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="./index.php" class="nav-link px-2 text-body-secondary">Inicio</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Sobre Nosotros</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Pelicula</a></li>
            </ul>
    </div>

    </footer>



    <!-- Enlace al archivo JavaScript de Bootstrap (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- Enlace al archivo JavaScript de Bootstrap (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
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