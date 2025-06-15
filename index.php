<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Generador de Dieta</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
      rel="stylesheet"
    />
    <style>
    #changing-word {
    display: inline-block;
    transition: transform 0.5s ease, opacity 0.5s ease, color 0.5s ease;
    text-transform: lowercase;
    }
    .slide-out {
    transform: translateY(-10px);
    opacity: 0;
    }
    </style>
  </head>
  <body>
    <?php
      session_start();
    ?>

    <div class="container flex-c">
      <?php include "components/navbar.php"?>
        <header class="banner flex-c">
            <h1>Dieta <span class="word"><span id="changing-word">inteligente</span></span></h1>
            <p class="text-lg">
              Obtén tu dieta saludable <br />
              basada en tus necesidades y objetivos
            </p>
            <br>
            <?php if(isset($_SESSION['id_cliente'])): ?>
            <div class="two-buttons">
                  <a href="views/estudioAntropometrico.php"><button class="btn">Pulsa para iniciar</button></a>
            </div>
            <?php /* else: ?>
              <div class="two-buttons">
                  <a href="views/registro.php"><button class="btn">Generar Dieta</button></a>
                  <a href="views/login.php"><button class="btn">Iniciar Sesión</button></a>
              </div>
            <?php */ endif; ?>
        </header>

        <div class="home-cards flex-c">
            <div class="card box-s">
                <i class="fa-solid fa-utensils"></i>
                <h3>Personalizada</h3>
                <p>Recibe un plan de alimentación único, creado especificamente para ti</p>
            </div>
            <div class="card box-s">
                <i class="fa-solid fa-leaf"></i>
                <h3>Nutritiva</h3>
                <p>Proporcionamos los nutrientes esenciales que tu cuerpo necesita</p>
            </div>
            <div class="card box-s">
                <i class="fa-solid fa-microchip"></i>
                <h3>Inteligente</h3>
                <p>Nuestra IA analiza tus datos, necesidades y preferencias</p>
            </div>
        </div>
    </div>
    <?php include "components/footer.html"?>
    <script
      src="https://kit.fontawesome.com/6209fab7df.js"crossorigin="anonymous">
    </script>
    <script src="js/app.js"></script>
  </body>
</html>