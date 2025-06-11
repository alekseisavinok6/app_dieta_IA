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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $peso = floatval($_POST['peso'] ?? 0);
    $talla = floatval($_POST['talla'] ?? 0);
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
            'ligero' => ['factor' => 1.375, 'desc' => 'Actividad ligera'],
            'moderado' => ['factor' => 1.55, 'desc' => 'Actividad moderada'],
            'intenso' => ['factor' => 1.725, 'desc' => 'Actividad intensa'],
            'muy_intenso' => ['factor' => 1.9, 'desc' => 'Actividad muy intensa'],
        ];

        $factor = $niveles[$actividad]['factor'] ?? 1;
        $nivel_actividad = $niveles[$actividad]['desc'] ?? 'Desconocido';

        $peso_ideal = pow($talla, 2) * 22; // ¿Esta líne es necesaria? Peso idel lo hemos calculado en estudioAntropometrico.php
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
        $_SESSION['calculo_energetico'] = [
            'geb' => round($geb, 2),
            'get' => round($get, 2),
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
            $stmt = $conn->prepare("UPDATE datos_cliente SET peso = ?, talla = ?, edad = ?, sexo = ?, actividad = ?, geb = ?, get = ?, vct = ?, peso_ideal = ? WHERE id_cliente = ?");
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

$geb = $_SESSION['calculo_energetico']['geb'] ?? null;
$get = $_SESSION['calculo_energetico']['get'] ?? null;
$vct = $_SESSION['calculo_energetico']['vct'] ?? null;
$nivel_actividad = $_SESSION['calculo_energetico']['nivel_actividad'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cálculo GEB, GET y VCT</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <?php include "../components/navbar.php"; ?>
        <div class="generarDieta-container flex-c box-s">
            <div class="generar-left">
                <img src="../imgs/img1.jpg" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <a href="<?= BASE_URL ?>index.php" class="logo">
                    <img src="<?= BASE_URL ?>imgs/logo2.png" alt="DietaApp Logo" style="height: 60px;">
                </a>

                <h2>Cálculo del Gasto Energético ⚡</h2>

                <?php if (!empty($mensaje)): ?>
                    <p style="color:green;"><?= $mensaje ?></p>
                <?php elseif (!empty($error)): ?>
                    <p style="color:red;"><?= $error ?></p>
                <?php endif; ?>

                <form method="POST" class="form">
                    <label for="peso">Peso (kg):</label>
                    <input type="number" step="0.1" name="peso" required value="<?= htmlspecialchars($peso) ?>">

                    <label for="talla">Talla (m):</label>
                    <input type="number" step="0.01" name="talla" required value="<?= htmlspecialchars($talla) ?>">

                    <label for="edad">Edad (años):</label>
                    <input type="number" name="edad" required value="<?= htmlspecialchars($edad) ?>">

                    <label>Sexo:</label>
                    <div class="radio-group">
                        <label><input type="radio" name="sexo" value="hombre" <?= $sexo === 'hombre' ? 'checked' : '' ?>> Hombre</label>
                        <label><input type="radio" name="sexo" value="mujer" <?= $sexo === 'mujer' ? 'checked' : '' ?>> Mujer</label>
                    </div>

                    <label for="actividad">Nivel de actividad física:</label>
                    <select name="actividad" required>
                        <option value="">Seleccione...</option>
                        <option value="sedentario" <?= $actividad === 'sedentario' ? 'selected' : '' ?>>Sedentario</option>
                        <option value="ligero" <?= $actividad === 'ligero' ? 'selected' : '' ?>>Actividad ligera</option>
                        <option value="moderado" <?= $actividad === 'moderado' ? 'selected' : '' ?>>Actividad moderada</option>
                        <option value="intenso" <?= $actividad === 'intenso' ? 'selected' : '' ?>>Actividad intensa</option>
                        <option value="muy_intenso" <?= $actividad === 'muy_intenso' ? 'selected' : '' ?>>Actividad muy intensa</option>
                    </select>

                    <br>
                    <button type="submit" class="btn">Calcular</button>
                </form>

                <?php if ($geb && $get && $vct): ?>
                    <div class="resultados">
                        <p><strong>GEB:</strong> <?= number_format($geb, 2) ?> kcal/día</p>
                        <p><strong>GET:</strong> <?= number_format($get, 2) ?> kcal/día</p>
                        <p><strong>VCT:</strong> <?= number_format($vct, 2) ?> kcal/día</p>
                        <p><strong>Nivel de actividad:</strong> <?= htmlspecialchars($nivel_actividad) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
