<?php
require_once 'config.php';
require_once 'functions.php';
secure_session_start();
require_login();
$page_title = 'Dashboard';
require 'header.php';
?>

<div class="row">
  <div class="col-md-12">
    <h1>Olá, <?=htmlspecialchars($_SESSION['user_name'])?>!</h1>
    <p>Você está logado. Aqui vai conteúdo protegido.</p>
    <a class="btn btn-secondary" href="logout.php">Sair</a>
  </div>
</div>

<?php require 'footer.php'; ?>
