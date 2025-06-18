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
   <img src="<?= BASE_URL ?>imgs/logo-main-2.png" alt="DietaApp Logo" style="height: 60px;"></a>
  <?php if(isset($_SESSION['id_cliente'])): ?>
    <div class="two-buttons menu-links">
      <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://x.com" target="_blank"><i class="fab fa-twitter"></i></a>
      <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
      <a href="https://www.mail.com" target="_blank"><i class="fa-solid fa-envelope"></i></a>
      <a href="<?= BASE_URL ?>controllers/logoutController.php" class="menu-link"><button class="btn">Cerrar sesión</button></a>
      <a href="<?= BASE_URL ?>views/perfil.php"><button class="btn btn-perfil"> <?= $inicial ?></button></a>      
    </div>
  <?php else: ?>
    <div class="two-buttons">
      <a href="<?= BASE_URL ?>views/registro.php"><button class="btn">Registrarse</button></a>
      <a href="<?= BASE_URL ?>views/login.php"><button class="btn">Iniciar sesión</button></a>
    </div>
  <?php endif; ?>
</nav>