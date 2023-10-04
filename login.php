<?php
session_start()
?>

<!DOCTYPE html>
<html>

<head>
  <title>Slide Navbar</title>
  <link rel="stylesheet" type="text/css" href="slide navbar style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet" />
</head>

<link rel="stylesheet" type="text/css" href="./assets/css/login.css">

<body>
  <div class="main">
    <input type="checkbox" id="chk" aria-hidden="true" />

    <div class="signup">
      <form method="POST" action="./controller/registro.php">
        <label aria-hidden="true">Registrarse</label>
        <input type="text" name="user" placeholder="Nombre" />
        <input type="password" name="password" placeholder="Contraseña" />
        <button type="submit">Registro</button>
      </form>
    </div>

    <div class="login">
      <form method="POST" action="./controller/login.php">
        <label for="chk" aria-hidden="true">Logarse</label>
        <input type="text" name="user" placeholder="Nombre" />
        <input type="password" name="password" placeholder="Contraseña" />
        <button type="submit">Login</button>
      </form>
    </div>
  </div>

  <!-- Enlace al archivo JavaScript de Bootstrap (CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

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