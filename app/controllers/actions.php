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
    header('Location: ' . app_url('views/admin/candidates.php'));
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
    header('Location: ' . app_url('views/admin/voters.php'));
    exit();
}

if (isset($_GET['election'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['election']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }
    $stmt = $pdo->prepare("UPDATE elections SET status=? WHERE id=?");
    $stmt->execute(['completed', $_GET['election']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}

if (isset($_GET['golive'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['golive']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }
    $stmt = $pdo->prepare("UPDATE elections SET status=? WHERE id=?");
    $stmt->execute(['ongoing', $_GET['golive']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT id FROM elections WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    $election = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$election) {
        http_response_code(404);
        exit('Election not found');
    }
    $stmt = $pdo->prepare("DELETE FROM elections WHERE id=?");
    $stmt->execute([$_GET['delete']]);
    header('Location: ' . app_url('views/common/elections.php'));
    exit();
}
