<?php
require './vendor/autoload.php';
require '../model/mysql.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();

// CONECTAR A LA BASE DE DATOS
$mysql = new MySQL();
$mysql->conectar();

$consulta = $mysql->generarConsulta("SELECT peliculas.idPeliculas, peliculas.nom_peliculas,
AVG(generos.idGeneros) AS Promedio_Genero, AVG(idiomas.idIdiomas) AS Promedio_Idioma
FROM peliculas.peliculas 
INNER JOIN peliculas.peliculas_has_generos ON peliculas.idPeliculas = peliculas_has_generos.Peliculas_idPeliculas 
INNER JOIN peliculas.generos ON generos.idGeneros = peliculas_has_generos.Generos_idGeneros 
INNER JOIN peliculas.idiomas_has_peliculas ON peliculas.idPeliculas = idiomas_has_peliculas.Peliculas_idPeliculas 
INNER JOIN peliculas.idiomas ON idiomas.idIdiomas = idiomas_has_peliculas.Idiomas_idIdiomas 
GROUP BY peliculas.idPeliculas, peliculas.nom_peliculas;");

$rowNumber = 1;

if (mysqli_num_rows($consulta) > 0) {

    // Agregar encabezados a la hoja de cálculo
    $activeWorksheet->setCellValue('A1', 'ID');
    $activeWorksheet->setCellValue('B1', 'Nombre de Película');
    $activeWorksheet->setCellValue('C1', 'Promedio de Género');
    $activeWorksheet->setCellValue('D1', 'Promedio de Idioma');

    while ($row = mysqli_fetch_assoc($consulta)) {
        // Escribir los resultados en la hoja de cálculo
        $activeWorksheet->setCellValue('A' . ($rowNumber + 1), $row["idPeliculas"]);
        $activeWorksheet->setCellValue('B' . ($rowNumber + 1), $row["nom_peliculas"]);
        $activeWorksheet->setCellValue('C' . ($rowNumber + 1), $row["Promedio_Genero"]);
        $activeWorksheet->setCellValue('D' . ($rowNumber + 1), $row["Promedio_Idioma"]);
        $rowNumber++;
    }
}

// Configurar el encabezado HTTP para la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reportePromedioPelicula.xlsx"');
header('Cache-Control: max-age=0');

// Guardar el archivo Excel en la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Desconectar de la base de datos
$mysql->desconectar();
