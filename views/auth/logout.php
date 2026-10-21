<?php
session_start();
require_once __DIR__ . '/../../app/paths.php';
session_start();
session_destroy();
header('Location: ' . app_url('views/auth/login.php'));
exit;