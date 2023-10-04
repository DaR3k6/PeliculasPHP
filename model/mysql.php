<?php
class Mysql
{
    private $ipServidor = "localhost";
    private $usuarioBase = "root";
    private $contrasena = "";
    private $conexion;

    //metodo para conectar la base de datos
    public function conectar()
    {
        $this->conexion = mysqli_connect($this->ipServidor, $this->usuarioBase, $this->contrasena);
    }
    //metodo para obtener la conexion 
    public function getConexion()
    {
        return $this->conexion;
    }

    //metodo que cierra la conexion
    public function desconectar()
    {
        mysqli_close($this->conexion);
    }

    //metodo que efectua una consulta y devuelve su resultado
    public function generarConsulta($consulta)
    {
        mysqli_query($this->conexion, "SET lc_time_names ='es_ES'");
        //atade el uso de caracteres especiales como tildes con el formato utf8
        mysqli_query($this->conexion, "SET NAMES 'utf8'");

        $this->resultadoConsulta = mysqli_query($this->conexion, $consulta);

        return $this->resultadoConsulta;
    }
}
