<?php

if (!function_exists('setFlashAlert')) {
    function setFlashAlert(string $message, string $type = 'success'): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $allowedTypes = ['success', 'error', 'warning', 'info'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'info';
        }

        $_SESSION['alert_message'] = $message;
        $_SESSION['alert_type'] = $type;
    }
}

if (!function_exists('renderAlert')) {
    function renderAlert(string $message = '', string $type = 'info', int $duration = 6000): void
    {
        if ($message === '') {
            return;
        }

        $allowedTypes = ['success', 'error', 'warning', 'info'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = 'info';
        }

        $titles = [
            'success' => 'Success',
            'error' => 'Something went wrong',
            'warning' => 'Please check',
            'info' => 'Notice',
        ];
        ?>
        <div class="site-alert site-alert--<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"
             role="<?= $type === 'error' ? 'alert' : 'status' ?>"
             aria-live="<?= $type === 'error' ? 'assertive' : 'polite' ?>"
             data-alert-duration="<?= $duration ?>">
            <div class="site-alert__icon" aria-hidden="true"></div>
            <div class="site-alert__content">
                <strong><?= htmlspecialchars($titles[$type], ENT_QUOTES, 'UTF-8') ?></strong>
                <span><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <button class="site-alert__close" type="button" aria-label="Dismiss notification">&times;</button>
        </div>
        <?php
    }
}

if (!function_exists('renderFlashAlert')) {
    function renderFlashAlert(int $duration = 6000): void
    {
        $message = $_SESSION['alert_message'] ?? ($_SESSION['message'] ?? '');
        $type = $_SESSION['alert_type'] ?? ($_SESSION['message_type'] ?? 'info');
        unset($_SESSION['alert_message'], $_SESSION['alert_type'], $_SESSION['message'], $_SESSION['message_type']);

        if ($type === 'danger') {
            $type = 'error';
        }
        renderAlert((string) $message, (string) $type, $duration);
    }
}
