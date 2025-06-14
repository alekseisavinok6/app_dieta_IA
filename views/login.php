<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DietaApp-Login</title>
    <link rel="stylesheet" href="../css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
      rel="stylesheet"
    />
</head>
<body>
    <?php
    session_start();
    if (isset($_SESSION['id_cliente'])) {
        header("Location: ../index.php");
        exit();
    }
  ?>
    <div class="login-container flex-c box-s">
        <div class="login-left">
            <img src="../imgs/dieta-app.png" alt="Imagen de fondo" />
        </div>
        <div class="login-right">
            <a class="login-top" href="../index.php">
            <img src="../imgs/logo2.png" alt="DietaApp Logo" style="height: 60px;"></a>
            <h3>Bienvenido!</h3>
            <p class="text-lg">Inicia sesión para acceder a tus dietas personalizadas</p>
            <form id="login-form" class="login-form flex-c" action="../controllers/loginController.php" method="POST"> <!-- Cambia la acción al controlador correspondiente -->
                <div>
                    <input type="email" name="correo" placeholder="Correo"  />
                    <p class="input-correo-error input-error no-display">El correo no es válido</p>
                    <input type="password" name="password" placeholder="Contraseña"  />
                    <p class="input-password-error input-error no-display">La contraseña debe tener por lo menos 12 carácteres</p>
                </div>
                <?php 
                    if (isset($_GET['error']) && $_GET['error'] === 'credenciales') {
                        echo '<p class="form-msg"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Error:</strong> Correo o contraseña incorrectos.</p>';
                    }
                ?>
                <p class="form-msg hidden"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Error:</strong> Por favor, rellena el formulario correctamente.</p>
                <input type="submit" class="btn" value="Iniciar Sesión"/>
            </form>
            <?php
            include("../controllers/loginController.php");
            ?>
            </p>
            <p class="text-md">¿No tienes su cuenta? <a href="registro.php" class="link">Regístrate</a></p>
            <p class="text-md"><a href="../views/login.php" class="link disable">¿Has olvidado la contraseña?</a></p>    
        </div>
    </div>
    <?php include "../components/footer.html"?>
    <script src="../js/loginScript.js"></script>
    <script src="https://kit.fontawesome.com/6209fab7df.js "crossorigin="anonymous"></script>
</body>
</html>