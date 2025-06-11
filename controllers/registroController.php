<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "conexionLocal.php";

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $peso = (int)$_POST["peso"];
        $altura = (int)$_POST["altura"];
        $alergenos = $_POST["alergenos"] ?? [];
        $alergenosBBDD = implode(",", $alergenos);
        $intolerancias = $_POST["intolerancias"] ?? [];
        $intoleranciasBBDD = implode(",", $intolerancias);
        $enfermedades = $_POST["enfermedades" ?? []];
        $enfermedadesBBDD = implode(",", $enfermedades);
        $sexo = $_POST["sexo"] ?? "Hombre";
        $f_nacimiento = $_POST["f_nacimiento"];
        $edad = 0;
        $password = trim($_POST["password"]);
        $password2 = trim($_POST["password2"]);

        $errores = [];
        
        // VERIFICAR QUE EL CORREO NO ESTÉ REGISTRADO
        // SI ESTÁ REGISTRADO, NO MANDA NADA A LA BBDD PERO TE REDIRIGE A UNA PAGINA DE ERROR 
        // (INVESTIGAR FETCH PARA PODER MOSTRAR MENSAJES DE ERROR EN EL FORMULARIO)
        $consultaCorreo = $conexion->prepare("SELECT id_cliente FROM clientes WHERE correo = ?");
        $consultaCorreo->bind_param("s",$correo);
        $consultaCorreo->execute();
        $consultaCorreo->store_result();
        if($consultaCorreo->num_rows > 0) {
            header("Location: ../views/registro.php?error=correo_duplicado");
           $errores['correo'] = "El correo ya está registrado.";
           exit();
        }
        $consultaCorreo->close();

        //VALIDACIONES
        if (empty($nombre) || !preg_match("/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s']{2,40}$/", $nombre)) {
            $errores['nombre'] = "El nombre o apellido no es válido.";
        }
        if( empty($apellido) || !preg_match("/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s']{2,40}$/", $apellido)) {
            $errores['apellido'] = "El nombre o apellido no es válido.";
        }
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = "El correo no es válido.";
        }
        if (empty($peso) || $peso < 20 || $peso > 300){
            $errores['peso'] = "El peso no es válido.";
        }
        if (empty($altura) || $altura < 50 || $altura > 250){
            $errores['altura'] = "La altura no es válida.";
        }
        if (empty($alergenos)) {
            $errores['alergenos'] = "Los alergenos no son válidos";
        } 
        if (empty($intolerancias)) {
            $errores['intolerancias'] = "Las intolerancias no son válidas";
        }
        if (empty($enfermedades)) {
            $errores['enfermedades'] = "Las enfermedades no son válidas";
        }
        if ($sexo !== "Hombre" && $sexo !== "Mujer") {
            $errores['sexo'] = "El sexo no es válido.";
        }
        if (empty($f_nacimiento)) {
            $errores['fNacimiento'] = "La fecha de nacimiento es obligatoria.";
        } else {
            // CALCULAR EDAD
            $fecha_nacimiento = new DateTime($f_nacimiento); // YYYY/MM/DD"
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nacimiento)->y;

            if ($edad < 2 || $edad > 100) {
                $errores['edad'] = "La edad no es válida.";
            }
        }
        // VALIDACION Y ENCRIPTACIÓN DE CONTRASEÑA
        // AGREGAR MÁS SEGURIDAD (MAYUSCULAS, NÚMEROS, CARACTERES ESPECIALES)
        if (strlen($password) < 12) {
            $errores['password'] = "La contraseña debe tener al menos 12 caracteres.";
        }
        if ($password !== $password2) {
            $errores['password2'] = "Las contraseñas no coinciden.";
        } 
        if (!isset($errores['password']) && !isset($errores['password2'])) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        }

        if(empty($errores)) {
            $stmt = $conexion->prepare("INSERT INTO clientes(nombre, apellido, correo, password, edad, sexo, altura, peso, enfermedades, alergias, intolerancias) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssisiisss", $nombre, $apellido, $correo, $password, $edad, $sexo, $altura, $peso, $enfermedadesBBDD, $alergenosBBDD, $intoleranciasBBDD);
            $resultado = $stmt->execute();
            if ($resultado) {
                $consultaUsuario = $conexion->prepare("SELECT id_cliente, nombre, apellido, altura, peso, peso_deseado, alergias, intolerancias FROM clientes WHERE correo = ?");
                $consultaUsuario->bind_param("s", $correo);
                $consultaUsuario->execute();
                $consultaUsuario->store_result();
                $consultaUsuario->bind_result($id_cliente, $nombre, $apellido, $altura, $peso, $pesoDeseado, $alergias, $intolerancias);
                $consultaUsuario->fetch();

                session_start();
                $_SESSION['id_cliente'] = $id_cliente;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['altura'] = $altura;
                $_SESSION['peso'] = $peso;
                $_SESSION['peso_deseado'] = $pesoDeseado;
                $_SESSION['alergias'] = $alergenosBBDD;
                $_SESSION['intolerancias'] = $intoleranciasBBDD;
                
                $stmt->close();
                $consultaUsuario->close();
                header("Location: ../views/perfil.php");
                exit();
            } else {
                echo "<p>Error al registrar el usuario: " . $conexion->error . "</p>";
            }
        } else {
            foreach ($errores as $error) {
                echo "<p>$error</p>";
            }
        }
    }
$conexion->close();
?>