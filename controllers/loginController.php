<?php 
    include_once "conexionLocal.php";
    //session_start();
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $correo = trim($_POST['correo'] ?? "");
        $password = trim($_POST['password'] ?? "");

        $errores = [];

        // VERIFICAR QUE EL CORREO EXISTE 
        $stmt = $conexion->prepare("SELECT id_cliente, nombre, apellido, altura, peso, peso_deseado, alergias, intolerancias FROM clientes WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        // SI NO EXISTE
        if($stmt->num_rows < 1) {
            $errores['inicio'] = "Correo o contraseña incorrecto.";
        } else {
            $consultaPassword = $conexion->prepare("SELECT password FROM clientes WHERE correo = ?");
            $consultaPassword->bind_param("s",$correo);
            $consultaPassword->execute();
            $consultaPassword->store_result();
            $consultaPassword->bind_result($hashGuardado);
            $consultaPassword->fetch();

            if (strlen($password) < 12) {
                $errores['password'] = "La contraseña debe tener al menos 12 caracteres.";
                echo "<p>La contraseña introducido no existe.</p>";
            } else {
                if(!password_verify($password, $hashGuardado)) {
                    $errores['inicio'] = "Correo o contraseña incorrecto.";
                } else {
                    $stmt->bind_result($id_cliente, $nombre, $apellido, $altura, $peso, $pesoDeseado, $alergias, $intolerancias);
                    $stmt->fetch();
                }
            }
        }

        if (empty($errores)){
            $_SESSION['id_cliente'] = $id_cliente;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido'] = $apellido;
            $_SESSION['altura'] = $altura;
            $_SESSION['peso'] = $peso;
            $_SESSION['peso_deseado'] = $pesoDeseado;
            $_SESSION['alergias'] = $alergias;
            $_SESSION['intolerancias'] = $intolerancias;
            $stmt->close();
            header("Location: ../views/perfil.php");
            exit();
        } else {
            header("Location: ../views/login.php?error=credenciales");
            exit();
        }
    }
    $conexion->close();
    ?>