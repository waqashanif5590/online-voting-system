<?php

// Check whether a user is logged in.
function requireLogin(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . app_url('views/auth/login.php'));
        exit;
    }
}

// Check whether the logged-in user has the required role.
function requireRole(string $role): void
{
    requireLogin();

    if (($_SESSION['role'] ?? '') !== $role) {
        http_response_code(403);
        exit('Access denied.');
    }
}

// Check whether the user's account is blocked.
// This assumes account status is stored in the session.
// For reliable enforcement, also verify the current status in the database.
function requireActiveAccount(): void
{
    requireLogin();

    if (($_SESSION['status'] ?? '') == false) {
        session_destroy();

        header('Location: ' . app_url('views/auth/login.php'));
        exit;
    }
}
