<?php
    include_once "conexionLocal.php";
    session_start();
    $id = $_SESSION['id_cliente'];

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $altura = (int)$_POST["altura"];
        $peso = (int)$_POST["peso"];
        $pesoDeseado = (int)$_POST["pesoDeseado"];
        $alergia = $_POST['alergias'] ?? "";
        $intolerancia = $_POST['intolerancias'] ?? "";


        
        $errores = [];

        if(!empty($altura) && $altura >= 50 && $altura <= 250){
            $stmt = $conexion->prepare("UPDATE clientes SET altura = ? WHERE id_cliente = ?");
            $stmt->bind_param("ii", $altura, $id);
            $stmt->execute();
            session_start();
            $_SESSION['altura'] = $altura;
            $stmt->close();
        }
        if(!empty($peso) && $peso >= 30 && $peso <= 300){
            $stmt = $conexion->prepare("UPDATE clientes SET peso = ? WHERE id_cliente = ?");
            $stmt->bind_param("ii", $peso, $id);
            $stmt->execute();
            session_start();
            $_SESSION['peso'] = $peso;
            $stmt->close();
        }
        if(!empty($pesoDeseado) && $pesoDeseado >= 30 && $pesoDeseado <= 200){
            $stmt = $conexion->prepare("UPDATE clientes SET peso_deseado = ? WHERE id_cliente = ?");
            $stmt->bind_param("ii", $pesoDeseado, $id);
            $stmt->execute();
            session_start();
            $_SESSION['peso_deseado'] = $pesoDeseado;
            $stmt->close();
        }

        //AGREGAR ALERGIAS E INTOLERANCIAS
        if(!empty($alergia)){
            $consultaAlergias = $conexion->prepare("SELECT alergias FROM clientes WHERE id_cliente = ?");
            $consultaAlergias->bind_param("i", $id);
            $consultaAlergias->execute();
            $consultaAlergias->store_result();
            $consultaAlergias->bind_result($alergias);
            $consultaAlergias->fetch();
            // COMRUEBA QUE LA NUEVA ALERGIA NO ESTÉ EN ALERGIAS DE LA BBDD PARA NO REPETIRLA
            if (strpos($alergias,$alergia) === false){
                $alergias = $alergias . "," . $alergia;
            }
            $stmt = $conexion->prepare("UPDATE clientes SET alergias = ? WHERE id_cliente = ?");
            $stmt->bind_param("si", $alergias, $id);
            $stmt->execute();
            session_start();
            $_SESSION['alergias'] = $alergias;
            $stmt->close();
        }
        if(!empty($intolerancia)){
            $consultaintolerancias = $conexion->prepare("SELECT intolerancias FROM clientes WHERE id_cliente = ?");
            $consultaintolerancias->bind_param("i", $id);
            $consultaintolerancias->execute();
            $consultaintolerancias->store_result();
            $consultaintolerancias->bind_result($intolerancias);
            $consultaintolerancias->fetch();
            // COMRUEBA QUE LA NUEVA INTOLERANCIA NO ESTÉ EN INTOLERANCIAS DE LA BBDD PARA NO REPETIRLA
            if (strpos($intolerancias,$intolerancia) === false){
                $intolerancias = $intolerancias . "," . $intolerancia;
            }
            $stmt = $conexion->prepare("UPDATE clientes SET intolerancias = ? WHERE id_cliente = ?");
            $stmt->bind_param("si", $intolerancias, $id);
            $stmt->execute();
            session_start();
            $_SESSION['intolerancias'] = $intolerancias;
            $stmt->close();
        }

        // ELIMINAR ALERGIAS E INTOLERANCIAS
        if(isset($_POST['eliminarAlergia'])) {
            $alergia = $_POST['eliminarAlergia'];
            $consultaAlergias = $conexion->prepare("SELECT alergias FROM clientes WHERE id_cliente = ?");
            $consultaAlergias->bind_param("i", $id);
            $consultaAlergias->execute();
            $consultaAlergias->store_result();
            $consultaAlergias->bind_result($alergias);
            $consultaAlergias->fetch();

            //STRING -> ARRAY
            $alergias = explode(',', $alergias);
            //ELIMINAR
            $alergias = array_diff($alergias, [$alergia]);
            //ARRAY -> STRING
            $alergias = implode(",",$alergias);

            $stmt = $conexion->prepare("UPDATE clientes SET alergias = ? WHERE id_cliente = ?");
            $stmt->bind_param("si", $alergias, $id);
            $stmt->execute();
            session_start();
            $_SESSION['alergias'] = $alergias;
            $stmt->close();
        }
        if(isset($_POST['eliminarIntolerancia'])) {
            $intolerancia = $_POST['eliminarIntolerancia'];
            $consultaintolerancias = $conexion->prepare("SELECT intolerancias FROM clientes WHERE id_cliente = ?");
            $consultaintolerancias->bind_param("i", $id);
            $consultaintolerancias->execute();
            $consultaintolerancias->store_result();
            $consultaintolerancias->bind_result($intolerancias);
            $consultaintolerancias->fetch();

            //STRING -> ARRAY
            $intolerancias = explode(',', $intolerancias);
            //ELIMINAR
            $intolerancias = array_diff($intolerancias, [$intolerancia]);
            //ARRAY -> STRING
            $intolerancias = implode(",",$intolerancias);

            $stmt = $conexion->prepare("UPDATE clientes SET intolerancias = ? WHERE id_cliente = ?");
            $stmt->bind_param("si", $intolerancias, $id);
            $stmt->execute();
            session_start();
            $_SESSION['intolerancias'] = $intolerancias;
            $stmt->close();
        }

        header("Location: ../views/perfil.php");
        exit();
    }
?>