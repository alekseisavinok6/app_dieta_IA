<?php
    session_start();
    session_unset(); // Limpia todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: http://localhost/app_dieta_IA/");
    exit();
?>