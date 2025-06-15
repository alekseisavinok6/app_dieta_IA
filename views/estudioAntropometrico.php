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
</style>

</head>
<body>
    <div class="container">
        <?php include "../components/navbar.php"; ?>

        <div class="generarDieta-container flex-c box-s">
            <div class="generar-left">
                <img src="../imgs/antropometria.png" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <h3><i>Paso 1: Estudio antropométrico</i> 📑</h3>

                <?php if (isset($mensaje)): ?>
                    <p style="color:green;"><?= $mensaje ?></p>
                <?php elseif (isset($error)): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>

                <form action="estudioAntropometrico.php" method="post">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" step="0.01" name="peso" id="peso" required value="<?= $_SESSION['peso'] ?? '' ?>">
                    <br><br>
                    <label for="talla">Talla (m):</label>
                    <input type="number" step="0.01" name="talla" id="talla" required value="<?= $_SESSION['talla'] ?? '' ?>">
                    <br><br>
                    <button type="submit" class="btn">Calcular</button>
                </form>
                    <a href="<?= BASE_URL ?>views/calcularGEB.php"><button class="btn-relleno-suave-pequeno-cursiva">➡︎ Siguiente paso</button></a>

                <?php if (isset($_SESSION['imc'])): ?>
                    <div class="resultados">
                        <p><strong>IMC:</strong> <?= $_SESSION['imc'] ?></p>
                        <p><strong>Peso Ideal:</strong> <?= $_SESSION['peso_ideal'] ?> kg</p>
                        <p><strong>Clasificación:</strong> <?= ucfirst($_SESSION['clasificacion']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
