<?php
    include_once "conexionLocal.php";
    session_start();
    $id = $_SESSION['id_cliente'];

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nivelActividad = (float)$_POST["nivelActividad"] ?? 1.65;
        $objetivo = $_POST["objetivo"] ?? "mantenerPeso";
        $comidasDias = (int)$_POST["comidasDias"] ?? 3;
        $preferencias = $_POST["preferencias"] ?? "";
        $comentario = trim($_POST["comentario"] ?? "");

        $errores = [];

        if (!in_array($nivelActividad, [1.2, 1.4, 1.65, 2])) {
            $errores["nivelActividad"] = "El nivel de actividad no es válido.";
        }
        if(!in_array($objetivo, ["mantenerPeso", "subirPeso", "bajarPeso"])) {
            $errores['objetivo'] = "El objetivo no es válido.";
        }
        if (!in_array($comidasDias, [3, 4, 5])) {
            $errores['comidasDias'] = "El número de comidas al día no es válido.";
        }
        
        if (empty($errores)) {
            //OBTENER DATOS DEL CLIENTE
            $cliente = $conexion->prepare("SELECT edad, sexo, altura, peso, peso_deseado, enfermedades, alergias, intolerancias FROM clientes WHERE id_cliente = ?");
            $cliente->bind_param("i", $id);
            $cliente->execute();
            $cliente->bind_result($edad, $sexo, $altura, $peso, $peso_deseado, $enfermedades, $alergias, $intolerancias);
            $cliente->fetch();
            $cliente->close();

            //PROMPT PARA LA IA
            //RECOMIENDO QUE LA DIETA SEA DIARIA Y NO SEMANAL YA QUE CONSUME MUCHO PEDIR UNA DIETA SEMANAL CON TANTOS CAMPOS
            $prompt = "
            Eres un nutricionista. Genera una dieta de $comidasDias comidas al día para un cliente con estos datos:
            - Edad: $edad años.
            - Sexo: $sexo
            - Altura: $altura cm
            - Peso: $peso kg
            - IMC: 
            - Gasto energético basal: 
            - Nivel de actividad: $nivelActividad
            - Calculo del gasto energético total: 
            - Objetivo: $objetivo
            - Preferencias: $preferencias
            - Alergias: $alergias
            - Intolerancias: $intolerancias
            - Enfermedades: $enfermedades
            - Comentario del cliente: $comentario

            Devuelve la dieta en formato JSON estructurado.
            
            La dieta debe tener una breve descripción.

            Cada comida debe contener por lo menos un plato con su correspondiente lista de ingredientes, y cada ingrediente
            debe tener:

            - nombre,
            - nutriente,
            - alergenos,
            - valorCalorico,
            - peso,
            - medida (un vaso, una cucharada, etc)

            La dieta debe tener un valor calórico total en cada comida y al final.
            ";

            header("Location: ../views/dieta.php");
            exit();
        } else {
            foreach ($errores as $error) {
                echo "<p>$error</p>";
            }
        }
    }
    $conexion->close();
?>