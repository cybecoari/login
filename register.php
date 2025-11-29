<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// register.php
require_once 'config.php';
require_once 'functions.php';
secure_session_start();
ensure_https();

$page_title = 'Registrar';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token CSRF inválido.';
    }

    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!$name) $errors[] = 'Nome é obrigatório.';
    if (!$email) $errors[] = 'Email inválido.';
    if (strlen($password) < 4) $errors[] = 'A senha deve ter pelo menos 4 caracteres.';
    if ($password !== $password_confirm) $errors[] = 'As senhas não coincidem.';

    if (empty($errors)) {
        // Verificar se email já existe
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email já cadastrado.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $password_hash]);
            $success = 'Registro realizado com sucesso. Você pode entrar agora.';
        }
    }
}

require 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Registrar</h2>
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?=htmlspecialchars($e)?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="alert alert-success"><?=htmlspecialchars($success)?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required value="<?=htmlspecialchars($_POST['name'] ?? '')?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirmar Senha</label>
        <input type="password" name="password_confirm" class="form-control" required>
      </div>
      <button class="btn btn-primary">Registrar</button>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>
