<?php 
define("BASE_URL", "http://localhost/app_dieta_IA/");
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "prueba_dietaapp";

$conexion = new mysqli($host, $usuario, $password, $base_datos);

if($conexion->connect_error){
    die("Error de conexión: " . $conexion->connect_error);
} 
?>