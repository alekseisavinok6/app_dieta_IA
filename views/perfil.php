<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DietaApp-Perfil</title>
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
  // $id_cliente = $_SESSION['id_cliente'];
  // $nombre = $_SESSION['nombre'];
  // $apellido = $_SESSION['apellido'];
  // $correo = $_SESSION['correo'];
  // $edad = $_SESSION['edad'];
  // $sexo = $_SESSION['sexo'];
  // $talla = $_SESSION['talla'];
  // $peso = $_SESSION['peso'];
  // $enfermedades = $_SESSION['enfermedades'];
  // $enfermedadesA = explode(',', $enfermedades);
  // $alergias = $_SESSION['alergias'];
  // $alergiasA = explode(',', $alergias);
  // $intolerancias = $_SESSION['intolerancias'];
  // $intoleranciasA = explode(',', $intolerancias);
  // $inicial = strtoupper(substr($nombre,0,1));
  if (!isset($_SESSION['id_cliente'])) {
    header("Location: ../views/login.php");
    exit();
  }
  ?>
  <div class="container flex-c">
      <?php include "../components/navbar.php"?>
      <div class="profile-container flex-c">
        <!-- IZQUIERDA -->
        <div class="profile-left-container flex-c">
          <div class="profile-icon">
            <?= $inicial ?>
          </div>
          <!-- <div class="list-dieta-container box-s">
            <h3 class="dieta-title">Dietas</h3>
            <ul class="list-dieta">
              <li class="dieta-item"><a href="#" class="dieta-link" >Dieta 1</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Nombre Dieta</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Miércoles</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Jueves</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Viernes</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Sábado</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">Domingo</a></li>
              <li class="dieta-item"><a href="#" class="dieta-link">28-03-2001</a></li>
            </ul>
          </div> -->
          <!-- <a href="../views/generarDieta.php"><button class="btn btn-lg">Generar Dieta</button></a> -->
        </div>

        <!-- DERECHA -->
        <div class="profile-right-container flex-c">
          <h3 style="font-size: xx-large;">Información personal:</h3>
            <div class="data-profile-container box-s">
              <!-- <h3>Id: <i><?= $id_cliente ?></i></h3>
              <h3>Nombre: <i><?= $nombre ?></i></h3>
              <h3>Apellido: <i><?= $apellido ?></i></h3>
              <h3>Correo: <i><?= $correo ?></i></h3>
              <h3>Edad: <i><?= $edad ?></i></h3>
              <h3>Sexo: <i><?= $sexo ?></i></h3>
              <h3>Talla (m): <i><?= $talla ?></i></h3>
              <h3>Peso (kg): <i><?= $peso ?></i></h3>
              <h3>Enfermedades: <i><?= $enfermedades ?></i></h3>
              <h3>Alergias: <i><?= $alergias ?></i></h3>
              <h3>Intolerancias: <i><?= $intolerancias ?></i></h3> -->

              <h3 style="font-size: 22px;">Id: <i><?= $_SESSION['id_cliente']; ?></i></h3>
              <h3 style="font-size: 22px;">Apellido: <i><?= $_SESSION['apellido']; ?></i></h3>
              <h3 style="font-size: 22px;">Nombre: <i><?= $_SESSION['nombre']; ?></i></h3>
              <h3 style="font-size: 22px;">Correo: <i><?= $_SESSION['correo']; ?></i></h3>
              <h3 style="font-size: 22px;">Edad: <i><?= $_SESSION['edad']; ?></i></h3>
              <h3 style="font-size: 22px;">Sexo: <i><?= $_SESSION['sexo']; ?></i></h3>
              <h3 style="font-size: 22px;">Talla (m): <i><?= $_SESSION['talla']; ?></i></h3>
              <h3 style="font-size: 22px;">Peso (kg): <i><?= $_SESSION['peso']; ?></i></h3>
              <h3 style="font-size: 22px;">Enfermedades: <i><?= $_SESSION['enfermedades']; ?></i></h3>
              <h3 style="font-size: 22px;">Alergias: <i><?= $_SESSION['alergias']; ?></i></h3>
              <h3 style="font-size: 22px;">Intolerancias: <i><?= $_SESSION['intolerancias']; ?></i></h3>
              <h3 style="font-size: 22px;">Clasificación: <i><?= $_SESSION['clasificacion']; ?></i></h3>
              <h3 style="font-size: 22px;">Actividad: <i><?= $_SESSION['actividad']; ?></i></h3>
              <h3 style="font-size: 22px;">Imc: <i><?= $_SESSION['imc']; ?></i></h3>
              <h3 style="font-size: 22px;">Geb: <i><?= $_SESSION['calculo_energetico']['geb']; ?></i></h3>
              <h3 style="font-size: 22px;">Get: <i><?= $_SESSION['calculo_energetico']['get1']; ?></i></h3>
              <h3 style="font-size: 22px;">Vct: <i><?= $_SESSION['calculo_energetico']['vct']; ?></i></h3>












            <!-- <div class="actual-data-profile flex-c">
              <?= $nombre ?>
              <h3>Ajustes</h3>
              <h4>Talla: <?= $talla ?></h4>
              <h4>Peso: <?= $peso ?></h4>
            </div>
            <div class="perfil-form">
              <div class="inputs-container flex-c"> 
                <?= $nombre ?>
                <form class="input-group" id="altura-form" action="../controllers/perfilController.php" method="POST">
                  <label for="altura">Altura</label>
                  <input type="number" name="altura" id="altura">
                  <p>cm</p>
                  <button type="submit" class="btn btn-input" name="actualizarAltura"><i class="fa-solid fa-check"></i></button>
                </form>
                <form class="input-group" id="peso-form" action="../controllers/perfilController.php" method="POST">
                  <label for="peso">Peso</label>
                  <input type="number" name="peso" id="peso">
                  <p>kg</p>
                  <button type="submit" class="btn btn-input" name="actualizarPeso"><i class="fa-solid fa-check"></i></button>
                </form>
                <form class="input-group" id="peso-deseado-form" action="../controllers/perfilController.php" method="POST">
                  <label for="pesoDeseado">Peso Deseado</label>
                  <input type="number" name="pesoDeseado" id="pesoDeseado">
                  <p>kg</p>
                  <button type="submit" class="btn btn-input" name="actualizarPesoDeseado"><i class="fa-solid fa-check"></i></button>
                </form>
              </div>
              <p class="input-altura-error input-registro-error"></p>
              <div class="select-container flex-c">
                <div class="select-container-inner flex-c">
                  <label for="alergias">Alergias</label>
                  <form class="select-group input-group" action="../controllers/perfilController.php" method="POST">
                    <select name="alergias" id="">
                      <option value="">-- Select --</option>
                      <option value="huevo">Huevo</option>
                      <option value="frutosSecos">Frutos Secos</option>
                      <option value="gluten">Glúten</option>
                      <option value="pescado">Pescado</option>
                    </select>
                    <button type="submit" class="btn btn-input" name="actualizarAlergias"><i class="fa-solid fa-check"></i></button>
                  </form>
                  <div class="select-container-box box-s">
                    <?php foreach($alergiasA as $alergia): ?> 
                      <?php if($alergia != ""): ?>
                        <form class="select-container-item box-s flex-c" action="../controllers/perfilController.php" method="POST">
                          <h4><?= htmlspecialchars(strtoupper($alergia)) ?></h4>
                          <input type="hidden" name="eliminarAlergia" value="<?= htmlspecialchars($alergia) ?>">
                          <button type="submit" class="btn-trash"><i class="fa-solid fa-trash"></i></button>
                        </form>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="select-container-inner flex-c">
                  <label for="intolerancias">Intolerancias</label>
                  <form class="select-group input-group" action="../controllers/perfilController.php" method="POST">
                    <select name="intolerancias" id="">
                      <option value="">-- Select --</option>
                      <option value="lactosa">Lactosa</option>
                      <option value="gluten">Glúten</option>
                      <option value="fructuosa">Fructuosa</option>
                      <option value="histamina">Histamina</option>
                    </select>
                    <button type="submit" class="btn btn-input"><i class="fa-solid fa-check"></i></button>
                  </form>
                  <div class="select-container-box box-s">
                    <?php foreach($intoleranciasA as $intolerancia): ?> 
                      <?php if($intolerancia != "NULL"): ?>
                        <form class="select-container-item box-s flex-c" action="../controllers/perfilController.php" method="POST">
                          <h4><?= htmlspecialchars(strtoupper($intolerancia)) ?></h4>
                          <input type="hidden" name="eliminarIntolerancia" value="<?= htmlspecialchars($intolerancia) ?>">
                          <button type="submit" class="btn-trash"><i class="fa-solid fa-trash"></i></button>
                        </form>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
  </div>
  <br><br>

  <?php include "../components/footer.html"?>
  <!-- <script src="../js/perfilScript.js"></script> -->
  <script
    src="https://kit.fontawesome.com/6209fab7df.js"
    crossorigin="anonymous"
  ></script>
</body>
</html>