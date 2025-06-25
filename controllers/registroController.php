<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include "conexionLocal.php";

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = trim($_POST["nombre"] ?? "");
        $apellido = trim($_POST["apellido"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $peso = (float)$_POST["peso"];
        $talla = (float)$_POST["talla"];
        // ALERGENOS
        $alergenos = $_POST["alergenos"] ?? [];
        $otros_alergenos = trim($_POST["otros_alergenos"] ?? "");
        if (!empty($otros_alergenos)) {
            $alergenos_extra = array_map('trim', explode(',', $otros_alergenos));
            $alergenos = array_merge($alergenos, $alergenos_extra);
        }
        $alergenos = array_filter(array_unique($alergenos));
        if (in_array("null", $alergenos) && count($alergenos) > 1) {
            $alergenos = array_diff($alergenos, ["null"]);
        }
        // INTOLERANCIAS
        $intolerancias = $_POST["intolerancias"] ?? [];
        $otras_intolerancias = trim($_POST["otras_intolerancias"] ?? "");
        if (!empty($otras_intolerancias)) {
            $intolerancias_extra = array_map('trim', explode(',', $otras_intolerancias));
            $intolerancias = array_merge($intolerancias, $intolerancias_extra);
        }
        $intolerancias = array_filter(array_unique($intolerancias));
        if (in_array("null", $intolerancias) && count($intolerancias) > 1) {
            $intolerancias = array_diff($intolerancias, ["null"]);
        }
        // ENFERMEDADES
        $enfermedades = $_POST["enfermedades"] ?? [];
        $otras_enfermedades = trim($_POST["otras_enfermedades"] ?? "");
        if (!empty($otras_enfermedades)) {
            $enfermedades_extra = array_map('trim', explode(',', $otras_enfermedades));
            $enfermedades = array_merge($enfermedades, $enfermedades_extra);
        }
        $enfermedades = array_filter(array_unique($enfermedades));
        if (in_array("null", $enfermedades) && count($enfermedades) > 1) {
            $enfermedades = array_diff($enfermedades, ["null"]);
        }
        // SEXO
        $sexo = $_POST["sexo"] ?? "Hombre";
        $f_nacimiento = $_POST["f_nacimiento"];
        $edad = 0;
        $contrasena = trim($_POST["contrasena"]);
        $contrasena2 = trim($_POST["contrasena2"]);

        $errores = [];
        
        // VERIFICAR QUE EL CORREO NO ESTÉ REGISTRADO
        // SI ESTÁ REGISTRADO, NO MANDA NADA A LA BBDD PERO TE REDIRIGE A UNA PAGINA DE ERROR 
        // (INVESTIGAR FETCH PARA PODER MOSTRAR MENSAJES DE ERROR EN EL FORMULARIO)
        $consultaCorreo = $conexion->prepare("SELECT id_cliente FROM cliente WHERE correo = ?");
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
            $errores['nombre'] = "El nombre no es válido.";
        }
        if( empty($apellido) || !preg_match("/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s']{2,40}$/", $apellido)) {
            $errores['apellido'] = "El apellido no es válido.";
        }
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = "El correo no es válido.";
        }
        if (empty($peso) || $peso < 20 || $peso > 300){
            $errores['peso'] = "El peso no es válido.";
        }
        if (empty($talla) || $talla < 50 || $talla > 250){
            $errores['talla'] = "La talla no es válida.";
        } else {
            $talla = $talla / 100; // Convertir a metros
        }
        if (empty($alergenos)) {
            $errores['alergenos'] = "Debes seleccionar al menos un alérgeno o escribirlo.";
        }
        $alergenosBBDD = implode(", ", $alergenos); 
        if (empty($intolerancias)) {
            $errores['intolerancias'] = "Debes seleccionar al menos una intolerancia o escribirla.";
        }
        $intoleranciasBBDD = implode(", ", $intolerancias);
        if (empty($enfermedades)) {
            $errores['enfermedades'] = "Debes seleccionar al menos una enfermedad o escribirla.";
        }
        $enfermedadesBBDD = implode(", ", $enfermedades);
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
        if (strlen($contrasena) < 12) {
            $errores['contrasena'] = "La contraseña debe tener al menos 12 caracteres.";
        }
        if ($contrasena !== $contrasena2) {
            $errores['contrasena2'] = "Las contraseñas no coinciden.";
        } 
        if (!isset($errores['contrasena']) && !isset($errores['contrasena2'])) {
            $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        }

        if(empty($errores)) {
            $stmt = $conexion->prepare(
                "INSERT INTO cliente (
                nombre, 
                apellido, 
                correo, 
                contrasena, 
                edad, 
                sexo, 
                talla, 
                peso, 
                enfermedades, 
                alergias, 
                intolerancias) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssisddsss", 
                $nombre, 
                $apellido, 
                $correo, 
                $contrasena, 
                $edad, 
                $sexo, 
                $talla, 
                $peso, 
                $enfermedadesBBDD, 
                $alergenosBBDD, 
                $intoleranciasBBDD
            );
            $resultado = $stmt->execute();
            if ($resultado) {
                $consultaUsuario = $conexion->prepare(
                    "SELECT 
                    id_cliente, 
                    nombre, 
                    apellido, 
                    talla, 
                    peso, 
                    enfermedades, 
                    alergias, 
                    intolerancias 
                    FROM cliente 
                    WHERE correo = ?"
                );
                $consultaUsuario->bind_param("s", $correo);
                $consultaUsuario->execute();
                $consultaUsuario->store_result();
                $consultaUsuario->bind_result(
                    $id_cliente, 
                    $nombre, 
                    $apellido, 
                    $talla, 
                    $peso, 
                    $enfermedades, 
                    $alergias, 
                    $intolerancias
                );
                $consultaUsuario->fetch();

                session_start();
                $_SESSION['id_cliente'] = $id_cliente;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['apellido'] = $apellido;
                $_SESSION['correo'] = $correo;
                $_SESSION['edad'] = $edad;
                $_SESSION['sexo'] = $sexo;
                $_SESSION['talla'] = $talla;
                $_SESSION['peso'] = $peso;                
                $_SESSION['enfermedades'] = $enfermedadesBBDD;
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