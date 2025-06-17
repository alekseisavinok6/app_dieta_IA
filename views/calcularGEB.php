<?php
session_start();

// Redirección si no hay sesión activa (opcional)
if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.php");
    exit();
}

$mensaje = $error = "";
$peso = $_SESSION['peso'] ?? '';
$talla = $_SESSION['talla'] ?? '';
$edad = $_SESSION['edad'] ?? '';
$sexo = $_SESSION['sexo'] ?? '';
$actividad = $_SESSION['actividad'] ?? '';
$peso_ideal = $_SESSION['peso_ideal'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //$peso = floatval($_POST['peso'] ?? 0);
    //$talla = floatval($_POST['talla'] ?? 0);
    $edad = intval($_POST['edad'] ?? 0);
    $sexo = $_POST['sexo'] ?? '';
    $actividad = $_POST['actividad'] ?? '';

    // Cálculo de gásto energético basal (GEB)
    if ($peso && $talla && $edad && $sexo && $actividad) {
        // Fórmula Harris-Benedict
        if ($sexo === 'hombre') {
            $geb = 66.5 + (13.75 * $peso) + (5.003 * $talla * 100) - (6.775 * $edad);
        } else {
            $geb = 655.1 + (9.563 * $peso) + (1.850 * $talla * 100) - (4.676 * $edad);
        }

        // Factores de actividad
        $niveles = [
            'sedentario' => ['factor' => 1.2, 'desc' => 'Sedentario'],
            'ligera' => ['factor' => 1.375, 'desc' => 'Actividad ligera'],
            'moderada' => ['factor' => 1.55, 'desc' => 'Actividad moderada'],
            'intensa' => ['factor' => 1.725, 'desc' => 'Actividad intensa'],
            'muy_intensa' => ['factor' => 1.9, 'desc' => 'Actividad muy intensa'],
        ];

        $factor = $niveles[$actividad]['factor'] ?? 1;
        $nivel_actividad = $niveles[$actividad]['desc'] ?? 'Desconocido';

        // Calcular Gasto Energético Total (GET)
        $get = $geb * $factor;
        
        // Calcular VCT (usando peso ideal)
        if ($peso_ideal !== null) {
            if ($sexo === 'hombre') {
                $vct = (66.5 + (13.75 * $peso_ideal) + (5 * ($talla * 100)) - (6.75 * $edad)) * $factor;
            } elseif ($sexo === 'mujer') {
                $vct = (655 + (9.563 * $peso_ideal) + (1.850 * ($talla * 100)) - (4.676 * $edad)) * $factor;
            }
        }

        // Guardar en sesión
        $_SESSION['peso'] = $peso;
        $_SESSION['talla'] = $talla;
        $_SESSION['edad'] = $edad;
        $_SESSION['sexo'] = $sexo;
        $_SESSION['actividad'] = $actividad;
        $_SESSION['peso_ideal'] = $peso_ideal;
        $_SESSION['calculo_energetico'] = [
            'gasto_energetico_basal' => round($geb, 2),
            'gasto_energetico_total' => round($get, 2),
            'vct' => round($vct, 2),
            'nivel_actividad' => $nivel_actividad
        ];

        // Conectar a la base de datos
        $host = "localhost";
        $usuario = "root";
        $contrasena = "";
        $bd = "prueba_dietaapp";

        $conn = new mysqli($host, $usuario, $contrasena, $bd);

        if ($conn->connect_error) {
            $error = "Error de conexión con la base de datos: " . $conn->connect_error;
        } else {
            $id_cliente = $_SESSION['id_cliente'];
            $stmt = $conn->prepare("UPDATE datos_cliente SET 
            peso = ?, 
            talla = ?, 
            edad = ?, 
            sexo = ?, 
            actividad = ?, 
            gasto_energetico_basal = ?, 
            gasto_energetico_total = ?,
            vct = ?, 
            peso_ideal = ? 
            WHERE id_cliente = ?"
            );
            $stmt->bind_param("ddissddddi", $peso, $talla, $edad, $sexo, $actividad, $geb, $get, $vct, $peso_ideal, $id_cliente);

            if ($stmt->execute()) {
                $mensaje .= "Datos actualizados correctamente en la base de datos.";
            } else {
                $error = "Error al actualizar los datos: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }

        $mensaje = "Cálculo realizado correctamente.";
    } else {
        $error = "Por favor, completa todos los campos correctamente.";
    }
}

$geb = $_SESSION['calculo_energetico']['gasto_energetico_basal'] ?? null;
$get = $_SESSION['calculo_energetico']['gasto_energetico_total'] ?? null;
$vct = $_SESSION['calculo_energetico']['vct'] ?? null;
$nivel_actividad = $_SESSION['calculo_energetico']['nivel_actividad'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cálculo GEB, GET y VCT</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .btn-relleno-suave-pequeno-cursiva {
            display: inline-block;
            /* Reducimos el padding para un botón más pequeño */
            padding: 6px 12px;
            /* Reducimos el tamaño de la fuente */
            font-size: 14px;
            font-weight: bold;
            /* Añadimos font-style: italic para el texto en cursiva */
            font-style: italic;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: 2px solid #ccc; /* Borde inicial gris claro */
            border-radius: 5px;
            color: #333; /* Texto inicial gris oscuro */
            background-color: #fff; /* Fondo inicial blanco */
            transition: all 0.3s ease; /* Transición suave para todos los cambios */
}

            .btn-relleno-suave-pequeno-cursiva:hover {
            background-color: #f0f0f0; /* Fondo ligeramente gris al pasar el ratón */
            border-color: #999; /* Borde más oscuro al pasar el ratón */
            color: #000; /* Texto más oscuro al pasar el ratón */
}

            .radio-group {
            display: flex;
            gap: 20px; /* espacio entre Hombre y Mujer */
            margin-top: 5px;
            align-items: center;
            }

            .custom-radio {
            position: relative;
            padding-left: 28px;
            cursor: pointer;
            font-size: 16px;
            user-select: none;
            display: flex;
            align-items: center;
            }

            .custom-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
            }

            .radio-mark {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 16px;
            width: 16px;
            background-color: white;
            border: 2px solid #328e6e;
            border-radius: 50%;
            transition: all 0.3s ease;
            }

            .custom-radio input[type="radio"]:checked ~ .radio-mark::after {
            content: "";
            position: absolute;
            top: 4px;
            left: 4px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #328e6e;
            }

            /* Estilos para el tooltip */
        .tooltip {
        position: relative;
        display: inline-block;
        cursor: pointer;
        font-size: 0.85em; /* Para hacer el icono ℹ️ más pequeño */
        margin-left: 5px;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 240px;
            background-color: #f9f9f9;
            color: #333;
            text-align: left;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Posiciona arriba del ícono */
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            font-size: 1em; /* Tamaño discreto del texto */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

            /* Esto ajusta el tamaño del ícono ℹ️ y el texto del tooltip cuando está dentro de encabezados */
        .small-tooltip {
        font-size: 0.65em; /* Hace que ℹ️ no herede el tamaño grande de <h3> */
        }

        .small-tooltip .tooltiptext {
        font-size: 0.9em; /* Igual que en otras partes como <label> */
        width: 240px;
        }
</style>
</head>
<body>
    <div class="container">
        <?php include "../components/navbar.php"; ?>
        <div class="generarDieta-container flex-c box-s">
            <div class="generar-left">
                <?php if(isset($_SESSION['id_cliente'])): ?>
                <div style="font-size: larger;">Sesión activa para: <strong><?= $_SESSION['nombre'] ?> <?= $_SESSION['apellido'] ?></strong></div>
                <?php endif; ?>
                <img src="../imgs/calculo_gasto_energetico.png" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <h3><i>Paso 2: Cálculo del gasto energético</i> 🔥
                    <span class="tooltip small-tooltip">ℹ️
                        <span class="tooltiptext">
                        El gasto energético es la cantidad de energía (calorías) que tu cuerpo necesita diariamente para funcionar, teniendo en cuenta tu metabolismo basal y tu nivel de actividad física.
                        </span>
                    </span>
                </h3>
                <?php if (!empty($mensaje)): ?>
                    <p style="color:green;"><?= $mensaje ?></p>
                <?php elseif (!empty($error)): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>
                <form action="calcularGEB.php" method="POST">
                    <!-- <label for="peso">Peso (kg):</label>
                    <input type="number" step="0.01" name="peso" required value="<?= htmlspecialchars($peso) ?>">
                    <label for="talla">Talla (m):</label>
                    <input type="number" step="0.01" name="talla" required value="<?= htmlspecialchars($talla) ?>"> -->
                    <label for="edad">Edad (años):</label>
                    <input type="number" name="edad" required value="<?= htmlspecialchars($edad) ?>">
                    <div class="form-group">
                    <br>
                    <label for="sexo">Sexo:</label>
                    <div class="radio-group">
                        <label class="custom-radio">
                        <input type="radio" name="sexo" value="hombre" <?= $sexo === 'hombre' ? 'checked' : '' ?>>
                        <span class="radio-mark"></span>
                        Hombre
                        </label>
                        <br>
                        <label class="custom-radio">
                        <input type="radio" name="sexo" value="mujer" <?= $sexo === 'mujer' ? 'checked' : '' ?>>
                        <span class="radio-mark"></span>
                        Mujer
                        </label>
                    </div>
                    </div>
                    <br>

                    <label for="actividad">Nivel de actividad física:</label>
                    <select name="actividad" required>
                        <option value="">Seleccione...</option>
                        <option value="sedentario" <?= $actividad === 'sedentario' ? 'selected' : '' ?>>Sedentario 🧘🏽‍♂️</option>
                        <option value="ligera" <?= $actividad === 'ligera' ? 'selected' : '' ?>>Actividad ligera 🤹🏻‍♂️</option>
                        <option value="moderada" <?= $actividad === 'moderada' ? 'selected' : '' ?>>Actividad moderada 🤸🏻‍♂️</option>
                        <option value="intensa" <?= $actividad === 'intensa' ? 'selected' : '' ?>>Actividad intensa 🚴🏻‍♀️</option>
                        <option value="muy_intensa" <?= $actividad === 'muy_intensa' ? 'selected' : '' ?>>Actividad muy intensa 🚴🏻‍♀️🚴🏻‍♀️</option>
                    </select><br><br>
                    <button type="submit" class="btn">Calcular</button>
                </form>
                    <a href="<?= BASE_URL ?>views/generarDieta.php"><button class="btn-relleno-suave-pequeno-cursiva">➡︎ Siguiente paso</button></a>
                <?php if ($geb && $get && $vct): ?>
                    <div class="resultados">
                        <p><strong>GEB:</strong> <?= number_format($geb, 2) ?> kcal/día
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">
                                    Gasto Energético Basal (GEB): es la energía mínima que tu cuerpo necesita para funciones vitales como respirar y mantener la temperatura corporal en reposo.
                                </span>
                            </span>
                        </p>
                        <p><strong>GET:</strong> <?= number_format($get, 2) ?> kcal/día
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">
                                    Gasto Energético Total (GET) es la suma del gasto energético basal (GEB), la actividad física y otros factores como la digestión de alimentos.
                                </span>
                            </span>
                        </p>
                        <p><strong>VCT:</strong> <?= number_format($vct, 2) ?> kcal/día
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">
                                    Valor Calórico Total (VCT) es la cantidad total de calorías que se recomienda consumir al día para alcanzar tu objetivo (mantener, subir o bajar de peso).
                                </span>
                            </span>
                        </p>
                        <p><strong>Nivel de actividad:</strong> <?= htmlspecialchars($nivel_actividad) ?>
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">
                                    Se refiere a cuán activo físicamente eres en tu día a día. Incluye tu rutina laboral y deportiva. Este valor afecta al cálculo del gasto energético total.
                                </span>
                            </span>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
