<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $peso = isset($_POST['peso']) ? floatval($_POST['peso']) : null;
    $talla = isset($_POST['talla']) ? floatval($_POST['talla']) : null;

    // Cálculo del IMC y peso ideal
    if ($peso && $talla) {
        $imc = $peso / pow($talla, 2);
        $peso_ideal = 22 * pow($talla, 2);

        // Clasificación según OMS
        if ($imc < 18.5) {
            $clasificacion = "bajo peso";
        } elseif ($imc < 25) {
            $clasificacion = "peso normal";
        } elseif ($imc < 30) {
            $clasificacion = "sobrepeso";
        } elseif ($imc < 35) {
            $clasificacion = "obesidad grado I";
        } elseif ($imc < 40) {
            $clasificacion = "obesidad grado II";
        } else {
            $clasificacion = "obesidad grado III";
        }

        // Guardar en sesión
        $_SESSION['peso'] = $peso;
        $_SESSION['talla'] = $talla;
        $_SESSION['imc'] = round($imc, 2);
        $_SESSION['peso_ideal'] = round($peso_ideal, 2);
        $_SESSION['clasificacion'] = $clasificacion;

        // Guardar en base de datos
        $id_cliente = $_SESSION['id_cliente'];
        $conn = new mysqli("localhost", "root", "", "prueba_dietaapp");

        if ($conn->connect_error) {
            $error = "Error de conexión: " . $conn->connect_error;
        } else {
            $stmt = $conn->prepare("UPDATE datos_cliente SET peso = ?, talla = ?, imc = ?, peso_ideal = ?, clasificacion = ? WHERE id_cliente = ?");
            $stmt->bind_param("ddddsi", $peso, $talla, $imc, $peso_ideal, $clasificacion, $id_cliente);

            if (!$stmt->execute()) {
                $error = "Error al guardar los datos: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }

        $mensaje = "Estudio antropométrico calculado correctamente.";
    } else {
        $error = "Por favor, ingresa peso y talla válidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estudio Antropométrico</title>
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
                <img src="../imgs/antropometria.png" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <h3><i>Paso 1: Estudio antropométrico</i> 📑
                    <span class="tooltip small-tooltip">ℹ️
                        <span class="tooltiptext">
                        El estudio antropométrico consiste en medir y analizar características físicas como el peso y la talla para calcular el IMC, peso ideal y clasificar el estado nutricional.
                        </span>
                    </span>
                </h3>
                <?php if (isset($mensaje)): ?>
                    <p style="color:green;"><?= $mensaje ?></p>
                <?php elseif (isset($error)): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>

                <form action="estudioAntropometrico.php" method="post">
                    <label for="peso">Peso (kg):
                        <span class="tooltip">ℹ️
                            <span class="tooltiptext">Tu peso corporal actual en kilogramos.</span>
                        </span>
                    </label>
                    <input type="number" step="0.01" name="peso" id="peso" required value="<?= $_SESSION['peso'] ?? '' ?>">
                    <br><br>
                    <label for="talla">Talla (m):
                        <span class="tooltip">ℹ️
                            <span class="tooltiptext">Tu altura expresada en metros (por ejemplo, 1.75).</span>
                        </span>
                    </label>
                    <input type="number" step="0.01" name="talla" id="talla" required value="<?= $_SESSION['talla'] ?? '' ?>">
                    <br><br>
                    <button type="submit" class="btn">Calcular</button>
                </form>
                    <a href="<?= BASE_URL ?>views/calcularGEB.php"><button class="btn-relleno-suave-pequeno-cursiva">➡︎ Siguiente paso</button></a>

                <?php if (isset($_SESSION['imc'])): ?>
                    <div class="resultados">
                        <p><strong>IMC:</strong> <?= $_SESSION['imc'] ?>
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">El Índice de Masa Corporal (IMC) se calcula dividiendo tu peso por tu talla al cuadrado. Se utiliza para evaluar tu estado nutricional.</span>
                            </span>
                        </p>
                        <p><strong>Peso Ideal:</strong> <?= $_SESSION['peso_ideal'] ?> kg
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">Peso estimado que se considera saludable según tu altura. Aquí se calcula usando un IMC ideal de 22.</span>
                            </span>
                        </p>
                        <p><strong>Clasificación:</strong> <?= ucfirst($_SESSION['clasificacion']) ?>
                            <span class="tooltip">ℹ️
                                <span class="tooltiptext">Clasificación según la Organización Mundial de la Salud (OMS) basada en tu IMC: normal, sobrepeso, obesidad, etc.</span>
                            </span>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
