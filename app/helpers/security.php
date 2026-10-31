<?php

// Start one session in every page that uses login or security.
function startSecuritySession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

startSecuritySession();

// Make data safe to show inside HTML.
function cleanText(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
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

function checkCsrfToken(): void
{
    $sentToken = $_POST['csrf_token'] ?? ($_GET['csrf_token'] ?? '');

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
