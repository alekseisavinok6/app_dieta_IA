<?php 
    include_once "conexionLocal.php";
    //session_start();
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $correo = trim($_POST['correo'] ?? "");
        $contrasena = trim($_POST['contrasena'] ?? "");

        $errores = [];

        // VERIFICAR QUE EL CORREO EXISTE 
        $stmt = $conexion->prepare("SELECT id_cliente, nombre, apellido, talla, peso, enfermedades, alergias, intolerancias FROM cliente WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        // SI NO EXISTE
        if($stmt->num_rows < 1) {
            $errores['inicio'] = "Correo o contraseña incorrecto.";
        } else {
            $consultaContrasena = $conexion->prepare("SELECT contrasena FROM cliente WHERE correo = ?");
            $consultaContrasena->bind_param("s",$correo);
            $consultaContrasena->execute();
            $consultaContrasena->store_result();
            $consultaContrasena->bind_result($hashGuardado);
            $consultaContrasena->fetch();

            if (strlen($contrasena) < 12) {
                $errores['contrasena'] = "La contraseña debe tener al menos 12 caracteres.";
                echo "<p>La contraseña introducido no existe.</p>";
            } else {
                if(!password_verify($contrasena, $hashGuardado)) {
                    $errores['inicio'] = "Correo o contraseña incorrecto.";
                } else {
                    $stmt->bind_result($id_cliente, $nombre, $apellido, $talla, $peso, $enfermedades, $alergias, $intolerancias);
                    $stmt->fetch();
                }
            }
        }

        if (empty($errores)){
            $_SESSION['id_cliente'] = $id_cliente;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;
            $_SESSION['talla'] = $talla;
            $_SESSION['peso'] = $peso;
            $_SESSION['enfermedades'] = $enfermedades;
            $_SESSION['alergias'] = $alergias;
            $_SESSION['intolerancias'] = $intolerancias;
            $stmt->close();
            header("Location: ../index.php");
            exit();
        } else {
            header("Location: ../views/login.php?error=credenciales");
            exit();
        }
    }
    $conexion->close();
    ?>