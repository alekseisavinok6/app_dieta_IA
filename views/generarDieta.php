<?php
session_start();

// --- INICIO: Obtención de datos y cálculos iniciales ---
// Verificar si todos los datos necesarios de los pasos anteriores de la sesión están disponibles
if (
    !isset($_SESSION['imc']) ||
    !isset($_SESSION['calculo_energetico']['vct']) ||
    !isset($_SESSION['sexo']) ||
    !isset($_SESSION['edad']) ||
    !isset($_SESSION['peso']) ||
    !isset($_SESSION['talla']) ||
    !isset($_SESSION['calculo_energetico']['nivel_actividad']) ||
    !isset($_SESSION['clasificacion'])
) {
    // Redirigir de nuevo si faltan los datos subyacentes. Ajustar la ruta según sea necesario.
    header("Location: estudioAntropometrico.php");
    exit();
}

// Extraer datos básicos del usuario de la sesión
$imc = $_SESSION['imc'];
$clasificacion_oms = $_SESSION['clasificacion'];
$peso_actual = $_SESSION['peso'];
$talla_metros = $_SESSION['talla'];
$edad = $_SESSION['edad'];
$sexo = $_SESSION['sexo'];
$nivel_actividad_original = $_SESSION['actividad'] ?? 'moderada'; // Utilizamos el valor bruto de la actividad de calcularGEB
$nivel_actividad_descriptivo = $_SESSION['calculo_energetico']['nivel_actividad'];
$geb = $_SESSION['calculo_energetico']['gasto_energetico_basal'];
$get = $_SESSION['calculo_energetico']['gasto_energetico_total'];
$vct_calculado_inicial = $_SESSION['calculo_energetico']['vct']; // Cálculo del VCT a partir del GEB

// Obtener las preferencias del usuario a partir del formulario enviado
$preferencias_form = $_SESSION['form_preferencias']['preferencias'] ?? '';
$comentario_form = $_SESSION['form_preferencias']['comentario'] ?? '';
$objetivo_form = $_SESSION['form_preferencias']['objetivo'] ?? '';
$comidas_dia_form = $_SESSION['form_preferencias']['comidasDias'] ?? '';

// Obtener la dieta generada desde la sesión generarDietaController.php después de llamar a la API
$dieta_generada = $_SESSION['dieta_generada'] ?? null;
unset($_SESSION['dieta_generada']); // Limpiar después de la visualización para evitar datos obsoletos

// Procesamiento de mensajes potenciales del controlador
$mensaje = $_SESSION['mensaje_dieta_app'] ?? '';
unset($_SESSION['mensaje_dieta_app']);
$error = $_SESSION['error_dieta_app'] ?? '';
unset($_SESSION['error_dieta_app']);

