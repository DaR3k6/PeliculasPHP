<?php

require('./fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        $this->SetFont('Arial', 'B', 19);
        $this->Cell(45);
        $this->SetFillColor(39, 39, 39); // Cambiamos el color de fondo
        $this->SetTextColor(255, 255, 255); // Cambiamos el color del texto
        $this->Cell(80, 20, utf8_decode('Peliculas'), 2, 1, 'C', 1); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea
        $this->Ln(3);
        $this->SetTextColor(0, 0, 0); // Restauramos el color del texto

        /* TITULO DE LA TABLA */
        //color
        $this->SetTextColor(39, 39, 39);
        $this->Cell(40); // mover a la derecha
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(100, 10, utf8_decode("Peliculas creadas"), 0, 1, 'C', 0);
        $this->Ln(7);

        /* TITULO DE LA TABLA */
        $this->SetFillColor(39, 39, 39);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(163, 163, 163);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(14, 10, utf8_decode('ID'), 1, 0, 'C', 1);
        $this->Cell(30, 10, utf8_decode('PELICULA'), 1, 0, 'C', 1);
        $this->Cell(40, 10, utf8_decode('DESCRIPCION'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('ESTADO'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $this->Cell(25, 10, utf8_decode('GENERO'), 1, 0, 'C', 1);
        $this->Cell(22, 10, utf8_decode('IDIOMA'), 1, 1, 'C', 1); // Aumentamos el ancho de la celda
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $hoy = date('d/m/Y');
        $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina);
    }
}

$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); // muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); // colorBorde

$i = $i + 1;

if (
    isset($_POST['fechaInicio']) && !empty($_POST['fechaInicio']) &&
    isset($_POST['fechaFinal']) && !empty($_POST['fechaFinal'])
) {
    // se hace llamado del modelo de conexion y consultas 
    require_once '../model/mysql.php';
    // se capturan las variables que vienen desde el formulario
    $fechaInicio = $_POST['fechaInicio'];
    $fechaFinal = $_POST['fechaFinal'];
    // se instancia la clase, es decir, se llama para poder usar los métodos
    $mysql = new MySQL();
    // se hace uso del método para conectarse a la base de datos 
    $mysql->conectar();

    // se guarda en una variable la consulta utilizando  el método para dicho proceso
    $consulta = $mysql->generarConsulta("SELECT peliculas.idPeliculas,peliculas.nom_peliculas, peliculas.descripcion,peliculas.estado, peliculas.Fecha_publi, generos.nom_genero,idiomas.nom_idioma FROM peliculas.peliculas 
    INNER JOIN peliculas.peliculas_has_generos ON peliculas.idPeliculas = peliculas_has_generos.Peliculas_idPeliculas
    INNER JOIN peliculas.generos ON generos.idGeneros = generos.idGeneros 
    INNER JOIN peliculas.idiomas_has_peliculas ON idiomas_has_peliculas.Peliculas_idPeliculas = peliculas.idPeliculas 
    INNER JOIN peliculas.idiomas ON idiomas.idIdiomas = idiomas_has_peliculas.Idiomas_idIdiomas 
    WHERE peliculas.Fecha_publi BETWEEN '$fechaInicio' AND '$fechaFinal' ORDER BY peliculas.Fecha_publi ASC; ");

    // se desconecta de la base de datos para liberar memoria
    $mysql->desconectar();

    /* TABLA */
    while ($row = mysqli_fetch_array($consulta)) {
        // Movie name
        $movieName = utf8_decode($row['nom_peliculas']);
        if (strlen($movieName) > 20) {
            $movieName = substr($movieName, 0, 17) . '...';
        }

        // Description
        $description = utf8_decode($row['descripcion']);
        if (strlen($description) > 50) {
            $description = substr($description, 0, 10);
        }

        $pdf->Cell(14, 10, $row['idPeliculas'], 1, 0, 'C', 0);
        $pdf->Cell(30, 10, $movieName, 1, 0, 'C', 0);
        $pdf->Cell(40, 10, $description, 1, 0, 'C', 0);
        $pdf->Cell(25, 10, $row['estado'] ? 'ACTIVO' : 'INACTIVO', 1, 0, 'C', 0);
        $pdf->Cell(25, 10, $row['Fecha_publi'], 1, 0, 'C', 0);
        $pdf->Cell(25, 10, $row['nom_genero'], 1, 0, 'C', 0);
        $pdf->Cell(22, 10, utf8_decode($row['nom_idioma']), 1, 1, 'C', 0); // Aumentamos el ancho de la celda
    }
} else {
    echo "Por favor, ingrese bien la fecha";
}

$pdf->Output('Prueba.pdf', 'I'); // nombreDescarga, Visor(I->visualizar - D->descargar)
