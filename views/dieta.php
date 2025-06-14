<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tu Dieta Generada</title>
    <link rel="stylesheet" href="../css/styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 2em;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }
        .dieta-content {
            white-space: pre-wrap;
            word-wrap: break-word; 
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1.05em;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        .disclaimer {
            font-style: italic;
            color: #666;
            font-size: 0.9em;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #eee;
        }
        .btn-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn-group .btn {
            display: inline-block;
            margin: 0 10px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include "../components/navbar.php"; ?> <?php if (isset($_SESSION['dieta_generada'])): ?>
        <h2><i>Tu dieta personalizada </i>🥗🍽️🥑</h2>

        <div class="dieta-content">
            <?= htmlspecialchars($_SESSION['dieta_generada']) ?>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-success" onclick="window.print()">Imprimir dieta</button>
            <a href="<?= BASE_URL ?? '/' ?>views/generarDieta.php" class="btn btn-secondary">Generar nueva dieta</a>
            <a href="<?= BASE_URL ?? '/' ?>index.php" class="btn btn-primary">Volver al inicio</a>
        </div>


        <p class="disclaimer">
            <i>¡Nota importante!</i>: Esta dieta ha sido generada por Inteligencia Artificial basándose en tus datos y preferencias. Es una sugerencia y no sustituye la consulta con un profesional de la nutrición. Consulta a un médico o dietista antes de realizar cambios significativos en tu alimentación, especialmente si tienes condiciones médicas preexistentes.
        </p>

    <?php else: ?>
        <h2>Dieta no disponible</h2>
        <p>No se ha generado ninguna dieta o ha habido un problema. Por favor, <a href="<?= BASE_URL ?? '/' ?>views/generarDieta.php">vuelve al formulario para generar una nueva dieta</a>.</p>
        <?php if (isset($_SESSION['error_dieta_app'])): ?>
            <p style="color:red;"><strong>Error:</strong> <?= htmlspecialchars($_SESSION['error_dieta_app']) ?></p>
            <?php unset($_SESSION['error_dieta_app']); // Limpiar el error después de mostrarlo ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>