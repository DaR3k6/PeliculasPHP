<?php
require './vendor/autoload.php';
require '../model/mysql.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

//CONECTO A LA BASE DE DATOS
$mysql = new MySQL();
$mysql->conectar();

$consulta = $mysql->generarConsulta("SELECT peliculas.idPeliculas,peliculas.nom_peliculas,idiomas.nom_idioma,usuarios.user,COUNT(peliculas.nom_peliculas) AS CANTIDAD_PELICULAS FROM peliculas.peliculas 
INNER JOIN peliculas.peliculas_has_generos ON peliculas.idPeliculas = peliculas_has_generos.Peliculas_idPeliculas 
INNER JOIN peliculas.generos ON generos.idGeneros = peliculas_has_generos.Generos_idGeneros 
INNER JOIN peliculas.idiomas_has_peliculas ON idiomas_has_peliculas.Peliculas_idPeliculas = peliculas.idPeliculas 
INNER JOIN peliculas.idiomas ON idiomas.idIdiomas = idiomas_has_peliculas.Idiomas_idIdiomas 
INNER JOIN peliculas.usuarios ON usuarios.idUsuarios = peliculas.Usuarios_idUsuarios 
GROUP BY peliculas.idPeliculas,peliculas.nom_peliculas,idiomas.nom_idioma,usuarios.user;");

$rowNumber = 1;

if (mysqli_num_rows($consulta) > 0) {

    // Agregar encabezados a la hoja de cálculo
    $activeWorksheet->setCellValue('A1', 'ID');
    $activeWorksheet->setCellValue('B1', 'Nombre de Película');
    $activeWorksheet->setCellValue('C1', 'Idioma');
    $activeWorksheet->setCellValue('D1', 'Usuario');
    $activeWorksheet->setCellValue('E1', 'Cantidad de Películas');

    while ($row = mysqli_fetch_assoc($consulta)) {
        // Escribir los resultados en la hoja de cálculo
        $activeWorksheet->setCellValue('A' . ($rowNumber + 1), $row["idPeliculas"]);
        $activeWorksheet->setCellValue('B' . ($rowNumber + 1), $row["nom_peliculas"]);
        $activeWorksheet->setCellValue('C' . ($rowNumber + 1), $row["nom_idioma"]);
        $activeWorksheet->setCellValue('D' . ($rowNumber + 1), $row["user"]);
        $activeWorksheet->setCellValue('E' . ($rowNumber + 1), $row["CANTIDAD_PELICULAS"]);
        $rowNumber++;
    }
}

// Configurar el encabezado HTTP para la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reportePelicula.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Desconectar de la base de datos
$mysql->desconectar();
