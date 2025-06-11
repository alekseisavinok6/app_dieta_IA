<?php
$servername = "localhost"; // o el host que te aparezca en el panel si no es localhost
$database = "u336643015_Dietista1234";
$username = "u336643015_Dietista1234"; // ¡Este es el correcto!
$password = "Dietista1234@";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
echo "Conexión exitosa";
mysqli_close($conn);
?>