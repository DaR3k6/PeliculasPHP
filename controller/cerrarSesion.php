<?php
require_once "../model/usuario.php";
session_start();
unset($_SESSION['user']);
unset($_SESSION['acceso']);
session_destroy();
header("Location:../login.php");
exit();
