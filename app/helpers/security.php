<?php

// Start one session in every page that uses login or security.
function startSecuritySession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        // HttpOnly: JavaScript cannot read the cookie. SameSite: not sent from other websites.
        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

startSecuritySession();

// Make data safe to show inside HTML.
function cleanText(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Short helper: safely print any value (even null) inside HTML.
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Make one random token for forms. It helps stop fake form submissions.
function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfInput(): string
{
    return '<input type="hidden" name="csrf_token" value="' . cleanText(getCsrfToken()) . '">';
}

// Draw a button that submits a small POST form. Used for actions like delete, approve and block.
// $fields = extra hidden values, e.g. ['election_id' => 5, 'action' => 'delete'].
function postButton(string $url, array $fields, string $label, string $class = '', string $confirm = '', string $formClass = 'inline'): string
{
    $html = '<form method="post" action="' . e($url) . '" class="' . e($formClass) . '"';
    if ($confirm !== '') {
        $html .= ' onsubmit="return confirm(' . e(json_encode($confirm)) . ')"';
    }
    $html .= '>' . csrfInput();
    foreach ($fields as $name => $value) {
        $html .= '<input type="hidden" name="' . e($name) . '" value="' . e($value) . '">';
    }
    $html .= '<button type="submit" class="' . e($class) . '">' . $label . '</button></form>';
    return $html;
}

function checkCsrfToken(): void
{
    // Only accept the token from a POST form, never from the URL.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('This action must be sent as a form.');
    }

    $sentToken = $_POST['csrf_token'] ?? '';

    if (!is_string($sentToken) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $sentToken)
    ) {
        http_response_code(403);
        exit('Security check failed. Please go back and try again.');
    }
}

function requireLogin(): void
{
    if (
        empty($_SESSION['login']) ||
        empty($_SESSION['user_id'])
    ) {
        header('Location: ' . app_url('views/auth/login.php'));
        exit;
    }

    // Check the account in the database each time, so a blocked user is logged out immediately.
    global $pdo;
    if (!isset($pdo)) {
        require_once APP_ROOT . '/app/config/database.php';
    }
    $stmt = $pdo->prepare('SELECT status, role FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$account || (int) $account['status'] !== 1) {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . app_url('views/auth/login.php'));
        exit;
    }

    // Always trust the role stored in the database.
    $_SESSION['user_role'] = $account['role'];
    $_SESSION['status'] = $account['status'];
}

function requireRole(string $role): void
{
    requireLogin();

    $userRole = $_SESSION['user_role'] ?? ($_SESSION['role'] ?? '');

    if ($userRole !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

// Check the account status without treating a missing value as blocked.
function requireActiveAccount(): void
{
    requireLogin();

    if (array_key_exists('status', $_SESSION) && $_SESSION['status'] == false) {
        session_destroy();
        header('Location: ' . app_url('views/auth/login.php'));
        exit;
    }
}
