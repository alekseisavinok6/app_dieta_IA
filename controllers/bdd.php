<?php
$servername = "localhost";//no tocar el servername
$database = "u336643015_Dietista1234";//nombre de la base de datos
$username = "u336643015_Dietista1234";//nombre del usuario
$password = "Dietista1234@";//contraseña

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Recoger datos del formulario
$Nombre = $_POST["Nombre"];
$Password = $_POST["Password"];
$Correo = $_POST["Correo"];
$Edad = $_POST["fecha"]; // Asumiendo que "fecha" es la edad, revisa si este campo es correcto
$Sexo = $_POST["Sexo"];
$Alergenos = $_POST["Alergenos"];
$Enfermedades = $_POST["Enfermedades"];

// Verificar conexión
if(!$conn) {
    echo "No se ha podido conectar con el servidor: " . mysqli_connect_error();
} else {
    echo "<b><h3>Hemos conectado al servidor</h3></b>";
}

// Seleccionar base de datos
$db = mysqli_select_db($conn, $database);

if (!$db) {
    echo "No se ha podido encontrar la base de datos";
} else {
    echo "<h3>Base de datos seleccionada:</h3>";
}

// Insertar datos
$instruccion_SQL = "INSERT INTO Cliente (Nombre, Pass, Correo, Fecha, Sexo, Alergenos, Enfermedades) 
                    VALUES ('$Nombre','$Password','$Correo','$Edad','$Sexo','$Alergenos','$Enfermedades')";
?>


