<?php
// config.php
// Altere para suas credenciais
define('DB_HOST', 'localhost');
define('DB_NAME', 'cyber_login');
define('DB_USER', 'cyber_login');
define('DB_PASS', '@cybercoari');

// Forçar HTTPS detection (opcional)
define('FORCE_HTTPS', false);

// PDO connection
try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // Em produção, não exiba o erro completo
    exit('Erro ao conectar ao banco: ' . $e->getMessage());
}
