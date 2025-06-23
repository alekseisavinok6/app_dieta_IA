<?php 
define("BASE_URL", "http://localhost/app_dieta_IA/");
$host = "localhost";
$usuario = "root";
$contrasena = "";
//$bd = "prueba_dietaapp";
$bd = "dieta_app";

$conexion = new mysqli($host, $usuario, $contrasena, $bd);

if($conexion->connect_error){
    die("Error de conexión: " . $conexion->connect_error);
}