<?php
require_once __DIR__ . '/../../app/paths.php';
session_start();
require_once APP_ROOT . '/app/config/database.php';
if (isset($_GET['approve'], $_GET['candidate'])) {
    if ($_GET['approve'] === 'true') {
        $stmt = $pdo->prepare("UPDATE candidates SET status=? WHERE id=?");
        $stmt->execute(['approved', $_GET['candidate']]);
    } else if ($_GET['approve'] === 'false') {
        $stmt = $pdo->prepare("UPDATE candidates SET status=? WHERE id=?");
        $stmt->execute(['rejected', $_GET['candidate']]);
    }
    header('Location: ../../views/admin/dashboard.php');
    exit();
}

if (isset($_GET['voter'])) {
    $stmt = $pdo->prepare("SELECT status FROM users WHERE id=?");
    $stmt->execute([$_GET['voter']]);
    $voter = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($voter === false) {
        http_response_code(404);
        exit('Voter not found');
    }
    if ($voter['status'] == true) {
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->execute([0, $_GET['voter']]);
    } else if ($voter['status'] == false) {
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->execute([1, $_GET['voter']]);
    }
    header('Location: ../../views/admin/dashboard.php');
    exit();
}
