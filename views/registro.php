<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DietaApp-Registrarse</title>
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

    define('BASE_URL', '../'); 
    if (isset($_SESSION['id_cliente'])) {
        header("Location: ../index.php");
        exit();
    }
?>
    <div class="registro-container box-s flex-c">
    <a href="<?= BASE_URL ?>index.php" class="logo">
    <img src="<?= BASE_URL ?>imgs/logo-main-2.png" alt="DietaApp Logo" style="height: 60px;"></a>
    <p class="text-lg">Crea tu cuenta para empezar a crear dietas 🍽️</p>
    <form id="registro-form" class="registro-form flex-c" action="../controllers/registroController.php" method="POST">
        <div>
            <div class="form-name">
                <div>
                    <input type="text" name="nombre" placeholder="Nombre" />
                    <p class="input-nombre-error input-registro-error no-display">El nombre no es válido</p>
                </div>
                <div>
                    <input type="text" name="apellido" placeholder="Apellido" />
                    <p class="input-apellido-error input-registro-error no-display">El apellido no es válido</p>
                </div>
            </div>
        </div>
        <div>
            <input type="email" name="correo" placeholder="Correo"/>
            <p class="input-correo-error input-registro-error no-display">El correo no es válido</p>
        </div>
        <div class="client-data">
            <div class="client-data-input form-peso">
                <label for="peso">Peso (kg)</label>
                <input type="number" name="peso" id="peso" placeholder="Peso" min="20" max="300"/>
                <p class="input-peso-error input-registro-error no-display">El peso introducido no es válido</p>
            </div>
            <div class="client-data-input form-altura">
                <label for="altura">Altura (cm)</label>
                <input type="number" name="altura" id="altura" placeholder="Altura" min="50" max="250"/>
                <p class="input-altura-error input-registro-error no-display">La altura introducida no es válida</p>
            </div>
            <div class="client-data-input form-nacimiento">
                <label for="f_nacimiento">Fecha nacimiento</label>
                <input type="date" name="f_nacimiento" id="f_nacimiento" />
                <p class="input-fNacimiento-error input-registro-error no-display">La fecha introducida no es válida</p>
            </div>
            <div class="client-data-input form-sexo">
                <label for="Sexo">Sexo biológico</label>
                <select name="sexo" id="sexo">
                    <option value="Hombre" selected>Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
                <p class="input-sexo-error input-registro-error no-display">El sexo no es válido</p>
            </div>
        </div>
        <p class="input-registro-error">Podrás agregar o eliminar más alergias en página de perfil.</p>
        <div class="client-data-checkbox flex-c">
            <div>
                <div class="checkbox-container alergenos-container flex-c">
                    <label for="alergenos">Alérgenos</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="alergenos[]" value="huevos">Huevos</label>
                        <label><input type="checkbox" name="alergenos[]" value="frutos secos">Frutos secos</label>
                        <label><input type="checkbox" name="alergenos[]" value="">Otras</label>
                        <label><input type="checkbox" name="alergenos[]" value="null">Ninguna</label>
                    </div>
                    <input type="text" name="otros_alergenos" placeholder="Otras alergias" />
                </div>
                <p class="input-alergenos-error input-registro-error no-display">Selecciona una opción</p>
            </div>
            <div>
                <div class="checkbox-container intolerancias-container flex-c">
                    <label for="intolerancias">Intolerancias</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="intolerancias[]" value="lactosa">Lactosa</label>
                        <label><input type="checkbox" name="intolerancias[]" value="gluten">Gluten</label>
                        <label><input type="checkbox" name="intolerancias[]" value="">Otras</label>
                        <label><input type="checkbox" name="intolerancias[]" value="null">Ninguna</label>
                    </div>
                    <input type="text" name="otras_intolerancias" placeholder="Otras intolerancias" />
                </div>
                <p class="input-intolerancias-error input-registro-error no-display">Selecciona una opción</p>
            </div>
            <div>
                <div class="checkbox-container enfermedades-container flex-c">
                    <label for="enfermedades">Enfermedades</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="enfermedades[]" value="diabetes">Diabetes</label>
                        <label><input type="checkbox" name="enfermedades[]" value="hipertension">Hipertensión</label>
                        <label><input type="checkbox" name="enfermedades[]" value="">Otras</label>
                        <label><input type="checkbox" name="enfermedades[]" value="null">Ninguna</label>
                    </div>
                    <input type="text" name="otras_enfermedades" placeholder="Otras enfermedades" />
                </div>
                <p class="input-enfermedades-error input-registro-error no-display">Selecciona una opción</p>
            </div>
        </div>
        <div class="form-password">
            <div>
                <input id="password" type="password" name="password" placeholder="Contraseña" />
                <p class="input-password-error input-registro-error  no-display">La contraseña debe tener por lo menos 12 carácteres</p>          
            </div>
            <div>
                <input id="password2" type="password" name="password2" placeholder="Confirmar contraseña" />
                <p class="input-password2-error input-registro-error  no-display">Las contraseñas no coinciden</p>
            </div>
        </div>
        <?php 
            if (isset($_GET['error']) && $_GET['error'] === 'correo_duplicado') {
                 echo '<p class="form-msg"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Error:</strong> El correo ingresado ya está registrado</p>';
            }
        ?>
        <p class="form-msg hidden"><i class="fa-solid fa-triangle-exclamation"></i> <strong>Error:</strong> Por favor, rellena el formulario correctamente.</p>
        <input type="submit" name="registrar" class="btn" value="Registrarse" style="width: 160px;"/>
        <p class="text-md">¿Ya tienes su cuenta? <a href="login.php" class="link">Iniciar sesión</a></p>    
    </form>
</div>
    <?php include "../components/footer.html"?> 
    <script src="../js/registroScript.js"></script>
    <script
      src="https://kit.fontawesome.com/6209fab7df.js"
      crossorigin="anonymous"
    ></script>
</body>
</html>