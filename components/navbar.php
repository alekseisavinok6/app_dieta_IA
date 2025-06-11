<?php
  if(!defined('BASE_URL')) {
    require_once __DIR__ . '/../controllers/conexionLocal.php';
  }
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado';
  $inicial = strtoupper(substr($nombre,0,1));
?>

<nav class="navbar flex-c box-s">
  <a href="<?= BASE_URL ?>index.php" class="logo">
   <img src="<?= BASE_URL ?>imgs/logo.png" alt="DietaApp Logo" style="height: 45px;"></a>
  <?php if(isset($_SESSION['id_cliente'])): ?>
    <div class="two-buttons menu-links">
      <a href="<?= BASE_URL ?>controllers/logoutController.php" class="menu-link"><button class="btn">Cerrar Sesión</button></a>
      <!-- <a href="<?= BASE_URL ?>views/generarDieta.php" class="menu-link"><button class="btn">Generar Dieta</button></a> -->
      <a href="<?= BASE_URL ?>views/perfil.php"><button class="btn btn-perfil"> <?= $inicial ?></button></a>
    </div>
  <?php else: ?>
    <div class="two-buttons">
      <a href="<?= BASE_URL ?>views/registro.php"><button class="btn">Registrarse</button></a>
      <a href="<?= BASE_URL ?>views/login.php"><button class="btn">Iniciar Sesión</button></a>
    </div>
  <?php endif; ?>
</nav>