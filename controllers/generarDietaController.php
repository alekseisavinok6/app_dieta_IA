<?php
session_start();
include_once "conexionLocal.php";

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

// Compruebe si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['generarDieta'])) {

    // --- 1. Obtener datos del usuario de la sesión (de los pasos de cálculo anteriores) ---
    $imc = $_SESSION['imc'] ?? null;
    $clasificacion_oms = $_SESSION['clasificacion'] ?? null;
    $peso_actual = $_SESSION['peso'] ?? null;
    $talla_metros = $_SESSION['talla'] ?? null;
    $edad = $_SESSION['edad'] ?? null;
    $sexo = $_SESSION['sexo'] ?? null;
    $nivel_actividad_original = $_SESSION['actividad'] ?? null; // por ejemplo, 'sedentario'
    $nivel_actividad_descriptivo = $_SESSION['calculo_energetico']['nivel_actividad'] ?? null; // por ejemplo, 'Actividad sedentaria'
    $geb = $_SESSION['calculo_energetico']['gasto_energetico_basal'] ?? null;
    $get = $_SESSION['calculo_energetico']['gasto_energetico_total'] ?? null;
    $vct_calculado_inicial = $_SESSION['calculo_energetico']['vct'] ?? null;

    // Comprobar si hay datos importantes disponibles
    if (!$imc || !$vct_calculado_inicial || !$sexo || !$edad || !$peso_actual || !$talla_metros || !$nivel_actividad_descriptivo) {
        $_SESSION['error_dieta_app'] = "No hay suficientes datos básicos para generar una dieta. Por favor, complete el estudio antropométrico y el cálculo energético.";
        header("Location: " . BASE_URL . "views/generarDieta.php");
        exit();
    }

    // --- 2. Obtenga las preferencias del usuario del formulario enviado ---
    $nivel_actividad_form_factor = floatval($_POST['nivelActividad'] ?? 1.55); // Factor moderado por defecto
    $objetivo_usuario_form = $_POST['objetivo'] ?? 'mantenerPeso';
    $comidas_dia_form = intval($_POST['comidasDias'] ?? 3);
    // Definir la distribución de calorías según el número de comidas
    $distribucion_comidas = [];

    switch ($comidas_dia_form) {
        case 3:
            $distribucion_comidas = [
                'Desayuno' => 0.35,
                'Comida'   => 0.40,
                'Cena'     => 0.25
            ];
            break;
        case 4:
            $distribucion_comidas = [
                'Desayuno' => 0.30,
                'Snack'    => 0.10,
                'Comida'   => 0.35,
                'Cena'     => 0.25
            ];
            break;
        case 5:
            $distribucion_comidas = [
                'Desayuno' => 0.25,
                'Snack'    => 0.10,
                'Comida'   => 0.35,
                'Merienda' => 0.10,
                'Cena'     => 0.20
            ];
            break;
    }

    $preferencias_dieta_form = $_POST['preferencias'] ?? '';
    $comentario_adicional_form = trim($_POST['comentario'] ?? '');

    // Guardar las preferencias del formulario en la sesión para un formulario fijo (opcional, pero mejora la experiencia del usuario)
    $_SESSION['form_preferencias'] = [
        'nivelActividad' => $nivel_actividad_form_factor, // Guardar el factor seleccionado
        'objetivo' => $objetivo_usuario_form,
        'comidasDias' => $comidas_dia_form,
        'preferencias' => $preferencias_dieta_form,
        'comentario' => $comentario_adicional_form
    ];


    // --- 3. Ajuste el VCT según el objetivo seleccionado por el usuario ---
    $vct_final_objetivo = $vct_calculado_inicial; // Comenzamos con el GET/VCT calculado

    switch ($objetivo_usuario_form) {
        case 'bajarPeso':
            // Reducir calorías para perder peso (por ejemplo, déficit de 300-500 kcal)
            // Resta 400 kcal al VCT inicial → genera un déficit calórico moderado para perder peso de forma saludable (aprox. 0.5 kg/semana).
            $vct_final_objetivo -= 400; // Ajustar según sea necesario
            // Proporcionamos calorías mínimas para mayor seguridad.
            // Así se evita generar dietas con un aporte calórico peligrosamente bajo.
            if ($sexo === 'mujer' && $vct_final_objetivo < 1200) $vct_final_objetivo = 1200;
            if ($sexo === 'hombre' && $vct_final_objetivo < 1500) $vct_final_objetivo = 1500;
            break;
        case 'subirPeso':
            // Aumentar las calorías para ganar peso (por ejemplo, un superávit de 300-500 kcal)
            // Suma 400 kcal al VCT → crea un superávit calórico para promover el aumento de masa muscular o peso corporal.
            $vct_final_objetivo += 400; // Ajustar según sea necesario
            break;
        case 'mantenerPeso':
        default:
            // No se hace ningún cambio
            // Se deja el VCT tal cual está, porque ya fue calculado con el objetivo de mantenimiento
            break;
    }

    // --- 4. Compilación de una solicitud detallada para Géminis ---
    $prompt_para_gemini = "Genera un plan de dieta personalizada para una persona que vive en España, con las siguientes características:\n\n";
    $prompt_para_gemini .= "- **Género:** " . ($sexo === 'hombre' ? 'masculino' : 'femenino') . "\n";
    $prompt_para_gemini .= "- **Edad:** " . $edad . " años\n";
    $prompt_para_gemini .= "- **Peso actual:** " . $peso_actual . " kg\n";
    $prompt_para_gemini .= "- **Altura (talla):** " . ($talla_metros * 100) . " cm\n"; // Convirtamos metros a cm para mayor claridad.
    $prompt_para_gemini .= "- **IMC:** " . number_format($imc, 2) . " (" . $clasificacion_oms . ")\n";
    $prompt_para_gemini .= "- **Nivel de actividad física especificado por el usuario:** " . $nivel_actividad_descriptivo . "\n";
    $prompt_para_gemini .= "- **Gasto energético total estimado (GET):** " . number_format($get, 2) . " kcal/día\n";
    $prompt_para_gemini .= "- **Objetivo dietético seleccionado por el usuario:** " . $objetivo_usuario_form . "\n";
    $prompt_para_gemini .= "- **Objetivo calórico total de la dieta (VCT):** " . number_format($vct_final_objetivo, 2) . " kcal/día\n\n";
        // Distribución de calorías al prompt
    $prompt_para_gemini .= "Distribuye las calorías diarias según esta proporción entre las comidas:\n";
    foreach ($distribucion_comidas as $nombre_comida => $proporcion) {
        $kcal = round($vct_final_objetivo * $proporcion);
        $prompt_para_gemini .= "- $nombre_comida: $kcal kcal (" . ($proporcion * 100) . "%)\n";
    }
    $prompt_para_gemini .= "\nImportante: El desayuno debe contener la mayor parte de la energía del día, y la cena debe ser la comida más ligera. No inviertas este orden.\n\n";
    $prompt_para_gemini .= "- **Creame la dieta a partir del valor VCT:" . $vct_final_objetivo . "\n";
    $prompt_para_gemini .= "Por favor, crea un plan de comidas de 7 días (una semana) que incluya " . $comidas_dia_form . " comidas al día.
    Calcúlame el menú en base a " . $vct_final_objetivo . " la dieta a partir del valor VCT.
    La dieta debe estar orientada a la gastronomía y productos típicos de España, siguiendo un patrón de dieta mediterránea. 
    Incluye comidas que un español suele comer en su día a día. Sé detallado y fácil de seguir. 
    Incluye una lista de compras y un resumen de macronutrientes diario. 
    Escribe la dieta como un texto informativo, día por día y comida por comida.\n\n"; // ¡Aquí está la clave para que IA Gemini "considere" la cocina y hábitos alimenticios españoles!

    // Añadiendo preferencias dietéticas
    if (!empty($preferencias_dieta_form)) {
        $pref_text = '';
        switch ($preferencias_dieta_form) {
            case 'ovolactovegetariana': $pref_text = 'La dieta debe ser ovolactovegetariana (sin carne ni pescado, pero con huevos y lácteos).'; break;
            case 'vegana': $pref_text = 'La dieta debe ser completamente vegana (sin productos animales).'; break;
            case 'cetogenica': $pref_text = 'La dieta debe ser cetogénica (muy baja en carbohidratos, alta en grasas y moderada en proteínas).'; break;
            case 'sinGluten': $pref_text = 'La dieta debe ser sin gluten.'; break;
            default: $pref_text = ''; break;
        }
        if (!empty($pref_text)) {
            $prompt_para_gemini .= "<i>Tipo de dieta preferido:</i> " . $pref_text . "\n";
        }
    }

    // Agregar comentarios adicionales
    if (!empty($comentario_adicional_form)) {
        $prompt_para_gemini .= "Comentarios adicionales de los usuarios: " . $comentario_adicional_form . "\n";
    }

    $prompt_para_gemini .= "\nIncluye una estimación de macronutrientes (proteínas, carbohidratos, grasas) para cada día y un ejemplo de lista de compras para la dieta de esta semana. Preséntala en un formato claro y fácil de leer.";


    // --- 5. Llamada a la API de Gemini ---
    $gemini_api_key = 'AIzaSyBy10f0PuX7e9Z7aMi1k0eKO8pDn_lK36U'; // Reemplaza con tu clave de API de Gemini
    $api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $gemini_api_key;

    $headers = [
        "Content-Type: application/json"
    ];
    $data = json_encode([
        "contents" => [
            ["parts" => [["text" => $prompt_para_gemini]]]
        ]
    ]);

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para el desarrollo, deshabilite la verificación SSL si es necesario. ¡ELIMINAR EN PRODUCCIÓN!

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    $dieta_generada = "";
    if ($curl_error) {
        $_SESSION['error_dieta_app'] = "Error al conectarse a la API de Gemini:" . $curl_error;
    } elseif ($http_code != 200) {
        $error_response = json_decode($response, true);
        $_SESSION['error_dieta_app'] = "Error de API de Gemini (Código HTTP: " . $http_code . "): " . ($error_response['error']['message'] ?? 'Error desconocido.');
    } else {
        $gemini_output = json_decode($response, true);
        $dieta_generada = $gemini_output['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo generar la dieta. Inténtalo de nuevo o ajusta tus preferencias.';

        // Guardamos la dieta generada en la sesión para mostrarla en dieta.php
        $_SESSION['dieta_generada'] = $dieta_generada;
        $_SESSION['mensaje_dieta_app'] = "Dieta generada con éxito.";
    }

    // --- 6. Redirigir de nuevo a dieta.php para mostrar los resultados ---
    header("Location: " . BASE_URL . "views/dieta.php");
    exit();


} else {
    // Si se recibe el acceso directamente sin enviar el formulario, redirigir o mostrar un error
    $_SESSION['error_dieta_app'] = "Acceso no autorizado al generador de dieta.";
    header("Location: " . BASE_URL . "views/generarDieta.php");
    exit();
}