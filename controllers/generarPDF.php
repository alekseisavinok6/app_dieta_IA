<?php
session_start();

if (!isset($_SESSION['dieta_generada'])) {
    exit("No hay dieta para exportar.");
}

require_once '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$dieta = $_SESSION['dieta_generada'];

$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

$html = "<h1>Dieta Personalizada</h1>";
$html .= "<p><strong>Descripción:</strong> " . htmlspecialchars($dieta['descripcion']) . "</p>";

$totalCalorias = 0;
foreach ($dieta['comidas'] as $nombreComida => $comida) {
    $html .= "<h2>" . htmlspecialchars($nombreComida) . " ({$comida['total_calorias']} kcal)</h2>";
    foreach ($comida['platos'] as $plato) {
        $html .= "<h3>" . htmlspecialchars($plato['nombre']) . "</h3><ul>";
        foreach ($plato['ingredientes'] as $ing) {
            $html .= "<li>" . htmlspecialchars($ing['nombre']) . " - " .
                     htmlspecialchars($ing['peso']) . " " .
                     htmlspecialchars($ing['medida']) . " - " .
                     htmlspecialchars($ing['valorCalorico']) . " kcal</li>";
        }
        $html .= "</ul>";
    }
    $totalCalorias += $comida['total_calorias'];
}

$html .= "<hr><p><strong>Total Calórico Diario:</strong> $totalCalorias kcal</p>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("dieta.pdf", ["Attachment" => false]);

exit();