// --- FIN: Adquisición de datos y cálculos iniciales ---
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Dieta Personalizada</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php include "../components/navbar.php"; ?>

        <div class="generarDieta-container flex-c box-s">
            <div class="generar-left">
                <img src="../imgs/generar_dieta.png" alt="Imagen de fondo" />
            </div>
            <div class="generar-right">
                <h3><i>Paso 3: Generación de dietas con IA</i> 🤖</h3>
                <?php if ($dieta_generada): ?>
                    <div class="dieta-generada-box">
                        <h3>Tu dieta personalizada:</h3>
                        <pre style="white-space: pre-wrap; word-wrap: break-word; background-color: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; font-size: 0.95em; line-height: 1.6;"><?= htmlspecialchars($dieta_generada) ?></pre>
                        <p style="margin-top: 20px; font-style: italic; color: #555;">
                            **Nota importante:** Esta dieta fue generada por Inteligencia Artificial con base en tus datos y preferencias. Es una recomendación y no sustituye la consulta con un nutricionista profesional. Consulta con un médico o nutricionista antes de realizar cambios significativos en tu dieta, especialmente si tienes alguna condición médica preexistente.
                        </p>
                         <button type="button" class="btn" onclick="window.print()">Imprimir la dieta</button>
                    </div>
                <?php else: // Mostrar formulario si aún no se ha generado la dieta ?>
                    <!-- <p class="text-lg">Envía un formulario con tus preferencias para generar una dieta.</p> -->
                    <?php if (!empty($mensaje)): ?>
                        <p style="color:green;"><?= $mensaje ?></p>
                    <?php elseif (!empty($error)): ?>
                        <p style="color:red;"><?= $error ?></p>
                    <?php endif; ?>

                    <form class="generar-form" id="generar-form" action="../controllers/generarDietaController.php" method="POST">
                        <div class="actividad">
                            <label for="nivelActividad">Nivel de actividad física:</label>
                            <select name="nivelActividad" id="nivelActividad">
                                <option value="1.2" <?= $nivel_actividad_original === 'sedentario' ? 'selected' : '' ?>>Inactivo (sedentario) 🧘🏽‍♂️</option>
                                <option value="1.375" <?= $nivel_actividad_original === 'ligera' ? 'selected' : '' ?>>Actividad ligera 🤹🏻‍♂️</option>
                                <option value="1.55" <?= $nivel_actividad_original === 'moderada' ? 'selected' : '' ?>>Actividad moderada 🤸🏻‍♂️</option>
                                <option value="1.725" <?= $nivel_actividad_original === 'intensa' ? 'selected' : '' ?>>Actividad intensa 🚴🏻‍♀️</option>
                                <option value="1.9" <?= $nivel_actividad_original === 'muy_intensa' ? 'selected' : '' ?>>Muy intenso 🚴🏻‍♀️🚴🏻‍♀️</option>
                            </select>
                        </div>
                        <div class="objetivo">
                            <label for="objetivo">Objetivo:</label>
                            <select name="objetivo" id="objetivo">
                                <?php
                                    $default_objetivo = '';
                                    if ($clasificacion_oms === "bajo peso") {
                                        $default_objetivo = 'subirPeso';
                                    } elseif (strpos($clasificacion_oms, 'obesidad') !== false || $clasificacion_oms === 'sobrepeso') {
                                        $default_objetivo = 'bajarPeso';
                                    } else {
                                        $default_objetivo = 'mantenerPeso';
                                    }
                                ?>
                                <option value="subirPeso" <?= ($objetivo_form === 'subirPeso' || $default_objetivo === 'subirPeso') ? 'selected' : '' ?>>Subir peso ☝️</option>
                                <option value="mantenerPeso" <?= ($objetivo_form === 'mantenerPeso' || $default_objetivo === 'mantenerPeso') ? 'selected' : '' ?>>Mantener peso 👍</option>
                                <option value="bajarPeso" <?= ($objetivo_form === 'bajarPeso' || $default_objetivo === 'bajarPeso') ? 'selected' : '' ?>>Bajar peso 👇</option>
                            </select>
                        </div>
                        <div class="comidasDia">
                            <label for="comidasDias">Número de comidas al día:</label>
                            <select name="comidasDias" id="comidasDias">
                                <option value="3" <?= ($comidas_dia_form == 3 || empty($comidas_dia_form)) ? 'selected' : '' ?>>3</option>
                                <option value="4" <?= ($comidas_dia_form == 4) ? 'selected' : '' ?>>4</option>
                                <option value="5" <?= ($comidas_dia_form == 5) ? 'selected' : '' ?>>5</option>
                            </select>
                        </div>
                        <div class="preferencias">
                            <label for="preferencias">Tipo de dieta (opcional):</label>
                            <select name="preferencias" id="preferencias">
                                <option value="" <?= empty($preferencias_form) ? 'selected' : '' ?>>Normal (sin restricciones especiales)</option>
                                <option value="ovolactovegetariana" <?= ($preferencias_form === 'ovolactovegetariana') ? 'selected' : '' ?>>Ovolactovegetariano</option>
                                <option value="vegana" <?= ($preferencias_form === 'vegana') ? 'selected' : '' ?>>Vegano</option>
                                <option value="cetogenica" <?= ($preferencias_form === 'cetogenica') ? 'selected' : '' ?>>Cetogénica</option>
                                <option value="sinGluten" <?= ($preferencias_form === 'sinGluten') ? 'selected' : '' ?>>Sin gluten</option>
                            </select>
                        </div>
                        <div class="comentario">
                            <label for="comentario">Comentarios adicionales (opcional):</label>
                            <textarea name="comentario" id="comentario" placeholder="Ejemplo: No me gusta el atún, soy alérgico al maní, prefiero la comida rápida."><?= htmlspecialchars($comentario_form) ?></textarea>
                        </div>
                        <p class="form-msg hidden"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Error:</strong> Por favor, rellene el formulario correctamente.</p>
                        <input type="submit" value="Generar dieta" name="generarDieta" class="btn" style="width: 160px;">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>