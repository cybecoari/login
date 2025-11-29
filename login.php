<?php
// login.php
require_once 'config.php';
require_once 'functions.php';
secure_session_start();
ensure_https();

$page_title = 'Entrar';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token CSRF inválido.';
    }

    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email) $errors[] = 'Email inválido.';
    if (!$password) $errors[] = 'Senha é obrigatória.';

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, password_hash, name FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            // login success
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = 'Credenciais inválidas.';
        }
    }
}

require 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h2>Entrar</h2>
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?=htmlspecialchars($e)?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?=csrf_token()?>">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary">Entrar</button>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>
