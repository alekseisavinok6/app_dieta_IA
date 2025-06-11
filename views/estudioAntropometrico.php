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
            $clasificacion = "Bajo peso";
        } elseif ($imc < 25) {
            $clasificacion = "Peso normal";
        } elseif ($imc < 30) {
            $clasificacion = "Sobrepeso";
        } elseif ($imc < 35) {
            $clasificacion = "Obesidad grado I";
        } elseif ($imc < 40) {
            $clasificacion = "Obesidad grado II";
        } else {
            $clasificacion = "Obesidad grado III";
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
</head>
<body>
    <div class="container">
        <?php include "../components/navbar.php"; ?>

        <div class="generarDieta-container flex-c box-s">
            <div class="generar-left">
                <img src="../imgs/img2.jpg" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <a href="<?= BASE_URL ?>index.php" class="logo">
                    <img src="<?= BASE_URL ?>imgs/logo2.png" alt="DietaApp Logo" style="height: 60px;">
                </a>

                <h2>Estudio Antropométrico 📑</h2>

                <?php if (isset($mensaje)): ?>
                    <p style="color:green;"><?= $mensaje ?></p>
                <?php elseif (isset($error)): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>

                <form action="estudioAntropometrico.php" method="post">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" step="0.01" name="peso" id="peso" required value="<?= $_SESSION['peso'] ?? '' ?>">
                    <br>
                    <label for="talla">Talla (m):</label>
                    <input type="number" step="0.01" name="talla" id="talla" required value="<?= $_SESSION['talla'] ?? '' ?>">
                    <br>
                    <button type="submit" class="btn">Calcular</button>
                </form>

                <?php if (isset($_SESSION['imc'])): ?>
                    <div class="resultados">
                        <p><strong>IMC:</strong> <?= $_SESSION['imc'] ?></p>
                        <p><strong>Peso Ideal:</strong> <?= $_SESSION['peso_ideal'] ?> kg</p>
                        <p><strong>Clasificación:</strong> <?= $_SESSION['clasificacion'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
