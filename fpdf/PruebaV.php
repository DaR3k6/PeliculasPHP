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
      $this->Cell(18, 10, utf8_decode('ID'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('NOMBRE'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('PELICULA'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('DESCRIPCION'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('ESTADO'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('GENERO'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('IDIOMA'), 1, 1, 'C', 1); // Aumentamos el ancho de la celda

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
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

/*$consulta_reporte_alquiler = $conexion->query("  ");*/

/*while ($datos_reporte = $consulta_reporte_alquiler->fetch_object()) {      
   }*/
$i = $i + 1;

if (
   isset($_POST['idUsuario']) && !empty($_POST['idUsuario'])

) {

   //se hace llamado del modelo de conexion y consultas 
   require_once '../model/mysql.php';
   //se capturan las variables que vienen desde el formulario
   $idusuario = $_POST['idUsuario'];

   // se instancia la clasem, es decir, se llama para poder usar los metodos
   $mysql = new MySQL();
   //se hace uso del metodo para conectarse a la base de datos 
   $mysql->conectar();

   //se guarda en una variable la consulta utilizando  el metodo para dicho proceso
   $consulta = $mysql->generarConsulta("SELECT peliculas.idPeliculas,usuarios.user, peliculas.nom_peliculas,peliculas.nom_peliculas,peliculas.descripcion,peliculas.estado,generos.nom_genero,idiomas.nom_idioma FROM peliculas.peliculas 
   INNER JOIN peliculas.peliculas_has_generos ON peliculas.idPeliculas = peliculas_has_generos.Peliculas_idPeliculas 
   INNER JOIN peliculas.generos ON generos.idGeneros = peliculas_has_generos.Generos_idGeneros
   INNER JOIN peliculas.idiomas_has_peliculas ON peliculas.idPeliculas = idiomas_has_peliculas.Peliculas_idPeliculas 
   INNER JOIN peliculas.idiomas ON idiomas.idIdiomas = idiomas_has_peliculas.Idiomas_idIdiomas 
   INNER JOIN peliculas.usuarios ON usuarios.idUsuarios = peliculas.Usuarios_idUsuarios WHERE peliculas.Usuarios_idUsuarios = $idusuario ");


   //se desconecta de la base de datos para librerar memoria
   $mysql->desconectar();

   /* TABLA */
   while ($row = mysqli_fetch_array($consulta)) {
      //consulta para traer los idiomas 
      $pdf->Cell(18, 10, utf8_decode($row['idPeliculas']), 1, 0, 'C', 0);
      $pdf->Cell(20, 10, utf8_decode($row['user']), 1, 0, 'C', 0);
      $pdf->Cell(30, 10, utf8_decode($row['nom_peliculas']), 1, 0, 'C', 0);
      $pdf->Cell(30, 10, utf8_decode($row['descripcion']), 1, 0, 'C', 0);
      $pdf->Cell(25, 10, utf8_decode($row['estado'] ? 'ACTIVO' : 'INACTIVO'), 1, 0, 'C', 0);
      $pdf->Cell(25, 10, utf8_decode($row['nom_genero']), 1, 0, 'C', 0);
      $pdf->Cell(25, 10, utf8_decode($row['nom_idioma']), 1, 1, 'C', 0);
   }
} else {
   echo "Por favor, ingrese un ID de usuario válido.";
}





$pdf->Output('Prueba.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)
